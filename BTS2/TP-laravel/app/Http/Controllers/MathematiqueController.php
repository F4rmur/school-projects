<?php

namespace App\Http\Controllers;

class MathematiqueController extends Controller
{
    public function addition(float $a, float $b): string
    {
        return __('ui.math.addition', ['a' => $a, 'b' => $b, 'result' => $a + $b]);
    }

    public function soustraction(float $a, float $b): string
    {
        return __('ui.math.subtraction', ['a' => $a, 'b' => $b, 'result' => $a - $b]);
    }

    public function multiplication(float $a, float $b): string
    {
        return __('ui.math.multiplication', ['a' => $a, 'b' => $b, 'result' => $a * $b]);
    }

    public function division(float $a, float $b): string
    {
        if ($b === 0.0) {
            return __('ui.math.division_by_zero');
        }

        return __('ui.math.division', ['a' => $a, 'b' => $b, 'result' => $a / $b]);
    }
}
