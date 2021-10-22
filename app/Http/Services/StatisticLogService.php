<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\StatisticLog;

class StatisticLogService
{

    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;
    public $combPeriodToday;

    public function __construct()
    {

        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);

    }

    public function ABP($period)
    {
        $this->combPeriodToday = $period;
        $this->adjustBulkPvb();
        $agents = Agent::latest()->get();
        foreach ($agents as $key => $agent) {
            $log = StatisticLog::where('period', $this->combPeriodToday)->where('member_id', $agent->member_id)->first();
            // if($this->combPeriodToday === '201310' && $agent->member_id == '202110141234') {
            //     $log2 = StatisticLog::where('period', $this->combPeriodToday)->where('member_id', $agent->member_id)->get();
            //     ddd($log2);
            // }
            if($log) {
                if (floatval($log->acc_pvb) < floatval(50)) {
                    $log->level = 1;
                }
                if (floatval($log->acc_pvb) >= floatval(50) && floatval($log->acc_pvb) < floatval(200)) {
                    $log->level = 2;

                }
                if (floatval($log->acc_pvb) >= floatval(200)) {
                    $log->level = 3;
                }
                if (floatval($log->acc_gbv) >= floatval(800)) {
                    $log->level = 4;
                }
                if (floatval($log->acc_gbv) >= floatval(5000)) {
                    $log->level = 5;

                }
                if (floatval($log->acc_gbv) >= floatval(20000)) {
                    $log->level = 6;
                }
                if (floatval($log->acc_gbv) >= floatval(80000)) {
                    $log->level = 7;
                }
                if (floatval($log->acc_gbv) >= floatval(320000)) {
                    $log->level = 8;
                }
                $log->save();
            }

        }

    }

    protected function adjustBulkPvb()
    {
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $users = Agent::latest()->get();
        foreach ($users as $key => $user) {

            // $stats = AgentStatistics::where('agent_id', $user->member_id)->first();
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
            // if($this->combPeriodToday === '201310' && $user->member_id == '202110141234') {
            //     ddd($this->ACCGBV);
            // }
            $stats = new StatisticLog();
            $stats->member_id = $user->member_id;
            $stats->period = $this->combPeriodToday;
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
