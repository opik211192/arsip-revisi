<?php

namespace App\Http\Controllers\Permissions;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function create()
    {
        $roles = Role::get();
        $users = User::has('roles')->get();
        return view('permission.assign.user.create', compact('roles', 'users'));
    }

    public function store()
    {
        request()->validate([
            'email' => 'required',
            'roles' => 'required|array',
            
        ]);

        $user = User::where('email', request('email'))->first();
        $user->assignRole(request('roles'));
        return back();
    }

    public function edit(User $user)
    {
        return view('permission.assign.user.edit', [
            'user' => $user,
            'roles' => Role::get(),
            'users' => User::has('roles')->get(),
        ]);
    }

    public function update(User $user)
    {
        $user->syncRoles(request('roles'));
        return redirect()->route('assign.user.create');
    }
}
