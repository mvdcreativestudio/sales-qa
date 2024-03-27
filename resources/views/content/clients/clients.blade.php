@extends('layouts/layoutMaster')

@section('title', 'eCommerce Customer All - Apps')

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
@vite('resources/assets/js/custom-js/clients-list.js')
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Chelato /</span> Clientes
</h4>

<!-- customers List Table -->
<div class="card">

  <div class="card-datatable table-responsive">
    <table class="datatables-customers table border-top" data-ajax-url="{{ route('clients.datatable') }}">
      <thead>
        <tr>
          <th class="text-nowrap col-1">Id</th>
          <th class="col-2">Cliente</th>
          <th class="col-1">T/C</th>
          <th class="col-1">RUT / CI</th>
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
          <div class="mb-3">
            <label class="form-label" for="ecommerce-customer-add-name">Nombre*</label>
            <input type="text" class="form-control" id="ecommerce-customer-add-name" placeholder="John Doe" name="name" aria-label="John Doe" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="rut">RUT</label>
            <input type="text" class="form-control" id="rut" placeholder="123456789123" name="rut" aria-label="123456789123" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="rut">CI</label>
            <input type="text" class="form-control" id="ci" placeholder="123456789123" name="ci" aria-label="123456789123" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="ecommerce-customer-add-email">Email*</label>
            <input type="text" id="ecommerce-customer-add-email" class="form-control" placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email" />
          </div>
          <div>
            <label class="form-label" for="ecommerce-customer-add-contact">Teléfono</label>
            <input type="text" id="ecommerce-customer-add-contact" class="form-control phone-mask" placeholder="+(123) 456-7890" aria-label="+(123) 456-7890" name="phone" />
          </div>
        </div>

        <div class="ecommerce-customer-add-shiping mb-3 pt-3">
          <h6 class="mb-3">Información de facturación y envío</h6>
          <div class="mb-3">
            <label class="form-label" for="address">Dirección</label>
            <input type="text" id="ecommerce-customer-add-address" class="form-control" placeholder="45 Roker Terrace" aria-label="45 Roker Terrace" name="address" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="city">Ciudad</label>
            <input type="text" id="ecommerce-customer-add-town" class="form-control" placeholder="New York" aria-label="New York" name="city" />
          </div>
          <div class="row mb-3">
            <div class="col-12 col-sm-6">
              <label class="form-label" for="state">Departamento</label>
              <input type="text" id="ecommerce-customer-add-state" class="form-control" placeholder="Southern tip" aria-label="Southern tip" name="state" />
            </div>
          </div>
          <div>
            <label class="form-label" for="country">País</label>
            <select id="country" class="select2 form-select" name="country">
              <option value="">Select</option>
              <option value="Australia">Australia</option>
              <option value="Bangladesh">Bangladesh</option>
              <option value="Belarus">Belarus</option>
              <option value="Brazil">Brazil</option>
              <option value="Canada">Canada</option>
              <option value="China">China</option>
              <option value="France">France</option>
              <option value="Germany">Germany</option>
              <option value="India">India</option>
              <option value="Indonesia">Indonesia</option>
              <option value="Israel">Israel</option>
              <option value="Italy">Italy</option>
              <option value="Japan">Japan</option>
              <option value="Korea">Korea, Republic of</option>
              <option value="Mexico">Mexico</option>
              <option value="Philippines">Philippines</option>
              <option value="Russia">Russian Federation</option>
              <option value="South Africa">South Africa</option>
              <option value="Thailand">Thailand</option>
              <option value="Turkey">Turkey</option>
              <option value="Ukraine">Ukraine</option>
              <option value="United Arab Emirates">United Arab Emirates</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="United States">United States</option>
            </select>
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
