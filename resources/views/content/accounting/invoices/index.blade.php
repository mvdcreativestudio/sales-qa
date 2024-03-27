@extends('layouts/layoutMaster')

@section('title', 'Facturas')

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
  <span class="text-muted fw-light">Contabilidad /</span> Facturas
</h4>

<div class="d-flex col-12">
  <div class="d-flex col-6">
    <div class="m-2">
      <button type="button" class="btn btn btn-label-primary">Agregar</button>
    </div>
    <div class="mt-2">
      <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-label-secondary">Excel</button>
        <button id="btnGroupDrop1" type="button" class="btn btn-label-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Columnas</button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="javascript:void(0);">Dropdown link</a>
                    <a class="dropdown-item" href="javascript:void(0);">Dropdown link</a>
                  </div>
        <button type="button" class="btn btn-label-secondary">Actualizar CFEs</button>
        <button type="button" class="btn btn-label-secondary">Detalle</button>
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
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>CFE</th>
          <th>Moneda</th>
          <th>Total</th>
          <th>Saldo</th>
          <th>Comprobante</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="text-center">
        <tr>
          <th>5</th>
          <th>Rodelú SA</th>
          <th>07-03-2024</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-success">Aceptado</span>
          </td>
          <th>e-Factura</th>
          <th>USD</th>
          <th>2490</th>
          <th>2490</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-danger">Venta Crédito</span>
          </td>
          <th><i class="bx bx-right-arrow-alt"></i></th>
        </tr>
        <tr>
          <th>4</th>
          <th>El Italiano</th>
          <th>08-03-2024</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-success">Aceptado</span>
          </td>
          <th>e-Factura</th>
          <th>UYU</th>
          <th>9840</th>
          <th>9840</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-danger">Venta Crédito</span>
          </td>
          <th><i class="bx bx-right-arrow-alt"></i></th>
        </tr>
        <tr>
          <th>3</th>
          <th>Lo de pedro</th>
          <th>08-03-2024</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-success">Aceptado</span>
          </td>
          <th>e-Factura</th>
          <th>UYU</th>
          <th>11487</th>
          <th>11487</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-danger">Venta Crédito</span>
          </td>
          <th><i class="bx bx-right-arrow-alt"></i></th>
        </tr>
        <tr>
          <th>2</th>
          <th>Sal y pimienta</th>
          <th>09-03-2024</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-success">Aceptado</span>
          </td>
          <th>e-Factura</th>
          <th>UYU</th>
          <th>3210</th>
          <th>3210</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-danger">Venta Crédito</span>
          </td>
          <th><i class="bx bx-right-arrow-alt"></i></th>
        </tr>
        <tr>
          <th>1</th>
          <th>La Costeña</th>
          <th>09-03-2024</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-success">Aceptado</span>
          </td>
          <th>e-Factura</th>
          <th>UYU</th>
          <th>6450</th>
          <th>6450</th>
          <td class="style">
            <span class="badge rounded-pill bg-label-danger">Venta Crédito</span>
          </td>
          <th><i class="bx bx-right-arrow-alt"></i></th>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<!--/ Responsive Datatable -->
@endsection
