<?php

namespace App\Jobs;

use App\Http\Services\AwardService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AwardServiceJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $combPeriodToday;

    public function __construct($combPeriodToday)
    {
        $this->combPeriodToday = $combPeriodToday;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $awd = new AwardService($this->combPeriodToday);
        $awd->ABP();
    }
}
