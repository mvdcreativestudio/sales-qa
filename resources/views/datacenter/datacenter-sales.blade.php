@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/card-analytics.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js',)
@vite('resources/assets/vendor/libs/chartjs/chartjs.js')
@endsection



@section('page-script')
@vite(['resources/assets/js/datacenter-sales.js'])
@endsection

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

@section('content')

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  {{ session('error') }}
</div>
@endif

<script>
  window.paymentMethodsUrl = '{{ route('datacenter.paymentMethodsData') }}';
</script>

<div class="row sticky-top" style="top: 80px;">
  <!-- Filtros Temporales -->
  <div class="col-12 text-end" data-aos="fade-right">
    <form method="GET" action="{{ route('datacenter.sales') }}">
      <div class="d-inline-flex gap-2">
        <select name="period" class="form-select" id="timePeriodSelector">
          <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hoy</option>
          <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Esta Semana</option>
          <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Este Mes</option>
          <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Este Año</option>
          <option value="always" {{ $period == 'always' ? 'selected' : '' }}>Todo el registro</option>
          <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Personalizado</option>
        </select>

        <!-- Filtro por Local -->
        <select name="store_id" class="form-select">
          <option value="">Todos los Locales</option>
          @foreach ($stores as $store)
            <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
          @endforeach
        </select>

        <!-- Fechas Personalizadas -->
        <input type="date" name="start_date" id="startDate" class="form-control" value="{{ $startDate->format('Y-m-d') }}" {{ $period != 'custom' ? 'disabled' : '' }}>
        <input type="date" name="end_date" id="endDate" class="form-control" value="{{ $endDate->format('Y-m-d') }}" {{ $period != 'custom' ? 'disabled' : '' }}>

        <button type="submit" class="btn btn-primary">Filtrar</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('timePeriodSelector').addEventListener('change', function() {
  var isCustom = this.value === 'custom';
  document.getElementById('startDate').disabled = !isCustom;
  document.getElementById('endDate').disabled = !isCustom;
});
</script>

<script>
  AOS.init();
</script>

