<?php

namespace App\Http\Services;

use App\Models\CheckRunBill;
use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\BigAgent;
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
        $pd = CheckRunBill::where('type', 'statistics')->where('period', $period)->first();
        if(!$pd){
            $this->combPeriodToday = intval($period);


            $agents = Agent::where('period', '<=', $this->combPeriodToday)->latest()->get();
            foreach ($agents as $key => $agent) {
                if (intval($agent->period) <= intval($this->combPeriodToday)) {
                    $this->adjustBulkPvb($agent);
                    $log = StatisticLog::where('period', $this->combPeriodToday)->where('member_id', $agent->member_id)->first();
                    $salary = Salary::where('period', $this->combPeriodToday)->where('member_id', $agent->member_id)->first();
                    $logs = StatisticLog::distinct('period')->where('member_id', $agent->member_id)
                            ->where('owe_bl', '>', 0)->get();
                    // ddd($log);
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

                    // ddd($log);

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
            CheckRunBill::create([
                'period' => $period, 'type' => 'statistics'
            ]);
        }

    }

    protected function adjustBulkPvb($user)
    {
        $this->currentGBV = floatval(0);
        $this->ACCGBV = floatval(0);
        $this->ACCGBV = floatval(0);

        $st = StatisticLog::where('member_id', $user->member_id)->where('period', intval($this->combPeriodToday))->first();
        if(!$st) {

            if (intval($user->period) <= intval($this->combPeriodToday)) {
                $achTotal = floatval($user->currentach($this->combPeriodToday)->sum('total_pv'));
                $achTotal2 = $user->archievements->whereBetween('period', [$user->archievements->min('period'), intval($this->combPeriodToday+1)])->sum('total_pv') ?? floatval(0);
                $this->currentGBV = $user->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), intval($this->combPeriodToday+1)])->sum('total_pv') ?? floatval(0);

                $sponsers =  BigAgent::where('parent_id', $user->member_id)->where('period', '<=', $this->combPeriodToday)->get();

                foreach ($sponsers as $key => $spp) {
                    $this->currentGBV += $spp->archievements->where('period', $this->combPeriodToday)->sum('total_pv') ?? floatval(0);
                    $this->ACCGBV += $spp->archievements->whereBetween('period', [$user->archievements->min('period'), intval($this->combPeriodToday+1)])->sum('total_pv') ?? floatval(0);
                }
                // ddd($achTotal);
                $log = StatisticLog::where('period', intval($this->combPeriodToday-1))->where('member_id', $user->member_id)->first();
                $stats = new StatisticLog();
                $stats->member_id = $user->member_id;
                $stats->period = $this->combPeriodToday;
                $stats->current_pbv = $achTotal;
                $stats->acc_pvb = $achTotal2;
                $stats->current_gbv = $this->currentGBV;
                $stats->acc_gbv = $this->ACCGBV;
                $stats->level = $log->level ?? 1;

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
