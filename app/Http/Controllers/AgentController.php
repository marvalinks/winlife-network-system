<?php

namespace App\Http\Controllers;

use App\Http\Services\AwardService;
use App\Http\Services\BonusService;
use App\Http\Services\GroupService;
use App\Http\Services\LevelService;
use App\Http\Services\StatisticLogService;
use App\Jobs\AwardServiceJob;
use App\Jobs\LevelServiceJob;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\AwardQualifier;
use App\Models\BigAgent;
use App\Models\CheckGsjRun;
use App\Models\CheckRunBill;
use App\Models\GroupBv;
use App\Models\PersonalBv;
use App\Models\Salary;
use App\Models\StatisticLog;
use App\Models\UploadedData;
use App\Models\User;
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
        // $this->start();
    }

    protected function start()
    {

        // $lv = new LevelService($this->combPeriodToday);
        // $lv->ABP();
        // $grp = new GroupService();
        // $grp->GRP();
        // $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        // Salary::truncate();
        // $bns = new BonusService();
        // if(count($acs) > 0) {
        //     foreach ($acs as $key => $ac) {
        //         $bns->calculateBonus($ac);
        //     }
        // }
        // $this->dispatch(new LevelServiceJob($this->combPeriodToday));
        // $this->dispatch(new AwardServiceJob($this->combPeriodToday));

    }

    public function index(Request $request)
    {
        $months = [
            'January' => '01','February' => '02','March' => '03',
            'April' => '04','May' => '05','June' => '06','July' => '07',
            'August' => '08','September' => '09','October' => '10',
            'November' => '11','December' => '12'
        ];
        $ud = UploadedData::latest()->first();
        // dd(substr($ud->period, -2));
        if($ud) {
            $mth = $request->selectedMonth ?? substr($ud->period, -2);
        }else{
            $mth = $request->selectedMonth ?? date('m');
        }
        $yr = intval($request->selectedYear ?? date('Y'));
        if($request->memberid) {
            $memberid = $request->memberid;
            $combPeriod = $request->selectedYear.''. $request->selectedMonth;
            $yr = intval($request->selectedYear);
            $mth = $request->selectedMonth;
            $combPeriodToday = $this->combPeriodToday;
            // $upld = UploadedData::where('data', 'a')->where('period', $combPeriod)->first();

            // if(!$upld) {
            //     $request->session()->flash('alert-danger', 'Achievements not uploaded for this month');
            //     return view('pages.agents.index', compact('memberid','yr', 'mth', 'months', 'combPeriod', 'combPeriodToday'));
            // }
            $user =  BigAgent::where('member_id', $memberid)->where('level', 0)->first();
            if($user) {
                if(intval($user->period) > intval($combPeriod)) {
                    $request->session()->flash('alert-danger', 'Member ID not found in for this period!');
                    return redirect()->route('admin.agents');
                }
                $sponsers =  BigAgent::where('parent_id', $memberid)->where('period', '<=', $combPeriod)->orderBy('level', 'asc')->simplePaginate(100);

                $user = $user;
                $request->session()->flash('alert-success', 'Data results!');
                return view('pages.agents.index', compact('memberid','yr', 'mth', 'months', 'user', 'sponsers', 'combPeriod', 'combPeriodToday'));
            }else{
                $request->session()->flash('alert-danger', 'Member ID not found in system!');
                return redirect()->route('admin.agents');
            }
        }

        return view('pages.agents.index', compact('months', 'yr', 'mth'));
    }
    public function clearAgents(Request $request)
    {
        Agent::truncate();
        Achivement::truncate();
        AgentStatistics::truncate();
        StatisticLog::truncate();
        CheckGsjRun::truncate();
        CheckRunBill::truncate();
        BigAgent::truncate();
        Bonus::truncate();
        AwardQualifier::truncate();
        PersonalBv::truncate();
        GroupBv::truncate();

        $request->session()->flash('alert-success', 'All agent data successfully reset!');
        return back();
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
            'telephone' => '', 'address' => ''
        ]);
        $agents = BigAgent::where('member_id', $id)->get();
        foreach($agents as $ag) {
            $ag->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname']
            ]);
        }
        $data['sponser_id'] = $request->sponser_id ?? null;
        $agent = $agent->update($data);
        $request->session()->flash('alert-success', 'Agent successfully updated!');
        return back();
    }
    public function edit(Request $request, $id)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        // ini_set('memory_limit', '-1');
        // ini_set('max_execution_time', 0);
        // $sponser = Agent::where('member_id', $id)->first();
        $combPeriod = $this->combPeriodToday;
        $yr = date('Y');
        $mth = date('m');
        if($request->year && $request->month) {
            $combPeriod = $request->year.''. $request->month;
            $yr = intval($request->year);
            $mth = $request->month;
        }

        $user =  BigAgent::where('member_id', $id)->where('level', 0)->first();

        if(intval($user->period) > intval($combPeriod)) {
            $request->session()->flash('alert-danger', 'Member ID not found in for this period!');
            return redirect()->route('admin.agents');
        }
        $sponsers =  BigAgent::where('parent_id', $id)->where('period', '<=', $combPeriod)->orderBy('level', 'asc')->simplePaginate(100);
        $sponser = $user;
        $combPeriodToday = $this->combPeriodToday;
        $archievements = Achivement::where('member_id', $id)->orderBy('period', 'asc')->paginate(50);
        $months = [
            'January' => '01','February' => '02','March' => '03',
            'April' => '04','May' => '05','June' => '06','July' => '07',
            'August' => '08','September' => '09','October' => '10',
            'November' => '11','December' => '12'
        ];
        $awards = AwardQualifier::where('member_id', $id)->get();
        // ddd($archievements);
        return view('pages.agents.edit', compact('yr', 'mth', 'sponser', 'sponsers', 'combPeriod', 'archievements', 'months', 'awards'));
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
        // ddd($combPeriodToday);
        return view('pages.agents.payment', compact('sponsers', 'sponser', 'combPeriodToday'));
    }
    public function printReport(Request $request)
    {
        // ddd($request->all());
        if(count($request->agents) < 1) {
            $request->session()->flash('alert-error', 'No agents selected or found!');
            return back();
        }
        // $agents = Agent::whereIn('member_id', $request->agents)->get();
        $sponser = Agent::where('member_id', $request->agents[0])->first();
        $sponsers =  Agent::where('sponser_id', $sponser->member_id)->get();
        $combPeriod = $request->combPeriod;
        $pdf = SnappyPdf::loadView('pages.pdfs.agent-report', [
            'sponsers' => $sponsers, 'sponser' => $sponser, 'combPeriod' => $combPeriod
        ]);
        $orientation = 'landscape';
        $paper = 'A5';
        $pdf->setOrientation($orientation)
        ->setOption('page-size', $paper)
        ->setOption('margin-bottom', '0mm')
        ->setOption('margin-top', '8.7mm')
        ->setOption('margin-right', '0mm')
        ->setOption('margin-left', '0mm')
        ->setOption('enable-javascript', true)
        ->setOption('no-stop-slow-scripts', true)
        ->setOption('enable-smart-shrinking', true)
        ->setOption('javascript-delay', 1000)
        ->setTimeout(120);
        $name = $this->combPeriodToday.'-'.$sponser->member_id.'.pdf';
        return $pdf->download($name);
        // return view('pages.pdfs.agent-report', compact('sponser', 'sponsers', 'combPeriod'));
    }
}
