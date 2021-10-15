<?php

namespace App\Exports;

use App\Models\TemporalAchivement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArchievementTempExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $dd = TemporalAchivement::all();
        $bset = [];
        foreach ($dd as $key => $export) {
            if(!isset($export->agent)){
                array_push($bset, $export->member_id);
            }
        }
        $exports = TemporalAchivement::whereIn('member_id', $bset)->get();
        return view('exports.AgentA', [
            'aexports' => $exports
        ]);
    }
}
