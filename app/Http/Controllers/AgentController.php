<?php

namespace App\Http\Controllers;

use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use PDF;

class AgentController extends Controller
{

    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;
    public $combPeriodToday;

    public function __construct()
    {
        $this->middleware('auth');
        $this->combPeriodToday = date('Y').date('m');
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
    }

    public function index(Request $request)
    {

        return view('pages.agents.index');
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
            'telephone' => 'required', 'address' => 'required',
            'period' => 'required'
        ]);
        // $data['period'] = date('Y').date('m').date('d');
        $data['sponser_id'] = $request->sponser_id ?? null;
        $agent = Agent::create($data);
        $request->session()->flash('alert-success', 'Agent successfully added!');
        return back();

    }

    public function update(Request $request, $id)
    {
        
        $agent = Agent::where('member_id', $id)->first();
        $data = $request->validate([
            'firstname' => 'required', 'lastname' => 'required',
            'telephone' => 'required', 'address' => 'required',
            'period' => 'required'
        ]);
        $data['sponser_id'] = $request->sponser_id ?? null;
        $agent = $agent->update($data);
        $request->session()->flash('alert-success', 'Agent successfully updated!');
        return back();
    }
    public function edit(Request $request, $id)
    {
        $sponser = Agent::where('member_id', $id)->first();
        $sponsers =  Agent::with(['childrenSponsers'])->where('sponser_id', $id)->get();

        $this->currentGBV = $sponser->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
        $this->ACCGBV = $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);

        foreach ($sponser->sponsers as $key => $spp) {
            $this->currentGBV += $spp->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV += $spp->archievements->whereBetween('period', [$spp->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
            // $this->ACCGBV += 1;
            foreach ($spp->childrenSponsers as $k => $child_sponser) {
                //level2
                // $this->currentGBV += 1;
                $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                // $this->ACCGBV += 1;
                $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                $this->reloop($child_sponser);
            }
        }
        // ddd($this->currentGBV, $this->ACCGBV);
        $currentGBV = $this->currentGBV;
        $ACCGBV = $this->ACCGBV;
        $combPeriodToday = $this->combPeriodToday;
        $archievements = Achivement::where('member_id', $id)->latest()->paginate(150);
        return view('pages.agents.edit', compact('sponser', 'sponsers', 'archievements', 'ACCGBV', 'currentGBV', 'combPeriodToday'));
    }
    protected function adjustBulkPvb()
    {
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $users = Agent::latest()->get();
        foreach ($users as $key => $user) {
            $stats = AgentStatistics::where('agent_id', $user->member_id)->first();
            $archievements = Achivement::where('member_id', $user->member_id);
            $achTotal = $archievements->where('period', $this->combPeriodToday)->sum('total_pv');
            $achTotal2 = $archievements->sum('total_pv');
            $this->currentGBV = $user->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
            foreach ($user->sponsers as $key => $spp) {
                $this->currentGBV += $spp->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $spp->archievements->whereBetween('period', [$spp->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                foreach ($spp->childrenSponsers as $k => $child_sponser) {
                    $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                    $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                    $this->reloop($child_sponser);
                }
            }
            $stats->current_pbv = $achTotal;
            $stats->acc_pvb = $achTotal2;
            $stats->current_gbv = $this->currentGBV;
            $stats->acc_gbv = $this->ACCGBV;
            $stats->save();
        }
    }
    public function adjustPvb(Request $request, $id)
    {
        $data = $request->validate(['adj' => 'required']);
        $user = Agent::where('member_id', $id)->first();
        if(!$user) {
            $request->session()->flash('alert-error', 'PVB added for agent!');
            return back();
        }
        Achivement::create([
            'member_id' => $user->member_id, 'name' => $user->firstname.' '.$user->lastname,
            'period' => $this->combPeriodToday, 'total_pv' => floatval($data['adj']),
            'country' => 'GH'
        ]);
        $this->adjustBulkPvb();
        $request->session()->flash('alert-success', 'PVB added for agent!');
        return back();
    }
    public function reloop($child_sponser)
    {
        if ($child_sponser->sponsers) {
            foreach ($child_sponser->sponsers as $k => $childrenSponser) {
                // $this->currentGBV += 1;
                $this->currentGBV += $childrenSponser->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $childrenSponser->archievements->whereBetween('period', [$childrenSponser->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                // $this->ACCGBV += 1;
                $this->reloop($childrenSponser);
            }
        }
    }
    public function makePayment(Request $request, $id)
    {
        $sponser = Agent::where('member_id', $id)->first();
        $sponsers =  Agent::with(['childrenSponsers'])->where('sponser_id', $id)->get();
        $combPeriodToday = $this->combPeriodToday;
        return view('pages.agents.payment', compact('sponsers', 'sponser', 'combPeriodToday'));
    }
}
