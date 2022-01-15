<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\BigAgent;
use App\Models\Bonus;
use App\Models\Salary;
use App\Models\CheckRunBill;

class BonusService
{
    public $combPeriodToday;
    public $combPeriodPrevious;
    public $accgbv;
    public $loopcount;
    public $pugcount;


    public $old_cl;
    public $old_bl;
    public $new_cl;
    public $new_bl;

    public $user;

    public function __construct()
    {
        $this->combPeriodToday = date('Y') . date('m');
        $this->combPeriodPrevious = sprintf("%02d", (date('m') - 1));
        $this->accgbv = floatval(0);
        $this->loopcount = 0;
        $this->pugcount = 0;
    }

    public function calculateBonus($period)
    {
        $pd = CheckRunBill::where('type', 'bonus')->where('period', $period)->first();
        if (!$pd) :

            $users = Agent::where('period', '<=', $period)->latest()->get();
            $bns = Bonus::where('period', $period)->truncate();
            $sls = Salary::where('period', $period)->get();
            foreach ($bns as $key => $trn) {
                $trn->delete();
            }
            foreach ($sls as $key => $trn) {
                $trn->delete();
            }
            foreach ($users as $key => $user) {
                // ddd($user);
                $this->user = $user;
                // $user->group->cl = 5;
                // $user->group->save();
                // ddd($this->user->group->cl);
                $this->loopcount = 0;
                $this->pugcount = 0;
                $this->combPeriodToday = $period;
                $this->accgbv = floatval($user->currentach($period)->sum('total_pv'));
                // ddd($this->user->group);
                if (true) {
                    if (floatval($this->accgbv) > floatval(0)) {
                        $this->old_cl = $this->user->group->cl;
                        $this->old_bl = $this->user->group->bl;
                        $ags = BigAgent::where('member_id', $user->member_id)->where('period', '<=', $period)->where('level', '>', intval(0))->orderBy('level', 'asc')->get();

                        $this->doBonus($user, intval(0));
                        foreach ($ags as $key => $ag) {
                            $this->user = $user;
                            $user->group->cl = $this->old_cl;
                            $user->group->bl = $this->old_bl;
                            $user->group->save();
                            // ddd($ag, $ag->agent, intval($ag->level));
                            $this->doBonus($ag->agent, intval($ag->level));
                        }
                        $user->group->cl = $this->new_cl ?? $this->old_cl;
                        $user->group->bl = $this->new_bl ?? $this->old_bl;
                        $user->group->save();
                    } else {
                        $this->addSalary($user, floatval(0), intval(0));
                    }
                }

                // }
            }
            CheckRunBill::create([
                'period' => $period, 'type' => 'bonus'
            ]);
            return true;
        endif;
    }



    public function reloop($user)
    {

        if ($user->sponser) {
            $usd = Agent::where('member_id', $user->sponser->member_id)->first();
            $this->pugcount++;
            if (($usd->statlogs->where('period', $this->combPeriodToday)->first()->level ?? 1) > 2) {
                $this->doBonusSponser($user, $usd, $this->pugcount);
            }
            $this->loopcount++;
            $this->reloop($usd);
        }
    }

