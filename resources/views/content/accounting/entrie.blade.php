@extends('layouts/layoutMaster')

@section('title', 'Asiento Contable')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

@section('content')

<div class="row">
  <div class="card">
    <div class="card-header">
      <h4>Asiento Contable <span class="text-muted">/ #1879</span></h4>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between">
          <div class="mb-3">
            <label for="formFile" class="form-label">Fecha</label>
            <input class="form-control" type="date" value="2021-08-19" id="formFile">
          </div>
          <div class="mb-3 col-4">
            <label for="formFile" class="form-label">Serie</label>
            <select class="form-select" aria-label="Default select example">
              <option selected>A, Asiento general</option>
              <option value="1">Boleta</option>
              <option value="2">Factura</option>
              <option value="3">Nota de Crédito</option>
              <option value="4">Nota de Débito</option>
            </select>
          </div>
          <div class="mb-3 col-1">
            <label for="formFile" class="form-label">Referencia</label>
            <input class="form-control" type="text" value="*A2" id="formFile">
          </div>
          <div class="mb-3 col-2">
            <label for="formFile" class="form-label">Plantilla</label>
            <select class="form-select" aria-label="Default select example">
              <option selected>Sin Plantilla</option>
              <option value="1">Soles</option>
              <option value="2">Dólares</option>
            </select>
          </div>
          <div class="mb-3 col-1">
            <label for="formFile" class="form-label">Moneda</label>
            <select class="form-select" aria-label="Default select example">
              <option selected>UYU</option>
              <option value="1">USD</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label">Tipo de Cambio</label>
            <input class="form-control" type="text" value="N/A" id="formFile" disabled>
          </div>
      </div>
    </div>

    <div class="card-datatable table-responsive">
      <table class="dt-responsive table border-top">
        <thead class="text-center table-dark">
          <tr>
            <th class="font-white">Cuenta contable</th>
            <th class="font-white">IVA</th>
            <th class="font-white">Concepto</th>
            <th class="font-white">Debe</th>
            <th class="font-white">Haber</th>
            <th></th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr>
            <th>6400 - Sueldos y salarios</th>
            <th>Sin IVA</th>
            <th>Nóminas - Sueldos y salarios</th>
            <th>64.878,00</th>
            <th class="bg-gray2"></th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6420 - Banco</th>
            <th>Sin IVA</th>
            <th>Pago a proveedores</th>
            <th class="bg-gray2"></th>
            <th>18.946,00</th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6431 - Proveedores</th>
            <th>Sin IVA</th>
            <th>Compra materia prima</th>
            <th>18.946,00</th>
            <th class="bg-gray2"></th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6420 - Banco</th>
            <th>Sin IVA</th>
            <th>Transferencia bancaria</th>
            <th class="bg-gray2"></th>
            <th>280.000,00</th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6550 - Alquileres</th>
            <th>Sin IVA</th>
            <th>Pago de alquiler</th>
            <th>20.000,00</th>
            <th class="bg-gray2"></th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6420 - Banco</th>
            <th>Sin IVA</th>
            <th>Pago de alquiler</th>
            <th class="bg-gray2"></th>
            <th>20.000,00</th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6280 - Servicios profesionales</th>
            <th>22% IVA</th>
            <th>Consultoría externa</th>
            <th>12.100,00</th>
            <th class="bg-gray2"></th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>4720 - H.P. IVA Soportado</th>
            <th>22% IVA</th>
            <th>IVA por consultoría externa</th>
            <th>2.541,00</th>
            <th class="bg-gray2"></th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
          <tr>
            <th>6420 - Banco</th>
            <th>Sin IVA</th>
            <th>Pago por consultoría</th>
            <th class="bg-gray2"></th>
            <th>14.641,00</th>
            <th><i class="bx bx-trash"></i></th>
          </tr>
        </tbody>
        <tfoot class="text-center">
          <tr>
            <th class="bg-gray2"></th>
            <th class="bg-gray2"></th>
            <th class="bg-gray2"></th>
            <th class="font-white table-dark">118.465,00</th>
            <th class="font-white table-dark">333.587,00</th>
            <th><i class="bx bx-export"></i></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="d-flex justify-content-end m-2">
      <h6>Volver</h6>
    </div>
  </div>
</div>

@endsection
