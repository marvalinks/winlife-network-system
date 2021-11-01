<?php

namespace App\Jobs;

use App\Http\Services\GPService;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\CheckRunBill;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalcStatsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $period;
    public $agent;
    public function __construct($period, $agent)
    {
        $this->period = $period;
        $this->agent = $agent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ac = $this->period;
        $gps = new GPService($ac);
        $gps->start2($this->agent);
    }
}
