<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\Bonus;

class BonusService
{
    public $combPeriodToday;
    public $combPeriodPrevious;
    public $accgbv;
    public $loopcount;

    public function __construct()
    {
        $this->combPeriodToday = date('Y').date('m');
        $this->combPeriodPrevious = sprintf("%02d", (date('m') - 1));
        $this->accgbv = floatval(0);
        $this->loopcount = 0;
    }

    public function calculateBonus($period)
    {
        $users = Agent::latest()->get();

        foreach ($users as $key => $user) {
            $this->loopcount = 0;
            if($user->level > 2){
                $this->combPeriodToday = $period;
                $this->accgbv = floatval($user->currentach($period)->sum('total_pv'));
                $this->doBonus($user, 0);
                $this->loopcount++;
                $this->reloop($user);
            }
        }
        return true;
    }

    public $pug;
    public function reloop($user)
    {
        if($user->sponser) {
            $usd = Agent::where('member_id', $user->sponser->member_id)->first();
            $this->pug = $usd;
            $this->doBonus($usd, $this->loopcount);
            $this->loopcount++;
            $this->reloop($usd);
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




        if(floatval($accgbv) >= floatval(150)){
            $firstsplit = floatval(150);
            $amount += ($first_percent * 150);
            $rem = floatval($accgbv) - $firstsplit;
            if(floatval($rem) >= floatval(50)){
                $secondsplit = floatval(50);
                $amount += ($second_percent * $secondsplit);
                $rem = floatval($accgbv) - $secondsplit;
                $thirdsplit = floatval($accgbv) - $firstsplit - $secondsplit;
                $amount += ($third_percent * $thirdsplit);
            }else{
                $amount += ($first_percent * (floatval($accgbv) - $firstsplit));
            }
        }

        // if($key == 1) {
        //     ddd($accgbv, $user, $amount);
        // }





        $bonus = Bonus::where('member_id', $user->member_id)->where('period', $this->combPeriodToday)->first();
        if($key == 0) {
            if($bonus){
                $bonus->delete();
            }
            $bn = Bonus::create([
                'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                'amount' => $amount
            ]);
        }else{
            if(!$bonus){
                $bn = Bonus::create([
                    'member_id' => $user->member_id, 'period' => $this->combPeriodToday,
                    'amount' => $amount
                ]);
            }else{
                if($key > 11) {
                    if($user->level > 2) {
                        $bonus->period = $this->combPeriodToday;
                        $bonus->amount = $bonus->amount + $amount;
                        $bonus->save();
                    }
                }else{
                    $bonus->period = $this->combPeriodToday;
                    $bonus->amount = $bonus->amount + $amount;
                    $bonus->save();
                }
            }

        }
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
