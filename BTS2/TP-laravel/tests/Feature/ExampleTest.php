<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_to_login_when_opening_protected_urls(): void
    {
        foreach ([route('absence.index'), route('user.index'), route('roles.index')] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }
}
