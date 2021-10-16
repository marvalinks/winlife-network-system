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
            'member_id' => strval($row['distribution_no']),
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'telephone' => $row['phone'],
            'address' => $row['address'],
            'period' => strval($row['join_date']),
            'sponser_id' => strval($row['sponsor_no']),
            'nationality' => $row['nationality'],
            'bank_name' => $row['bank_name'] ?? 'null',
            'bank_no' => strval($row['bank_account']) ?? null,
            // 'momo_name' => $row['momoname'] ?? null,
            // 'momo_no' => strval($row['momono']) ?? null,
        ]);
    }
}
