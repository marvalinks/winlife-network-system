<?php

namespace App\Http\Controllers;

use App\Http\Services\AwardService;
use App\Http\Services\BigAgentService;
use App\Http\Services\BonusService;
use App\Http\Services\GPService;
use App\Http\Services\GroupService;
use App\Http\Services\StatisticLogService;
use App\Imports\AgentTempImport;
use App\Imports\ArchievementTempImport;
use App\Jobs\AgentUploadJob;
use App\Jobs\AwardServiceJob;
use App\Jobs\CalcStatsJob;
use App\Jobs\CalculateBonus;
use App\Jobs\GroupServiceJob;
use App\Jobs\Gstats;
use App\Jobs\LevelServiceJob;
use App\Jobs\Pstats;
use App\Jobs\StatisticLogJob;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\BigAgent;
use App\Models\CheckRunBill;
use App\Models\Salary;
use App\Models\StatisticLog;
use App\Models\TemporalAchivement;
use App\Models\TemporalAgent;
use App\Models\UploadedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class AdminController extends Controller
{

    public $combPeriodToday;
    public function __construct()
    {
        $this->middleware('auth');
        $this->combPeriodToday = date('Y').date('m');
        // $this->start();
    }

    public function testData()
    {

        BigAgent::truncate();
        $ss = new BigAgentService();
        $agents = Agent::latest()->pluck('member_id');
        // ddd($agents);
        foreach ($agents as $key => $agent) {
            // $ss->mk($agent);
            $this->dispatch(new AgentUploadJob($agent));
        }
        die('done processing...');
    }

    protected function start()
    {
        $this->dispatch(new LevelServiceJob($this->combPeriodToday));
        $this->dispatch(new AwardServiceJob($this->combPeriodToday));

        $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        $jobs = [];
        $batchid = '';

        if(count($acs) > 0) {
            foreach ($acs as $key => $ac) {

                //calculating the Statistcis
                // $jobs[] = new StatisticLogJob($ac);
                //calculating the Salary
                // $jobs[] = new CalculateBonus($ac);
                //calculating the groubBV and personalBV
                $jobs[] = new CalcStatsJob($ac);

                // $batch = Bus::batch([
                //     new StatisticLogJob($ac),
                //     new CalculateBonus($ac)
                // ])->dispatch();

            }
        }

    }

    public function chainJobs(Request $request)
    {


        // $aw = new AwardService('201509');
        // $aw->ABP();
        // ddd('okay');
        $acs = Achivement::distinct('period')->orderBy('period', 'asc')->pluck('period');
        $jobs = [];
        // ddd($acs);
        $jobs[] = new GroupServiceJob();
        if(count($acs) > 0) {
            foreach ($acs as $key => $ac) {
                $jobs[] = new StatisticLogJob($ac);
                // calculating the Salary
                $jobs[] = new CalculateBonus($ac);
                //calculating the groubBV and personalBV
                // $jobs[] = new CalcStatsJob($ac);
                // $this->calcStats($ac);
                // $jobs[] = new AwardServiceJob($ac);
            }
        }
        // $jobs[] = new StatisticLogJob('201507');
        $batch = Bus::batch($jobs)->dispatch();
        // return $batch->id;
        if(count($jobs) > 0) {
            $request->session()->flash('alert-success', 'Achivement successfully uploaded. Please wait, bonus is calculating...');
            return redirect()->route('batch.progress');
            // return redirect()->route('admin.dashboard', ['batch_id' => $batch->id]);
        }
        return back();
    }

    protected function calcStats($period)
    {

        // $pd = CheckRunBill::where('type', 'gps')->where('period', $period)->first();

        // if(!$pd){
            $jobs = [];
            $agents = Agent::latest()->pluck('member_id');
            // ddd($agents);
            foreach ($agents as $agent) {
                $jobs[] = new CalcStatsJob($period, $agent);
            }
            // ddd($jobs);

            // CheckRunBill::create([
            //     'period' => $this->combPeriod, 'type' => 'gps'
            // ]);
        // }
            // $jobs[] = new CalcStatsJob($period, $agent);
            $batch = Bus::batch($jobs)->dispatch();

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
        // $batch = $this->start();
        $batch = null;
        if($request->batch_id) {
            $batch = Bus::findBatch($request->batch_id);
            ddd($batch);
        }
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
            $request->session()->flash('alert-error', 'There was a problem with your excel file.');
            return back();
        }
        $this->fixTemp();
        $exports = TemporalAgent::latest()->paginate(100);
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

        TemporalAchivement::truncate();
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        // ddd($request->all());
        try {
            Excel::import(new ArchievementTempImport(), $data['file']);
        } catch (\Throwable $th) {
            $request->session()->flash('alert-error', 'There was a problem with your excel file.');
            return back();
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
