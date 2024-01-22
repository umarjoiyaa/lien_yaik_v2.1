<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('User List') ||
            Auth::user()->hasPermissionTo('User Create') ||
            Auth::user()->hasPermissionTo('User Edit') ||
            Auth::user()->hasPermissionTo('User Delete')
        ) {
            $users = User::select('id','name','email')->get();
            Helper::logSystemActivity('User', 'User List');
            return view("users.user.index", compact('users'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('User Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $roles = Role::select('id', 'name')->get();
        Helper::logSystemActivity('User', 'User Create');
        return view("users.user.create", compact("roles"));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('User Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $this->validate($request, [
            'email' => [
                'required',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'name' => 'required',
            'role' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ],
            'confirm_password' => 'required|same:password'
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role_ids = json_encode($request->role);
        $user->password = Hash::make($request->confirm_password);
        if($request->file('profile')){
            $file= $request->file('profile');
            $filename= date('YmdHis').$file->getClientOriginalName();
            $file->move('profile', $filename);
            $user->profile =  $filename;
        }
        $user->save();
        foreach ($request->role as $roleId) {
            $role = Role::find($roleId);
            $user->assignRole([$role->name]);
        }
        Helper::logSystemActivity('User', 'User Store');
        return redirect()->route('user.index')->with('custom_success', 'User has been Succesfully Added!');
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
    public function edit(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('User Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $user = User::find($id);
        $roles = Role::select('id', 'name')->get();
        Helper::logSystemActivity('User', 'User Edit');
        return view("users.user.edit", compact("user", "roles"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('User Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $this->validate($request, [
            'email' => [
                'required',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($id),
            ],
            'name' => 'required',
            'role' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ],
            'confirm_password' => 'required|same:password'
        ]);

        $user = User::find($id);
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role_ids = json_encode($request->role);
        $user->password = Hash::make($request->confirm_password);
        if($request->file('profile')){
            $file= $request->file('profile');
            $filename= date('YmdHis').$file->getClientOriginalName();
            $file->move('profile', $filename);
            $user->profile =  $filename;
        }
        $user->save();
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        foreach ($request->role as $roleId) {
            $role = Role::find($roleId);
            $user->assignRole([$role->name]);
        }
        Helper::logSystemActivity('User', 'User Update');
        return redirect()->route('user.index')->with('custom_success', 'User has been Succesfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('User Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        // Prevent from from self deleting
        $user = User::find($id);
        if ($id == Auth::user()->id) {
            return back()->with('custom_errors', 'You Can`t delete yourself. Ask super admin to do that.');
        }
        $user->delete();
        Helper::logSystemActivity('User', 'User Delete');
        return redirect()->route('user.index')->with('custom_success', 'User has been Succesfully Deleted!');
    }
}
