<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('Profile View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $user = Auth::user();
        Helper::logSystemActivity('Profile', 'Profile View');
        return view('layouts.profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Profile Update')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validator = null;

            $validatedData = $request->validate([
                'name' => 'required',
                'picture'=>'mimes:jpeg,png,jpg,gif',
                'email' => [
                    'required',
                    Rule::unique('users', 'email')->whereNull('deleted_at')->ignore(Auth::user()->id),
                ],
            ], [
              'picture.mimes' => 'The Profile must be an image (jpeg, png, jpg, gif) .'
          ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        $users = User::find(Auth::user()->id);
        $users->name = $request->name;
        $users->email = $request->email;
        if ($request->file('profile')) {
            $file = $request->file('profile');
            $filename = date('YmdHis') . $file->getClientOriginalName();
            $file->move('profile', $filename);
            $users->profile = $filename;
        }
        if($request->is_file != 1){
            $users->profile = null;
        }
        $users->save();
        Helper::logSystemActivity('Profile', 'Profile Update');
        return redirect()->route('user.profile')->with('custom_success', 'Your Profile has been Updated Successfully !');
    }

    public function password(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Profile Update')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $validator = null;

        $validatedData = $request->validate([
            'currentpassword' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Auth::validate(['email' => Auth::user()->email, 'password' => $value])) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'newpassword' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ],
            'confirmpassword' => 'required|same:newpassword',
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }
        $users = User::find(Auth::user()->id);
        $users->password = Hash::make($request->confirmpassword);
        $users->save();
        Helper::logSystemActivity('Profile', 'Profile Password Change');
        return redirect()->route('user.profile')->with('custom_success', 'Password Changed Succesfully !');
    }
}
