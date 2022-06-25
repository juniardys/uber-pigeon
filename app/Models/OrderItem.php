<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
