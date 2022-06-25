<?php

namespace App\Http\Controllers\v1;

use App\Models\Timeoff;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TimeoffResource;
use App\Http\Requests\TimeoffCreateRequest;
use App\Http\Requests\TimeoffUpdateRequest;

class TimeoffController extends Controller
{
    use ApiResponse;

    public function get(Request $request) {
        try {
            $query = new Timeoff();

            if ($request->get('timeoff_id')) {
                $query->where('timeoff_id', $request->get('timeoff_id'));
            }

            $timeoffs = $query->paginate($request->limit ?: 10);
            return $this->responseSuccess(TimeoffResource::collection($timeoffs)->response()->getData(true), 'Timeoffs list retrieved succesfully!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    public function create(TimeoffCreateRequest $request) {
        try {
            DB::beginTransaction();

            $check = Timeoff::where('pigeon_id', $request->input('pigeon_id'))
                ->where('start', '<', $request->input('end'))
                ->where('end', '>', $request->input('start'))
                ->first();

            if ($check) throw new \Exception("Already off in selected time!");
        
            $timeoff = Timeoff::create([
                'pigeon_id' => $request->input('pigeon_id'),
                'reason' => $request->input('reason'),
                'desription' => $request->input('desription'),
                'start' => $request->input('start'),
                'end' => $request->input('end'),
            ]);

            DB::commit();
            return $this->responseSuccess(new TimeoffResource($timeoff), 'Timeoff created succesfully!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }

    public function update(TimeoffUpdateRequest $request, $id) {
        try {
            DB::beginTransaction();

            $timeoff = Timeoff::find($id);

            if (!$timeoff) throw new \Exception("Data not found");

            $check = Timeoff::where('pigeon_id', $request->input('pigeon_id'))
                ->where('start', '<', $request->input('end'))
                ->where('end', '>', $request->input('start'))
                ->where('id', '!=', $timeoff->id)
                ->first();

            if ($check) throw new \Exception("Already off in selected time!");

            $timeoff->pigeon_id = $request->input('pigeon_id');
            $timeoff->reason = $request->input('reason');
            $timeoff->desription = $request->input('desription');
            $timeoff->start = $request->input('start');
            $timeoff->end = $request->input('end');

            DB::commit();
            return $this->responseSuccess(new TimeoffResource($timeoff), 'Timeoff saved!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }

    public function delete($id) {
        try {
            DB::beginTransaction();

            $timeoff = Timeoff::find($id);

            if ($timeoff) $timeoff->delete();

            DB::commit();
            return $this->responseSuccess(null, 'Timeoff deleted!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }
}
