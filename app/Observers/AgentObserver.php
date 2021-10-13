<?php

namespace App\Observers;

use App\Models\Agent;
use App\Models\AgentStatistics;

class AgentObserver
{
    /**
     * Handle the Agent "created" event.
     *
     * @param  \App\Models\Agent  $agent
     * @return void
     */
    public function created(Agent $agent)
    {
        AgentStatistics::create([
            'agent_id' => $agent->member_id, 'period' => $agent->period,
            'sponser_id' => $agent->sponser_id ?? null
        ]);
    }

    /**
     * Handle the Agent "updated" event.
     *
     * @param  \App\Models\Agent  $agent
     * @return void
     */
    public function updated(Agent $agent)
    {
        $agent->stats->agent_id = $agent->member_id;
        $agent->stats->period = $agent->period;
        $agent->stats->save();
    }

    /**
     * Handle the Agent "deleted" event.
     *
     * @param  \App\Models\Agent  $agent
     * @return void
     */
    public function deleted(Agent $agent)
    {
        //
    }

    /**
     * Handle the Agent "restored" event.
     *
     * @param  \App\Models\Agent  $agent
     * @return void
     */
    public function restored(Agent $agent)
    {
        //
    }

    /**
     * Handle the Agent "force deleted" event.
     *
     * @param  \App\Models\Agent  $agent
     * @return void
     */
    public function forceDeleted(Agent $agent)
    {
        //
    }
}
