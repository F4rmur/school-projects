<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AccueilController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }
}
