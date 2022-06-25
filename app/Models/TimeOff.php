<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timeoff extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    public const REASON_SICK_LEAVE = 'sick_leave';
    public const REASON_REST = 'rest';
    public const REASON_OTHER = 'other';

    public const REASON_LIST = [
        self::REASON_SICK_LEAVE,
        self::REASON_REST,
        self::REASON_OTHER,
    ];

    public const REASON_LABEL = [
        self::REASON_SICK_LEAVE => 'Sick Leave',
        self::REASON_REST => 'Rest',
        self::REASON_OTHER => 'Other',
    ];

    protected $guarded = [];

    public function pigeon() {
        return $this->belongsTo(Pigeon::class);
    }
}
