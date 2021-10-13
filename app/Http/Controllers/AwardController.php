<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $awards = Award::latest()->get();
        return view('pages.awards.index', compact('awards'));
    }
    public function add(Request $request)
    {
        return view('pages.awards.add');
    }
    public function post(Request $request)
    {
        $data = $request->validate([
            'name' => 'required', 'period' => 'required',
            'min_level' => 'required', 'min_bv' => 'required'
        ]);
        Award::create($data);
        $request->session()->flash('alert-success', 'Award successfully added!');
        return back();
    }
}
