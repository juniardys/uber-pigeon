<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request) {
        try {
            DB::beginTransaction();
        
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            DB::commit();
            return $this->responseSuccess(new UserResource($user), 'Successfully registered!');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError($th->getMessage());
        }
    }

    public function login(LoginRequest $request) {
        try {
            if (!Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                throw new \Exception("Invalid login!");
            }

            $user = $request->user();
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            return $this->responseSuccess(['token' => $token], 'Successfully login!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    public function logout(Request $request) {
        try {
            if ($request->user()) {
                $request->user()->token()->revoke();
            }
            return $this->responseSuccess('Successfully logout!');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }
}
