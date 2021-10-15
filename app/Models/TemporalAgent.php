<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalAgent extends Model
{
    use HasFactory;
    protected  $guarded = [];

    public function agent()
    {
        return $this->hasOne(Agent::class, 'member_id', 'member_id');
    }
    public function sponser()
    {
        return $this->belongsTo(Agent::class, 'sponser_id', 'member_id');
    }
    public function msponser()
    {
        return $this->belongsTo(TemporalAgent::class, 'sponser_id', 'member_id');
    }
    public function msponsers()
    {
        return $this->hasMany(TemporalAgent::class, 'sponser_id', 'member_id');
    }
}
