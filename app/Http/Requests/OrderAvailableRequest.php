<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class OrderAvailableRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'distance' => 'required|numeric',
            'deadline' => 'required|date|after:now',
            'items' => 'required',
            'items.*.name' => 'required',
            'items.*.weight' => 'required|numeric',
        ];
    }
}
