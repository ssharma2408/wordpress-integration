@extends('layouts.admin')
@section('content')
<form method="post" action="{{ route("admin.document.store") }}">
	@csrf
	<textarea class="form-control" id="tinymce" name="document_content">{!! $content !!}</textarea>

	<button type="submit">Update</button>
</form>
@endsection
@section('scripts')@parent
<script type="text/javascript">
        tinymce.init({
            selector: 'textarea#tinymce',
            height: 600
        });
</script>
@endsection