<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\AgentStatistics;
use App\Models\AwardQualifier;
use App\Models\CheckRunBill;

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
        $pd = CheckRunBill::where('type', 'awards')->where('period', $this->combPeriodToday)->first();
        if (!$pd) {
            $this->start();
            CheckRunBill::create([
                'period' => $this->combPeriodToday, 'type' => 'awards'
            ]);
        }
    }

    public function start()
    {
        $agents = Agent::latest()->get();
        foreach ($agents as $key => $agent) {
            $sponsers = Agent::where('sponser_id', $agent->member_id)->get();
            // {{$user->awardlogs->where('period', $combPeriod)->first()->level ?? 1 ?? 'NA'}}
            //trip award
            foreach ($sponsers as $key => $sponser) {
                $ct = 0;
                if ($sponser->awardlogs->where('period', $this->combPeriodToday)->first()->level ?? 1 >= 5) {
                    $ct++;
                }
                if ($ct >= 4) {
                    if (floatval($agent->accgbv($this->combPeriodToday)) >= floatval(20000)) {
                        $award = 'International Trip Award';
                        $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '0211')->first();
                        if (!$awd) {
                            AwardQualifier::create([
                                'award_id' => '0211', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                            ]);
                        }
                    }
                }
            }
            // if($sponsers->where('level', 5)->count() === 4) {
            //     if(floatval($agent->accgbv($this->combPeriodToday)) >= floatval(20000)) {
            //         $award = 'International Trip Award';
            //         $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '0211')->first();
            //         if(!$awd) {
            //             AwardQualifier::create([
            //                 'award_id' => '0211', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
            //             ]);
            //         }
            //     }
            // }
            //car award
            foreach ($sponsers as $key => $sponser) {
                $ct = 0;
                if ($sponser->awardlogs->where('period', $this->combPeriodToday)->first()->level ?? 1 >= 6) {
                    $ct++;
                }
                if ($ct >= 4) {
                    if (floatval($agent->accgbv($this->combPeriodToday)) >= floatval(80000)) {
                        $award = 'Small Car Award';

                        $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '5D94B98A')->first();
                        if (!$awd) {
                            AwardQualifier::create([
                                'award_id' => '5D94B98A', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                            ]);
                        }
                    }
                }
            }
            if ($sponsers->count() >= 4) {
                $cnt = 0;
                foreach ($sponsers as $key => $sponser) {
                    if (floatval($sponser->accgbv($this->combPeriodToday)) >= floatval(20000)) {
                        $cnt++;
                    }
                }
                if ($cnt >= 4) {
                    $award = 'Small Car Award';

                    $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '5D94B98A')->first();
                    if (!$awd) {
                        AwardQualifier::create([
                            'award_id' => '5D94B98A', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                        ]);
                    }
                }
            } elseif ($sponsers->where('level', 6)->count() == 3 && floatval($agent->accgbv($this->combPeriodToday)) >= floatval(85000)) {
                $award = 'Small Car Award';
                $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '5D94B98A')->first();
                if (!$awd) {
                    AwardQualifier::create([
                        'award_id' => '5D94B98A', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                    ]);
                }
            } elseif ($sponsers->where('level', 6)->count() == 2 && floatval($agent->accgbv($this->combPeriodToday)) >= floatval(210000)) {
                $award = 'Small Car Award';
                $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '5D94B98A')->first();
                if (!$awd) {
                    AwardQualifier::create([
                        'award_id' => '5D94B98A', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                    ]);
                }
            }
            //cash award

            if ($sponsers->count() >= 4) {
                $cnt = 0;
                foreach ($sponsers as $key => $sponser) {
                    if (floatval($sponser->acc_gbv) >= floatval(80000)) {
                        $cnt++;
                    }
                }
                if ($cnt >= 4) {
                    $award = 'Cash award';
                    AwardQualifier::create([
                        'award_id' => 'CF29', 'member_id' => $agent->member_id
                    ]);
                }
            } elseif ($sponsers->where('level', 7)->count() == 3 && floatval($agent->accgbv($this->combPeriodToday)) >= floatval(380000)) {
                $award = 'Cash award';

                $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', 'CF29')->first();
                if (!$awd) {
                    AwardQualifier::create([
                        'award_id' => 'CF29', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                    ]);
                }
            } elseif ($sponsers->where('level', 6)->count() == 2 && floatval($agent->accgbv($this->combPeriodToday)) >= floatval(650000)) {
                $award = 'Cash award';
                $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', 'CF29')->first();
                if (!$awd) {
                    AwardQualifier::create([
                        'award_id' => 'CF29', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                    ]);
                }
            }

            if ($agent->level >= 8) {
                if ($sponsers->count() >= 1) {
                    $cnt = 0;
                    if (floatval($agent->accgbv($this->combPeriodToday)) >= floatval(1000000)) {
                        foreach ($sponsers as $key => $sponser) {
                            if ($sponser->level === 8) {
                                $aw = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', 'CF29')->first();
                                if ($aw) {
                                    $cnt++;
                                }
                            }
                        }
                    }
                    if ($cnt >= 1) {
                        $award = 'Getaway vaction award';
                        $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '72F4B9A5')->first();
                        if (!$awd) {
                            AwardQualifier::create([
                                'award_id' => '72F4B9A5', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                            ]);
                        }
                    }
                }
            }

            if ($agent->level >= 8) {
                if ($sponsers->count() >= 1) {
                    $cnt = 0;
                    if (floatval($agent->accgbv($this->combPeriodToday)) >= floatval(1300000)) {
                        foreach ($sponsers as $key => $sponser) {
                            if ($sponser->level === 8) {
                                $aw = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', 'CF29')->first();
                                if ($aw) {
                                    $cnt++;
                                }
                            }
                        }
                    }
                    if ($cnt >= 5) {
                        $award = 'Big car award';
                        $awd = AwardQualifier::where('member_id', $agent->member_id)->where('award_id', '9087')->first();
                        if (!$awd) {
                            AwardQualifier::create([
                                'award_id' => '9087', 'member_id' => $agent->member_id, 'period' => $this->combPeriodToday
                            ]);
                        }
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
