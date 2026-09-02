<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\DTOs\UserData;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request)
    {
        $this->authorize('manage.users');
        
        $filters = $request->only(['search', 'role', 'status']);
        $users = $this->userRepository->paginateWithRoles(15, $filters);
        $roles = Role::pluck('name', 'id');
        
        return view('admin.users.index', compact('users', 'filters', 'roles'));
    }

    public function create()
    {
        $this->authorize('manage.users');
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $userData = UserData::fromArray($request->validated());

        try {
            $this->userService->createUser($userData);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->authorize('manage.users');
        $user = $this->userRepository->findWithRoles($user->id);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('manage.users');
        $user = $this->userRepository->findWithRoles($user->id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = UserData::fromArray($request->validated());

        try {
            $this->userService->updateUser($user->id, $userData);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $this->authorize('manage.users');
        
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,inactive'
        ]);
        
        try {
            $this->userService->updateStatus($user->id, $validated['status']);
            return back()->with('success', 'User status updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('manage.users');
        
        try {
            $this->userService->deleteUser($user->id);
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
