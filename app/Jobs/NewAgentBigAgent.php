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
        $ss->mk($ag->member_id);

    }
}
