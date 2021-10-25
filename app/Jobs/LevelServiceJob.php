<?php

namespace App\Jobs;

use App\Http\Services\LevelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LevelServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $lv = new LevelService($this->combPeriodToday);
        $lv->ABP();
    }
}
