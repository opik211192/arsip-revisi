<?php

namespace App\Http\Controllers\Permissions;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::get();
        return view('permission.permissions.index', compact('permissions'));
    }

    public function store()
    {
       request()->validate([
           'name' => 'required',
       ]);

       Permission::create([
            'name' => request('name'),
            'guard_name' => request('guard_name') ?? 'web',
       ]);

       return back();
    }

    public function edit(Permission $permission)
    {
        return view('permission.permissions.edit', compact('permission'));
    }

    public function update(Permission $permission)
    {
        request()->validate([
            'name' => 'required',
        ]);

        $permission->update([
            'name' => request('name'),
            'guard_name' => request('guard_name'),
        ]);

        return redirect()->route('permissions.index');
    }

    public function delete(Permission $permission)
    {
        $permission->delete();
        return back();
    }
}
