<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Validation\Rule;

class SubAdminController extends Controller
{
    public function index()
    {
        $subAdmins = User::where('role', 'sub-admin')->get();
        return view('dashboard.sub-admins.index', compact('subAdmins'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('dashboard.sub-admins.create', compact("permissions"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone|numeric|digits:10',
            'password' => 'required|confirmed|min:4',
        ]);
        $subAdmin =   User::create([
            'name' => $request->name,
            'phone' => "$request->phone",
            'password' => Hash::make($request->password),
            'user_id' => auth()->id(),
            'role' => 'sub-admin',
            'confirmed' => 1,
            'fcm' => null,
            'blocked' => 0,
        ]);

        $subAdmin->permissions()->sync($request->permissions);
        return redirect()->route('sub-admins.index')
            ->with('success', 'Sub Admin successfully created');
    }

    public function edit($id)
    {
        $subAdmin = User::with("permissions")->findOrFail($id);
        $permissions = Permission::all();
        return view("dashboard.sub-admins.create", compact("subAdmin", "permissions"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => [
                'required',
                'unique:users,phone'.$id,
                'numeric',
                'digits:10',
                Rule::unique('users')->ignore($id)
            ],
            'password' => 'required|confirmed|min:4',
        ]);
        $subAdmin = User::with('permissions')->where('role', 'sub-admin')->findOrFail($id);
        $subAdmin->name = $request->name;
        $subAdmin->phone = "$request->phone";
        $subAdmin->password = Hash::make($request->password);
        $subAdmin->update();
        $subAdmin->permissions()->sync($request->permissions);

        return redirect()->route('sub-admins.index')
            ->with('success', 'Sub Admin successfully updated');
    }

    public function delete($id)
    {
        $subAdmin = User::with('permissions')->where('role', 'sub-admin')->findOrFail($id);
        $subAdmin->delete();
        return back()->with("success", "Sub Admin has been deleted");
    }

    public function toogleBlock(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric'
        ]);
        $user = User::findOrFail($request->user_id);
        $user->blocked = intval($request->blocked);
        $user->save();
    }

    public function editPermissions($id)
    {
        $subAdmin = User::with('permissions')->where('role', 'sub-admin')->findOrFail($id);
        $permissions = Permission::all();
        return view("dashboard.sub-admins.permissions-update", compact('subAdmin', 'permissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required'
        ]);

        $subAdmin = User::where('role', 'sub-admin')->findOrFail($id);
        $subAdmin->permissions()->sync($request->permissions);
        return back()->with("success", "Permissions has been updated");
    }
}
