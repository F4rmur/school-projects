<?php

namespace Tests\Feature;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FortifyActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_creation_hashes_password_and_rejects_duplicate_email(): void
    {
        $action = new CreateNewUser;
        $password = $this->randomPassword();
        $input = [
            ...$this->profileInput(fake()->unique()->safeEmail()),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $user = $action->create($input);

        $this->assertDatabaseHas('users', ['email' => $input['email']]);
        $this->assertTrue(Hash::check($input['password'], $user->password));

        $this->assertValidationError('email', function () use ($action, $input): void {
            $action->create($input);
        });
    }

    public function test_password_reset_updates_the_password_and_rejects_unconfirmed_passwords(): void
    {
        $user = User::factory()->create();
        $action = new ResetUserPassword;
        $password = $this->randomPassword();

        $action->reset($user, [
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertTrue(Hash::check($password, $user->fresh()->password));

        $unconfirmedPassword = $this->randomPassword();
        $this->assertValidationError('password', function () use ($action, $user, $unconfirmedPassword): void {
            $action->reset($user, [
                'password' => $unconfirmedPassword,
                'password_confirmation' => $unconfirmedPassword.'-different',
            ]);
        });
    }

    public function test_authenticated_user_can_change_their_password(): void
    {
        $user = User::factory()->create();
        $password = $this->randomPassword();

        $this->actingAs($user);

        (new UpdateUserPassword)->update($user, [
            'current_password' => 'password',
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertTrue(Hash::check($password, $user->fresh()->password));
    }

    public function test_password_change_rejects_an_incorrect_current_password_without_changing_it(): void
    {
        $user = User::factory()->create();
        $originalPassword = $user->password;
        $this->actingAs($user);

        $unconfirmedPassword = $this->randomPassword();
        $this->assertValidationError('current_password', function () use ($user, $unconfirmedPassword): void {
            (new UpdateUserPassword)->update($user, [
                'current_password' => 'wrong-'.$this->randomPassword(),
                'password' => $unconfirmedPassword,
                'password_confirmation' => $unconfirmedPassword,
            ]);
        });

        $this->assertSame($originalPassword, $user->fresh()->password);
    }

    public function test_profile_information_is_updated_and_duplicate_email_is_rejected(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $action = new UpdateUserProfileInformation;
        $input = $this->profileInput(fake()->unique()->safeEmail());

        $action->update($user, $input);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            ...$input,
        ]);

        $this->assertValidationError('email', function () use ($action, $input, $otherUser, $user): void {
            $action->update($user, [
                ...$input,
                'email' => $otherUser->email,
            ]);
        });
    }

    public function test_email_change_resets_verification_and_sends_a_verification_notification(): void
    {
        Notification::fake();
        $originalEmail = fake()->unique()->safeEmail();
        $updatedEmail = fake()->unique()->safeEmail();
        $profile = $this->profileInput($originalEmail);
        $user = new class extends User implements MustVerifyEmail
        {
            use MustVerifyEmailTrait;

            protected $table = 'users';
        };
        $user->forceFill([
            ...$profile,
            'password' => Hash::make($this->randomPassword()),
            'email_verified_at' => now(),
        ])->save();

        (new UpdateUserProfileInformation)->update($user, $this->profileInput($updatedEmail));

        $this->assertNull($user->fresh()->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /**
     * @return array{nom: string, prenom: string, email: string}
     */
    private function profileInput(string $email): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => $email,
        ];
    }

    private function randomPassword(): string
    {
        return fake()->password(12);
    }

    private function assertValidationError(string $field, callable $action): void
    {
        try {
            $action();
            $this->fail("Expected a validation error for [$field].");
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey($field, $exception->errors());
        }
    }
}
