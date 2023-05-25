@extends('layouts.admin')
@section('content')
@can('document_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.documents.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.document.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.document.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Document">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.document.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.document.fields.path') }}
                        </th>
                        <th>
                            {{ trans('cruds.document.fields.is_converted') }}
                        </th>
                        <th>
                            {{ trans('cruds.document.fields.version') }}
                        </th>
                        <th>
                            {{ trans('cruds.document.fields.client') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $key => $document)
                        <tr data-entry-id="{{ $document->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $document->id ?? '' }}
                            </td>
                            <td>
                                {{ $document->path ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $document->is_converted ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $document->is_converted ? 'checked' : '' }}>
                            </td>
                            <td>
                                @foreach($document->versions as $key => $item)
                                    <span class="badge badge-info">{{ $item->version_number }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $document->client->name ?? '' }}
                            </td>
                            <td>
                                @can('document_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.documents.show', $document->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('document_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.documents.edit', $document->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('document_delete')
                                    <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('document_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.documents.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Document:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection