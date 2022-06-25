<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Pigeon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PigeonResource;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderAvailableRequest;
use App\Http\Resources\OrderResource;
use App\Traits\Generator;

class OrderController extends Controller
{
    use ApiResponse, Generator;

    public function get(Request $request) {
        try {
            $orders = Order::with('items')->paginate($request->limit ?: 10);
            return $this->responseSuccess(OrderResource::collection($orders)->response()->getData(true), 'Order list retrieved succesfully!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    public function available(OrderAvailableRequest $request) {
        try {
            $distance = $request->input('distance');
            $deadline = $request->input('deadline');
            $items = $request->input('items');
            $weights = collect($items)->sum('weight');

            $pigeons = Pigeon::query()
                ->select('pigeons.*')
                ->leftJoin('timeoffs', function($q) use ($deadline) {
                    $q->on('pigeons.id', '=', 'timeoffs.pigeon_id')
                        ->whereNull('timeoffs.deleted_at')
                        ->where('timeoffs.start', '<=', DB::raw("'$deadline'"))
                        ->where('timeoffs.end', '>=', DB::raw("'$deadline'"));
                })
                ->leftJoin('orders', function($q) use ($deadline) {
                    $q->on('pigeons.id', '=', 'orders.pigeon_id')
                        ->whereNull('orders.deleted_at')
                        ->whereRaw('DATE_ADD(orders.deadline, INTERVAL pigeons.downtime HOUR) >= ?', [$deadline])
                        ->orderBy('orders.deadline', 'desc')
                        ->limit(1);
                })
                ->whereNull('timeoffs.id')
                ->whereNull('orders.id')
                ->where(function($q) use ($weights) {
                    $q->whereNull('pigeons.weight')
                        ->orWhere('pigeons.weight', '>=', $weights);
                })
                ->where('pigeons.range', '>=', $distance)
                ->distinct()
                ->paginate($request->limit ?: 10);

            $pigeons->getCollection()->transform(function($item, $key) use ($distance) {
                $item->total_cost = $item->cost * $distance;
                return $item;
            });

            return $this->responseSuccess(PigeonResource::collection($pigeons)->response()->getData(true), 'Available pigeon list retrieved successfully!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    public function create(OrderCreateRequest $request) {
        try {
            DB::beginTransaction();

            $pigeon_id = $request->input('pigeon_id');
            $distance = $request->input('distance');
            $deadline = $request->input('deadline');
            $items = $request->input('items');
            $weights = collect($items)->sum('weight');

            $pigeon = Pigeon::query()
                ->select('pigeons.*')
                ->leftJoin('timeoffs', function($q) use ($deadline) {
                    $q->on('pigeons.id', '=', 'timeoffs.pigeon_id')
                        ->whereNull('timeoffs.deleted_at')
                        ->where('timeoffs.start', '<=', DB::raw("'$deadline'"))
                        ->where('timeoffs.end', '>=', DB::raw("'$deadline'"));
                })
                ->leftJoin('orders', function($q) use ($deadline) {
                    $q->on('pigeons.id', '=', 'orders.pigeon_id')
                        ->whereNull('orders.deleted_at')
                        ->whereRaw('DATE_ADD(orders.deadline, INTERVAL pigeons.downtime HOUR) >= ?', [$deadline])
                        ->orderBy('orders.deadline', 'desc')
                        ->limit(1);
                })
                ->whereNull('timeoffs.id')
                ->whereNull('orders.id')
                ->where(function($q) use ($weights) {
                    $q->whereNull('pigeons.weight')
                        ->orWhere('pigeons.weight', '>=', $weights);
                })
                ->where('pigeons.range', '>=', $distance)
                ->where('pigeons.id', $pigeon_id)
                ->first();

            if (!$pigeon) throw new \Exception("Pigeon is not available");

            $order = Order::create([
                'code' => $this->generateOrderCode(),
                'user_id' => $request->user()->id,
                'pigeon_id' => $pigeon_id,
                'distance' => $distance,
                'deadline' => $deadline,
                'cost_per_km' => $pigeon->cost,
                'total_cost' => $pigeon->cost * $distance,
                'note' => $request->input('note'),
            ]);

            $create_items = [];
            foreach ($items as $key => $item) {
                $create_items[] = [
                    'name' => $item['name'],
                    'weight' => $item['weight'],
                    'note' => isset($item['note']) ? $item['note']: '',
                ];
            }

            if (!empty($create_items)) {
                $order->items()->createMany($create_items);
                $order->load('items');
            }

            DB::commit();
            return $this->responseSuccess(new OrderResource($order), 'Order created succesfully!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }
}
