<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register()
    {
        return view('frontend.register');
    }

    public function doRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'username'=> 'required|unique:users',
            'email'=> 'required|email|unique:users',
            'mobile'=> 'required|numeric|unique:users',
            'password'=> 'required|confirmed',
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['name']       = $request->name;
        $data['username']   = $request->username;
        $data['email']      = $request->email;
        $data['mobile']     = $request->mobile;
        $data['password']   = bcrypt($request->password);

        User::create($data);

        return redirect()->route('login')->with([
            'message' => 'User created successfully',
            'alert-type' => 'success'
        ]);
    }

    public function login()
    {
        return view('frontend.login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'=> 'required',
            'password'=> 'required',
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (filter_var($request->login_id, FILTER_VALIDATE_EMAIL)) {
            $user_id = 'email';
        } elseif (filter_var((int)$request->login_id, FILTER_VALIDATE_INT)) {
            $user_id = 'mobile';
        } else {
            $user_id = 'username';
        }

        $request->merge([
            $user_id => $request->login_id
        ]);

        if (Auth::attempt($request->only($user_id, 'password'), $request->filled('remember'))) {
            return redirect()->route('home')->with([
                'message' => 'logged in successfully',
                'alert-type' => 'success'
            ]);
        } else {
            return redirect()->route('login')->with([
                'message' => 'These credentials do not match our records.',
                'alert-type' => 'danger'
            ]);
        }
    }

    public function doLogout()
    {
        Auth::logout();
        return redirect()->route('login')->with([
            'message' => 'logged out successfully',
            'alert-type' => 'success'
        ]);

    }


}
