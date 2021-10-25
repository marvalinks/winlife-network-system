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
    public function statlogs()
    {
        return $this->hasMany(StatisticLog::class, 'member_id', 'member_id');
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
    public function childrenSponsers()
    {
        return $this->hasMany(Agent::class, 'sponser_id', 'member_id')->with('sponsers');
    }
    public function grandchildren()
    {
        return $this->childrenSponsers()->with('grandchildren');
    }
    public function getCalculategbvAttribute($value)
    {
        return $this->childrenSponsers->count();
    }
    public function getNameAttribute($value)
    {
        return $this->firstname.' '. $this->lastname;
    }

    public $combPeriod;
    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;

    public function currentgbv($combPeriod)
    {
        $this->currentGBV = 0.0;
        $this->ACCGBV = 0.0;
        // ddd($combPeriod);
        $id = $this->member_id;
        $this->combPeriod = $combPeriod;
        $agents =  Agent::where('sponser_id', $id)->get();
        $user =  Agent::where('member_id', $id)->first();

        $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        foreach ($agents as $key => $sponser) {

            $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
            foreach ($sponser->childrenSponsers as $k => $child_sponser) {
                $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                $this->reloop($child_sponser);
            }
        }
        return $this->currentGBV;

    }
    public function accgbv($combPeriod)
    {
        $this->currentGBV = 0.0;
        $this->ACCGBV = 0.0;

        $id = $this->member_id;
        $this->combPeriod = $combPeriod;
        $agents =  Agent::where('sponser_id', $id)->get();
        $user =  Agent::where('member_id', $id)->first();
        $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        foreach ($agents as $key => $sponser) {

            $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
            foreach ($sponser->childrenSponsers as $k => $child_sponser) {
                $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                $this->reloop($child_sponser);
            }
        }
        return $this->ACCGBV;

    }
    protected function reloop($child_sponser)
    {
        if ($child_sponser->sponsers) {
            foreach ($child_sponser->sponsers as $k => $childrenSponser) {
                // $this->currentGBV += 1;
                $this->currentGBV += $childrenSponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $childrenSponser->archievements->whereBetween('period', [$childrenSponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                // $this->ACCGBV += 1;
                $this->reloop($childrenSponser);
            }
        }
    }

}
