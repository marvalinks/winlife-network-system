<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\AgentStatistics;

class TestCampController extends Controller
{
    public $sponsers;
    public $user;
    public $memberid = "";
    public $showtable = false;
    public $excelfile;
    public $excelLoading = false;
    public $excelLoadingSuccess = false;
    public $achfile;
    public $months;
    public $selectedYear = "2021";
    public $selectedMonth = "10";
    public $combPeriod;
    public $combPeriodToday;
    public $disbutton = true;
    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;

    public function __construct()
    {
        $this->sponsers = Agent::latest()->get();
        $this->combPeriodToday = date('Y').date('m');
        $this->months = [
            'January' => '01','February' => '02','March' => '03',
            'April' => '04','May' => '05','June' => '06','July' => '07',
            'August' => '08','September' => '09','October' => '10',
            'November' => '11','December' => '12'
        ];
        // $this->memberid = Agent::first()->member_id ?? '';
    }

    public function search(Request $request)
    {
        $months = [
            'January' => '01','February' => '02','March' => '03',
            'April' => '04','May' => '05','June' => '06','July' => '07',
            'August' => '08','September' => '09','October' => '10',
            'November' => '11','December' => '12'
        ];
        
        if($request->member_id) {
            $id = $request->member_id;
            $this->combPeriod = $this->selectedYear.''. $this->selectedMonth;
            $agents =  Agent::where('sponser_id', $id)->simplePaginate(10);
            $user =  Agent::where('member_id', $id)->first();
            if($user) {
                $user = $user;
                $sponsers = $agents;
                
            }

            $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

            foreach ($agents as $key => $sponser) {
                $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                foreach ($sponser->childrenSponsers as $k => $child_sponser) {
                    $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                    $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                    $this->reloop($child_sponser);
                }
            }
            $sh = 1;
            $showtable = 1;
            $combPeriod = $this->combPeriod;
            $currentGBV = $this->currentGBV;
            $ACCGBV = $this->ACCGBV;
            return view('pages.agents.index', compact('currentGBV', 'ACCGBV','combPeriod', 'months', 'sh', 'showtable', 'user', 'sponsers'));
        }
        
        return view('pages.agents.index', compact('months'));
        
    }
    public function reloop($child_sponser)
    {
        if ($child_sponser->sponsers) {
            foreach ($child_sponser->sponsers as $k => $childrenSponser) {
                $this->currentGBV += $childrenSponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $childrenSponser->archievements->whereBetween('period', [$childrenSponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                $this->reloop($childrenSponser);
            }
        }
    }
}
