@extends('layouts/layoutMaster')

@section('title', 'Cupones')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-coupons-list.js'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js"></script>

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Marketing /</span> Cupones
</h4>

<div class="row">
  <div class="col-sm-12 col-lg-4 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-check"></i></span>
          </div>
          <h4 class="ms-1 mb-0">42</h4>
        </div>
        <p class="mb-1 fw-medium me-1">Total Cupones</p>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-lg-4 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='bx bx-time'></i></span>
          </div>
          <h4 class="ms-1 mb-0">8</h4>
        </div>
        <p class="mb-1 fw-medium me-1">Cupones Activos</p>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-lg-4 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-error-circle'></i></span>
          </div>
          <h4 class="ms-1 mb-0">34</h4>
        </div>
        <p class="mb-1">Cupones Inactivos</p>
      </div>
    </div>
  </div>
</div>



<div class="card">
  <div class="card pb-3">
    <h5 class="card-header pb-0">
      Cupones
      <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addCouponModal">
        Agregar Cupón
      </button>
      <div class="dropdown d-inline float-end mx-2">
        <button class="btn btn-primary dropdown-toggle d-none" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          Acciones
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="#" id="deleteSelected">Eliminar seleccionados</a></li>
        </ul>
      </div>
    </h5>
  </div>
  <div class="card-datatable table-responsive pt-0">
    @if($coupon->count() > 0)
      <table class="table datatables-coupons">
        <thead>
          <tr>
            <th></th>
            <th>ID</th>
            <th>Código</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Fecha de Creación</th>
            <th>Fecha de Expiración</th>
            <th>Creado por</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <!-- Datos llenados por DataTables -->
        </tbody>
      </table>
    @else
      <div class="text-center py-5">
        <h4>No hay cupones</h4>
        <p class="text-muted">Agrega un nuevo cupón para comenzar</p>
      </div>
    @endif
  </div>
</div>


@include('content.e-commerce.backoffice.marketing.coupons.add-coupon')
@include('content.e-commerce.backoffice.marketing.coupons.edit-coupon')

@endsection

