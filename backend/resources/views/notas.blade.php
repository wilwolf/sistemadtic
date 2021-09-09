@extends(backpack_view('blank'))
@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    //$crud->entity_name_plural => url($crud->route),
    trans('llenado de notas') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{{ $detalles[0]->tituloe }}</span>
      <small id="datatable_info_stack">{{ $detalles[0]->modalidad. ' '. $detalles[0]->version. ' | '. $detalles[0]->docente }}</small>
    </h2>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">

<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />

<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
@endsection
@section('content')

<!-- -->
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="">

        <div class="row mb-0">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
            <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
          </div>
        </div>

        <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2" cellspacing="0">
            <thead>
              <th>Nro</th>
              <th>Carnet</th>
              <th>Apellidos</th>
              <th>Nombres</th>
              <th>Nota</th>
            </thead>
            <tbody>
                <form id="editable-form" class="editable-form">
                @foreach ($notas as $key=>$column)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $column->carnet }}</td>
                    <td>{{ $column->apellidos}}</td>
                    <td>{{ $column->nombre}}</td>
                    <td>
                        <a href="#" class="update" data-name="nota" id="nota{{ $column->id }}" data-type="number"
                            data-pk="{{ $column->id }}" data-title="Enter name">{{ $column->nota }}</a>
                    </td>
                </tr>
                @endforeach
                </form>
            </tbody>
            <tfoot>
              
            </tfoot>
          </table>
    </div>
  </div>
<!-- -->


<script>
    (function($) {
        'use strict';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $(function() {
        if ($('#editable-form').length) {
                $.fn.editable.defaults.mode = 'inline';
                $.fn.editableform.buttons =
                '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
                    '<i class="fa fa-fw fa-check"></i>' +
                    '</button>' +
                '<button type="button" class="btn btn-warning btn-sm editable-cancel">' +
                    '<i class="fa fa-fw fa-times"></i>' +
                    '</button>';
                @foreach ($notas as $key=>$column)
                    $('#nota{{ $column->id }}').editable({
                        url: '{{ url("admin/inscripciones/updatenotas") }}',
                        title: 'Update',
                    });
                @endforeach
        }
        });
    })(jQuery);
</script>
@endsection
