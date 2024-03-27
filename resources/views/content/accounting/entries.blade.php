@extends('layouts/layoutMaster')

@section('title', 'Asientos')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection





@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Contabilidad /</span> Asientos
</h4>

<div class="d-flex col-12">
  <div class="d-flex col-6">
    <div class="m-2">
      <button type="button" class="btn btn btn-label-primary">Agregar</button>
    </div>
    <div class="mt-2">
      <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-label-secondary">Excel</button>
        <button type="button" class="btn btn-label-secondary">Detalle</button>
        <button id="btnGroupDrop1" type="button" class="btn btn-label-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Columnas</button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="javascript:void(0);">Dropdown link</a>
                    <a class="dropdown-item" href="javascript:void(0);">Dropdown link</a>
                  </div>
        <button type="button" class="btn btn-label-secondary">Papelera</button>
      </div>
    </div>
  </div>
  <div class="d-flex col-6 mt-2">
    <div class="input-group input-daterange m-2" id="bs-datepicker-daterange">
      <input type="text" id="dateRangePicker" placeholder="MM/DD/YYYY" class="form-control" />
      <span class="input-group-text">al</span>
      <input type="text" placeholder="MM/DD/YYYY" class="form-control" />
    </div>
    <div class="mt-2">
      <input type="text" class="form-control mb-0" id="defaultFormControlInput" placeholder="Buscar..." aria-describedby="defaultFormControlHelp" />
    </div>
  </div>

</div>

<!-- Responsive Datatable -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="dt-responsive table border-top">
      <thead class="text-center">
        <tr>
          <th>N°</th>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Concepto</th>
          <th>Moneda</th>
          <th>Importe</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="text-center">
        <tr>
          <th>1</th>
          <th>11-03-2024</th>
          <th>Diario Principal</th>
          <th>Cierre Febrero</th>
          <th>USD</th>
          <th>18490</th>
          <th><a href="entrie"><i class="bx bx-right-arrow-alt"></i></a></th>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<!--/ Responsive Datatable -->
@endsection
