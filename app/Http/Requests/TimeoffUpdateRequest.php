<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class TimeoffUpdateRequest extends BaseRequest
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
            'reason' => 'required|in:sick_leave,rest,other',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ];
    }
}
