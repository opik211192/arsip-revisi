<?php

namespace App\Http\Controllers\Permissions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Permission, Role};

class AssignController extends Controller
{
    public function create()
    {
        $roles = Role::get();
        $permissions = Permission::get();

        return view('permission.assign.create', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store()
    {   
        request()->validate([
            'role' => 'required',
            'permissions' => 'array|required',
        ]);

        $role = Role::find(request('role'));
        $role->givePermissionTo(request('permissions'));

        return back()->with('success', "Permission has been assign to the role {$role->name}");
        
    }

    public function edit(Role $role)
    {
        return view('permission.assign.edit', [
            'role' => $role,
            'roles' => Role::get(),
            'permissions' => Permission::get(),
        ]);
    }

    public function update(Role $role)
    {
        request()->validate([
            'role' => 'required',
            'permissions' => 'array|required',
        ]);

        $role->syncPermissions(request('permissions'));

        return redirect()->route('assign.create')->with('success', 'The Permissions has ben Synced.');
    }
}
