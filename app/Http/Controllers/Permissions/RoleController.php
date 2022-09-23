<?php

namespace App\Http\Controllers\Permissions;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        return view('permission.roles.index', compact('roles'));
    }

    public function store()
    {
       request()->validate([
           'name' => 'required',
       ]);

       Role::create([
            'name' => request('name'),
            'guard_name' => request('guard_name') ?? 'web',
       ]);

       return back();
    }

    public function edit(Role $role)
    {
        return view('permission.roles.edit', compact('role'));
    }

    public function update(Role $role)
    {
        request()->validate([
            'name' => 'required',
        ]);

        $role->update([
            'name' => request('name'),
            'guard_name' => request('guard_name') ?? 'web',
        ]);

        return redirect()->route('roles.index');
    }

    public function delete(Role $role)
    {
        $role->delete();
        return back();
    }
}
