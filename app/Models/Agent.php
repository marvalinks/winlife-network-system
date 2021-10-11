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
    public function sponser()
    {
        return $this->belongsTo(Agent::class, 'sponser_id', 'member_id');
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

    public $combPeriod;
    public $currentGBV = 0.0;
    public $ACCGBV = 0.0;

    public function currentgbv($combPeriod)
    {
        $this->currentGBV = 0.0;
        $this->ACCGBV = 0.0;
        // ddd($this->ACCGBV);
        $id = $this->member_id;
        $this->combPeriod = $combPeriod;
        $agents =  Agent::with(['childrenSponsers'])->where('sponser_id', $id)->get();
        $user =  Agent::with(['grandchildren','childrenSponsers', 'archievements', 'sponsers'])->where('member_id', $id)->first();

        $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        foreach ($agents as $key => $sponser) {

            //level 1
            $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
            foreach ($sponser->childrenSponsers as $k => $child_sponser) {
                //level2
                $this->currentGBV += $child_sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
                $this->ACCGBV += $child_sponser->archievements->whereBetween('period', [$child_sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
                $this->reloop($child_sponser);
            }
        }
        // ddd($this->ACCGBV);
        return $this->currentGBV;

    }
    public function accgbv($combPeriod)
    {
        $id = $this->member_id;
        $this->combPeriod = $combPeriod;
        $agents =  Agent::with(['childrenSponsers'])->where('sponser_id', $id)->get();
        $user =  Agent::with(['grandchildren','childrenSponsers', 'archievements', 'sponsers'])->where('member_id', $id)->first();
        $this->currentGBV = $user->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
        $this->ACCGBV = $user->archievements->whereBetween('period', [$user->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);

        foreach ($agents as $key => $sponser) {

            //level 1
            $this->currentGBV += $sponser->archievements->where('period', $this->combPeriod)->sum('total_pv') ?? floatval(0);
            $this->ACCGBV += $sponser->archievements->whereBetween('period', [$sponser->archievements->min('period'), $this->combPeriod])->sum('total_pv') ?? floatval(0);
            foreach ($sponser->childrenSponsers as $k => $child_sponser) {
                //level2
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
