<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PagesController extends Controller
{


    public function login(Request $request)
    {
        return view('pages.auth.login');
    }
    public function register(Request $request)
    {
        return view('pages.auth.register');
    }
    public function postLogin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required', 'password' => 'required'
        ]);
        $user = User::where('username', $data['username'])->first();
        if(!$user){
            return back()->withErrors([
                'invalid' => 'Sorry, username not registered in system.',
            ]);
        }
        if (Auth::attempt(['email' => $user->email, 'password' => $data['password']])) {
            $request->session()->regenerate();
            return redirect(RouteServiceProvider::HOME);
        }
    }
    public function postRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required', 'username' => 'required|unique:users',
            'email' => 'required|unique:users', 'password' => 'required'
        ]);
        unset($data['password']);
        $data['password'] = Hash::make($request->password);
        User::create($data);
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('login');
    }
}
