@extends('layouts/layoutMaster')

@section('title', 'Crear Factura')

@section('vendor-style')
@vite('resources/assets/vendor/libs/flatpickr/flatpickr.scss')
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/app-invoice.scss')
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/offcanvas-send-invoice.js',
  'resources/assets/js/app-invoice-add.js'
])
@endsection

@section('content')
<div class="row invoice-add">
  <!-- Invoice Add-->
  <div class="col-lg-9 col-12 mb-lg-0 mb-4">
    <div class="card invoice-preview-card">
      <div class="card-body">
        <div class="row p-sm-3 p-0">
          <div class="col-md-6 mb-md-0 mb-4">
            <div class="d-flex svg-illustration mb-4 gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
            </div>
            <p class="mb-1">{{$companySettings->name}}</p>
            <p class="mb-1">{{$companySettings->address}},</p>
            <p class="mb-1">{{$companySettings->state}}, {{$companySettings->country}}</p>
            <p class="mb-1">{{$companySettings->email}}</p>
            <p class="mb-1">{{$companySettings->phone}}</p>
          </div>
          <div class="col-md-6">
            <dl class="row mb-2">
              <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                <span class="h4 text-capitalize mb-0 text-nowrap">Factura #</span>
              </dt>
              <dd class="col-sm-6 d-flex justify-content-md-end">
                <div class="w-px-150">
                  <input type="text" class="form-control" disabled placeholder="3905" value="3905" id="invoiceId" />
                </div>
              </dd>
              <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                <span class="fw-normal">Fecha:</span>
              </dt>
              <dd class="col-sm-6 d-flex justify-content-md-end">
                <div class="w-px-150">
                  <input type="text" class="form-control date-picker" placeholder="DD-MM-YYYY" />
                </div>
              </dd>
              <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                <span class="fw-normal">Vencimiento:</span>
              </dt>
              <dd class="col-sm-6 d-flex justify-content-md-end">
                <div class="w-px-150">
                  <input type="text" class="form-control date-picker" placeholder="DD-MM-YYYY" />
                </div>
              </dd>
            </dl>
          </div>
        </div>

        <hr class="my-4 mx-n4" />

        <div class="row p-sm-3 p-0">
          <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-4">
            <h6 class="pb-2">Factura a:</h6>
            <p class="mb-1">Gastón Dotta</p>
            <p class="mb-1">Sumeria Factory SAS</p>
            <p class="mb-1">Dr. Bolivar Baliñas 2635</p>
            <p class="mb-1">+598 96 434 220</p>
            <p class="mb-0">gdotta@mvdstudio.com.uy</p>
          </div>
        </div>

        <hr class="mx-n4" />

        <form class="source-item py-sm-3">
          <div class="mb-3" data-repeater-list="group-a">
            <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
              <div class="d-flex border rounded position-relative pe-0">
                <div class="row w-100 m-0 p-3">
                  <div class="col-md-6 col-12 mb-md-0 mb-3 ps-md-0">
                    <p class="mb-2 repeater-title">Artículo</p>
                    <select class="form-select item-details mb-2">
                      <option selected disabled>Seleccionar artículo</option>
                      <option value="App Design">Articulo 1</option>
                      <option value="App Customization">Articulo 2</option>
                      <option value="ABC Template">Articulo 3</option>
                      <option value="App Development">Articulo 4</option>
                    </select>
                    <textarea class="form-control" rows="2" placeholder="Descripción del artículo"></textarea>
                  </div>
                  <div class="col-md-3 col-12 mb-md-0 mb-3">
                    <p class="mb-2 repeater-title">Precio</p>
                    <input type="text" class="form-control invoice-item-price mb-2" placeholder="$0" min="12" />
                    <div>
                      <span>Descuento:</span>
                      <span class="discount me-2">0%</span>
                    </div>
                    <div>
                      <span>IVA: </span>
                      <span class="tax-1 me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Tax 1">0%</span>
                    </div>
                  </div>
                  <div class="col-md-2 col-12 mb-md-0 mb-3">
                    <p class="mb-2 repeater-title">Cantidad</p>
                    <input type="text" class="form-control invoice-item-qty" placeholder="1" min="1" max="50" />
                  </div>
                  <div class="col-md-1 col-12 pe-0">
                    <p class="mb-2 repeater-title">Precio</p>
                    <p class="mb-0">$24.00</p>
                  </div>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                  <i class="bx bx-x fs-4 text-muted cursor-pointer" data-repeater-delete></i>
                  <div class="dropdown">
                    <i class="bx bx-cog bx-xs text-muted cursor-pointer more-options-dropdown" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    </i>
                    <div class="dropdown-menu dropdown-menu-end w-px-300 p-3" aria-labelledby="dropdownMenuButton">

                      <div class="row g-3">
                        <div class="col-12">
                          <label for="discountInput" class="form-label">Descuento(%)</label>
                          <input type="number" class="form-control" id="discountInput" min="0" max="100" />
                        </div>
                        <div class="col-md-6">
                          <label for="taxInput1" class="form-label">IVA</label>
                          <select name="tax-1-input" id="taxInput1" class="form-select tax-select">
                            <option value="0%" selected>0%</option>
                            <option value="22%">22%</option>
                          </select>
                        </div>
                      </div>
                      <div class="dropdown-divider my-3"></div>
                      <button type="button" class="btn btn-label-primary btn-apply-changes">Aplicar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="button" class="btn btn-primary" data-repeater-create>Agregar Artículo</button>
            </div>
          </div>
        </form>

        <hr class="my-4 mx-n4" />

        <div class="row py-sm-3">
            <div class="col-8">
              <div class="mb-3">
                <label for="note" class="form-label fw-medium">Notas:</label>
                <textarea class="form-control" rows="2" id="note" placeholder=""></textarea>
              </div>
            </div>
          <div class="col-md-4 d-flex justify-content-end">
            <div class="invoice-calculations">
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Subtotal:</span>
                <span class="fw-medium">$00.00</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Descuento:</span>
                <span class="fw-medium">$00.00</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">IVA:</span>
                <span class="fw-medium">$00.00</span>
              </div>
              <hr />
              <div class="d-flex justify-content-between">
                <span class="w-px-100">Total:</span>
                <span class="fw-medium">$00.00</span>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-4" />


      </div>
    </div>
  </div>
  <!-- /Invoice Add-->

  <!-- Invoice Actions -->
  <div class="col-lg-3 col-12 invoice-actions">
    <div class="card mb-4">
      <div class="card-body">
        <button class="btn btn-primary d-grid w-100 mb-3" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="bx bx-paper-plane bx-xs me-1"></i>Confirmar</span>
        </button>
        <a href="{{url('app/invoice/preview')}}" class="btn btn-label-secondary d-grid w-100 mb-3">Vista previa</a>
        <button type="button" class="btn btn-label-secondary d-grid w-100">Salir</button>
      </div>
    </div>
    <div>
      <p class="mb-2">Método de pago</p>
      <select class="form-select mb-4">
        <option value="Bank Account">Transferencia Bancaria</option>
        <option value="Paypal">Paypal</option>
        <option value="Card">Tarjeta Crédito/Débito</option>
        <option value="UPI Transfer">Efectivo</option>
        <option value="check">Cheque</option>
      </select>
      {{-- <div class="d-flex justify-content-between mb-2">
        <label for="payment-terms" class="mb-0">Payment Terms</label>
        <label class="switch switch-primary me-0">
          <input type="checkbox" class="switch-input" id="payment-terms" checked="">
          <span class="switch-toggle-slider">
            <span class="switch-on">
              <i class="bx bx-check"></i>
            </span>
            <span class="switch-off">
              <i class="bx bx-x"></i>
            </span>
          </span>
          <span class="switch-label"></span>
        </label>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <label for="client-notes" class="mb-0">Client Notes</label>
        <label class="switch switch-primary me-0">
          <input type="checkbox" class="switch-input" id="client-notes">
          <span class="switch-toggle-slider">
            <span class="switch-on">
              <i class="bx bx-check"></i>
            </span>
            <span class="switch-off">
              <i class="bx bx-x"></i>
            </span>
          </span>
          <span class="switch-label"></span>
        </label>
      </div>
      <div class="d-flex justify-content-between">
        <label for="payment-stub" class="mb-0">Payment Stub</label>
        <label class="switch switch-primary me-0">
          <input type="checkbox" class="switch-input" id="payment-stub">
          <span class="switch-toggle-slider">
            <span class="switch-on">
              <i class="bx bx-check"></i>
            </span>
            <span class="switch-off">
              <i class="bx bx-x"></i>
            </span>
          </span>
          <span class="switch-label"></span>
        </label>
      </div> --}}
    </div>
  </div>
  <!-- /Invoice Actions -->
</div>

<!-- Offcanvas -->
@include('_partials/_offcanvas/offcanvas-send-invoice')
<!-- /Offcanvas -->
@endsection
