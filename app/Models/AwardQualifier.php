<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardQualifier extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function award()
    {
        return $this->belongsTo(Award::class, 'award_id', 'award_id');
    }
}
