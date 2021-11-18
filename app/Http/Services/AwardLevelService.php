<?php

namespace App\Http\Services;

use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\AwardLevel;
use App\Models\BigAgent;
use App\Models\CheckRunBill;

class AwardLevelService
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
        $pd = CheckRunBill::where('type', 'award_level')->where('period', $period)->first();
        if(!$pd){
            $this->combPeriodToday = intval($period);
            $agents = Agent::latest()->get();
            foreach ($agents as $key => $agent) {
                $this->adjustBulkPvb($agent);
                $log = AwardLevel::where('period', $this->combPeriodToday)->where('member_id', $agent->member_id)->first();
                $logs = AwardLevel::where('period', $this->combPeriodToday)->where('sponser_id', $agent->sponser_id);
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
                if ($logs->where('level', '>=', 4)->count() >= 4 && floatval($log->acc_gbv) >= floatval(5000)) {
                    $log->level = 5;
                }
                if ($logs->where('level', '>=', 5)->count() >= 4 && floatval($log->acc_gbv) >= floatval(20000)) {
                    $log->level = 6;
                }
                if ($logs->where('level', '>=', 6)->count() >= 4 && floatval($log->acc_gbv) >= floatval(80000)) {
                    $log->level = 7;
                }
                if ($logs->where('level', '>=', 7)->count() >= 4 && floatval($log->acc_gbv) >= floatval(320000)) {
                    $log->level = 8;
                }
                $log->save();
            }
            CheckRunBill::create([
                'period' => $period, 'type' => 'award_level'
            ]);
        }

    }

    protected function adjustBulkPvb($user)
    {
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $this->ACCGBV = floatval(0);

        $st = AwardLevel::where('member_id', $user->member_id)->where('period', intval($this->combPeriodToday))->first();
        if(!$st) {

            if (intval($user->period) <= intval($this->combPeriodToday)) {
                $achTotal = floatval($user->currentach($this->combPeriodToday)->sum('total_pv'));
                $achTotal2 = $user->archievements->whereBetween('period', ['201307', $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                $this->currentGBV = $user->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV = $user->archievements->whereBetween('period', ['201307', $this->combPeriodToday])->sum('total_pv') ?? floatval(0);

                $sponsers =  BigAgent::where('parent_id', $user->member_id)->where('period', '<=', $this->combPeriodToday)->get();

                foreach ($sponsers as $key => $spp) {
                    $this->currentGBV += $spp->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                    $this->ACCGBV += $spp->archievements->whereBetween('period', ['201307', $this->combPeriodToday])->sum('total_pv') ?? floatval(0);
                }
                // ddd($achTotal);

                $stats = new AwardLevel();
                $stats->member_id = $user->member_id;
                $stats->period = $this->combPeriodToday;
                $stats->current_pbv = $achTotal;
                $stats->acc_pvb = $achTotal2;
                $stats->current_gbv = $this->currentGBV;
                $stats->acc_gbv = $this->ACCGBV;
                $stats->sponser_id = $user->sponser_id ?? null;
                $stats->save();
            }
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
