<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('order_edit');
    }

    public function rules()
    {
        return [
            'order' => [
                'string',
                'required',
            ],
            'status' => [
                'string',
                'required',
            ],
            'total' => [
                'string',
                'required',
            ],
            'customer' => [
                'string',
                'nullable',
            ],
            'date_created' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
