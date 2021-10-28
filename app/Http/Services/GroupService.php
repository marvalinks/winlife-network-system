<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\GroupAmt;

class GroupService
{



    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function GRP()
    {

        $user = $this->user;
        $gpp = GroupAmt::where('member_id', $user->member_id)->first();
        if($gpp) {
            $gpp->delete();
        }

        $accgbv = floatval($user->archievements()->sum('total_pv')) ?? floatval(0);
        if(floatval($accgbv) > floatval(200)){
            GroupAmt::create([
                'member_id' => $user->member_id, 'group1' => 1, 'group2' => 1,
                'group3' => 1, 'level' => 3, 'cl' => 1, 'cl2' => 1
            ]);
        }elseif(floatval($accgbv) > floatval(150) && floatval($accgbv) <= floatval(200)) {
            GroupAmt::create([
                'member_id' => $user->member_id, 'group1' => 1, 'group2' => 1,
                'group3' => 0, 'level' => 2, 'cl' => 1, 'cl2' => 1
            ]);
        }else{
            GroupAmt::create([
                'member_id' => $user->member_id, 'group1' => 1, 'group2' => 0,
                'group3' => 0, 'level' => 1, 'cl' => 1, 'cl2' => 1
            ]);
        }

        // GroupAmt::truncate();
        // $agents = Agent::latest()->get();
        // foreach ($agents as $key => $user) {
        //     $accgbv = floatval($user->archievements()->sum('total_pv')) ?? floatval(0);
        //     if(floatval($accgbv) > floatval(200)){
        //         GroupAmt::create([
        //             'member_id' => $user->member_id, 'group1' => 1, 'group2' => 1,
        //             'group3' => 1, 'level' => 3, 'cl' => 1, 'cl2' => 1
        //         ]);
        //     }elseif(floatval($accgbv) > floatval(150) && floatval($accgbv) <= floatval(200)) {
        //         GroupAmt::create([
        //             'member_id' => $user->member_id, 'group1' => 1, 'group2' => 1,
        //             'group3' => 0, 'level' => 2, 'cl' => 1, 'cl2' => 1
        //         ]);
        //     }else{
        //         GroupAmt::create([
        //             'member_id' => $user->member_id, 'group1' => 1, 'group2' => 0,
        //             'group3' => 0, 'level' => 1, 'cl' => 1, 'cl2' => 1
        //         ]);
        //     }
        // }

    }

}
