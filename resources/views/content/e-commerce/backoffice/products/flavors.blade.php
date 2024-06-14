@extends('layouts/layoutMaster')

@section('title', 'Sabores')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-flavors-list.js'
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
  <span class="text-muted fw-light">E-Commerce /</span> Sabores
</h4>

<div class="card">
  <div class="card pb-3">
    <h5 class="card-header pb-0 row text-end">
      <div class="justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFlavorModal">
          Agregar Sabor
        </button>
      </div>
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
    @if($flavor->count() > 0)
      <table class="table datatables-flavors">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <!-- Datos llenados por DataTables -->
        </tbody>
      </table>
    @else
      <div class="text-center py-5">
        <h4>No hay sabores</h4>
        <p class="text-muted">Agrega un nuevo sabor para comenzar</p>
      </div>
    @endif
  </div>
</div>

<style>
  .addRawMaterials {
    box-shadow: none;
    padding: 0px;
    border: 1px solid #e9ecef;
  }

  .addRawMaterials .card-header, .card-body {
    background-color: #f8f9fa;
  }
</style>

<script>
  const rawMaterials = @json($rawMaterials);
</script>



@include('content.e-commerce.backoffice.products.flavors.add-flavor')
@include('content.e-commerce.backoffice.products.flavors.add-multiple-flavors')
@include('content.e-commerce.backoffice.products.flavors.edit-flavor')

@endsection
