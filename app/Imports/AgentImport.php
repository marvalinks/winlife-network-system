<?php

namespace App\Imports;

use App\Models\Agent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentImport implements ToModel, WithHeadingRow
{

    public function __construct()
    {
        $this->users = Agent::select('member_id')->get();
    }

    public function model(array $row)
    {
        // $user = $this->users->where('member_id', strval($row['memberid']))->first();
        return new Agent([
            'member_id' => strval($row['memberid']),
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'telephone' => $row['phone'],
            'address' => $row['address'],
            'period' => strval($row['period']),
            'sponser_id' => strval($row['sponsorid']),
            'nationality' => $row['nationality'],
            'bank_name' => $row['bankname'],
            'bank_no' => strval($row['bankno']),
            'momo_name' => $row['momoname'],
            'momo_no' => strval($row['momono']),
        ]);
    }
}
