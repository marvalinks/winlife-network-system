<?php

namespace App\Imports;

use App\Models\Achivement;
use App\Models\Agent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArchievementImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{

    private $users;

    public function __construct()
    {
        $this->users = Agent::select('member_id')->get();
    }
    public function model(array $row)
    {
        $user = $this->users->where('member_id', strval($row['member_id']))->first();
        // ddd($user);
        if($user):
            return new Achivement([
                'member_id' => strval($row['member_id']),
                'name' => $row['names'] ?? null,
                'period' => $row['period'],
                'total_pv' => $row['total_pv'] ?? floatval(0),
                'country' => $row['country'],
            ]);
        endif;
    }

    public function batchSize(): int
    {
        return 80;
    }

    public function chunkSize(): int
    {
        return 80;
    }
}
