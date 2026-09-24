<?php

namespace App\Http\Controllers;

use App\Models\Motif;
use Illuminate\Http\Request;

class MotifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('motifs/vueMotifs', ['motifs' => Motif::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Motif $motif)
    {
        return view('motifs/vueMotifUnique', ['motif' => $motif]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Motif $motif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Motif $motif)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Motif $motif)
    {
        //
    }
}
