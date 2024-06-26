@extends('layouts/layoutMaster')

@section('title', 'Listado de Órdenes a Proveedores')

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
    window.supplierOrderCreateTemplate = "{{ route('supplier-orders.create') }}";
    window.supplierOrderEditTemplate = "{{ route('supplier-orders.edit', ':id') }}";
    window.supplierOrderDeleteTemplate = "{{ route('supplier-orders.destroy', ':id') }}";
    window.csrfToken = "{{ csrf_token() }}";
    var supplierOrders = @json($supplierOrders);
    window.hasViewAllSupplierOrdersPermission = @json(auth()->user()->can('view_all_supplier-orders'));
</script>
@vite(['resources/assets/js/app-supplier-orders-list.js'])
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Órdenes a Proveedores /</span> Listado
</h4>

@if (session('success'))
<div class="alert alert-success mt-3 mb-3">
  {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger mt-3 mb-3">
  {{ session('error') }}
</div>
@endif

@if ($errors->any())
@foreach ($errors->all() as $error)
  <div class="alert alert-danger">
    {{ $error }}
  </div>
@endforeach
@endif

<div class="card mb-4">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
            <div>
              <h6 class="mb-2">Total de Órdenes</h6>
              <h4 class="mb-2">{{ $supplierOrders->count() }}</h4>
              <p class="mb-0"><span class="text-muted me-2">Total</span></p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="bx bx-purchase-tag bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>

        @php
        $statuses = ['pending' => 'Pendiente', 'sending' => 'Enviando', 'completed' => 'Completada'];
        @endphp
        @foreach ($statuses as $status => $label)
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-{{ $loop->iteration + 1 }} {{ $loop->iteration == count($statuses) ? null : 'border-end' }} pb-3 pb-sm-0">
            <div>
              <h6 class="mb-2">Órdenes {{ $label }}</h6>
              <h4 class="mb-2">{{ $supplierOrders->where('shipping_status', $status)->count() }}</h4>
              <p class="mb-0"><span class="text-muted me-2">Total</span></p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-{{ $status == 'completed' ? 'success' : ($status == 'sending' ? 'warning' : 'secondary') }}">
                <i class="bx bx-{{ $status == 'completed' ? 'check' : ($status == 'sending' ? 'package' : 'timer')}} bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title">Órdenes a Proveedores</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="table datatables-supplier-orders border-top">
      <thead>
        <tr>
          <th>ID</th>
          <th>Proveedor</th>
          <th>Fecha de Orden</th>
          <th>Estado de Envío</th>
          <th>Materias Primas</th>
          @if(auth()->user()->can('view_all_raw-materials'))
            <th>Tienda</th>
          @endif
          <th>Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal fade" id="modalRawMaterials" tabindex="-1" aria-labelledby="modalRawMaterialsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRawMaterialsLabel">Materias Primas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalRawMaterialsBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

@endsection
