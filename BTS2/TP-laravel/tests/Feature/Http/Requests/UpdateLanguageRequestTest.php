<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateLanguageRequest;
use LogicException;
use Tests\TestCase;

class UpdateLanguageRequestTest extends TestCase
{
    public function test_reports_invalid_supported_locale_configuration(): void
    {
        config(['app.supported_locales' => null]);

        $this->expectException(LogicException::class);

        (new UpdateLanguageRequest)->rules();
    }
}
