<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\Salary;
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
            $salary = Salary::where('period', $this->combPeriodToday)->where('member_id', $agent->member_id)->first();
            $logs = StatisticLog::distinct('period')->where('member_id', $agent->member_id)
                    ->where('owe_bl', '>', 0)->get();
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
                if (floatval($log->acc_gbv) >= floatval(3000)) {
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

            if($log->level === 5) {

                $cpv = $log->current_pbv;
                foreach ($logs as $key => $lg) {
                    $a = floatval($lg->owe_bl) - floatval($lg->paid_bl);
                    if(floatval($cpv) >= $a) {
                        $b = $cpv - $a;
                        $cpv = $b;
                        $lg->paid_bl = $a;
                        $lg->save();
                        if(floatval($lg->owe_bl) === floatval($lg->paid_bl)) {
                            $sl = Salary::where('period', $lg->period)->where('member_id', $agent->member_id)->first();
                            if($sl) {
                                $sl->active = 1;
                                $sl->save();
                            }
                        }

                    }

                }

                if(floatval($cpv) < floatval(15)) {
                    $log->owe_bl = floatval(15) - floatval($cpv);
                    $log->save();
                    if($salary) {
                        $salary->active = 0;
                        $salary->save();
                    }
                }
            }
            if($log->level === 6) {
                $cpv = $log->current_pbv;
                foreach ($logs as $key => $lg) {
                    $a = floatval($lg->owe_bl) - floatval($lg->paid_bl);
                    if(floatval($cpv) >= $a) {
                        $b = $cpv - $a;
                        $cpv = $b;
                        $lg->paid_bl = $a;
                        $lg->save();
                        if(floatval($lg->owe_bl) === floatval($lg->paid_bl)) {
                            $sl = Salary::where('period', $lg->period)->where('member_id', $agent->member_id)->first();
                            if($sl) {
                                $sl->active = 1;
                                $sl->save();
                            }
                        }

                    }

                }
                if(floatval($cpv) < floatval(20)) {
                    $log->owe_bl = floatval(20) - floatval($cpv);
                    $log->save();
                    if($salary) {
                        $salary->active = 0;
                        $salary->save();
                    }
                }
            }
            if($log->level === 7) {
                $cpv = $log->current_pbv;
                foreach ($logs as $key => $lg) {
                    $a = floatval($lg->owe_bl) - floatval($lg->paid_bl);
                    if(floatval($cpv) >= $a) {
                        $b = $cpv - $a;
                        $cpv = $b;
                        $lg->paid_bl = $a;
                        $lg->save();
                        if(floatval($lg->owe_bl) === floatval($lg->paid_bl)) {
                            $sl = Salary::where('period', $lg->period)->where('member_id', $agent->member_id)->first();
                            if($sl) {
                                $sl->active = 1;
                                $sl->save();
                            }
                        }

                    }

                }
                if(floatval($cpv) < floatval(25)) {
                    $log->owe_bl = floatval(25) - floatval($cpv);
                    $log->save();
                    if($salary) {
                        $salary->active = 0;
                        $salary->save();
                    }
                }
            }
            if($log->level === 8) {
                $cpv = $log->current_pbv;
                foreach ($logs as $key => $lg) {
                    $a = floatval($lg->owe_bl) - floatval($lg->paid_bl);
                    if(floatval($cpv) >= $a) {
                        $b = $cpv - $a;
                        $cpv = $b;
                        $lg->paid_bl = $a;
                        $lg->save();
                        if(floatval($lg->owe_bl) === floatval($lg->paid_bl)) {
                            $sl = Salary::where('period', $lg->period)->where('member_id', $agent->member_id)->first();
                            if($sl) {
                                $sl->active = 1;
                                $sl->save();
                            }
                        }

                    }

                }
                if(floatval($cpv) < floatval(40)) {
                    $log->owe_bl = floatval(40) - floatval($cpv);
                    $log->save();
                    if($salary) {
                        $salary->active = 0;
                        $salary->save();
                    }
                }
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
