@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.documentVersion.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.document-versions.update", [$documentVersion->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="document_id">{{ trans('cruds.documentVersion.fields.document') }}</label>
                <select class="form-control select2 {{ $errors->has('document') ? 'is-invalid' : '' }}" name="document_id" id="document_id" required>
                    @foreach($documents as $id => $entry)
                        <option value="{{ $id }}" {{ (old('document_id') ? old('document_id') : $documentVersion->document->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('document'))
                    <div class="invalid-feedback">
                        {{ $errors->first('document') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.documentVersion.fields.document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="content">{{ trans('cruds.documentVersion.fields.content') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}" name="content" id="content">{!! old('content', $documentVersion->content) !!}</textarea>
                @if($errors->has('content'))
                    <div class="invalid-feedback">
                        {{ $errors->first('content') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.documentVersion.fields.content_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="version_number">{{ trans('cruds.documentVersion.fields.version_number') }}</label>
                <input class="form-control {{ $errors->has('version_number') ? 'is-invalid' : '' }}" type="number" name="version_number" id="version_number" value="{{ old('version_number', $documentVersion->version_number) }}" step="1" required>
                @if($errors->has('version_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('version_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.documentVersion.fields.version_number_helper') }}</span>
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

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.document-versions.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $documentVersion->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection