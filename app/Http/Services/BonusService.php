<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\Bonus;
use App\Models\Salary;

class BonusService
{
    public $combPeriodToday;
    public $combPeriodPrevious;
    public $accgbv;
    public $loopcount;
    public $pugcount;

    public function __construct()
    {
        $this->combPeriodToday = date('Y').date('m');
        $this->combPeriodPrevious = sprintf("%02d", (date('m') - 1));
        $this->accgbv = floatval(0);
        $this->loopcount = 0;
        $this->pugcount = 0;
    }

    public function calculateBonus($period)
    {
        $users = Agent::latest()->get();
        $bns = Bonus::where('period', $period)->truncate();
        $sls = Salary::where('period', $period)->get();
        foreach ($bns as $key => $trn) {
            $trn->delete();
        }
        foreach ($sls as $key => $trn) {
            $trn->delete();
        }
        foreach ($users as $key => $user) {
            $this->loopcount = 0;
            $this->pugcount = 0;
            $this->combPeriodToday = $period;
            $this->accgbv = floatval($user->currentach($period)->sum('total_pv'));
            if($user->level > 2){
                $this->doBonus($user, $this->loopcount++);
            }
            $this->loopcount++;
            $this->reloop($user);
        }
        return true;
    }



    public function reloop($user)
    {

        if($user->sponser) {
            $usd = Agent::where('member_id', $user->sponser->member_id)->first();
            $this->pugcount++;
            if ($usd->level > 2) {
                $this->doBonusSponser($user, $usd, $this->pugcount);
            }
            $this->loopcount++;
            $this->reloop($usd);
        }
    }

