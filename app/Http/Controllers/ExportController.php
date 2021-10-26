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
        foreach ($agg as $key => $ag) {
            if(!$ag->agent) {
                Agent::create([
                    'member_id' => $ag->member_id, 'sponser_id' => $ag->sponser_id,
                    'firstname' => $ag->firstname, 'lastname' => $ag->lastname,
                    'telephone' => $ag->telephone, 'address' => $ag->address,
                    'period' => $ag->period, 'nationality' => $ag->nationality,
                    'bank_name' => $ag->bank_name, 'bank_no' => $ag->bank_no,
                ]);
            }
        }
        UploadedData::create([
            'data' => 'r', 'period' => $agg[0]->period ?? $agg[1]->period
        ]);
        $request->session()->flash('alert-success', 'Agent registration successfully uploaded!');
        return back();
    }
    public function uploadExportA(Request $request)
    {
        $agg = TemporalAchivement::all();
        $ups = UploadedData::where('period', $agg[0]->period ?? $agg[1]->period)->where('data', 'a')->first();
        if($ups) {
            $request->session()->flash('alert-danger', 'Achivement form already uploaded for '.$agg[0]->period ?? $agg[1]->period);
            return back();
        }
        foreach ($agg as $key => $ag) {
            $agent = Agent::where('member_id', $ag->member_id)->first();
            if($agent) {
                Achivement::create([
                    'member_id' => $ag->member_id, 'name' => $agent->name,
                    'period' => $ag->period, 'total_pv' => $ag->total_pv,
                    'country' => $ag->country,
                ]);
            }
        }

        UploadedData::create([
            'data' => 'a', 'period' => $agg[0]->period ?? $agg[1]->period
        ]);
        $this->start();
        $request->session()->flash('alert-success', 'Agent achivement successfully uploaded!');
        return back();
    }
}
