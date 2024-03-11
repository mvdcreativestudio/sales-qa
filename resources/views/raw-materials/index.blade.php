@extends('layouts/layoutMaster')

@section('title', 'Listado de Materias Primas')

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
<script type="text/javascript">
    var rawMaterials = @json($rawMaterials);
</script>
<!--@vite(['resources/assets/js/raw-materials-list.js'])-->
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Gestión /</span> Listado de Materias Primas
</h4>

<div class="card mb-4">
  <div class="card-body">
    <div class="row gy-4 gy-sm-1">
      <div class="col-sm-6 col-lg-3">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="mb-2">Total Materias Primas</h6>
            <h4 class="mb-2">150</h4>
            <p class="mb-0"><span class="text-muted me-2">En almacen</span><span class="badge bg-label-success">+5%</span></p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="bx bx-layer bx-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title">Materias Primas</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="table datatables-raw-materials border-top">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Unidad de Medida</th>
          <th>Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {

    window.baseUrl = "{{ asset('storage/assets/img/raw_materials/') }}";

    let borderColor, bodyBg, headingColor;

    if (isDarkStyle) {
      borderColor = config.colors_dark.borderColor;
      bodyBg = config.colors_dark.bodyBg;
      headingColor = config.colors_dark.headingColor;
    } else {
      borderColor = config.colors.borderColor;
      bodyBg = config.colors.bodyBg;
      headingColor = config.colors.headingColor;
    }

    var dt_raw_materials_table = $('.datatables-raw-materials');
    
    var rawMaterialAdd = '{{ route('raw-materials.create') }}';
    var rawMaterialEdit = '{{ route('raw-materials.edit', ':id') }}';
    var rawMaterialDelete = '{{ route('raw-materials.destroy', ':id') }}';

    if (dt_raw_materials_table.length) {
      dt_raw_materials_table.DataTable({
        data: rawMaterials,
        columns: [
          { data: 'image_url' },
          { data: 'name' },
          { data: 'description' },
          { data: 'unit_of_measure' },
        ],
        columnDefs: [
          {
            targets: 0,
            searchable: false,
            orderable: false,
            render: function(data, type, row) {
              var imageUrl = window.baseUrl + '/' + data;
              return `<img src="${imageUrl}" alt="Imagen" class="img-fluid rounded" style="max-width: 60px; height: auto;">`;
            }
          },
          {
            targets: 1,
            searchable: true,
            responsivePriority: 1,
            orderable: true,
            render: function (data, type, row, meta) {
              return '<span>' + data + '</span>';
            }
          },
          {
            targets: 2,
            searchable: true,
            responsivePriority: 3,
            orderable: true,
            render: function (data, type, row, meta) {
              return (data && data.length > 50) ? '<span>' + data.substr(0, 50) + '...' + '</span>' : data;
            }
          },
          {
            targets: 3,
            searchable: true,
            responsivePriority: 4,
            orderable: true,
            render: function (data, type, row, meta) {
              return '<span>' + data + '</span>';
            }
          },
          {
            targets: 4,
            searchable: false,
            responsivePriority: 5,
            orderable: false,
            render: function (data, type, row, meta) {
              return `
                <div class="dropdown">
                  <button class="btn btn-icon btn-icon-only" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="${rawMaterialEdit.replace(':id', row.id)}">
                      <i class="bx bx-pencil"></i> Editar
                    </a>
                    <a class="dropdown-item" href="${rawMaterialDelete.replace(':id', row.id)}">
                      <i class="bx bx-trash"></i> Eliminar
                    </a>
                  </div>
                </div>
              `;
            }
          }
        ],
        language: {
          searchPlaceholder: 'Buscar...',
          sLengthMenu: '_MENU_',
          info: 'Mostrando _START_ a _END_ de _TOTAL_ Materias Primas',
          paginate: {
            first:    'Primero',
            last:     'Último',
            next:     '<span class="mx-2">Siguiente</span>',
            previous: '<span class="mx-2">Anterior</span>'
          },
          aria: {
            sortAscending:  ': activar para ordenar la columna ascendente',
            sortDescending: ': activar para ordenar la columna descendente'
          },
          emptyTable: "No hay datos disponibles en la tabla",
          zeroRecords: "No se encontraron coincidencias",
          lengthMenu: "_MENU_",
          loadingRecords: "Cargando...",
          processing: "Procesando...",
          search: "",
          infoFiltered: "(filtrado de un total de _MAX_ Materias Primas)",
          infoEmpty: "Mostrando 0 a 0 de 0 Materias Primas",
        },
        dom:
        '<"card-header d-flex border-top rounded-0 flex-wrap py-md-0"' +
        '<"me-5 ms-n2 pe-5"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0"lB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
        buttons: [
          {
              text: '<i class="bx bx-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Crear</span>',
              className: 'btn btn-primary ml-3',
              action: function () {
                  window.location.href = rawMaterialAdd;
              }
          },
        ],
      });
    }

    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
    $('.dt-buttons').addClass('d-flex flex-wrap');

    $('div.dataTables_filter input').addClass('form-control');
    $('div.dataTables_length select').addClass('form-select');
  });

</script>
@endsection