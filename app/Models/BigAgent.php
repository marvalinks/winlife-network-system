<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigAgent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stats()
    {
        return $this->hasOne(AgentStatistics::class, 'agent_id', 'member_id');
    }
    public function statlogs()
    {
        return $this->hasMany(StatisticLog::class, 'member_id', 'member_id');
    }
    public function logs($period)
    {
        return $this->statlogs->where('period', $period)->first()->level ?? null;
    }
    public function salary()
    {
        return $this->hasOne(Salary::class, 'member_id', 'member_id');
    }
    public function bonus()
    {
        return $this->hasOne(Bonus::class, 'member_id', 'member_id');
    }
    public function archievements()
    {
        return $this->hasMany(Achivement::class, 'member_id', 'member_id');
    }
    public function awards()
    {
        return $this->hasMany(AwardQualifier::class, 'member_id', 'member_id');
    }
    public function sponser()
    {
        return $this->belongsTo(Agent::class, 'sponser_id', 'member_id');
    }
    public function group()
    {
        return $this->belongsTo(GroupAmt::class, 'member_id', 'member_id');
    }
    public function sponsers()
    {
        return $this->hasMany(Agent::class, 'sponser_id', 'member_id');
    }
    public function bonuses()
    {
        return $this->hasMany(Bonus::class, 'member_id', 'member_id');
    }
    public function currentbonus($date)
    {
        return $this->bonuses->where('period', $date)->first();
    }
    public function salaries()
    {
        return $this->hasMany(Salary::class, 'member_id', 'member_id');
    }
    public function currentsalary($date)
    {
        return $this->salaries->where('period', $date)->first();
    }
    public function currentach($date)
    {
        return $this->archievements->where('period', $date);
    }
    public function cgbv()
    {
        return $this->hasMany(PersonalBv::class, 'member_id', 'member_id');
    }
    public function cgbv2()
    {
        return $this->hasMany(GroupBv::class, 'member_id', 'member_id');
    }


}
