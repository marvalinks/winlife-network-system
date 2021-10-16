<?php

namespace App\Imports;

use App\Models\TemporalAchivement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArchievementTempImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // ddd($row);
        return new TemporalAchivement([
            'member_id' => strval($row['distributor_no']),
            'name' => $row['names'] ?? null,
            'period' => $row['period'],
            'total_pv' => $row['total_pv'] ?? floatval(0),
            'country' => $row['country'],
        ]);
    }
}
