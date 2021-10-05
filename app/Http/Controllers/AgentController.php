<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentStatistics;
use Illuminate\Http\Request;

class AgentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $sponsers = Agent::latest()->paginate(50);
        return view('pages.agents.index', compact('sponsers'));
    }
    public function add(Request $request)
    {
        $sponsers = Agent::orderBy('firstname', 'asc')->get();
        return view('pages.agents.add', compact('sponsers'));
    }
    public function post(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|unique:agents',
            'firstname' => 'required', 'lastname' => 'required',
            'telephone' => 'required', 'address' => 'required'
        ]);
        $data['period'] = date('Y').date('m').date('d');
        $agent = Agent::create($data);
        $stats = AgentStatistics::where('agent_id', $agent->member_id)->first();
        if($stats) {
            $request->session()->flash('alert-warning', 'Agent statistics already exists!');
            return back();
        }
        AgentStatistics::create([
            'agent_id' => $agent->member_id, 'period' => $agent->period,
            'sponser_id' => $request->sponser_id ?? null
        ]);
        $request->session()->flash('alert-success', 'Agent successfully added!');
        return back();

    }
}
