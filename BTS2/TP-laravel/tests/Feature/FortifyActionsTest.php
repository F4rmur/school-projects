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
        $input = [
            'nom' => 'Martin',
            'prenom' => 'Alice',
            'email' => 'alice@example.test',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
        ];

        $user = $action->create($input);

        $this->assertDatabaseHas('users', ['email' => $input['email']]);
        $this->assertTrue(Hash::check($input['password'], $user->password));

        try {
            $action->create($input);
            $this->fail('A duplicate email should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('email', $exception->errors());
        }
    }

    public function test_password_reset_updates_the_password_and_rejects_unconfirmed_passwords(): void
    {
        $user = User::factory()->create();
        $action = new ResetUserPassword;
        $password = 'AnotherSecure123!';

        $action->reset($user, [
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertTrue(Hash::check($password, $user->fresh()->password));

        try {
            $action->reset($user, [
                'password' => 'DifferentSecure123!',
                'password_confirmation' => 'NotTheSame123!',
            ]);
            $this->fail('An unconfirmed password should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('password', $exception->errors());
        }
    }

    public function test_authenticated_user_can_change_their_password(): void
    {
        $user = User::factory()->create();
        $password = 'UpdatedSecure123!';

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

        try {
            (new UpdateUserPassword)->update($user, [
                'current_password' => 'incorrect-password',
                'password' => 'UpdatedSecure123!',
                'password_confirmation' => 'UpdatedSecure123!',
            ]);
            $this->fail('An incorrect current password should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('current_password', $exception->errors());
        }

        $this->assertSame($originalPassword, $user->fresh()->password);
    }

    public function test_profile_information_is_updated_and_duplicate_email_is_rejected(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $action = new UpdateUserProfileInformation;
        $input = [
            'nom' => 'Nouveau nom',
            'prenom' => 'Nouveau prénom',
            'email' => 'updated@example.test',
        ];

        $action->update($user, $input);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            ...$input,
        ]);

        try {
            $action->update($user, [
                ...$input,
                'email' => $otherUser->email,
            ]);
            $this->fail('An email already used by another account should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('email', $exception->errors());
        }
    }

    public function test_email_change_resets_verification_and_sends_a_verification_notification(): void
    {
        Notification::fake();
        $user = new class extends User implements MustVerifyEmail
        {
            use MustVerifyEmailTrait;

            protected $table = 'users';
        };
        $user->forceFill([
            'nom' => 'Martin',
            'prenom' => 'Alice',
            'email' => 'alice@example.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ])->save();

        (new UpdateUserProfileInformation)->update($user, [
            'nom' => 'Martin',
            'prenom' => 'Alice',
            'email' => 'alice.updated@example.test',
        ]);

        $this->assertNull($user->fresh()->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
