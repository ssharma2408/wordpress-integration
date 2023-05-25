@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.documentVersion.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.document-versions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.documentVersion.fields.id') }}
                        </th>
                        <td>
                            {{ $documentVersion->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.documentVersion.fields.document') }}
                        </th>
                        <td>
                            {{ $documentVersion->document->path ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.documentVersion.fields.content') }}
                        </th>
                        <td>
                            {!! $documentVersion->content !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.documentVersion.fields.version_number') }}
                        </th>
                        <td>
                            {{ $documentVersion->version_number }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.document-versions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection