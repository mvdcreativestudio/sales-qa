@extends('layouts/layoutMaster')

@section('title', 'Elaboraciones')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-ecommerce-product-list.js'
])
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Producción /</span> Elaboraciones
</h4>

@if(session('success'))
  <div class="alert alert-success d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-success border-label-success p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Correcto!</h6>
      <span>{{ session('success') }}</span>
    </div>
  </div>
@elseif(session('error'))
  <div class="alert alert-danger d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-danger border-label-danger p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Error!</h6>
      <span>{{ session('error') }}</span>
    </div>
  </div>
@endif

<!-- Production List Table -->
<div class="card">
  <div class="card-header">
    <div class="d-flex col-12 justify-content-between align-items-center">
      <!-- Ver / Ocultar columnas de la tabla -->
      <div class="d-flex">
        <p class="text-muted small">
          <a href="" class="toggle-switches" data-bs-toggle="collapse" data-bs-target="#columnSwitches" aria-expanded="false" aria-controls="columnSwitches">Ver / Ocultar columnas de la tabla</a>
        </p>
      </div>
    </div>

    <!-- Columnas de la tabla -->
    <div class="collapse" id="columnSwitches">
      <div class="mt-0 d-flex flex-wrap">
        <div class="mx-0">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="0" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Producto</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="1" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Sabor</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="2" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Cantidad</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="3" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Acciones</span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <div class="card-datatable table-responsive pt-0 mt-0">
    <table class="datatables-productions table border-top" data-ajax-url="{{ route('productions.index') }}">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Sabor</th>
          <th>Cantidad</th>
          <th>Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@endsection

<script>
'use strict';

$(function () {
  var dt_production_table = $('.datatables-productions');

  if (dt_production_table.length) {
    var dt_productions = dt_production_table.DataTable({
      ajax: {
        url: dt_production_table.data('ajax-url'),
        type: 'GET'
      },
      columns: [
        { data: 'product_name' },
        { data: 'flavor_name' },
        { data: 'quantity' },
        { data: 'actions', orderable: false, searchable: false }
      ],
      columnDefs: [
        {
          targets: 0,
          render: function (data, type, full, meta) {
            return data ? data : 'N/A';
          }
        },
        {
          targets: 1,
          render: function (data, type, full, meta) {
            return data ? data : 'N/A';
          }
        }
      ],
      order: [0, 'asc'],
      dom: '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center pt-0"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      lengthMenu: [10, 25, 50, 100],
      language: {
        search: '',
        searchPlaceholder: 'Buscar...',
        sLengthMenu: '_MENU_',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoFiltered: "filtrados de _MAX_ elaboraciones",
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        },
        pagingType: "full_numbers",
        emptyTable: 'No hay registros disponibles',
        dom: 'Bfrtip',
        renderer: "bootstrap"
      },
      buttons: [
        {
          text: '<i class="bx bx-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Añadir elaboración</span>',
          className: 'add-new btn btn-primary',
          action: function () {
            window.location.href = '{{ route("productions.create") }}';
          }
        }
      ]
    });

    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
    $('.dt-buttons').addClass('d-flex flex-wrap');
    $('.dataTables_length label select').addClass('form-select form-select-sm');
    $('.dataTables_filter label input').addClass('form-control');
  }

  $('.datatables-productions tbody').on('click', '.delete-button', function () {
    var productionId = $(this).data('id');

    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Una vez eliminada, no podrás recuperar esta elaboración.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
          type: "DELETE",
          url: '{{ url("productions") }}/' + productionId,
          data: {
            _token: csrfToken
          },
          success: function (response) {
            Swal.fire({
              title: '¡Eliminado!',
              text: 'La elaboración ha sido eliminada correctamente.',
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            });
            dt_productions.ajax.reload(null, false);
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
            Swal.fire({
              title: 'Error',
              text: 'Hubo un error al intentar eliminar la elaboración.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
      }
    });
  });
});
</script>
