<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeOff extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];

    public function pigeon() {
        return $this->belongsTo(Pigeon::class);
    }
}
