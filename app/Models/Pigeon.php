<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pigeon extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function timeOffs() {
        return $this->hasMany(TimeOff::class);
    }
}
