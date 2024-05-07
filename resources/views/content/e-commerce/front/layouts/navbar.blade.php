@php
  $stores = App\Models\Store::all();
@endphp

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <img src="{{ asset('assets/img/branding/chelato-white.png') }}" alt="" class="navbar-logo">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        @if(session('store'))
        <li class="nav-item">
          <a class="nav-link nav-link-menu" href="#" data-bs-toggle="modal" data-bs-target="#selectStoreModal">
            <i class="fa-solid fa-location-dot"></i> {{ session('store')['name'] }}
          </a>
        </li>
        @endif
      </ul>
      <ul class="navbar-nav navbar-nav-scroll">
        <li>
          <a class="nav-link nav-link-menu" aria-current="page" href="#">Iniciar Sesión</a>
        </li>
        <li>
          <a href="" class="nav-link nav-link-menu"><i class="fa-regular fa-user"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link nav-link-menu" onclick="confirmChangeStore()"><i class="fa-solid fa-cart-shopping"></i> Cambiar de tienda</a>
        </li>
        <li>
          <a href="" class="nav-link nav-link-menu"><i class="fa-solid fa-bars"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="selectStoreModal" tabindex="-1" aria-labelledby="selectStoreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="selectStoreModalLabel">Cambiar de tienda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-2">
        <div class="alert alert-danger" role="alert">
          Perderás los productos que hayas agregado al carrito.
        </div>
        <form id="changeStoreForm" action="{{ route('cart.selectStore') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="storeId" class="form-label">Seleccionar tienda:</label>
            <select class="form-select" name="storeId" id="storeId">
              @foreach($stores as $store)
              <option value="{{ $store->id }}">{{ $store->name }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Cambiar tienda</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  function confirmChangeStore() {
    $('#selectStoreModal').modal('show');
  }

  // Reinicializar el formulario de cambio de tienda cada vez que se muestre el modal
  $('#selectStoreModal').on('show.bs.modal', function () {
    $('#changeStoreForm')[0].reset(); // Reiniciar el formulario
  });
</script>
