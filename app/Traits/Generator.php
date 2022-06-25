<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Generator
 */
trait Generator {
    public function generateOrderCode() {
        do {
            $code = Str::random(8);
            $check = Order::where('code', $code)->first();
        } while ($check);
        return $code;
    }
}
