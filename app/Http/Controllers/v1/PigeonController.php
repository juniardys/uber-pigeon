<?php

namespace App\Http\Controllers\v1;

use App\Models\Pigeon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PigeonResource;
use App\Http\Requests\PigeonCreateRequest;
use App\Http\Requests\PigeonUpdateRequest;
use App\Traits\ApiResponse;

class PigeonController extends Controller
{
    use ApiResponse;

    public function get(Request $request) {
        try {
            $pigeons = Pigeon::paginate($request->limit ?: 10);
            return $this->responseSuccess(PigeonResource::collection($pigeons)->response()->getData(true), 'Pigeon list retrieved succesfully!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    public function create(PigeonCreateRequest $request) {
        try {
            DB::beginTransaction();
        
            $pigeon = Pigeon::create([
                'name' => $request->input('name'),
                'speed' => $request->input('speed'),
                'range' => $request->input('range'),
                'cost' => $request->input('cost'),
                'downtime' => $request->input('downtime'),
                'weight' => $request->input('weight'),
            ]);

            DB::commit();
            return $this->responseSuccess(new PigeonResource($pigeon), 'Pigeon created succesfully!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }

    public function update(PigeonUpdateRequest $request, $id) {
        try {
            DB::beginTransaction();

            $pigeon = Pigeon::find($id);

            if (!$pigeon) throw new \Exception("Data not found");
        
            $pigeon->name = $request->input('name');
            $pigeon->speed = $request->input('speed');
            $pigeon->range = $request->input('range');
            $pigeon->cost = $request->input('cost');
            $pigeon->downtime = $request->input('downtime');
            $pigeon->weight = $request->input('weight');

            DB::commit();
            return $this->responseSuccess(new PigeonResource($pigeon), 'Pigeon saved!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }

    public function toggleStatus($id) {
        try {
            DB::beginTransaction();

            $pigeon = Pigeon::find($id);

            if (!$pigeon) throw new \Exception("Data not found");
        
            $pigeon->is_active = !$pigeon->is_active;
            $pigeon->save();

            DB::commit();
            return $this->responseSuccess(new PigeonResource($pigeon), 'Pigeon status changed!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }

    public function delete($id) {
        try {
            DB::beginTransaction();

            $pigeon = Pigeon::find($id);

            if ($pigeon) $pigeon->delete();

            DB::commit();
            return $this->responseSuccess(null, 'Pigeon deleted!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }
}
