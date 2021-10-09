<?php

namespace App\Observers;

use App\Models\AgentStatistics;

class AgentStatisticsObserver
{
    /**
     * Handle the AgentStatistics "created" event.
     *
     * @param  \App\Models\AgentStatistics  $agentStatistics
     * @return void
     */
    public function created(AgentStatistics $agentStatistics)
    {
        //
    }

    /**
     * Handle the AgentStatistics "updated" event.
     *
     * @param  \App\Models\AgentStatistics  $agentStatistics
     * @return void
     */
    public function updated(AgentStatistics $agentStatistics)
    {
        //
    }

    /**
     * Handle the AgentStatistics "deleted" event.
     *
     * @param  \App\Models\AgentStatistics  $agentStatistics
     * @return void
     */
    public function deleted(AgentStatistics $agentStatistics)
    {
        //
    }

    /**
     * Handle the AgentStatistics "restored" event.
     *
     * @param  \App\Models\AgentStatistics  $agentStatistics
     * @return void
     */
    public function restored(AgentStatistics $agentStatistics)
    {
        //
    }

    /**
     * Handle the AgentStatistics "force deleted" event.
     *
     * @param  \App\Models\AgentStatistics  $agentStatistics
     * @return void
     */
    public function forceDeleted(AgentStatistics $agentStatistics)
    {
        //
    }
}
