<?php

namespace App\Http\Requests;

use App\Models\DocumentVersion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDocumentVersionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('document_version_create');
    }

    public function rules()
    {
        return [
            'document_id' => [
                'required',
                'integer',
            ],
            'content' => [
                'required',
            ],
            'version_number' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
