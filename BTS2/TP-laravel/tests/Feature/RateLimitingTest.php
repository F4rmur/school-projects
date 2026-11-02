<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    public function test_registered_login_two_factor_and_passkey_limiters_use_the_expected_keys(): void
    {
        $loginRequest = Request::create(
            '/login',
            'POST',
            ['email' => 'ÜSER@example.test'],
            [],
            [],
            ['REMOTE_ADDR' => '192.0.2.10'],
        );
        $loginLimiter = RateLimiter::limiter('login');
        $this->assertNotNull($loginLimiter);
        $loginLimit = $loginLimiter($loginRequest);
        $this->assertSame(5, $loginLimit->maxAttempts);
        $this->assertSame('user@example.test|192.0.2.10', $loginLimit->key);

        $session = $this->app['session.store'];
        $session->put('login.id', 42);
        $twoFactorRequest = Request::create('/two-factor-challenge', 'POST');
        $twoFactorRequest->setLaravelSession($session);
        $twoFactorLimiter = RateLimiter::limiter('two-factor');
        $this->assertNotNull($twoFactorLimiter);
        $twoFactorLimit = $twoFactorLimiter($twoFactorRequest);
        $this->assertSame(5, $twoFactorLimit->maxAttempts);
        $this->assertSame(42, $twoFactorLimit->key);

        $passkeyRequest = Request::create(
            '/passkeys/login',
            'POST',
            ['credential' => ['id' => 'credential-id']],
            [],
            [],
            ['REMOTE_ADDR' => '192.0.2.10'],
        );
        $passkeyLimiter = RateLimiter::limiter('passkeys');
        $this->assertNotNull($passkeyLimiter);
        $passkeyLimit = $passkeyLimiter($passkeyRequest);
        $this->assertSame(10, $passkeyLimit->maxAttempts);
        $this->assertSame('credential-id|192.0.2.10', $passkeyLimit->key);

        $fallbackPasskeyRequest = Request::create(
            '/passkeys/login',
            'POST',
            [],
            [],
            [],
            ['REMOTE_ADDR' => '192.0.2.10'],
        );
        $fallbackPasskeyRequest->setLaravelSession($session);
        $fallbackPasskeyLimit = $passkeyLimiter($fallbackPasskeyRequest);
        $this->assertSame(10, $fallbackPasskeyLimit->maxAttempts);
        $this->assertSame($session->getId().'|192.0.2.10', $fallbackPasskeyLimit->key);
    }
}
