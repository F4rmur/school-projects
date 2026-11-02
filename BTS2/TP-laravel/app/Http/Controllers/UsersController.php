<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\users as UserRecord;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use LogicException;

class UsersController extends Controller
{
    public function __construct(
        private UserRepository $users,
        private RoleRepository $roles,
    ) {}

    public function index(): View
    {
        $currentUserId = Gate::allows('manage-users') ? null : (int) Auth::id();
        $users = $this->users->allWithAbsences($currentUserId);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        Gate::authorize('create', UserRecord::class);

        $roles = $this->roles->all();

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = $request->validatedRoleName();
        unset($validated['role']);
        $validated['password'] = Hash::make($request->validatedPassword());

        $user = $this->users->create($validated);
        $this->roles->syncForUser($this->userKey($user), $role);

        return redirect()->route('user.show', $user)
            ->with('success', __('ui.flash.user_created'));
    }

    public function show(UserRecord $idUser): View
    {
        Gate::authorize('view', $idUser);
        $idUser->load('absences.motif');

        return view('users.show', ['user' => $idUser]);
    }

    public function edit(UserRecord $idUser): View
    {
        Gate::authorize('update', $idUser);

        $roles = $this->roles->all();
        $currentRole = $this->roles->userRoleName($this->userKey($idUser)) ?? 'utilisateur';

        return view('users.edit', [
            'user' => $idUser,
            'roles' => $roles,
            'currentRole' => $currentRole,
        ]);
    }

    public function update(UpdateUserRequest $request, UserRecord $idUser): RedirectResponse
    {
        $validated = $request->validated();

        $role = $request->validatedRoleName();
        unset($validated['role']);

        $password = $request->validatedPassword();

        if (filled($password)) {
            $validated['password'] = Hash::make($password);
        } else {
            // A blank password means the existing credential remains unchanged.
            unset($validated['password']);
        }

        $this->users->update($idUser, $validated);
        $this->roles->syncForUser($this->userKey($idUser), $role);

        return redirect()->route('user.show', $idUser)
            ->with('success', __('ui.flash.user_updated'));
    }

    private function userKey(UserRecord $user): int|string
    {
        $key = $user->getKey();

        if (! is_int($key) && ! is_string($key)) {
            throw new LogicException('A persisted user must have an integer or string key.');
        }

        return $key;
    }
}
