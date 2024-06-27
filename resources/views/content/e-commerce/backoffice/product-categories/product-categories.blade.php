@extends('layouts/layoutMaster')

@section('title', 'Categorías')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/vendor/libs/select2/select2.scss',
'resources/assets/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/vendor/libs/quill/typography.scss',
'resources/assets/vendor/libs/quill/katex.scss',
'resources/assets/vendor/libs/quill/editor.scss'
])
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/app-ecommerce.scss')
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/quill/katex.js',
  'resources/assets/vendor/libs/quill/quill.js'
])
@endsection

@section('page-script')
@vite('resources/assets/js/app-ecommerce-category-list.js')
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">E-Commerce /</span> Categorias
</h4>

@php
    $totalCategories = $categories->count();
    $activeCategories = $categories->where('status', 1)->count();
@endphp

<div class="card mb-4">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-6">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
            <div>
              <h6 class="mb-2">Total de Categorías</h6>
              <h4 class="mb-2">{{ $totalCategories }}</h4>
              <p class="mb-0"><span class="text-muted me-2">Total</span></p>
            </div>
            <div class="avatar me-sm-4">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="bx bx-category bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-4">
        </div>
        <div class="col-sm-6 col-lg-6">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
            <div>
              <h6 class="mb-2">Categorías Activas</h6>
              <h4 class="mb-2">{{ $activeCategories }}</h4>
              <p class="mb-0"><span class="text-muted me-2">Activas</span></p>
            </div>
            <div class="avatar me-lg-4">
              <span class="avatar-initial rounded bg-label-success">
                <i class="bx bx-check bx-sm"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="app-ecommerce-category">
  <!-- Category List Table -->
  <div class="card">
  <div class="card-header">
    <h5 class="card-title">Categorías</h5>
    <div class="d-flex">
        <p class="text-muted small">
          <a href="" class="toggle-switches" data-bs-toggle="collapse" data-bs-target="#columnSwitches" aria-expanded="false" aria-controls="columnSwitches">Ver / Ocultar columnas de la tabla</a>
        </p>
      </div>
      <div class="collapse" id="columnSwitches">
      <div class="mt-0 d-flex flex-wrap">
        <div class="mx-0">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="0" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">ID</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="1" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Nombre</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="2" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Descripción</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="3" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Estado</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="4" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Acciones</span>
          </label>
</div>
      </div>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-category-list table border-top">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th class="">Descripción</th>
            <th class="">Estado</th>
            <th class="">Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Offcanvas to add new category -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCategoryList" aria-labelledby="offcanvasEcommerceCategoryListLabel">
    <!-- Offcanvas Header -->
    <div class="offcanvas-header py-4">
      <h5 id="offcanvasEcommerceCategoryListLabel" class="offcanvas-title">Crear categoría</h5>
      <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- Offcanvas Body -->
    <div class="offcanvas-body border-top">
      <form action="{{ route('product-categories.store') }}" method="POST" enctype="multipart/form-data" class="pt-0" id="eCommerceCategoryListForm">
        @csrf
        <!-- Title -->
        <div class="mb-3">
          <label class="form-label" for="ecommerce-category-title">Nombre</label>
          <input type="text" class="form-control" id="ecommerce-category-title" placeholder="Ingrese el nombre de la categoría" name="name" aria-label="category title">
        </div>
        <!-- Slug -->
        <div class="mb-3">
          <label class="form-label" for="ecommerce-category-slug">Slug</label>
          <input type="text" id="ecommerce-category-slug" class="form-control" placeholder="Ingrese el slug" aria-label="slug" name="slug">
        </div>
        <!-- Image -->
        <div class="mb-3">
          <label class="form-label" for="ecommerce-category-image">Imagen</label>
          <input class="form-control" name="image" type="file" id="ecommerce-category-image">
        </div>
        <!-- Parent category -->
        <div class="mb-3 ecommerce-select2-dropdown">
          <label class="form-label" for="ecommerce-category-parent-category">Categoría padre</label>
          <select id="ecommerce-category-parent-category" class="select2 form-select" data-placeholder="Seleccione la categoría padre">
            <option value="">Seleccione la categoría padre</option>
          </select>
        </div>
        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <input type="hidden" name="description" id="hidden-description">
          <div class="form-control p-0 pt-1">
            <div class="comment-editor border-0" id="ecommerce-category-description" contenteditable="true"></div>
            <div class="comment-toolbar border-0 rounded">
              <div class="d-flex justify-content-end">
                <span class="ql-formats me-0">
                  <button class="ql-bold"></button>
                  <button class="ql-italic"></button>
                  <button class="ql-underline"></button>
                  <button class="ql-list" value="ordered"></button>
                  <button class="ql-list" value="bullet"></button>
                  <button class="ql-link"></button>
                  <button class="ql-image"></button>
                </span>
              </div>
            </div>
          </div>
        </div>
        <!-- Hidden status field -->
        <input type="hidden" name="status" value="2">
        <!-- Instock switch -->
        <div class="d-flex justify-content-between align-items-center border-top pt-3">
          <span class="mb-0 h6">Estado</span>
          <div class="w-25 d-flex justify-content-end">
            <label class="switch switch-primary switch-sm me-4 pe-2">
              <input type="checkbox" class="switch-input" value="1" id="statusSwitch" checked name="status">
              <span class="switch-toggle-slider"></span>
            </label>
          </div>
        </div>
        <!-- Submit and reset -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Crear categoría</button>
          <button type="reset" class="btn bg-label-danger" data-bs-dismiss="offcanvas">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

