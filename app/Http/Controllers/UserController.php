<?php

namespace App\Http\Controllers;

use App\Models\BvRate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::latest()->paginate(50);
        return view('pages.users.index', compact('users'));
    }
    public function add(Request $request)
    {
        return view('pages.users.add');
    }
    public function post(Request $request)
    {
        $data = $request->validate([
            'name' => 'required', 'username' => 'required', 'email' => 'required|unique:users',
            'password' => 'required', 'roleid' => 'required',
        ]);
        unset($data['password']);
        $data['password'] = Hash::make($request->password);
        User::create($data);
        $request->session()->flash('alert-success', 'User successfully added!');
        return back();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    public function configuration(Request $request)
    {
        $conf = BvRate::first();
        return view('pages.users.configuration', compact('conf'));
    }
    public function postConfiguration(Request $request)
    {
        $data = $request->validate(['rate' => 'required']);
        $conf = BvRate::first();
        if($conf) {
            $conf->rate = $data['rate'];
            $conf->save();
        }else{
            BvRate::create($data);
        }
        $request->session()->flash('alert-success', 'BV Rate successfully modified!');
        return back();
    }
}
