@extends('layouts/layoutMaster')

@section('title', 'Pedidos')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js"></script>

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-orders-list.js'
])
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">E-Commerce /</span> Pedidos
</h4>


<!-- Order List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-order table border-top" data-symbol="{{ $settings->currency_symbol }}">
      <thead>
        <tr>
          <th></th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Local</th>
          <th>Importe</th>
          <th>Pago</th>
          <th>Envío</th>
          <th></th>
          <th>Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@endsection
