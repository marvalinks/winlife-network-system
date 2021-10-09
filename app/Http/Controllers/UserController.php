<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
