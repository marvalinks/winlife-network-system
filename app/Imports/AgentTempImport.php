<?php

namespace App\Imports;

use App\Models\TemporalAgent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentTempImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // ddd($row);
        return new TemporalAgent([
            'member_id' => strval($row['memberid']),
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'telephone' => $row['phone'],
            'address' => $row['address'],
            'period' => strval($row['period']),
            'sponser_id' => strval($row['sponsorid']),
            'nationality' => $row['nationality'],
            'bank_name' => $row['bankname'] ?? 'null',
            'bank_no' => strval($row['bankno']) ?? null,
            'momo_name' => $row['momoname'] ?? null,
            'momo_no' => strval($row['momono']) ?? null,
        ]);
    }
}
