<?php

namespace App\Http\Services;

use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;
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

    // public function ABP($memberid)
    // {
    //     $this->memberid = $memberid;
    //     $pd = CheckRunBill::where('type', 'gps')->where('period', $this->combPeriod)->first();
    //     $this->currentgbv();
    //     $this->accgbv();
    //     if(!$pd){


    //     }

    // }

    public function currentgbv($id)
    {
        $this->memberid = $id;
        $this->currentGBV = 0.0;
        $this->ACCGBV = 0.0;
        $agents =  Agent::where('sponser_id', $id)->get();
        $user =  Agent::where('member_id', $id)->first();

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
        PersonalBv::create([
            'member_id' => $this->memberid, 'period' => $this->combPeriod,
            'amount' => floatval($this->currentGBV)
        ]);


    }

    public function accgbv($id)
    {
        $this->memberid = $id;
        $this->currentGBV = 0.0;
        $this->ACCGBV = 0.0;
        $agents =  Agent::where('sponser_id', $id)->get();
        $user =  Agent::where('member_id', $id)->first();

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
        GroupBv::create([
            'member_id' => $this->memberid, 'period' => $this->combPeriod,
            'amount' => floatval($this->ACCGBV)
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


