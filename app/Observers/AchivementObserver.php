<?php

namespace App\Observers;

use App\Http\Controllers\AgentController;
use App\Http\Services\BonusService;
use App\Http\Services\LevelService;
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

        $lvs = new LevelService($achivement->period);
        $lvs->ABP();

        // $bns = new BonusService();
        // $bns->calculateBonus($achivement->period);
        // ddd();

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
