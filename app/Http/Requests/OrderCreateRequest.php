<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class OrderCreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pigeon_id' => 'required|exists:pigeons,id',
            'distance' => 'required|numeric',
            'deadline' => 'required|date|after:now',
            'items' => 'required',
            'items.*.name' => 'required',
            'items.*.weight' => 'required|numeric',
        ];
    }
}
