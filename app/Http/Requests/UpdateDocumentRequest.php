<?php

namespace App\Http\Requests;

use App\Models\Document;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('document_edit');
    }

    public function rules()
    {
        return [
            'path' => [
                'string',
                'required',
            ],
            'versions.*' => [
                'integer',
            ],
            'versions' => [
                'array',
            ],
            'client_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
