<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;
use App\Models\Achivement;

class AgentUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        if(!$ag->agent) {
            Agent::create([
                'member_id' => $ag->member_id, 'sponser_id' => $ag->sponser_id,
                'firstname' => $ag->firstname, 'lastname' => $ag->lastname,
                'telephone' => $ag->telephone, 'address' => $ag->address,
                'period' => $ag->period, 'nationality' => $ag->nationality,
                'bank_name' => $ag->bank_name, 'bank_no' => $ag->bank_no,
            ]);
        }
    }
}