<div class="row">
  <!-- single card  -->
  <div class="col-12">
    <div class="card mb-4" data-aos="fade-up">
      <div class="card-widget-separator-wrapper">
        <div class="card-body card-widget-separator">
          <div class="row gy-4 gy-sm-1">
            <div class="col-sm-6 col-lg-3">
              <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                <div>
                  <h3 class="mb-1">{{ $storesCount }}</h3>
                  @if ($storesCount == 1)
                    <p class="mb-0">Local</p>
                  @else
                    <p class="mb-0">Locales</p>
                  @endif
                </div>
                <span class="badge bg-label-secondary rounded p-2 me-sm-4">
                  <i class="bx bx-user bx-sm"></i>
                </span>
              </div>
              <hr class="d-none d-sm-block d-lg-none me-4">
            </div>
            <div class="col-sm-6 col-lg-3">
              <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                <div>
                  <h3 class="mb-1">{{ $registredClients }}</h3>
                  <p class="mb-0">Clientes registrados</p>
                </div>
                <span class="badge bg-label-secondary rounded p-2 me-lg-4">
                  <i class="bx bx-file bx-sm"></i>
                </span>
              </div>
              <hr class="d-none d-sm-block d-lg-none">
            </div>
            <div class="col-sm-6 col-lg-3">
              <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                <div>
                  <h3 class="mb-1">{{ $productsCount }}</h3>
                  @if($productsCount == 1)
                    <p class="mb-0">Producto</p>
                  @else
                    <p class="mb-0">Productos</p>
                  @endif
                </div>
                <span class="badge bg-label-secondary rounded p-2 me-sm-4">
                  <i class="bx bx-check-double bx-sm"></i>
                </span>
              </div>
            </div>
            <div class="col-sm-6 col-lg-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h3 class="mb-1">{{ $categoriesCount }}</h3>
                  @if($categoriesCount == 1)
                    <p class="mb-0">Categoría</p>
                  @else
                    <p class="mb-0">Categorías</p>
                  @endif
                </div>
                <span class="badge bg-label-secondary rounded p-2">
                  <i class="bx bx-error-circle bx-sm"></i>
                </span>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card Border Shadow -->
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card animated-card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-check"></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{ $ordersCount['delivered'] }}</h4>
        </div>
        @if($ordersCount['delivered'] == 1)
          <p class="mb-1 fw-medium me-1">Pedido completado</p>
        @else
          <p class="mb-1 fw-medium me-1">Pedidos completados</p>
        @endif
        <p class="mb-0">
          {{-- <span class="fw-medium me-1 text-success">+18.2%</span> --}}
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card animated-card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-time'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{ $ordersCount['pending'] }}</h4>
        </div>
        @if($ordersCount['pending'] == 1)
          <p class="mb-1 fw-medium me-1">Pedido pendiente</p>
        @else
          <p class="mb-1 fw-medium me-1">Pedidos pendientes</p>
        @endif
        <p class="mb-0">
          {{-- <span class="fw-medium me-1 text-danger">-8.7%</span> --}}
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card animated-card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-error-circle'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{ $ordersCount['cancelled'] }}</h4>
        </div>
        @if($ordersCount['cancelled'] == 1)
          <p class="mb-1 fw-medium me-1">Pedido cancelado</p>
        @else
          <p class="mb-1">Pedidos cancelados</p>
        @endif
        <p class="mb-0">
          {{-- <span class="fw-medium me-1 text-success">+4.3%</span> --}}
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card animated-card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='bx bx-line-chart'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{ $settings->currency_symbol }}{{ $averageTicket }}</h4>
        </div>
        <p class="mb-1">Ticket medio</p>
        <p class="mb-0">
          {{-- <span class="fw-medium me-1 text-danger">-2.5%</span> --}}
        </p>
      </div>
    </div>
  </div>


  <!-- Total Income -->
  <div class="col-12 mb-4">
    <div class="card" data-aos="flip-right">
      <div class="row row-bordered g-0">
        <div class="col-md-8">
          <div class="card-header">
            <h5 class="card-title mb-0">Ingresos totales</h5>
            <small class="card-subtitle">Reporte anual</small>
          </div>
          <div class="card-body">
            <div id="totalIncomeChart"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card-header d-flex justify-content-between">
            <div>
              <h5 class="card-title mb-0">Reporte</h5>
              <small class="card-subtitle">Promedio mensual histórico: {{ $settings->currency_symbol }}{{$averageMonthlySales}}</small>
            </div>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="totalIncome" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalIncome">
                <a class="dropdown-item" href="javascript:void(0);">Última semana</a>
                <a class="dropdown-item" href="javascript:void(0);">Último mes</a>
                <a class="dropdown-item" href="javascript:void(0);">Último año</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="report-list">
              <div class="report-list-item rounded-2 mb-3">
                <div class="d-flex align-items-start">
                  <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-store"></i></span>
                  </div>
                  <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                    <div class="d-flex flex-column">
                      <span>Físico</span>
                      <h5 class="mb-0"> {{ $settings->currency_symbol }}{{ $physicalIncomes }} </h5>
                    </div>
                    {{-- <small class="text-success">+2.34%</small> --}}
                  </div>
                </div>
              </div>
              <div class="report-list-item rounded-2 mb-3">
                <div class="d-flex align-items-start">
                  <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-laptop"></i></span>
                  </div>
                  <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                    <div class="d-flex flex-column">
                      <span>E-Commerce</span>
                      <h5 class="mb-0"> {{ $settings->currency_symbol }}{{ $ecommerceIncomes }} </h5>
                    </div>
                    {{-- <small class="text-danger">-1.15%</small> --}}
                  </div>
                </div>
              </div>
              <div class="report-list-item rounded-2">
                <div class="d-flex align-items-start">
                  <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-shape-square"></i></span>
                  </div>
                  <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                    <div class="d-flex flex-column">
                      <span>Total</span>
                      <h5 class="mb-0">{{ $settings->currency_symbol }}{{ $totalIncomes }}</h5>
                    </div>
                    {{-- <small class="text-success">+1.35%</small> --}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Total Income -->
  </div>
  <!--/ Total Income -->


  <div class="col-md-12 col-12 mb-4 order-2 order-xl-0">
    <div class="card h-100 text-center" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500" data-aos-duration="500">
        <div class="card-header">
            <h5 class="card-title text-start pb-4 mb-0">Comparativas</h5>
            <ul class="nav nav-pills nav- card-header-pills" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-browser" aria-controls="navs-pills-browser" aria-selected="true">Locales</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-os" aria-controls="navs-pills-os" aria-selected="false">Productos</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-coupons" aria-controls="navs-pills-coupons" aria-selected="false">Cupones</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-categories" aria-controls="navs-pills-categories" aria-selected="false">Categorías</button>
                </li>
            </ul>
        </div>
        <div class="tab-content pt-0">
            <div class="tab-pane fade show active" id="navs-pills-browser" role="tabpanel">
                <!-- Tabla de ventas por local -->
                <div class="table-responsive text-start text-nowrap">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Local</th>
                                <th>Ventas</th>
                                <th class="w-50">Porcentaje del total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesByStore as $index => $store)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($store['store'], 0, 2) }}</span>
                                        </div>
                                        <span>{{ $store['store'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $settings->currency_symbol }}{{ $store['storeTotal'] }}</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="progress w-100" style="height:10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $store['percent'] }}%" aria-valuenow="{{ $store['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="fw-medium">{{ number_format($store['percent'], 2) }}%</small>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Productos -->
            <div class="tab-pane fade" id="navs-pills-os" role="tabpanel">
                <!-- Tabla de Ventas por Producto -->
                <div class="table-responsive text-start text-nowrap">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Producto</th>
                                <th>Ventas</th>
                                <th class="w-50">Porcentaje del total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesByProduct as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span>{{ $product['product'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $settings->currency_symbol }}{{ number_format($product['productTotal'], 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="progress w-100" style="height:10px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $product['percent'] }}%" aria-valuenow="{{ $product['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="fw-medium">{{ number_format($product['percent'], 2, ',', '.') }}%</small>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Categorías -->
            <div class="tab-pane fade" id="navs-pills-categories" role="tabpanel">
                <!-- Tabla de Ventas por Categoría -->
                <div class="table-responsive text-start text-nowrap">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Categoría</th>
                                <th>Ventas</th>
                                <th class="w-50">Porcentaje del total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesByCategory as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span>{{ $category['category'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $settings->currency_symbol }}{{ number_format($category['categoryTotal'], 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="progress w-100" style="height:10px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $category['percent'] }}%" aria-valuenow="{{ $category['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="fw-medium">{{ number_format($category['percent'], 2, ',', '.') }}%</small>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Cupones -->
            <div class="tab-pane fade" id="navs-pills-coupons" role="tabpanel">
                <!-- Tabla de uso de Cupones -->
                <div class="table-responsive text-start text-nowrap">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cupón</th>
                                <th>Usos</th>
                                <th class="w-50">Monto Total Descuento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($couponUsage as $index => $coupon)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $coupon['code'] }}</td>
                                <td>{{ $coupon['uses'] }}</td>
                                <td>{{ $settings->currency_symbol }}{{ number_format((float) $coupon['total_discount'], 2, ',', '.') }}</td>
                              </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>

  <!-- Gráfica de promedio de pedidos por hora -->
  <div class="col-12 mb-4 mt-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Promedio de ventas por hora</h5>
      </div>
      <div class="card-body">
        <canvas id="averageOrdersByHourChart"></canvas>
      </div>
    </div>
  </div>

    <!-- Gráfica venta por locales -->
    <div class="col-md-4 col-12 mb-4 mt-4">
      <div class="card" data-aos="zoom-in">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2">Ventas por local</h5>
            </div>
        </div>
        <div class="card-body">
            <div id="deliveryExceptionsChart" style="height: 420px;"></div>
        </div>
      </div>
    </div>
    <!--/ Gráfica venta por locales -->

  <!-- Gráfica métodos de pago -->
  <div class="col-md-4 col-12 mb-4 mt-4">
    <div class="card" data-aos="zoom-in">
      <div class="card-header d-flex align-items-center justify-content-between">
          <div class="card-title mb-0">
              <h5 class="m-0 me-2">Métodos de Pago</h5>
          </div>
      </div>
      <div class="card-body">
          <div id="paymentMethodsChart" style="height: 420px;"></div> <!-- Asegúrate de que tenga dimensiones -->
      </div>
    </div>
  </div>
  <!--/ Gráfica métodos de pago -->

</div>



<script>
  $(document).ready(function() {
      $('.progress-bar').each(function() {
          var bar_value = $(this).attr('aria-valuenow') + '%'
          $(this).animate({ width: bar_value }, { duration: 1000, easing: 'swing' });
      });
  });
  </script>

<script>
  AOS.init();

  // Total Income Chart
  const averageOrdersByHourData = @json($averageOrdersByHour);
</script>
@endsection
