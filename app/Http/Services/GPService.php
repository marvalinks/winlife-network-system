<?php

namespace App\Http\Services;

use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\BigAgent;
use App\Models\CheckGsjRun;
use App\Models\CheckRunBill;
use App\Models\GroupBv;
use App\Models\PersonalBv;

class GPService
{

    public $combPeriod;
    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;
    public $memberid;

    public function __construct($period)
    {
        $this->combPeriod = $period;
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
    }

    public function start()
    {
        $pd = CheckRunBill::where('type', 'gps')->where('period', $this->combPeriod)->first();
        if(!$pd){
            $agents = Agent::latest()->pluck('member_id');
            foreach ($agents as $agent) {
                $this->currentgbv($agent);
                $this->accgbv($agent);
            }
            CheckRunBill::create([
                'period' => $this->combPeriod, 'type' => 'gps'
            ]);
        }

    }

    public function start2($id)
    {
        $pd = CheckGsjRun::where('member_id', $id)->where('period', $this->combPeriod)->first();
        if(!$pd) {
            $this->currentgbv($id);
            $this->accgbv($id);
            CheckGsjRun::create(['member_id' => $id, 'period' => $this->combPeriod]);
        }
    }

    public function currentgbv($id)
    {
        $this->memberid = $id;
        // $this->currentGBV = 0.0;
        // $this->ACCGBV = 0.0;
        $user =  Agent::where('member_id', $id)->first();

        // $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        // $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        // $agents =  BigAgent::where('parent_id', $id)->where('period', '<=', $this->combPeriod)->get();

        // foreach ($agents as $key => $sponser) {
        //     $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        //     $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
        // }
        // $pv = PersonalBv::where('member_id', $this->memberid)->where('period', $this->combPeriod)->first();
        // if($pv) {
        //     $pv->delete();
        // }
        // ddd($user->currentgbv($this->combPeriod));
        PersonalBv::create([
            'member_id' => $this->memberid, 'period' => $this->combPeriod,
            'amount' => floatval($user->currentgbv($this->combPeriod))
        ]);


    }

    public function accgbv($id)
    {
        $this->memberid = $id;
        // $this->currentGBV = 0.0;
        // $this->ACCGBV = 0.0;
        // $agents =  BigAgent::where('parent_id', $id)->where('period', '<=', $this->combPeriod)->get();
        $user =  Agent::where('member_id', $id)->first();

        // $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        // $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        // foreach ($agents as $key => $sponser) {

        //     $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        //     $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        // }
        // $gv = GroupBv::where('member_id', $this->memberid)->where('period', $this->combPeriod)->first();
        // if($gv) {
        //     $gv->delete();
        // }
        // ddd($user->currentgbv($this->combPeriod));
        GroupBv::create([
            'member_id' => $this->memberid, 'period' => $this->combPeriod,
            'amount' => floatval($user->accgbv($this->combPeriod))
        ]);

    }

    protected function reloop($child_sponser)
    {
        if ($child_sponser->sponsers) {
            foreach ($child_sponser->sponsers as $k => $childrenSponser) {
                // $this->currentGBV += 1;
                $this->currentGBV += $childrenSponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $childrenSponser->archievements->whereBetween('period', [$childrenSponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                // $this->ACCGBV += 1;
                $this->reloop($childrenSponser);
            }
        }
    }

}


