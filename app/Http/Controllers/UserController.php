<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateRole(User $user, Request $request)
    {
        $this->authorize('updateRole', $user);

        $request->validate([
            'role' => 'required|string|in:user,author,admin',
        ]);

        $role = $request->role;

        $user->role()->associate(Role::where('name', $role)->first());
        $user->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('index', User::class);

        $users = User::with('role')->get();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function show(User $user)
    {
        $this->authorize('index', User::class);

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }
}
