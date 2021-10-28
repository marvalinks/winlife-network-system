<?php

namespace App\Jobs;

use App\Http\Services\GPService;
use App\Models\Achivement;
use App\Models\Agent;
use App\Models\CheckRunBill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalcStatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $period;
    public function __construct($period)
    {
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Agent::latest()->chunk(50, function ($users){
        //     foreach ($users as $user) {
        //         $this->dispatch(new Gstats($this->period, $user->member_id));
        //     }
        // });
        $ac = $this->period;
        $pd = CheckRunBill::where('type', 'gps')->where('period', $this->period)->first();
        if(!$pd) {
            // Agent::latest()->chunk(50, function ($users) use ($ac){
            //     foreach ($users as $user)  {
            //         if (intval($user->period) <= intval($ac)) {
            //             $this->dispatch(new Gstats($this->period, $user->member_id));
            //         }
            //     }

            // });
            $agents = Agent::latest()->get();
            foreach ($agents as $key => $user) {
                if (intval($user->period) <= intval($ac)) {
                    $this->dispatch(new Gstats($this->period, $user->member_id))->delay(3);
                }
            }
            CheckRunBill::create([
                'period' => $this->period, 'type' => 'gps'
            ]);
        }
    }
}
