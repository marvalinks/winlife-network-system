<?php

namespace App\Http\Controllers;

use App\Http\Services\BonusService;
use App\Http\Services\GroupService;
use App\Http\Services\StatisticLogService;
use App\Imports\AgentTempImport;
use App\Imports\ArchievementTempImport;
use App\Jobs\CalcStatsJob;
use App\Jobs\CalculateBonus;
use App\Jobs\Gstats;
use App\Jobs\Pstats;
use App\Jobs\StatisticLogJob;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\CheckRunBill;
use App\Models\Salary;
use App\Models\StatisticLog;
use App\Models\TemporalAchivement;
use App\Models\TemporalAgent;
use App\Models\UploadedData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->start();
    }

    protected function start()
    {
        // StatisticLog::truncate();
        // Salary::truncate();
        // $grp = new GroupService();
        // $grp->GRP();
        $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        if(count($acs) > 0) {
            foreach ($acs as $key => $ac) {
                // $st = new StatisticLogService();
                // $st->ABP($ac);
                // $this->dispatch(new CalculateBonus($ac));
                // $this->dispatch(new StatisticLogJob($ac));
                // $this->dispatch(new CalcStatsJob($ac));
                // $pd = CheckRunBill::where('type', 'gps')->where('period', $ac)->first();
                // if(!$pd) {
                //     Agent::latest()->chunk(100, function ($users) use ($ac){
                //         foreach ($users as $user)  {
                //             if (intval($user->period) <= intval($ac)) {
                //                 $this->dispatch(new Gstats($ac, $user->member_id));
                //             }
                //         }
                //         CheckRunBill::create([
                //             'period' => $ac, 'type' => 'gps'
                //         ]);
                //     });
                // }

            }
        }

    }

    public function reloadStatistics()
    {
        $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        if(count($acs) > 0) {
            foreach ($acs as $key => $ac) {
                $pd = CheckRunBill::where('type', 'gps')->where('period', $ac)->first();
                if(!$pd) {
                    Agent::latest()->chunk(100, function ($users) use ($ac){
                        foreach ($users as $user)  {
                            if (intval($user->period) <= intval($ac)) {
                                $this->dispatch(new Gstats($ac, $user->member_id));
                                $this->dispatch(new Pstats($ac, $user->member_id));
                            }
                        }
                    });
                    CheckRunBill::create([
                        'period' => $ac, 'type' => 'gps'
                    ]);
                }

            }
        }
        die('completed');
    }

    public function dashboard(Request $request)
    {
        return view('pages.dashboard');
    }
    public function deleteDBS(Request $request)
    {
        return view('others.delete-dbs');
    }
    public function postdeleteDBS(Request $request)
    {
        $data = $request->validate([
            'type' => 'required', 'period' => 'required'
        ]);

        if($data['type'] == 'a') {
            $acs = Achivement::where('period', $data['period'])->get();
            foreach ($acs as $key => $ac) {
               $ac->delete();
            }
        }
        $ups = UploadedData::where('period', $data['period'])->where('data', 'a')->first();
        if($ups) {
            $ups->delete();
        }
        $request->session()->flash('alert-success', 'Data successfully deleted!');
        return back();
    }

    public function uploadRegistration(Request $request)
    {
        return view('others.uploads.upload-registration');
    }
    public function uploadAchivement(Request $request)
    {
        error_reporting(E_ALL);
        ini_set('post_max_size', '6G');
        ini_set('upload_max_filesize', '4G');
        ini_set('display_errors', 1);
        ini_set('memory_limit', '-1');
        return view('others.uploads.upload-achivement');
    }
    public function postuploadRegistration(Request $request)
    {
        TemporalAgent::truncate();
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        try {
            Excel::import(new AgentTempImport(), $data['file']);
        } catch (\Throwable $th) {
            return back()->withError('There was a problem with your excel file.');
        }
        $this->fixTemp();
        $exports = TemporalAgent::latest()->get();
        return view('others.uploads.upload-registration', compact('exports'));
    }
    public function postuploadAchivement(Request $request)
    {

        // error_reporting(E_ALL);
        // ini_set('post_max_size', '6G');
        // ini_set('upload_max_filesize', '4G');
        // ini_set('display_errors', 1);
        // ini_set('memory_limit', '-1');
        // // ini_set('max_execution_time', 0);
        // ddd($request->all());
        TemporalAchivement::truncate();
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        try {
            Excel::import(new ArchievementTempImport(), $data['file']);
        } catch (\Throwable $th) {
            return back()->withError('There was a problem with your excel file.');
        }
        $this->fixTemp();
        $aexports = TemporalAchivement::latest()->get();
        return view('others.uploads.upload-achivement', compact('aexports'));
    }

    protected function fixTemp()
    {
        $dels = TemporalAgent::where('member_id', '')->orWhereNull('member_id')->get();
        $ass = TemporalAchivement::where('member_id', '')->orWhereNull('member_id')->get();
        foreach ($dels as $key => $del) {
            $del->delete();
        }
        foreach ($ass as $key => $del) {
            $del->delete();
        }

    }


}
