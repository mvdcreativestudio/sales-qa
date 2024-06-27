@extends('layouts/layoutMaster')

@section('title', 'Clientes')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/select2/select2.scss'
  ])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/moment/moment.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/vendor/libs/select2/select2.js',
'resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js',
'resources/assets/vendor/libs/cleavejs/cleave.js',
'resources/assets/vendor/libs/cleavejs/cleave-phone.js'
])
@endsection

@section('page-script')
@vite('resources/assets/js/clients-list.js')
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Chelato /</span> Clientes
</h4>

@if(session('success'))
  <div class="alert alert-success d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-success border-label-success p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Correcto!</h6>
      <span>{{ session('success') }}</span>
    </div>
  </div>
@elseif(session('error'))
  <div class="alert alert-danger d-flex" role="alert">
    <span class="badge badge-center rounded-pill bg-danger border-label-danger p-3 me-2"><i class="bx bx-user fs-6"></i></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">¡Error!</h6>
      <span>{{ session('error') }}</span>
  </div>
@endif

<!-- customers List Table -->
<div class="card">
<div class="card-header">
    <h5 class="card-title">Clientes</h5>
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
            <span class="switch-label">Cliente</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="2" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Dirección</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="3" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Ciudad</span>
          </label>
        </div>
        <div class="mx-3">
          <label class="switch switch-square">
            <input type="checkbox" class="toggle-column switch-input" data-column="4" checked>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Departamento</span>
          </label>
        </div>
  </div>
</div>
  <div class="card-datatable table-responsive">
    <table class="datatables-customers table border-top" data-ajax-url="{{ route('clients.datatable') }}">
      <thead>
        <tr>
          <th class="text-nowrap col-1">Id</th>
          <th class="col-2">Cliente</th>
          <th class="col-2">Dirección</th>
          <th class="col-1">Ciudad</th>
          <th class="text-nowrap col-1">Departamento</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new customer -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCustomerAdd" aria-labelledby="offcanvasEcommerceCustomerAddLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasEcommerceCustomerAddLabel" class="offcanvas-title">Crear cliente</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="ecommerce-customer-add pt-0" id="eCommerceCustomerAddForm" method="POST" action="{{ route('clients.store') }}" onsubmit="return false">
        @csrf
        <div class="ecommerce-customer-add-basic mb-3">
          <h6 class="mb-3">Información básica</h6>
          <div class="col-md mb-3">
            <small class="text-light fw-medium d-block">Tipo de cliente</small>
            <div class="form-check form-check-inline mt-1">
                <input class="form-check-input" type="radio" name="type" id="individualType" value="individual" checked />
                <label class="form-check-label" for="individualType">Consumidor Final</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="companyType" value="company" />
                <label class="form-check-label" for="companyType">Empresa</label>
            </div>
        </div>
          <div class="mb-3">
            <label class="form-label" for="ecommerce-customer-add-name">Nombre*</label>
            <input type="text" class="form-control" id="ecommerce-customer-add-name" placeholder="Ingrese el nombre del cliente" name="name" aria-label="John" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="ecommerce-customer-add-lastname">Apellido*</label>
            <input type="text" class="form-control" id="ecommerce-customer-add-lastname" placeholder="Ingrese el apellido del cliente" name="lastname" aria-label="Doe" />
          </div>
          <!-- Campo CI -->
          <div class="mb-3" id="ciField">
            <label class="form-label" for="ci">CI</label>
            <input type="text" class="form-control" id="ci" placeholder="46615326" name="ci" aria-label="46615326" />
          </div>

          <!-- Campo RUT -->
          <div class="mb-3" id="rutField" style="display: none;">
            <label class="form-label" for="rut">RUT</label>
            <input type="text" class="form-control" id="rut" placeholder="123456789123" name="rut" aria-label="123456789123" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="ecommerce-customer-add-email">Email*</label>
            <input type="text" id="ecommerce-customer-add-email" class="form-control" placeholder="mail@empresa.com" aria-label="mail@empresa.com" name="email" />
          </div>
          <div>
            <label class="form-label" for="ecommerce-customer-add-contact">Teléfono</label>
            <input type="text" id="ecommerce-customer-add-contact" class="form-control phone-mask" placeholder="+(598) 123 456" aria-label="+(598) 123 456" name="phone" />
          </div>
        </div>

        <div class="ecommerce-customer-add-shiping mb-3 pt-2">
          <div class="mb-3">
            <label class="form-label" for="address">Dirección</label>
            <input type="text" id="ecommerce-customer-add-address" class="form-control" placeholder="Ingrese la dirección" aria-label="Ingrese la dirección" name="address" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="city">Ciudad</label>
            <input type="text" id="ecommerce-customer-add-town" class="form-control" placeholder="Ingrese la ciudad" aria-label="Ingrese la ciudad" name="city" />
          </div>
          <div class="row mb-3">
            <div class="col-12 col-sm-6">
              <label class="form-label" for="state">Departamento</label>
              <input type="text" id="ecommerce-customer-add-state" class="form-control" placeholder="Montevideo" aria-label="Montevideo" name="state" />
            </div>
          </div>
          <div>
            <label for="country" class="form-label">País</label>
            <select id="country" class="form-select form-select" name="country">
              <option>Seleccionar país</option>
              <option value="Uruguay">Uruguay</option>
              <option value="Argentina">Argentina</option>
              <option value="Paraguay">Paraguay</option>
            </select>
          </div>
          <div class="mb-3 mt-3">
            <label class="form-label" for="website">Sitio Web</label>
            <input type="text" id="ecommerce-customer-add-town" class="form-control" placeholder="Ingrese el sitio web" name="website" />
          </div>



        </div>

        <div class="d-sm-flex mb-3 pt-3">
          <div class="me-auto mb-2 mb-md-0">
            <h6 class="mb-0">Use as a billing address?</h6>
            <small class="text-muted">If you need more info, please check budget.</small>
          </div>
          <label class="switch m-auto pe-2">
            <input type="checkbox" class="switch-input" />
            <span class="switch-toggle-slider">
              <span class="switch-on"></span>
              <span class="switch-off"></span>
            </span>
          </label>
        </div>
        <div class="pt-3">
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Crear cliente</button>
          <button type="reset" class="btn bg-label-danger" data-bs-dismiss="offcanvas">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
