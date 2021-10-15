<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalAchivement extends Model
{
    use HasFactory;
    protected  $guarded = [];

    public function agent()
    {
        return $this->hasOne(Agent::class, 'member_id', 'member_id');
    }
}
