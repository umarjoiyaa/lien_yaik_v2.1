<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Role List') ||
            Auth::user()->hasPermissionTo('Role Create') ||
            Auth::user()->hasPermissionTo('Role Edit') ||
            Auth::user()->hasPermissionTo('Role Delete')
        ) {
            Helper::logSystemActivity('Role', 'Role List');
            $roles = Role::select('id', 'name')->get();
            return view("users.role.index", compact('roles'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Role Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $permissions = Permission::get();
        $users = Helper::getpermissions('users');
        $others = Helper::getpermissions('others');
        $reports = Helper::getpermissions('reports');
        $products = Helper::getpermissions('products');
        $materials = Helper::getpermissions('materials');
        $dashboards = Helper::getpermissions('dashboards');
        $warehouses = Helper::getpermissions('warehouses');
        $productions = Helper::getpermissions('productions');
        $inprogresses = Helper::getpermissions('progresses');
        
        Helper::logSystemActivity('Role', 'Role Create');
        return view("users.role.create", compact("users", "others", "reports", "products", "materials", "dashboards", "warehouses", "productions", "inprogresses", "permissions"));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Role Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles', 'name'),
            ],
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $permissions = $request->input('permission');
        $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);
        Helper::logSystemActivity('Role', 'Role Store');
        return redirect()->route('role.index')->with('custom_success', 'Role has been Succesfully Added!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Role Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $role = Role::find($request->id);
        $permissions = Permission::get();
        $users = Helper::getpermissions('users');
        $others = Helper::getpermissions('others');
        $reports = Helper::getpermissions('reports');
        $products = Helper::getpermissions('products');
        $materials = Helper::getpermissions('materials');
        $dashboards = Helper::getpermissions('dashboards');
        $warehouses = Helper::getpermissions('warehouses');
        $productions = Helper::getpermissions('productions');
        $inprogresses = Helper::getpermissions('progresses');

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $request->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
            
        Helper::logSystemActivity('Role', 'Role Edit');
        return view("users.role.edit", compact("users", "others", "reports", "products", "materials", "dashboards", "warehouses", "productions", "inprogresses", "permissions", "role", "rolePermissions"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Role Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($request->id),
            ],
            'permission' => 'required',
        ]);

        $role = Role::find($request->id);
        $role->name = $request->input('name');
        $role->save();

        $permissions = $request->input('permission');
        $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);
        Helper::logSystemActivity('Role', 'Role Update');
        return redirect()->route('role.index')->with('custom_success', 'Role has been Succesfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Role Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        // Prevent from from self deleting
        $role = Role::find($id);
        $existrole = User::whereJsonContains('role_ids', $id)->exists();
        if ($role->id == Auth::user()->roles->pluck('id')[0]) {
            return back()->with('custom_errors', 'This role has been assigned to you. You cannot delete it. Ask super admin to do that.');
        }
        if ($existrole) {
            return back()->with('custom_errors', 'This role has been assigned to someone. You cannot delete it. First Unassign role from user registration');
        }
        DB::table("roles")->where('id', $id)->delete();
        Helper::logSystemActivity('Role', 'Role Delete');
        return redirect()->route('role.index')->with('custom_success', 'Role has been Succesfully Deleted!');
    }
}
