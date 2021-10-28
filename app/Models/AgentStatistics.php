<?php

namespace App\Models;

use App\Http\Services\LevelService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentStatistics extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function particularperiodlevel($combPeriod)
    {

        $lv = new LevelService($combPeriod);
        $lv->ABP();

        return 0;

    }
}
