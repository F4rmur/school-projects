<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = users::with('absences.motif')
            ->withCount('absences')
            ->orderBy('nom')
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', users::class);

        $roles = Role::query()->orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', users::class);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'in:homme,femme'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ]);

        $role = $validated['role'];
        unset($validated['role']);
        $validated['password'] = Hash::make($validated['password']);
        unset($validated['password_confirmation']);

        $user = users::create($validated);
        $authUser = User::query()->findOrFail($user->getKey());
        Bouncer::sync($authUser)->roles([$role]);
        Bouncer::refresh($authUser);

        return redirect()->route('user.show', $user)
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(users $idUser)
    {
        $idUser->load('absences.motif');

        return view('users.show', ['user' => $idUser]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(users $idUser)
    {
        Gate::authorize('update', $idUser);

        $roles = Role::query()->orderBy('name')->get();
        $currentRole = User::query()->findOrFail($idUser->getKey())->getRoles()->first()?->name ?? 'utilisateur';

        return view('users.edit', [
            'user' => $idUser,
            'roles' => $roles,
            'currentRole' => $currentRole,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, users $idUser)
    {
        Gate::authorize('update', $idUser);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'in:homme,femme'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($idUser->getKey()),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ]);

        $role = $validated['role'];
        unset($validated['role']);

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $idUser->update($validated);

        $authUser = User::query()->findOrFail($idUser->getKey());
        Bouncer::sync($authUser)->roles([$role]);
        Bouncer::refresh($authUser);

        return redirect()->route('user.show', $idUser)
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(users $users)
    {
        //
    }
}
