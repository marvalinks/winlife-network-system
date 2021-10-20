<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\AgentStatistics;

class AwardService
{

    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;
    public $combPeriodToday;

    public function __construct($period)
    {
        $this->combPeriodToday = $period;
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
    }

    public function ABP()
    {
        $this->adjustBulkPvb();
        $agents = Agent::latest()->get();
        foreach ($agents as $key => $agent) {
            $sponsers = Agent::where('sponser_id', $agent->member_id)->get();
            if($sponsers->where('level', 5)->count() === 4) {
                if(floatval($agent->stats->acc_gbv) >= floatval(20000)) {
                    $award = 'International Trip Award';
                }
            }
            if($agent->sponsers->count() >= 4) {

            }
        }

        // foreach ($agents as $key => $agent) {
        //     if ($agent->sponsers->where('level', 4)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(5000)) {
        //         $award = 'Tv award';
        //     }elseif ($agent->sponsers->where('level', 5)->count() === 4 && floatval($agent->stats->acc_gbv) >= floatval(20000)) {
        //         $award = 'International Trip Award';
        //     }elseif ($agent->sponsers->where('level', 6)->count() >= 2 && floatval($agent->stats->acc_gbv) >= floatval(210000)) {
        //         $award = 'Small Car Award';
        //     }elseif ($agent->sponsers->where('level', 6)->count() >= 3 && floatval($agent->stats->acc_gbv) >= floatval(85000)) {
        //         $agent->stats->level = 7;
        //         $agent->level = 7;
        //     }elseif ($agent->sponsers->where('level', 6)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(72000)) {
        //         $agent->stats->level = 7;
        //         $agent->level = 7;
        //     }elseif ($agent->sponsers->where('level', 7)->count() >= 2 && floatval($agent->stats->acc_gbv) >= floatval(600000)) {
        //         $agent->stats->level = 8;
        //         $agent->level = 8;
        //     }elseif ($agent->sponsers->where('level', 7)->count() >= 3 && floatval($agent->stats->acc_gbv) >= floatval(270000)) {
        //         $agent->stats->level = 8;
        //         $agent->level = 8;
        //     }elseif ($agent->sponsers->where('level', 7)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(260000)) {
        //         $agent->stats->level = 8;
        //         $agent->level = 8;
        //     }else {
        //         $agent->stats->level = 1;
        //         $agent->level = 1;
        //     }
        //     $agent->stats->save();
        //     $agent->save();
        // }

    }

    protected function adjustBulkPvb()
    {
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $users = Agent::latest()->get();
        foreach ($users as $key => $user) {

            $stats = AgentStatistics::where('agent_id', $user->member_id)->first();
            $achTotal = floatval($user->currentach($this->combPeriodToday)->sum('total_pv'));
            $achTotal2 = $user->archievements->sum('total_pv');
            $this->currentGBV = $user->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
            foreach ($user->sponsers as $key => $spp) {
                $this->currentGBV += $spp->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $spp->archievements->whereBetween('period', [$spp->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                foreach ($spp->childrenSponsers as $k => $child_sponser) {
                    $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                    $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                    $this->reloop($child_sponser);
                }
            }
            // if($user->member_id == '201266664521') {
            //     ddd($achTotal2);
            // }
            $stats->current_pbv = $achTotal;
            $stats->acc_pvb = $achTotal2;
            $stats->current_gbv = $this->currentGBV;
            $stats->acc_gbv = $this->ACCGBV;
            $stats->save();
        }
    }

    public function reloop($child_sponser)
    {
        if ($child_sponser->sponsers) {
            foreach ($child_sponser->sponsers as $k => $childrenSponser) {
                $this->currentGBV += $childrenSponser->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $childrenSponser->archievements->whereBetween('period', [$childrenSponser->archievements->min('period'), $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                $this->reloop($childrenSponser);
            }
        }
    }

}
