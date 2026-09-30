<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    public function test_ui_translation_catalogues_have_the_same_keys(): void
    {
        $locales = ['fr', 'en', 'es'];
        $referenceKeys = array_keys(Arr::dot(require lang_path('fr/ui.php')));
        sort($referenceKeys);

        foreach (array_slice($locales, 1) as $locale) {
            $translationKeys = array_keys(Arr::dot(require lang_path($locale.'/ui.php')));
            sort($translationKeys);

            $this->assertSame($referenceKeys, $translationKeys, "UI translation keys differ for [$locale].");
        }
    }

    public function test_authentication_views_render_in_each_supported_language(): void
    {
        config(['session.driver' => 'array']);

        foreach ([
            'fr' => 'Se connecter',
            'en' => 'Sign in',
            'es' => 'Iniciar sesión',
        ] as $locale => $loginLabel) {
            $this->withSession(['locale' => $locale])
                ->get(route('login'))
                ->assertSee($loginLabel)
                ->assertSee('onchange="this.form.requestSubmit()"', false);
        }
    }

    public function test_homepage_renders_in_each_supported_language(): void
    {
        config(['session.driver' => 'array']);

        foreach ([
            'fr' => 'Accédez au suivi des absences.',
            'en' => 'Access absence tracking.',
            'es' => 'Accede al control de ausencias.',
        ] as $locale => $intro) {
            $this->withSession(['locale' => $locale])
                ->get(route('accueil'))
                ->assertSee($intro)
                ->assertSee(route('login'), false)
                ->assertDontSee('/register', false);
        }
    }

    public function test_registration_routes_return_not_found(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [])->assertNotFound();
    }

    public function test_selected_language_is_saved_and_used_by_authentication_views(): void
    {
        config(['session.driver' => 'array']);

        $this->from(route('login'))
            ->post(route('language.update'), ['locale' => 'es'])
            ->assertRedirect(route('login'))
            ->assertSessionHas('locale', 'es');

        $this->get(route('login'))->assertSee('Iniciar sesión');
    }

    public function test_unsupported_language_is_rejected(): void
    {
        config(['session.driver' => 'array']);

        $this->from(route('login'))
            ->post(route('language.update'), ['locale' => 'de'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('locale');
    }
}
