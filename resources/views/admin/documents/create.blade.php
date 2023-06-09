@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.document.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.documents.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="path">{{ trans('cruds.document.fields.path') }}</label>
                <input class="form-control {{ $errors->has('path') ? 'is-invalid' : '' }}" type="text" name="path" id="path" value="{{ old('path', '') }}" required>
                @if($errors->has('path'))
                    <div class="invalid-feedback">
                        {{ $errors->first('path') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.document.fields.path_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_converted') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_converted" value="0">
                    <input class="form-check-input" type="checkbox" name="is_converted" id="is_converted" value="1" {{ old('is_converted', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_converted">{{ trans('cruds.document.fields.is_converted') }}</label>
                </div>
                @if($errors->has('is_converted'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_converted') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.document.fields.is_converted_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="versions">{{ trans('cruds.document.fields.version') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('versions') ? 'is-invalid' : '' }}" name="versions[]" id="versions" multiple>
                    @foreach($versions as $id => $version)
                        <option value="{{ $id }}" {{ in_array($id, old('versions', [])) ? 'selected' : '' }}>{{ $version }}</option>
                    @endforeach
                </select>
                @if($errors->has('versions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('versions') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.document.fields.version_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="client_id">{{ trans('cruds.document.fields.client') }}</label>
                <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}" name="client_id" id="client_id" required>
                    @foreach($clients as $id => $entry)
                        <option value="{{ $id }}" {{ old('client_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('client'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.document.fields.client_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="content">{{ trans('cruds.document.fields.content') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}" name="content" id="content">{!! old('content') !!}</textarea>
                @if($errors->has('content'))
                    <div class="invalid-feedback">
                        {{ $errors->first('content') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.document.fields.content_helper') }}</span>
            </div>
            <div class="form-group">
                <button id = "load_btn" class="btn btn-danger" type="button">
                    Load
                </button>
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
                xhr.open('POST', '{{ route('admin.documents.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $document->id ?? 0 }}');
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

/* $(document).on('click', '#load_btn', function(event) {
        event.preventDefault();        

        getHTML();

    });
    var getHTML = function(){
        $.ajax({
            method:'POST',
			header:{
			  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
			},
            url:'gethtml', //Make sure your URL is correct
              data:{
				  _token: "{{ csrf_token() }}",
				  dataType: 'json', 
				  contentType:'application/json', 
				},
            success:function(data){
                console.log(data); //Please share cosnole data
                if(data.msg) //Check the data.msg isset?
                {
                    $("#content").html(data.msg); //replace html by data.msg
                }

            }
        });
    } */
	
	
	$(document).ready(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $("#load_btn").click(function(){
                $.ajax({
                    /* the route pointing to the post function */
                    url: 'gethtml',
                    type: 'GET',
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, message:$(".getinfo").val()},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                        $("#content").html(data.msg); 
                    }
                }); 
            });
       });    
</script>

@endsection