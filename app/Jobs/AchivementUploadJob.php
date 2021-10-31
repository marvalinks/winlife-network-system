<?php

namespace App\Jobs;

use App\Models\Achivement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;
use Illuminate\Bus\Batchable;

class AchivementUploadJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ag;
    public function __construct($ag)
    {
        $this->ag = $ag;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ag = $this->ag;
        $agent = Agent::where('member_id', $ag->member_id)->first();
        if($agent) {
            Achivement::create([
                'member_id' => $ag->member_id, 'name' => $agent->name,
                'period' => $ag->period, 'total_pv' => $ag->total_pv,
                'country' => $ag->country,
            ]);
        }
    }
}
