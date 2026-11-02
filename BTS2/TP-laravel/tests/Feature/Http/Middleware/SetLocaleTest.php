<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    public function test_reports_invalid_locale_configuration(): void
    {
        config(['app.supported_locales' => null]);

        $this->expectException(LogicException::class);

        (new SetLocale)->handle(
            Request::create('/'),
            static fn (Request $request): Response => new Response,
        );
    }
}
