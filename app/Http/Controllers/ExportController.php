<?php

namespace App\Http\Controllers;

use App\Exports\AgentTempExport;
use App\Exports\ArchievementTempExport;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\TemporalAgent;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{

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
            if(!isset($export->sponser)){
                array_push($bset, $export->member_id);
            }
            if(isset($export->msponser) && !isset($export->sponser)){
                array_push($bset, $export->member_id);
            }
        }
        $agg = TemporalAgent::whereNotIn('member_id', $bset)->get();
        foreach ($agg as $key => $ag) {
            Agent::create([
                'member_id' => $ag->member_id, 'sponser_id' => $ag->sponser_id,
                'firstname' => $ag->firstname, 'lastname' => $ag->lastname,
                'telephone' => $ag->telephone, 'address' => $ag->address,
                'period' => $ag->period, 'nationality' => $ag->nationality,
                'bank_name' => $ag->bank_name, 'bank_no' => $ag->bank_no,
            ]);
        }
        $request->session()->flash('alert-success', 'Agent registration successfully uploaded!');
        return back();
    }
    public function uploadExportA(Request $request)
    {
        // ddd($request->all());
        foreach ($request->member_id as $key => $member_id) {
            $agent = Agent::where('member_id', $member_id)->first();
            if($agent) {
                Achivement::create([
                    'member_id' => $member_id, 'name' => $agent->name,
                    'period' => $request->period[$key], 'total_pv' => $request->total_pv[$key],
                    'country' => $request->country[$key],
                ]);
            }
        }
        $request->session()->flash('alert-success', 'Agent achivement successfully uploaded!');
        return back();
    }
}
