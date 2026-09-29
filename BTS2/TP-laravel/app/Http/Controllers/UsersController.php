<?php

namespace App\Http\Controllers;

use App\Models\users as UserRecord;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function __construct(
        private UserRepository $users,
        private RoleRepository $roles,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->users->allWithAbsences();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', UserRecord::class);

        $roles = $this->roles->all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', UserRecord::class);

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

        $user = $this->users->create($validated);
        $this->roles->syncForUser($user->getKey(), $role);

        return redirect()->route('user.show', $user)
            ->with('success', __('ui.flash.user_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRecord $idUser)
    {
        $idUser->load('absences.motif');

        return view('users.show', ['user' => $idUser]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRecord $idUser)
    {
        Gate::authorize('update', $idUser);

        $roles = $this->roles->all();
        $currentRole = $this->roles->userRoleName($idUser->getKey()) ?? 'utilisateur';

        return view('users.edit', [
            'user' => $idUser,
            'roles' => $roles,
            'currentRole' => $currentRole,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRecord $idUser)
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

        $this->users->update($idUser, $validated);
        $this->roles->syncForUser($idUser->getKey(), $role);

        return redirect()->route('user.show', $idUser)
            ->with('success', __('ui.flash.user_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRecord $users)
    {
        //
    }
}
