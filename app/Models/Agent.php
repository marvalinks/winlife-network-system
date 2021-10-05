<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stats()
    {
        return $this->hasOne(AgentStatistics::class, 'agent_id', 'member_id');
    }
}
