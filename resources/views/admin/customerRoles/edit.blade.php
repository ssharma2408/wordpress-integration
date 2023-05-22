@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.customerRole.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.customer-roles.update", [$customerRole->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.customerRole.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $customerRole->name) }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.customerRole.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="discount_percentage">{{ trans('cruds.customerRole.fields.discount_percentage') }}</label>
                <input class="form-control {{ $errors->has('discount_percentage') ? 'is-invalid' : '' }}" type="number" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', $customerRole->discount_percentage) }}" step="0.01" required>
                @if($errors->has('discount_percentage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount_percentage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.customerRole.fields.discount_percentage_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection