<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class PigeonCreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'speed' => 'required|numeric',
            'range' => 'required|numeric',
            'cost' => 'required|numeric',
            'downtime' => 'required|numeric',
            'weight' => 'numeric|nullable',
        ];
    }
}