<!-- Offcanvas to edit category -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCategoryEdit" aria-labelledby="offcanvasEcommerceCategoryEditLabel">
  <div class="offcanvas-header py-4">
    <h5 id="offcanvasEcommerceCategoryEditLabel" class="offcanvas-title">Editar categoría</h5>
    <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body border-top">
    <form id="editECommerceCategoryListForm" enctype="multipart/form-data" class="pt-0">
      @csrf
      <div class="mb-3">
        <label class="form-label" for="edit_ecommerce-category-title">Nombre</label>
        <input type="text" class="form-control" id="edit_ecommerce-category-title" placeholder="Ingrese el nombre de la categoría" name="name" aria-label="category title">
      </div>
      <div class="mb-3">
        <label class="form-label" for="edit_ecommerce-category-slug">Slug</label>
        <input type="text" id="edit_ecommerce-category-slug" class="form-control" placeholder="Ingrese el slug" aria-label="slug" name="slug">
      </div>
      <div class="mb-3">
        <label class="form-label" for="edit_ecommerce-category-image">Imagen</label>
        <input class="form-control" name="image" type="file" id="edit_ecommerce-category-image">
      </div>
      <div class="mb-3 ecommerce-select2-dropdown">
        <label class="form-label" for="edit_ecommerce-category-parent-category">Categoría padre</label>
        <select id="edit_ecommerce-category-parent-category" class="select2 form-select" data-placeholder="Seleccione la categoría padre" name="parent_id">
          <option value="">Seleccione la categoría padre</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input type="hidden" name="description" id="edit-hidden-description">
        <div class="form-control p-0 pt-1">
          <div class="comment-editor border-0" id="edit_ecommerce-category-description" contenteditable="true"></div>
          <div class="comment-toolbar border-0 rounded">
              <div class="d-flex justify-content-end">
                <span class="ql-formats me-0">
                  <button class="ql-bold"></button>
                  <button class="ql-italic"></button>
                  <button class="ql-underline"></button>
                  <button class="ql-list" value="ordered"></button>
                  <button class="ql-list" value="bullet"></button>
                  <button class="ql-link"></button>
                  <button class="ql-image"></button>
                </span>
              </div>
            </div>
        </div>
      </div>
      <input type="hidden" name="status" value="2">
      <div class="d-flex justify-content-between align-items-center border-top pt-3">
        <span class="mb-0 h6">Estado</span>
        <div class="w-25 d-flex justify-content-end">
          <label class="switch switch-primary switch-sm me-4 pe-2">
            <input type="checkbox" class="switch-input" value="1" id="edit-statusSwitch" checked name="status">
            <span class="switch-toggle-slider"></span>
          </label>
        </div>
      </div>
      <div class="mb-3">
        <button type="button" id="editCategoryButton" class="btn btn-primary me-sm-3 me-1">Editar categoría</button>
        <button type="reset" class="btn bg-label-danger" data-bs-dismiss="offcanvas">Cancelar</button>
      </div>
    </form>
  </div>
</div>
</div>
@endsection
