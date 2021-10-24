<?php

namespace App\Http\Services;

use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;

class AwardLevelService
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
            if (floatval($agent->stats->acc_pvb) >= floatval(50) && floatval($agent->stats->acc_pvb) < floatval(200)) {
                $agent->stats->level = 2;
                $agent->level = 2;

            }elseif (floatval($agent->stats->acc_pvb) >= floatval(200)) {
                $agent->stats->level = 3;
                $agent->level = 3;
            }elseif (floatval($agent->stats->acc_gbv) >= floatval(800)) {
                $agent->stats->level = 4;
                $agent->level = 4;
            }elseif ($agent->sponsers->where('level', 4)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(5000)) {
                $agent->stats->level = 5;
                $agent->level = 5;
            }elseif ($agent->sponsers->where('level', 5)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(20000)) {
                $agent->stats->level = 6;
                $agent->level = 6;
            }elseif ($agent->sponsers->where('level', 6)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(80000)) {
                $agent->stats->level = 7;
                $agent->level = 7;
            }elseif ($agent->sponsers->where('level', 7)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(320000)) {
                $agent->stats->level = 8;
                $agent->level = 8;
            }else {
                $agent->stats->level = 1;
                $agent->level = 1;
            }
            $agent->stats->save();
            $agent->save();
        }

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
