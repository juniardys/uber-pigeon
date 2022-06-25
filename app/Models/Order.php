<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_ARRIVED = 'arrived';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED = 'canceled';

    public const STATUS_LIST = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_ARRIVED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELED,
    ];

    public const STATUS_LABEL = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_ARRIVED => 'Arrived',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELED => 'Canceled',
    ];

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pigeon() {
        return $this->belongsTo(Pigeon::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
