<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AccueilController extends Controller
{
    public function __construct(private string $title = 'accueil')
    {
        $this->title = $title;
    }

    /** @return View */
    public function index()
    {
        return view('welcome');
    }

    public function page($page = null): string
    {
        return "page $page";
    }

    public function title(?string $title = null): string
    {
        return $title ?? $this->title;
    }
}
