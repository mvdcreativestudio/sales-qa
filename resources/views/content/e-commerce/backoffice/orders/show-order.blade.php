@extends('layouts/layoutMaster')

@section('title', 'Detalle de pedido')

@if (request()->is('*/pdf'))
    <!-- Cargar estilos CSS directamente -->
    <link rel="stylesheet" href="{{ public_path('path/to/datatables-bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ public_path('path/to/datatables-responsive-bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ public_path('path/to/datatables-buttons-bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ public_path('path/to/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ public_path('path/to/form-validation.css') }}">
    <link rel="stylesheet" href="{{ public_path('path/to/select2.css') }}">
@else
    @section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/select2/select2.scss'
    ])
    @endsection

    @section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
      'resources/assets/vendor/libs/cleavejs/cleave.js',
      'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/select2/select2.js'
    ])
    @endsection

    @section('page-script')
    @vite([
      'resources/assets/js/app-ecommerce-order-details.js',
      'resources/assets/js/modal-add-new-address.js',
      'resources/assets/js/modal-edit-user.js'
    ])
    <script>
      window.orderProducts = @json($products);
    </script>
    @endsection
@endif

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">E-Commerce /</span> Detalles del pedido
</h4>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 card p-3">
  <div class="d-flex flex-column justify-content-center">
    <h6 class="mb-1 mt-3">Pedido #{{ $order->id }}
      @if($order->payment_status === 'paid')
        <span class="badge bg-label-info me-2 ms-2">Pago</span>
      @elseif($order->payment_status === 'pending')
        <span class="badge bg-label-danger me-2 ms-2">Pago pendiente</span>
      @elseif($order->payment_status === 'failed')
        <span class="badge bg-label-danger me-2 ms-2">Pago fallido</span>
      @endif
      @if($order->shipping_status === 'pending' && $order->shipping_method !== 'pickup')
        <span class="badge bg-label-warning">No enviado</span>
      @elseif($order->shipping_method === 'pickup')
        <span class="badge bg-label-primary">Retira en tienda</span>
      @elseif($order->shipping_status === 'shipped')
        <span class="badge bg-label-success">Enviado</span>
      @elseif($order->shipping_status === 'delivered')
        <span class="badge bg-label-info">Entregado</span>
      @endif
    </h6>
    <h6 class="card-title mb-1">Tienda:
      <span class="mb-1 me-2 ms-2">{{ $order->store->name }}</span>
    </h6>
    <h6 class="card-title mt-1">Método de pago:
      @if($order->payment_method === 'card')
        <span class="badge bg-label-primary me-2 ms-2">MercadoPago</span>
      @elseif($order->payment_method === 'efectivo')
        <span class="me-2 ms-2">Efectivo</span>
      @endif
    </h6>
    <p class="text-body mb-1">{{ date('d/m/Y', strtotime($order->date)) }} - {{ $order->time }}</p>

  </div>
  <div class="d-flex align-content-center flex-wrap gap-2">
    <a href="{{ route('orders.pdf', ['order' => $order->uuid]) }}?action=print" target="_blank" onclick="window.open(this.href, 'print_window', 'left=100,top=100,width=800,height=600').print(); return false;">
        <button class="btn btn-primary">Imprimir</button>
    </a>
    <a href="{{ route('orders.pdf', ['order' => $order->uuid]) }}?action=download" class="btn btn-label-primary">Descargar PDF</a>
    <button class="btn btn-label-danger delete-order">Eliminar</button>
  </div>



</div>

