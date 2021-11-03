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
        // $this->start();
    }

    protected function start()
    {
        $awd = new AwardService($this->combPeriodToday);
        $awd->ABP();
    }

    public function index(Request $request)
    {
        // ddd($request->all());
        $month = date('m');
        $year = intval(date('Y'));
        $memberid = "";
        $combPeriodToday = $this->combPeriodToday;
        $awards = Award::orderBy('order', 'asc')->get();
        $agents = Agent::query();
        if ($request->member_id) {
            $agents = $agents->where('member_id', $request->member_id);
            $memberid = $request->member_id;
        }
        if($request->selectedYear && $request->selectedMonth) {
            $combPeriodToday = $request->selectedYear.$request->selectedMonth;
            $year = intval($request->selectedYear);
            $month = $request->selectedMonth;
        }
        // ddd($this->combPeriodToday);
        $callback = function($query) use ($combPeriodToday) {
            $query->where('period', $combPeriodToday);
        };
        // ddd($agents);
        $agents = $agents->whereHas('awards', $callback)->with(['awards' => $callback])->latest()->paginate(30);


        // ddd($agents);

        $months = [
            'January' => '01','February' => '02','March' => '03',
            'April' => '04','May' => '05','June' => '06','July' => '07',
            'August' => '08','September' => '09','October' => '10',
            'November' => '11','December' => '12'
        ];
        // ddd($awards);
        return view('pages.awards.index', compact('awards', 'agents', 'months', 'month', 'year', 'memberid'));
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
