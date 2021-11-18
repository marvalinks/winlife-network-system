<?php

namespace App\Http\Services;

use App\Models\Agent;
use App\Models\BigAgent;
use App\Models\Bonus;
use App\Models\Salary;
use App\Models\CheckRunBill;

class BigAgentService
{

    public $lv;
    public $lvi;
    public $lvf;
    public $dsd;
    public $user;
    public $loopCount;
    public function __construct()
    {
        $this->lv = 1;
        $this->lvi = 1;
        $this->lvf = 2;
        $this->dsd = 1;
    }
    // public function new($memberid)
    // {
    //     $user =  Agent::where('member_id', $memberid)->first();
    //     BigAgent::create([
    //         'member_id' => $user->member_id, 'sponser_id' => $user->sponser_id ?? null,
    //         'parent_id' => null, 'level' => 0, 'period' => $user->period,
    //         'firstname' => $user->firstname, 'lastname' => $user->lastname
    //     ]);
    //     $a = BigAgent::where('parent_id', $user->sponser_id)
    // }
    public function mk($memberid)
    {
        $user =  Agent::where('member_id', $memberid)->first();
        $this->user = $user;
        $this->loopCount = 0;
        // $sponsers = Agent::where('sponser_id', $memberid)->get();
        BigAgent::create([
            'member_id' => $user->member_id, 'sponser_id' => $user->sponser_id ?? null,
            'parent_id' => null, 'level' => $this->loopCount, 'period' => $user->period,
            'firstname' => $user->firstname, 'lastname' => $user->lastname
        ]);
        if($user->sponser) {
            $this->loopCount++;
            BigAgent::create([
                'member_id' =>$user->member_id, 'sponser_id' =>$user->sponser_id ?? null,
                'parent_id' => $user->sponser->member_id, 'level' => $this->loopCount, 'period' =>$user->period,
                'firstname' =>$user->firstname, 'lastname' =>$user->lastname
            ]);
            $this->addSponser($user->sponser);
        }

        // foreach ($sponsers as $key => $sponser) {
        //     BigAgent::create([
        //         'member_id' => $sponser->member_id, 'sponser_id' => $sponser->sponser_id ?? null,
        //         'parent_id' => $user->member_id, 'level' => $this->lv, 'period' => $sponser->period,
        //         'firstname' => $sponser->firstname, 'lastname' => $sponser->lastname
        //     ]);
        //     foreach ($sponser->childrenSponsers as $k => $childrenSponser) {
        //         if($sponser->member_id === $childrenSponser->sponser_id){
        //             $num = 2;
        //             $this->lvi = $num;
        //         }
        //         $this->reloop($childrenSponser, $num);
        //     }
        // }


    }

    protected function addSponser($user)
    {
        if($user->sponser){
            $this->loopCount++;
            BigAgent::create([
                'member_id' =>$this->user->member_id, 'sponser_id' =>$this->user->sponser_id ?? null,
                'parent_id' => $user->sponser->member_id, 'level' => $this->loopCount, 'period' =>$this->user->period,
                'firstname' =>$this->user->firstname, 'lastname' =>$this->user->lastname
            ]);
            $this->addSponser($user->sponser);
        }
    }

    protected function reloop($child_sponser,$num, $p = false)
    {

        BigAgent::create([
            'member_id' => $child_sponser->member_id, 'sponser_id' => $child_sponser->sponser_id ?? null,
            'parent_id' => $this->user->member_id, 'level' => $num, 'period' => $child_sponser->period,
            'firstname' => $child_sponser->firstname, 'lastname' => $child_sponser->lastname
        ]);
        if ($child_sponser->sponsers):
            foreach ($child_sponser->sponsers as $ko => $childrenSponser) {
                if($child_sponser->member_id === $childrenSponser->sponser_id){
                    $this->lvf++;
                    $num = $num + 1;
                }
                // $child_sponser = $childrenSponser;
                $this->reloop($childrenSponser, $num);

            }
        endif;
    }

}