    protected function doBonusSponser($user, $sponser, $key)
    {


        $accgbv = $this->accgbv;
        $accgbvTT = floatval($user->archievements()->sum('total_pv'));
        $firstsplit = floatval(0);
        $secondsplit = floatval(0);
        $thirdsplit = floatval(0);
        $rem = floatval(0);
        $amount = floatval(0);

        $first_percent = 0;
        $second_percent = 0;
        $third_percent = 0;



        if($key > 6 || $key < 11) {
            $first_percent = 0;
            $second_percent = 0.02;
            $third_percent = 0;
        }
        if($key === 0) {
            $first_percent = 0;
            $second_percent = 0;
            $third_percent = 0.2;
        }
        if($key === 1) {
            $first_percent = 0.2;
            $second_percent = 0.25;
            $third_percent = 0.05;
        }
        if($key === 2) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.05;
        }
        if($key === 3) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if($key === 4) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if($key === 5) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.02;
        }
        if($key === 6) {
            $first_percent = 0.02;
            $second_percent = 0;
            $third_percent = 0;
        }

        if($key === 11) {
            $first_percent = 0;
            $second_percent = 0.05;
            $third_percent = 0;
        }
        if($key > 11) {
            $first_percent = 0.005;
            $second_percent = 0;
            $third_percent = 0;
        }

        // 201266669897





        if(floatval($accgbvTT) > floatval(200)){

            if($user->group->cl2 == 3) {
                $amount = $accgbv * $third_percent;
                $nw = $user->group->bl2 - floatval($accgbv);
                if($nw < 0) {
                    $user->group->bl2 = 0;
                    $user->group->cl2 = 3;
                }else{
                    $user->group->bl2 = $nw;
                }
                $user->group->save();

            }else{
                $firstsplit = floatval(150);
                $secondsplit = floatval(50);
                $thirdsplit = floatval($accgbv) - 200;
                $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit) + ($third_percent * $thirdsplit);
                $user->group->cl2 = 3;
                $user->group->bl2 = 0;
                $user->group->save();
            }

            // if ($this->combPeriodToday == "201309") {
            //     if($user->member_id === "201266669988"){
            //         ddd($amount);
            //     }
            // }

        }elseif((floatval($accgbvTT) > floatval(150)) && (floatval($accgbvTT) <= floatval(200))) {

            if($user->group->cl2 == 2) {
                $amount = $accgbv * $second_percent;
                $nw = $user->group->bl2 - floatval($accgbv);
                if($nw < 0) {
                    $user->group->bl2 = 0;
                    $user->group->cl2 = 3;
                    $user->group->level = 3;
                }else{
                    $user->group->bl2 = $user->group->bl2 - floatval($accgbv);
                }
                $user->group->save();

            }else{
                $firstsplit = floatval(150);
                $secondsplit = floatval($accgbv) - $firstsplit;
                $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit);
                $user->group->cl2 = 2;
                $user->group->bl2 = floatval(50) - $secondsplit;
                $user->group->save();
            }


        }else{
            if($user->group->cl2 == 1) {
                $amount = $accgbv * $first_percent;
                $nw = $user->group->bl2 - floatval($accgbv);
                if($nw < 0) {
                    $user->group->bl2 = 0;
                    $user->group->cl2 = 2;
                    $user->group->level = 2;
                }else{
                    $user->group->bl2 = $user->group->bl2 - floatval($accgbv);
                }
                $user->group->save();


            }else{

                $firstsplit = floatval(150);
                $amount += ($first_percent * $firstsplit);
                $user->group->cl2 = 1;
                $user->group->bl2 = floatval(150) - $firstsplit;
                $user->group->save();
            }


        }



        // if(floatval($accgbv) > floatval(200)){

        //     $firstsplit = floatval(150);
        //     $secondsplit = floatval(50);
        //     $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
        //     $amount = ($first_percent * $firstsplit) + ($second_percent * $secondsplit) + ($third_percent * $thirdsplit);

        // }elseif(floatval($accgbv) > floatval(150) && floatval($accgbv) <= floatval(200)) {

        //     $firstsplit = floatval(150);
        //     $secondsplit = floatval($accgbv) - $firstsplit;
        //     $amount = ($first_percent * $firstsplit) + ($second_percent * $secondsplit);

        // }else{
        //     $firstsplit = floatval(150);
        //     $amount = ($first_percent * floatval($accgbv));

        // }

        // if ($this->combPeriodToday == "201309") {
        //     // ddd($user, $sponser, $key);
        //     if($user->member_id === "201266669994"){
        //         // ddd($first_percent, $firstsplit);
        //         // ddd(floatval($user->archievements()->sum('total_pv')));
        //         // ddd($accgbv);
        //         ddd($accgbv, $user, $sponser, $amount, $key);
        //         ddd($user->group->group3);
        //         ddd($amount, $accgbv, floatval($user->archievements()->sum('total_pv')));
        //         ddd($accgbv - floatval($user->currentach($this->combPeriodToday)->sum('total_pv')));
        //         // ddd( - floatval($user->currentach($this->combPeriodToday)->sum('total_pv')))
        //     }
        // }



        // if ($this->combPeriodToday == "202110") {
        //     if($user->member_id === "201266669989"){
        //         ddd($amount, $bn);
        //     }
        // }



        // $bonus = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();
        $salary = Salary::where('member_id', $sponser->member_id)->where('period', $this->combPeriodToday)->first();

        if(!$salary){
            $bn = Salary::create([
                'member_id' => $sponser->member_id, 'period' => $this->combPeriodToday,
                'amount' => $amount, 'level' => $key
            ]);

        }else{
            if($key > 11) {
                if($user->level > 2) {
                    $salary->period = $this->combPeriodToday;
                    $salary->amount = $salary->amount + $amount;
                    $salary->save();
                }
            }else{
                $salary->period = $this->combPeriodToday;
                $salary->amount = $salary->amount + $amount;
                $salary->save();
            }
        }




    }
    protected function doBonus($user, $key)
    {


        $accgbv = $this->accgbv;
        $firstsplit = floatval(0);
        $secondsplit = floatval(0);
        $thirdsplit = floatval(0);
        $rem = floatval(0);
        $amount = floatval(0);

        $first_percent = 0;
        $second_percent = 0;
        $third_percent = 0;



        if($key > 6 || $key < 11) {
            $first_percent = 0;
            $second_percent = 0.02;
            $third_percent = 0;
        }
        if($key === 0) {
            $first_percent = 0;
            $second_percent = 0;
            $third_percent = 0.2;
        }
        if($key === 1) {
            $first_percent = 0.2;
            $second_percent = 0.25;
            $third_percent = 0.05;
        }
        if($key === 2) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.05;
        }
        if($key === 3) {
            $first_percent = 0.05;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if($key === 4) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.03;
        }
        if($key === 5) {
            $first_percent = 0.03;
            $second_percent = 0;
            $third_percent = 0.02;
        }
        if($key === 6) {
            $first_percent = 0.02;
            $second_percent = 0;
            $third_percent = 0;
        }

        if($key === 11) {
            $first_percent = 0;
            $second_percent = 0.05;
            $third_percent = 0;
        }
        if($key > 11) {
            $first_percent = 0.005;
            $second_percent = 0;
            $third_percent = 0;
        }

        // ddd($user->group);

        // if($user->group->group1) {
        //     $amount = ($first_percent * floatval($accgbv));
        // }
        // if($user->group->group2) {
        //     $amount = ($second_percent * floatval($accgbv));
        // }
        // if($user->group->group3) {
        //     $amount = ($third_percent *  floatval($accgbv));
        // }

        $accgbvTT = floatval($user->archievements()->sum('total_pv'));

        if(floatval($accgbvTT) > floatval(200)){

            // $firstsplit = floatval(150);
            // $secondsplit = floatval(50);
            // $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
            // $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit) + ($third_percent * $thirdsplit);
            // $user->group->cl = 3;
            // $user->group->bl = 0;
            // $user->group->save();

            if($user->group->cl == 3) {
                $amount = $accgbv * $third_percent;
                $nw = $user->group->bl - floatval($accgbv);
                if($nw < 0) {
                    $user->group->bl = 0;
                    $user->group->cl = 3;
                    $user->group->level = 3;
                }else{
                    $user->group->bl = $nw;
                }
                $user->group->save();

            }else{
                $firstsplit = floatval(150);
                $secondsplit = floatval(50);
                $thirdsplit = floatval($accgbv) - 200;
                $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit) + ($third_percent * $thirdsplit);
                $user->group->cl = 3;
                $user->group->bl = 0;
                $user->group->save();
            }

            // if ($this->combPeriodToday == "202110"  && $key == 0) {
            //     if($user->member_id === "201266669991"){
            //         ddd($user->group->bl, $accgbvTT, $accgbv, $amount, $third_percent);
            //     }
            // }

        }elseif((floatval($accgbvTT) > floatval(150)) && (floatval($accgbvTT) <= floatval(200))) {

            if($user->group->cl == 2) {
                $amount = $accgbv * $second_percent;
                $nw = $user->group->bl - floatval($accgbv);
                if($nw < 0) {
                    $user->group->bl = 0;
                    $user->group->cl = 3;
                    $user->group->level = 3;
                }else{
                    $user->group->bl = $nw;
                }
                $user->group->save();

            }else{
                $firstsplit = floatval(150);
                $secondsplit = floatval($accgbv) - $firstsplit;
                $amount += ($first_percent * $firstsplit) + ($second_percent * $secondsplit);
                $user->group->cl = 2;
                $user->group->bl = floatval(50) - $secondsplit;
                $user->group->save();
            }

        }else{
            if($user->group->cl == 1) {
                $amount = $accgbv * $first_percent;
                $nw = $user->group->bl - floatval($accgbv);
                if($nw < 0) {
                    $user->group->bl = 0;
                    $user->group->cl = 2;
                    $user->group->level = 2;
                }else{
                    $user->group->bl = $user->group->bl - floatval($accgbv);
                }
                $user->group->save();

            }else{
                $firstsplit = floatval(150);
                $amount += ($first_percent * $firstsplit);
                $user->group->cl = 1;
                $user->group->bl = floatval(150) - $firstsplit;
                $user->group->save();
            }

        }





        // if(floatval($accgbv) >= floatval(150)){
        //     $firstsplit = floatval(150);
        //     $amount += ($first_percent * 150);
        //     $rem = floatval($accgbv) - $firstsplit;
        //     $user->group->cl = 1;
        //     $user->group->bl = floatval(150) - floatval($accgbv);
        //     if(floatval($rem) > floatval(50)){
        //         $secondsplit = floatval(50);
        //         $amount += ($second_percent * $secondsplit);
        //         $rem = floatval($accgbv) - $secondsplit;
        //         if($user->group->level == 3) {
        //             $thirdsplit = floatval($accgbv);
        //         }else{
        //             $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
        //         }
        //         if ($this->combPeriodToday == "202110"  && $this->loopcount == 0) {
        //             if($user->member_id === "201266669991"){
        //                 ddd('$amount');
        //             }
        //         }
        //         $amount += ($third_percent * $thirdsplit);
        //         $user->group->cl = 3;
        //         $user->group->bl = 0;

        //     }else{
        //         // $amount += ($first_percent * (floatval($accgbv) - $firstsplit));
        //         $amount += ($second_percent * ($accgbv - $firstsplit));
        //         $user->group->cl = 2;
        //         $user->group->bl = floatval(200) - floatval($accgbv);
        //     }
        //     if($key == 0) {
        //         $user->group->save();
        //     }
        // }
        // if(floatval($accgbv) >= floatval(150)){
        //     $firstsplit = floatval(150);
        //     $amount += ($first_percent * 150);
        //     $rem = floatval($accgbv) - $firstsplit;
        //     if(floatval($rem) > floatval(50)){
        //         $secondsplit = floatval(50);
        //         $amount += ($second_percent * $secondsplit);
        //         $rem = floatval($accgbv) - $secondsplit;
        //         $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
        //         $amount += ($third_percent * $thirdsplit);

        //     }else{
        //         // $amount += ($first_percent * (floatval($accgbv) - $firstsplit));
        //         $amount += ($second_percent * ($accgbv - $firstsplit));
        //     }
        // }


        // if ($this->combPeriodToday == "201309"  && $key == 0) {
        //     if($user->member_id === "201266669991"){
        //         // ddd($user->group);
        //         // ddd(floatval($user->archievements()->sum('total_pv')));
        //         // ddd($accgbv);
        //         ddd($amount);
        //         ddd($user->group->group3);
        //         ddd($amount, $accgbv, floatval($user->archievements()->sum('total_pv')));
        //         ddd($accgbv - floatval($user->currentach($this->combPeriodToday)->sum('total_pv')));
        //         // ddd( - floatval($user->currentach($this->combPeriodToday)->sum('total_pv')))
        //     }
        // }

        // if ($this->combPeriodToday == "201309") {
        //     if($user->member_id === "201266669991"){
        //         ddd($accgbv, $firstsplit);
        //     }
        // }




        // $bonus = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();
        $salary = Salary::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();


        if(!$salary){
            $bn = Salary::create([
                'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                'amount' => $amount, 'level' => $key
            ]);
        }else{
            if($key > 11) {
                if($user->level > 2) {
                    $salary->period = $this->combPeriodToday;
                    $salary->amount = $salary->amount + $amount;
                    $salary->save();
                }
            }else{
                $salary->period = $this->combPeriodToday;
                $salary->amount = $salary->amount + $amount;
                $salary->save();

            }
        }

        // if($key == 0) {
        //     if($bonus){
        //         // $bonus->delete();
        //         $bonus->period = $this->combPeriodToday;
        //             $bonus->amount = $bonus->amount + $amount;
        //             $bonus->save();
        //     }
        //     $bn = Bonus::create([
        //         'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
        //         'amount' => $amount
        //     ]);
        // }else{
        //     if(!$bonus){
        //         $bn = Bonus::create([
        //             'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
        //             'amount' => $amount
        //         ]);
        //     }else{
        //         if($key > 11) {
        //             if($user->level > 2) {
        //                 $bonus->period = $this->combPeriodToday;
        //                 $bonus->amount = $bonus->amount + $amount;
        //                 $bonus->save();
        //             }
        //         }else{
        //             $bonus->period = $this->combPeriodToday;
        //             $bonus->amount = $bonus->amount + $amount;
        //             $bonus->save();
        //         }
        //     }

        // }
        // if(!$bonus){
        //     $bn = Bonus::create([
        //         'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
        //         'amount' => $amount
        //     ]);
        // }else{
        //     if($key > 11) {
        //         if($user->level > 2) {
        //             $bonus->period = $this->combPeriodToday;
        //             $bonus->amount = $bonus->amount + $amount;
        //             $bonus->save();
        //         }
        //     }else{
        //         $bonus->period = $this->combPeriodToday;
        //         $bonus->amount = $bonus->amount + $amount;
        //         $bonus->save();
        //     }
        // }
    }


}
