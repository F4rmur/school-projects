<?php

namespace App\Http\Controllers;

class MathematiqueController extends Controller
{
    public function addition(float $a, float $b): string
    {
        return "$a + $b = ".($a + $b);
    }

    public function soustraction(float $a, float $b): string
    {
        return "$a - $b = ".($a - $b);
    }

    public function multiplication(float $a, float $b): string
    {
        return "$a x $b = ".($a * $b);
    }

    public function division(float $a, float $b): string
    {
        if ($b === 0.0) {
            return 'Division par zéro impossible';
        }

        return "$a / $b = ".($a / $b);
    }
}
