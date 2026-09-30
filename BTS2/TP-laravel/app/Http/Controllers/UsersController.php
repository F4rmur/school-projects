<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\users as UserRecord;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

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
        $currentUserId = Gate::allows('manage-users') ? null : (int) auth()->id();
        $users = $this->users->allWithAbsences($currentUserId);

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
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

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
        Gate::authorize('view', $idUser);
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
    public function update(UpdateUserRequest $request, UserRecord $idUser)
    {
        $validated = $request->validated();

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
