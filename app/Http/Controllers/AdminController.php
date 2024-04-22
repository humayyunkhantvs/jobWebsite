<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Verified;

class AdminController extends Controller
{
    //

    public function resend_verification_link(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'An email with a new verification link has been sent to your email address.');
    }

    public function admindashboard()
    {
        return view('admin.pages.index');
    }

    public function login()
    {
        return view('admin.pages.login');
    }

    public function signup()
    {
        return view('admin.pages.signup');
    }

    public function signup_process(Request $req)
    {
        $data = [];
        $rules = array(
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        );
        $messages = array(
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password and Confirm Password must match',
        );
        $validation = Validator::make($req->all(), $rules, $messages);
        if ($validation->fails()) {

            $data['errors'] = $validation->errors();
            $data['response'] = false;
        } else {
            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->role = 'admin';
            $user->hash = hash_token();
            $user->save();
            $user->sendEmailVerificationNotification();

            $data['response'] = true;
            $data['message'] = "Admin Created Successfully.Please check your inbox to verify and login.";
            $data['redirect'] = route('login');
        }

        return response()->json($data);
    }


    public function login_process(Request $request)
    {
        $data = [];

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'password.required' => 'Password is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            $data['response'] = false;
        } else {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $data['response'] = true;
                $data['redirect'] = route('admindashboard');
            } else {
                $data['wrong_errors'] = true;
                $data['message'] = "Provided credentials are not correct";
            }
        }

        return response()->json($data);
    }


    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {

            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function update_profile()
    {

        $user = Auth::user();
        return view('admin.pages.update_profile', ['user' => $user]);
    }

    public function update_user(Request $req)
    {
        $data = [];
        $rules = [
            'name' => 'required',
            'old_password' => 'nullable|required_with:password',
            'password' => 'nullable|required_with:old_password|min:6|confirmed',
        ];

        $messages = [
            'name.required' => 'Name is required',
            'old_password.min' => 'Old Password must be at least 6 characters',
            'password.min' => 'New Password must be at least 6 characters',
            'password.confirmed' => 'Password and Confirm Password must match',
        ];
        $validation = Validator::make($req->all(), $rules, $messages);
        if ($validation->fails()) {
            $data['errors'] = $validation->errors();
        } else {
            $user = User::find($req->id);
            if ($req->filled('old_password')) {
                if (!Hash::check($req->old_password, $user->password)) {
                    $data['old_password_errors'] = 'Old password is incorrect.';
                }
            }
            if (!isset($data['errors']) && !isset($data['old_password_errors'])) {

                $user->name = $req->name;
                if ($req->filled('password')) {
                    $user->password = Hash::make($req->password);
                }
                $user->save();
                $data['response'] = true;
                $data['message'] = 'Profile updated successfully.';
            }
        }

        return response()->json($data);
    }

}