    protected function doBonusSponser($user, $sponser, $key)
    {


        $accgbv = $this->accgbv;

        // $accgbvTT = floatval($user->archievements()->sum('total_pv'));
        $accgbvTT = floatval($user->archievements->whereBetween('period', ['201308', $this->combPeriodToday])->sum('total_pv')) ?? floatval(0);
        $firstsplit = floatval(0);
        $secondsplit = floatval(0);
        $thirdsplit = floatval(0);
        $rem = floatval(0);
        $amount = floatval(0);

        $first_percent = 0;
        $second_percent = 0;
        $third_percent = 0;

        if ($key > 6 || $key < 11) {
            $first_percent = 0;
            $second_percent = 0.02;
            $third_percent = 0;
        }
        if ($key === 0) {
            $first_percent = 0;
            $second_percent = 0;
            $third_percent = 0.2;
        }
        if ($key === 1) {
            $first_percent = 0.2;
            $second_percent = 0.25;
            $third_percent = 0.05;
        }
        if ($key === 2) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.05;
        }
        if ($key === 3) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if ($key === 4) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if ($key === 5) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.02;
        }
        if ($key === 6) {
            $first_percent = 0.02;
            $second_percent = 0;
            $third_percent = 0;
        }

        if ($key === 11) {
            $first_percent = 0;
            $second_percent = 0.05;
            $third_percent = 0;
        }
        if ($key > 11) {
            $first_percent = 0.005;
            $second_percent = 0;
            $third_percent = 0;
        }

        // 201266669897

        // if ($this->combPeriodToday == "202110") {
        //     if($sponser->member_id === "202110141234"){
        //         ddd($accgbv, $accgbvTT, $key, $user);
        //     }
        // }




        if (floatval($accgbv) > floatval(0) && floatval($accgbvTT) > floatval(200)) {

            if ($user->group->cl2 == 3) {
                $amount = $accgbv * $third_percent;
                $nw = $user->group->bl2 - floatval($accgbv);
                if ($nw < 0) {
                    $user->group->bl2 = 0;
                    $user->group->cl2 = 3;
                } else {
                    $user->group->bl2 = $nw;
                }
                $user->group->save();
            } else {
                $firstsplit = floatval(150);
                $secondsplit = floatval(50);
                $thirdsplit = floatval($accgbv) - 200;
                $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit) + ($third_percent * $thirdsplit);
                $user->group->cl2 = 3;
                $user->group->bl2 = 0;
                $user->group->save();
            }
        }
        if (floatval($accgbv) > floatval(0) && ((floatval($accgbvTT) > floatval(150)) && (floatval($accgbvTT) <= floatval(200)))) {

            if ($user->group->cl2 == 2) {
                $amount = $accgbv * $second_percent;
                $nw = $user->group->bl2 - floatval($accgbv);
                if ($nw < 0) {
                    $user->group->bl2 = 0;
                    $user->group->cl2 = 3;
                    $user->group->level = 3;
                } else {
                    $user->group->bl2 = $user->group->bl2 - floatval($accgbv);
                }
                $user->group->save();
            } else {
                $firstsplit = floatval(150);
                $secondsplit = floatval($accgbv) - $firstsplit;
                $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit);
                $user->group->cl2 = 2;
                $user->group->bl2 = floatval(50) - $secondsplit;
                $user->group->save();
            }
        }
        if (floatval($accgbv) > floatval(0) && floatval($accgbvTT) <= floatval(150)) {
            if ($user->group->cl2 == 1) {
                $amount = $accgbv * $first_percent;
                $nw = $user->group->bl2 - floatval($accgbv);
                if ($nw < 0) {
                    $user->group->bl2 = 0;
                    $user->group->cl2 = 2;
                    $user->group->level = 2;
                } else {
                    $user->group->bl2 = $user->group->bl2 - floatval($accgbv);
                }
                $user->group->save();
            } else {

                $firstsplit = floatval(150);
                $amount += ($first_percent * $firstsplit);
                $user->group->cl2 = 1;
                $user->group->bl2 = floatval(150) - $firstsplit;
                $user->group->save();
            }
        }

        // if ($this->combPeriodToday == "201405") {
        //     if($sponser->member_id === "201266667531"){
        //         // ddd($first_percent, $second_percent, $third_percent);
        //         ddd($user->group, $accgbvTT, $accgbv, $amount, $key);
        //     }
        // }

        // if ($this->combPeriodToday == "201405") {
        //     if($sponser->member_id === "201266667531"){
        //         // ddd($first_percent, $second_percent, $third_percent);
        //         ddd($user->group, $accgbvTT, $accgbv, $amount, $key);
        //     }
        // }



        // $bonus = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();
        $salary = Salary::where('member_id', $sponser->member_id)->where('period', $this->combPeriodToday)->first();


        if (!$salary) {
            $bn = Salary::create([
                'member_id' => $sponser->member_id, 'period' => $this->combPeriodToday,
                'amount' => $amount, 'level' => $key
            ]);
        } else {
            if ($key > 11) {
                if ($user->level > 2) {
                    $salary->period = $this->combPeriodToday;
                    $salary->amount = $salary->amount + $amount;
                    $salary->save();
                }
            } else {
                $salary->period = $this->combPeriodToday;
                $salary->amount = $salary->amount + $amount;
                $salary->save();
            }
        }
    }

    protected function firstLevelCalculation($user, $accgbv, $key)
    {
        $firstsplit = floatval(0);
        $amount = floatval(0);

        $first_percent = 0;

        if ($key > 6 || $key < 11) {
            $first_percent = 0;
        }
        if ($key === 0) {
            $first_percent = 0;
        }
        if ($key === 1) {
            $first_percent = 0.2;
        }
        if ($key === 2) {
            $first_percent = 0.05;
        }
        if ($key === 3) {
            $first_percent = 0.05;
        }
        if ($key === 4) {
            $first_percent = 0.03;
        }
        if ($key === 5) {
            $first_percent = 0.03;
        }
        if ($key === 6) {
            $first_percent = 0.02;
        }

        if ($key === 11) {
            $first_percent = 0;
        }
        if ($key > 11) {
            $first_percent = 0.005;
        }

        if (floatval($accgbv) > floatval(0)) :
            if ($this->user->group->cl == 1) {
                if (floatval($accgbv) >= floatval($this->user->group->bl)) {
                    $amount_to_next = floatval($accgbv) - $this->user->group->bl;
                    $firstsplit = floatval($this->user->group->bl);
                    $amount = ($first_percent * $firstsplit);
                    $this->user->group->cl = 2;
                    $this->user->group->bl = 0;
                    $this->new_cl = 2;
                    $this->new_bl = 0;
                    $this->user->group->save();
                    $this->addSalary($user, $amount, $key);
                    $this->secondLevelCalculation($user, $amount_to_next, $key);
                } else {
                    $firstsplit = $accgbv;
                    $new_bl = $this->user->group->bl - floatval($accgbv);
                    $amount = ($first_percent * $firstsplit);
                    $this->user->group->cl = 1;
                    $this->user->group->bl = $new_bl;
                    $this->new_cl = 1;
                    $this->new_bl = $new_bl;
                    $this->user->group->save();
                    $this->addSalary($user, $amount, $key);
                }
            }
        endif;
    }
    protected function secondLevelCalculation($user, $accgbv, $key)
    {
        $secondsplit = floatval(0);
        $amount = floatval(0);

        $second_percent = 0;

        if ($key > 6 || $key < 11) {
            $second_percent = 0.02;
        }
        if ($key === 0) {
            $second_percent = 0;
        }
        if ($key === 1) {
            $second_percent = 0.25;
        }
        if ($key === 2) {
            $second_percent = 0;
        }
        if ($key === 3) {
            $second_percent = 0;
        }
        if ($key === 4) {
            $second_percent = 0;
        }
        if ($key === 5) {
            $second_percent = 0;
        }
        if ($key === 6) {
            $second_percent = 0;
        }

        if ($key === 11) {
            $second_percent = 0.05;
        }
        if ($key > 11) {
            $second_percent = 0;
        }



        if (floatval($accgbv) > floatval(0)) :
            if ($this->user->group->cl == 2) {
                if (floatval($this->user->group->bl) > floatval(0)) {
                    if (floatval($accgbv) >= floatval($user->group->bl)) {
                        $amount_to_next = floatval($accgbv) - $user->group->bl;
                        $secondsplit = floatval($user->group->bl);
                        $amount = ($second_percent * $secondsplit);
                        $user->group->cl = 3;
                        $user->group->bl = 0;
                        $this->new_cl = 3;
                        $this->new_bl = 0;
                        $user->group->save();
                        $this->addSalary($user, $amount, $key);
                        $this->thirdLevelCalculation($user, $amount_to_next, $key);
                    } else {
                        $secondsplit = $accgbv;
                        $new_bl = $user->group->bl - floatval($accgbv);
                        $amount = ($second_percent * $secondsplit);
                        $user->group->cl = 2;
                        $user->group->bl = $new_bl;
                        $this->new_cl = 2;
                        $this->new_bl = $new_bl;
                        $user->group->save();
                        $this->addSalary($user, $amount, $key);
                    }
                } else {
                    if (floatval($accgbv) >= floatval(50)) {
                        $amount_to_next = floatval($accgbv) - floatval(50);
                        $secondsplit = floatval(50);
                        $amount = ($second_percent * $secondsplit);
                        $this->user->group->cl = 3;
                        $this->user->group->bl = 0;
                        $this->new_cl = 3;
                        $this->new_bl = 0;
                        $this->user->group->save();
                        $this->addSalary($user, $amount, $key);
                        $this->thirdLevelCalculation($user, $amount_to_next, $key);
                    } else {
                        $secondsplit = $accgbv;
                        $new_bl = floatval(50) - floatval($accgbv);
                        $amount += ($second_percent * $secondsplit);
                        $this->user->group->cl = 2;
                        $this->user->group->bl = $new_bl;
                        $this->new_cl = 2;
                        $this->new_bl = $new_bl;
                        $this->user->group->save();
                        $this->addSalary($user, $amount, $key);
                    }
                }
            }
        endif;
    }
    protected function thirdLevelCalculation($user, $accgbv, $key)
    {
        $thirdsplit = floatval(0);
        $amount = floatval(0);
        $third_percent = 0;



        if ($key > 6 || $key < 11) {
            $third_percent = 0;
        }
        if ($key === 0) {

            $third_percent = 0.2;
        }
        if ($key === 1) {
            $third_percent = 0.05;
        }
        if ($key === 2) {
            $third_percent = 0.05;
        }
        if ($key === 3) {
            $third_percent = 0.03;
        }
        if ($key === 4) {
            $third_percent = 0.03;
        }
        if ($key === 5) {
            $third_percent = 0.02;
        }
        if ($key === 6) {
            $third_percent = 0;
        }

        if ($key === 11) {
            $third_percent = 0;
        }
        if ($key > 11) {
            $third_percent = 0;
        }

        // if ($this->user->member_id == "202288880005") {
        //     ddd($key, $accgbv);
        // }



        if (floatval($accgbv) > floatval(0)) {
            if ($this->user->group->cl == 3) {
                $thirdsplit = $accgbv;
                $amount = ($thirdsplit * $third_percent);

                $this->addSalary($user, $amount, $key);
            }
        }
    }

    protected function addSalary($user, $amount, $key)
    {
        $salary = Salary::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();

        // if ($key == 1) {
        //     ddd($user->statlogs->where('period', $this->combPeriodToday)->first());
        // }

        if (($user->statlogs->where('period', $this->combPeriodToday)->first()->level ?? 1) > 2) {
            if (!$salary) {
                $bn = Salary::create([
                    'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                    'amount' => $amount, 'level' => $key
                ]);
            } else {
                if ($key > 11) {
                    if ($user->level > 2) {
                        $salary->amount = $salary->amount + $amount;
                        $salary->save();
                    }
                } else {
                    $salary->amount = $salary->amount + $amount;
                    $salary->save();
                }
            }
        }
    }

    protected function doBonus($user, $key)
    {

        $accgbv = $this->accgbv;
        $accgbvTT = floatval($user->archievements->whereBetween('period', ['201308', $this->combPeriodToday])->sum('total_pv')) ?? floatval(0);
        $firstsplit = floatval(0);
        $secondsplit = floatval(0);
        $thirdsplit = floatval(0);
        $rem = floatval(0);
        $amount = floatval(0);



        $first_percent = 0;
        $second_percent = 0;
        $third_percent = 0;

        if ($key > 6 || $key < 11) {
            $first_percent = 0;
            $second_percent = 0.02;
            $third_percent = 0;
        }
        if ($key === 0) {
            $first_percent = 0;
            $second_percent = 0;
            $third_percent = 0.2;
        }
        if ($key === 1) {
            $first_percent = 0.2;
            $second_percent = 0.25;
            $third_percent = 0.05;
        }
        if ($key === 2) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.05;
        }
        if ($key === 3) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if ($key === 4) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if ($key === 5) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.02;
        }
        if ($key === 6) {
            $first_percent = 0.02;
            $second_percent = 0;
            $third_percent = 0;
        }

        if ($key === 11) {
            $first_percent = 0;
            $second_percent = 0.05;
            $third_percent = 0;
        }
        if ($key > 11) {
            $first_percent = 0.005;
            $second_percent = 0;
            $third_percent = 0;
        }




        // ddd($user->group);



        if ($this->user->group->cl == 1) {
            if (floatval($this->user->group->bl) > floatval(0)) {
                $this->firstLevelCalculation($user, $accgbv, $key);
            } else {
                $bl = floatval(0);
                $firstsplit = floatval(0);
                if (floatval($accgbv) >= floatval(150)) {
                    $amount_to_next = floatval($accgbv) - floatval(150);
                    $firstsplit = floatval(150);
                    $amount = ($first_percent * $firstsplit);
                    $this->user->group->cl = 2;
                    $this->user->group->bl = 0;
                    $this->new_cl = 2;
                    $this->new_bl = 0;
                    $this->user->group->save();
                    // ddd($amount);

                    $this->addSalary($user, $amount, $key);

                    $this->secondLevelCalculation($user, $amount_to_next, $key);
                    //next 50 calculation
                } else {
                    $bl = floatval(150) - floatval($accgbv);
                    $firstsplit = floatval($accgbv);
                    $amount += ($first_percent * $firstsplit);
                    $user->group->cl = 1;
                    $user->group->bl = $bl;
                    $this->new_cl = 1;
                    $this->new_bl = $bl;
                    $user->group->save();

                    $this->addSalary($user, $amount, $key);
                }
            }
        }

        if ($this->old_cl == 2) {
            $this->secondLevelCalculation($user, $accgbv, $key);
        }
        if ($this->old_cl == 3) {
            $this->thirdLevelCalculation($user, $accgbv, $key);
        }


        // if($key == 1) {
        //     ddd($amount, $user->salary, $key);
        // }

        // ddd('op');



        // ddd($user->salaries);


        // if(floatval($accgbvTT) <= floatval(150)){
        //     if($user->group->cl == 1) {
        //         $amount = $accgbv * $first_percent;
        //         $nw = $user->group->bl - floatval($accgbv);
        //         if($nw < 0) {
        //             $user->group->bl = 0;
        //             $user->group->cl = 2;
        //             $user->group->level = 2;
        //         }else{
        //             $user->group->bl = $user->group->bl - floatval($accgbv);
        //         }
        //         $user->group->save();

        //     }else{
        //         $firstsplit = floatval(150);
        //         $amount += ($first_percent * $firstsplit);
        //         $user->group->cl = 1;
        //         $user->group->bl = floatval(150) - floatval($accgbv);
        //         $user->group->save();
        //     }

        // }


        // if(floatval($accgbvTT) > floatval(200)){

        //     if($user->group->cl == 3) {
        //         $amount = $accgbv * $third_percent;
        //         $nw = $user->group->bl - floatval($accgbv);
        //         if($nw < 0) {
        //             $user->group->bl = 0;
        //             $user->group->cl = 3;
        //             $user->group->level = 3;
        //         }else{
        //             $user->group->bl = $nw;
        //         }
        //         $user->group->save();

        //     }else{
        //         $firstsplit = floatval(150);
        //         $secondsplit = floatval(50);
        //         $thirdsplit = floatval($accgbv) - 200;
        //         $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit) + ($third_percent * $thirdsplit);
        //         $user->group->cl = 3;
        //         $user->group->bl = 0;
        //         $user->group->save();
        //     }

        // if ($this->combPeriodToday == "202110") {
        //     if($user->member_id === "202110141234"){
        //         ddd($amount);
        //     }
        // }

        // if ($this->combPeriodToday == "201405") {
        //     if($user->member_id === "201412355498"){
        //         ddd($user->group->bl, $accgbvTT, $accgbv, $amount, $third_percent);
        //     }
        // }

        // }
        // if((floatval($accgbvTT) > floatval(150)) && (floatval($accgbvTT) <= floatval(200))) {

        //     if($user->group->cl == 2) {
        //         $amount = $accgbv * $second_percent;
        //         $nw = $user->group->bl - floatval($accgbv);
        //         if($nw < 0) {
        //             $user->group->bl = 0;
        //             $user->group->cl = 3;
        //             $user->group->level = 3;
        //         }else{
        //             $user->group->bl = $nw;
        //         }
        //         $user->group->save();

        //     }else{
        //         $firstsplit = floatval(150);
        //         $secondsplit = floatval($accgbv) - $firstsplit;
        //         $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit);
        //         $user->group->cl = 2;
        //         $user->group->bl = floatval(50) - $secondsplit;
        //         $user->group->save();
        //     }

        // }



    }
}
