<?php

namespace App\Http\Controllers;

use App\Http\Services\AwardService;
use App\Models\Agent;
use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AwardController extends Controller
{


    public $combPeriodToday;


    public function __construct()
    {
        $this->middleware('auth');
        $this->combPeriodToday = date('Y').date('m');
        $this->start();
    }

    protected function start()
    {
        $awd = new AwardService($this->combPeriodToday);
        $awd->ABP();
    }

    public function index(Request $request)
    {
        $awards = Award::orderBy('order', 'asc')->get();
        $agents = Agent::orderBy('created_at', 'asc')->paginate(200);
        return view('pages.awards.index', compact('awards', 'agents'));
    }
    public function add(Request $request)
    {
        return view('pages.awards.add');
    }
    public function post(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);
        $data['award_id'] = explode('-', strtoupper((string) Str::uuid()))[mt_rand(0,2)];
        Award::create($data);
        $request->session()->flash('alert-success', 'Award successfully added!');
        return back();
    }
}
