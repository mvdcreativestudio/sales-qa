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
    window.baseUrlAsset = "{{ asset('storage/assets/img/raw_materials/') }}";
    window.originUrlAsset = "{{ asset('storage/assets/img/') }}";
    window.rawMaterialAdd = "{{ route('raw-materials.create') }}";
    window.rawMaterialEditTemplate = "{{ route('raw-materials.edit', ':id') }}";
    window.rawMaterialDeleteTemplate = "{{ route('raw-materials.destroy', ':id') }}";
    window.csrfToken = "{{ csrf_token() }}";
    var rawMaterials = @if (isset($rawMaterials)) @json($rawMaterials) @else [] @endif;
    window.hasViewAllRawMaterialsPermission = @json(auth()->user()->can('view_all_raw-materials'));
</script>
@vite(['resources/assets/js/app-raw-materials-list.js'])
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Gestión /</span> Listado de Materias Primas
</h4>

<div class="card mb-4">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-4">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
            <div>
              <h6 class="mb-2">Materias Primas</h6>
              <h4 class="mb-2">{{ isset($rawMaterials) ? $rawMaterials->count() : 0 }}</h4>
              <p class="mb-0"><span class="text-muted me-2">Total</span></p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="bx bx-layer bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
            <div>
              <h6 class="mb-2">Unidades de Medida</h6>
              <h4 class="mb-2">{{ isset($quantityByUnitOfMeasure) ? $quantityByUnitOfMeasure->count() : 0 }}</h4>
              <p class="mb-0"><span class="text-muted me-2">Total</span></p>
            </div>
            <div class="avatar me-lg-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="bx bx-list-ol bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
        <div class="col-sm-12 col-lg-4">
          <div class="d-flex justify-content-between align-items-start pb-3 pb-sm-0 card-widget-3">
            <div>
              <h6 class="mb-2">Stock Total</h6>
              <h4 class="mb-2">{{ isset($totalStock) ? $totalStock : 0 }}</h4>
              <p class="mb-0 text-muted">Total en stock</p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="bx bx-bar-chart-alt bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
      </div>
    </div>
  </div>
</div>


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

<div class="card">
  <div class="card-header">
    <h5 class="card-title">Materias Primas</h5>
  </div>
  <div id="dataTableInit"
     data-base-url="{{ asset('storage/assets/img/raw_materials/') }}"
     data-raw-material-add="{{ route('raw-materials.create') }}"
     data-raw-material-edit="{{ route('raw-materials.edit', ':id') }}"
     data-raw-material-delete="{{ route('raw-materials.destroy', ':id') }}"
     style="display:none;"></div>
  <div class="card-datatable table-responsive">
    <table class="table datatables-raw-materials border-top">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Unidad de Medida</th>
          <th>Stock</th>
          @if(auth()->user()->can('view_all_raw-materials'))
            <th>Tienda</th>
          @endif
          <th>Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
