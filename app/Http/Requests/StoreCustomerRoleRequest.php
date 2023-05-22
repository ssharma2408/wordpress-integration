<?php

namespace App\Http\Requests;

use App\Models\CustomerRole;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCustomerRoleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('customer_role_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'discount_percentage' => [
                'numeric',
                'required',
            ],
        ];
    }
}