<!-- Order Details Table -->
<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">Detalles del pedido</h5>
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatables-order-details table" data-order-id="{{ $order->id }}">
          <thead>
            <tr>
              <th class="w-25">imagen</th>
              <th class="w-50">productos</th>
              <th class="w-25">precio</th>
              <th class="w-25">cantidad</th>
              <th>total</th>
            </tr>
          </thead>
        </table>
        @if($order->discount !== null && $order->discount !== 0)
          <div class="d-flex justify-content-between align-items-center m-3 mb-2 p-1">
            @if($order->discount !== null && $order->discount !== 0)
              <div class="d-flex align-items-center me-3">
                <span class="text-heading">Cupón utilizado:</span>
                <span class="badge bg-label-dark">{{$order->coupon->code}}</span>
              </div>
            @endif
            <div class="order-calculations">
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Subtotal:</span>
                <span class="text-heading">${{ $order->subtotal }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                @if($order->discount !== null && $order->discount !== 0)
                  <span class="w-px-100">Descuento:</span>
                  @if($order->discount !== null && $order->discount !== 0)
                    <span class="text-heading mb-0">-${{ $order->discount }}</span>
                  @else
                    <span class="text-heading mb-0">$0</span>
                  @endif
                @endif
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Envío:</span>
                <span class="text-heading">${{ $order->shipping }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <h6 class="w-px-100 mb-0">Total:</h6>
                <h6 class="mb-0">${{ $order->total }}</h6>
              </div>
            </div>
          </div>
        @else
          <div class="d-flex justify-content-end align-items-center m-3 mb-2 p-1">
            @if($order->discount !== null && $order->discount !== 0)
              <div class="d-flex align-items-center me-3">
                <span class="text-heading">Cupón utilizado:</span>
                <span class="badge bg-label-dark">{{$order->coupon->code}}</span>
              </div>
            @endif
            <div class="order-calculations">
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Subtotal:</span>
                <span class="text-heading">${{ $order->subtotal }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                @if($order->discount !== null && $order->discount !== 0)
                  <span class="w-px-100">Descuento:</span>
                  @if($order->discount !== null && $order->discount !== 0)
                    <span class="text-heading mb-0">-${{ $order->discount }}</span>
                  @else
                    <span class="text-heading mb-0">$0</span>
                  @endif
                @endif
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Envío:</span>
                <span class="text-heading">${{ $order->shipping }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <h6 class="w-px-100 mb-0">Total:</h6>
                <h6 class="mb-0">${{ $order->total }}</h6>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
    {{-- <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title m-0">Actividad de entrega</h5>
      </div>
      <div class="card-body">
        <ul class="timeline pb-0 mb-0">
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">Order was placed (Order ID: #32543)</h6>
                <span class="text-muted">Tuesday 11:29 AM</span>
              </div>
              <p class="mt-2">Your order has been placed successfully</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">Pick-up</h6>
                <span class="text-muted">Wednesday 11:29 AM</span>
              </div>
              <p class="mt-2">Pick-up scheduled with courier</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">Dispatched</h6>
                <span class="text-muted">Thursday 11:29 AM</span>
              </div>
              <p class="mt-2">Item has been picked up by courier</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">Package arrived</h6>
                <span class="text-muted">Saturday 15:20 AM</span>
              </div>
              <p class="mt-2">Package arrived at an Amazon facility, NY</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-left-dashed">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">Dispatched for delivery</h6>
                <span class="text-muted">Today 14:12 PM</span>
              </div>
              <p class="mt-2">Package has left an Amazon facility, NY</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-transparent pb-0">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-secondary"></span></span>
            <div class="timeline-event pb-0">
              <div class="timeline-header">
                <h6 class="mb-0">Delivery</h6>
              </div>
              <p class="mt-2 mb-0">Package will be delivered by tomorrow</p>
            </div>
          </li>
        </ul>
      </div>
    </div> --}}
  </div>
  <div class="col-12 col-lg-4">
    <div class="card mb-4">
      <div class="card-header">
        <h6 class="card-title m-0">Datos del cliente</h6>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-start align-items-center mb-4">
          <div class="d-flex flex-column">
            <a href="{{url('app/user/view/account')}}" class="text-body text-nowrap">
              <h6 class="mb-0">{{ $order->client->name }} {{$order->client->lastname}}</h6>
            </a>
            <small class="text-muted">ID: #{{ $order->client->id }}</small></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-4">
          <span class="avatar rounded-circle bg-label-success me-2 d-flex align-items-center justify-content-center"><i class="bx bx-cart-alt bx-sm lh-sm"></i></span>
          @if($clientOrdersCount > 1)
            <p class="mb-0">{{ $clientOrdersCount }} Pedidos</p>
          @else
            <p class="mb-0">{{ $clientOrdersCount }} Pedido</p>
          @endif
        </div>
        <div class="d-flex justify-content-between">
          <h6>Información de contacto</h6>
          <h6><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editUser">Editar</a></h6>
        </div>
        <p class=" mb-1">Email: {{ $order->client->email }}</p>
        <p class=" mb-0">Teléfono: {{ $order->client->phone }}</p>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between">
        <h6 class="card-title m-0">Dirección de envío</h6>
        <h6 class="m-0"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Editar</a></h6>
      </div>
      <div class="card-body">
        <p class="mb-0">{{ $order->client->address }}</p>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between">
        <h6 class="card-title m-0">Dirección de facturación</h6>
        <h6 class="m-0"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Editar</a></h6>
      </div>
      <div class="card-body">
        <p class="mb-4">{{ $order->client->address }}</p>
      </div>
    </div>
  </div>
</div>




<!-- Modals -->
@include('_partials/_modals/modal-edit-user')
@include('_partials/_modals/modal-add-new-address')

@endsection
