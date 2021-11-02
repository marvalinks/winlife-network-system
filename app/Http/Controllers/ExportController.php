<?php

namespace App\Http\Controllers;

use App\Exports\AgentTempExport;
use App\Exports\ArchievementTempExport;
use App\Http\Services\GroupService;
use App\Jobs\CalculateBonus;
use App\Jobs\LevelServiceJob;
use App\Jobs\StatisticLogJob;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\Salary;
use App\Models\StatisticLog;
use App\Models\TemporalAchivement;
use App\Models\TemporalAgent;
use App\Models\UploadedData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\AgentUploadJob;
use App\Jobs\AchivementUploadJob;
use App\Jobs\CalcStatsJob;
use App\Jobs\NewAgentBigAgent;
use Illuminate\Support\Facades\Bus;

class ExportController extends Controller
{

    public $combPeriodToday;

    public function __construct()
    {
        $this->middleware('auth');
        $this->combPeriodToday = date('Y').date('m');
    }

    protected function start()
    {
        $this->dispatch(new LevelServiceJob($this->combPeriodToday));
        StatisticLog::truncate();
        Salary::truncate();
        $grp = new GroupService();
        $grp->GRP();
        $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        Salary::truncate();
        if(count($acs) > 0) {
            foreach ($acs as $key => $ac) {
                $this->dispatch(new CalculateBonus($ac));
                $this->dispatch(new StatisticLogJob($ac));
            }
        }
    }

    public function exportAR()
    {
        $name = date('Y').date('m').date('d');
        return Excel::download(new AgentTempExport, 'agent-register-'.$name.'.xlsx');
    }
    public function exportAA()
    {
        $name = date('Y').date('m').date('d');
        return Excel::download(new ArchievementTempExport, 'agent-achivement-'.$name.'.xlsx');
    }

    public function uploadExportR(Request $request)
    {
        $dd = TemporalAgent::all();
        $bset = [];
        foreach ($dd as $key => $export) {
            if(!$export->msponser){
                if(!$export->sponser){
                    array_push($bset, $export->member_id);
                }
            }
        }
        $agg = TemporalAgent::whereNotIn('member_id', $bset)->get();
        $ups = UploadedData::where('period', $agg[0]->period ?? $agg[1]->period)->where('data', 'r')->first();
        if($ups) {
            $request->session()->flash('alert-danger', 'Registration form already uploaded for '.$agg[0]->period ?? $agg[1]->period);
            return back();
        }
        // ddd($agg->where('sponser_id', '202110141234'));
        $jobs = [];
        foreach ($agg as $key => $ag) {
            if($ag->member_id != $ag->sponser_id) {
                if(!$ag->agent) {
                    $jobs[] = new AgentUploadJob($ag);
                    $jobs[] = new NewAgentBigAgent($ag);
                }
            }

        }
        $batch = Bus::batch($jobs)->dispatch();
        UploadedData::create([
            'data' => 'r', 'period' => $agg[0]->period ?? $agg[1]->period
        ]);
        $request->session()->flash('alert-success', 'Agent registration successfully uploaded!');
        return back();
    }
    public function uploadExportA(Request $request)
    {
        $jobs = [];
        $agg = TemporalAchivement::all();
        $ups = UploadedData::where('period', $agg[0]->period ?? $agg[1]->period)->where('data', 'a')->first();
        if($ups) {
            $request->session()->flash('alert-danger', 'Achivement form already uploaded for '.$agg[0]->period ?? $agg[1]->period);
            return back();
        }
        foreach ($agg as $key => $ag) {
            $jobs[] = new AchivementUploadJob($ag);
            // $agent = Agent::where('member_id', $ag->member_id)->first();
            // if($agent) {
            //     Achivement::create([
            //         'member_id' => $ag->member_id, 'name' => $agent->name,
            //         'period' => $ag->period, 'total_pv' => $ag->total_pv,
            //         'country' => $ag->country,
            //     ]);
            // }
        }

        // $jobs[] = new StatisticLogJob($agg[0]->period ?? $agg[1]->period);
        // $jobs[] = new CalculateBonus($agg[0]->period ?? $agg[1]->period);
        // $jobs[] = new CalcStatsJob($agg[0]->period ?? $agg[1]->period);

        $batch = Bus::batch($jobs)->dispatch();
        UploadedData::create([
            'data' => 'a', 'period' => $agg[0]->period ?? $agg[1]->period
        ]);
        // return redirect()->route('chain.data');
        // $this->start();
        $request->session()->flash('alert-success', 'Agent achivement successfully uploaded!');
        return back();
    }
}
