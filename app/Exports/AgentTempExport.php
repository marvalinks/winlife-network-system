<?php

namespace App\Exports;

use App\Models\TemporalAgent;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AgentTempExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $dd = TemporalAgent::all();
        $bset = [];
        foreach ($dd as $key => $export) {
            if(!isset($export->sponser)){
                array_push($bset, $export->member_id);
            }
            if(isset($export->msponser) && !isset($export->sponser)){
                array_push($bset, $export->member_id);
            }
        }
        $exports = TemporalAgent::whereIn('member_id', $bset)->get();
        return view('exports.AgentR', [
            'exports' => $exports
        ]);
    }
}
