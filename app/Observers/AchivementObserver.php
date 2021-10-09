<?php

namespace App\Observers;

use App\Models\Achivement;
use App\Models\Agent;
use App\Models\AgentStatistics;

class AchivementObserver
{
    /**
     * Handle the Achivement "created" event.
     *
     * @param  \App\Models\Achivement  $achivement
     * @return void
     */
    public function created(Achivement $achivement)
    {
        $agent = Agent::where('member_id', $achivement->member_id)->first();
        if (floatval($agent->stats->acc_pvb) >= floatval(50) && floatval($agent->stats->acc_pvb) < floatval(200)) {
            $agent->stats->level = 2;
            $agent->level = 2;

        }elseif (floatval($agent->stats->acc_pvb) >= floatval(200)) {
            $agent->stats->level = 3;
            $agent->level = 3;
        }elseif ($agent->sponsers->where('level', 3)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(800)) {
            $agent->stats->level = 4;
            $agent->level = 4;
        }elseif ($agent->sponsers->where('level', 4)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(3000)) {
            $agent->stats->level = 5;
            $agent->level = 5;
        }elseif ($agent->sponsers->where('level', 5)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(18000)) {
            $agent->stats->level = 6;
            $agent->level = 6;
        }elseif ($agent->sponsers->where('level', 6)->count() >= 2 && floatval($agent->stats->acc_gbv) >= floatval(210000)) {
            $agent->stats->level = 7;
            $agent->level = 7;
        }elseif ($agent->sponsers->where('level', 6)->count() >= 3 && floatval($agent->stats->acc_gbv) >= floatval(85000)) {
            $agent->stats->level = 7;
            $agent->level = 7;
        }elseif ($agent->sponsers->where('level', 6)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(72000)) {
            $agent->stats->level = 7;
            $agent->level = 7;
        }elseif ($agent->sponsers->where('level', 7)->count() >= 2 && floatval($agent->stats->acc_gbv) >= floatval(600000)) {
            $agent->stats->level = 8;
            $agent->level = 8;
        }elseif ($agent->sponsers->where('level', 7)->count() >= 3 && floatval($agent->stats->acc_gbv) >= floatval(270000)) {
            $agent->stats->level = 8;
            $agent->level = 8;
        }elseif ($agent->sponsers->where('level', 7)->count() >= 4 && floatval($agent->stats->acc_gbv) >= floatval(260000)) {
            $agent->stats->level = 8;
            $agent->level = 8;
        }else {
            $agent->stats->level = 1;
            $agent->level = 1;
        }
        $agent->stats->save();
        $agent->save();

    }

    /**
     * Handle the Achivement "updated" event.
     *
     * @param  \App\Models\Achivement  $achivement
     * @return void
     */
    public function updated(Achivement $achivement)
    {
        //
    }

    /**
     * Handle the Achivement "deleted" event.
     *
     * @param  \App\Models\Achivement  $achivement
     * @return void
     */
    public function deleted(Achivement $achivement)
    {
        //
    }

    /**
     * Handle the Achivement "restored" event.
     *
     * @param  \App\Models\Achivement  $achivement
     * @return void
     */
    public function restored(Achivement $achivement)
    {
        //
    }

    /**
     * Handle the Achivement "force deleted" event.
     *
     * @param  \App\Models\Achivement  $achivement
     * @return void
     */
    public function forceDeleted(Achivement $achivement)
    {
        //
    }
}
