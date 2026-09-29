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
            'fr' => ['Se connecter', 'Créer un compte'],
            'en' => ['Sign in', 'Create account'],
            'es' => ['Iniciar sesión', 'Crear una cuenta'],
        ] as $locale => [$loginLabel, $registerLabel]) {
            $this->withSession(['locale' => $locale])
                ->get(route('login'))
                ->assertSee($loginLabel)
                ->assertSee('onchange="this.form.requestSubmit()"', false);

            $this->withSession(['locale' => $locale])
                ->get(route('register'))
                ->assertSee($registerLabel);
        }
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

    public function test_validation_messages_use_the_selected_language(): void
    {
        config(['session.driver' => 'array']);

        $this->withSession(['locale' => 'es'])
            ->post(route('register'), [])
            ->assertRedirect()
            ->assertSessionHasErrors(['nom', 'prenom', 'email', 'password'])
            ->assertSessionHas('errors', fn ($errors): bool => $errors->getBag('default')->first('nom') === 'El campo apellidos es obligatorio.');
    }
}
