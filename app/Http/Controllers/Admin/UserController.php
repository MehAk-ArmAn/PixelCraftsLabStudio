<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly ActivityLogger $logger) {}

    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        return view('admin.users.index', [
            'users' => User::query()->orderBy('name')->paginate(25),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.form', ['user' => new User(['role' => User::ROLE_EDITOR, 'is_active' => true]), 'mode' => 'create']);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
            'role' => ['required', Rule::in(User::ROLES)],
        ]);

        $user = User::create($data + ['is_active' => $request->boolean('is_active')]);

        $this->logger->log('created', $user, 'Admin user "'.$user->name.'" created with role '.$user->role.'.');

        return redirect()->route('admin.users.index')->with('status', 'Admin user created.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.form', ['user' => $user, 'mode' => 'edit']);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(10)->letters()->numbers()],
            'role' => ['required', Rule::in(User::ROLES)],
        ]);

        if (blank($data['password'])) {
            unset($data['password']);
        }

        $isSelf = $request->user()->is($user);

        // A super admin must never lock themselves out of their own panel.
        $user->fill($data + [
            'is_active' => $isSelf ? true : $request->boolean('is_active'),
        ]);

        if ($isSelf) {
            $user->role = User::ROLE_SUPER_ADMIN;
        }

        $user->save();

        $this->logger->logSaved('updated', $user, 'Admin user "'.$user->name.'" updated.');

        return back()->with('status', 'Admin user saved.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if (User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1 && $user->isSuperAdmin()) {
            return back()->withErrors(['user' => 'The last super admin cannot be deleted.']);
        }

        $name = $user->name;
        $user->delete();

        $this->logger->log('deleted', null, 'Admin user "'.$name.'" deleted.');

        return redirect()->route('admin.users.index')->with('status', 'Admin user deleted.');
    }
}
