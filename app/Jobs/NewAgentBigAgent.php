<?php

namespace App\Jobs;

use App\Http\Services\BigAgentService;
use App\Models\Agent;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewAgentBigAgent implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        $ss = new BigAgentService();
        if(!$ag->agent) {
            $agent = Agent::create([
                'member_id' => $ag->member_id, 'sponser_id' => $ag->sponser_id,
                'firstname' => $ag->firstname, 'lastname' => $ag->lastname,
                'telephone' => $ag->telephone, 'address' => $ag->address,
                'period' => $ag->period, 'nationality' => $ag->nationality,
                'bank_name' => $ag->bank_name, 'bank_no' => $ag->bank_no,
            ]);
            $ss->mk($agent->member_id);
        }

    }
}
