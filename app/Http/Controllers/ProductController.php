<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function index(Request $request)
    // {
    //     $sponsers = Agent::latest()->paginate(50);
    //     return view('pages.agents.index', compact('sponsers'));
    // }
    public function add(Request $request)
    {
        return view('pages.products.add');
    }
}
