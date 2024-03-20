@extends('content.e-commerce.front.layouts.ecommerce-layout')

@section('title', 'Store')

@section('content')

<div class="container">

  <!-- Categories -->
  <div class="row mt-4">
    <div class="card card-category col-md-3 col-6">
      <img src="assets\img\ecommerce\Cat-1.png" alt="Helados">
      <div class="category-card-text">
        <h5 class="category-name light">Helados</h5>
      </div>
    </div>
    <div class="card card-category col-md-3 col-6">
      <img src="assets\img\ecommerce\Cat-2.png" alt="Helados">
      <div class="category-card-text">
        <h5 class="category-name light">Milkshakes</h5>
      </div>
    </div>
    <div class="card card-category col-md-3 col-6">
      <img src="assets\img\ecommerce\Cat-3.png" alt="Helados">
      <div class="category-card-text">
        <h5 class="category-name light">Paletas</h5>
      </div>
    </div>
    <div class="card card-category col-md-3 col-6">
      <img src="assets\img\ecommerce\Cat-4.png" alt="Helados">
      <div class="category-card-text">
        <h5 class="category-name light">Tortas</h5>
      </div>
    </div>
  </div>

  <!-- End Categories -->
  <!-- Offers -->
  <div class="title-container mt-5">
    <h2 class="bold">OFERTAS</h2>
  </div>
  <div class="products-container">
    <div class="row">
      <div class="col-md-2 col-6" data-bs-toggle="modal" data-bs-target="#modalCenter">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-1.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Helado 1L</h5>
            <p class="product-price bold">$730</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-2.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Paleta Menta</h5>
            <p class="product-price bold">$180</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-3.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Paleta Franuí</h5>
            <p class="product-price bold">$180</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-4.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Helado 1/2L</h5>
            <p class="product-price bold">$410</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-1.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Helado 1L</h5>
            <p class="product-price bold">$730</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-2.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Paleta Menta</h5>
            <p class="product-price bold">$180</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-1.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Helado 1L</h5>
            <p class="product-price bold">$730</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-2.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Paleta Menta</h5>
            <p class="product-price bold">$180</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-3.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Paleta Franuí</h5>
            <p class="product-price bold">$180</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-4.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Helado 1/2L</h5>
            <p class="product-price bold">$410</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-1.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Helado 1L</h5>
            <p class="product-price bold">$730</p>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="card card-product">
          <img src="assets\img\ecommerce\prod-2.png" alt="Helados">
          <div class="product-card-text">
            <h5 class="product-name light">Paleta Menta</h5>
            <p class="product-price bold">$180</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- End Offers -->
  
  <div class="whatsapp-box">
    <a href="https://wa.me/59899999999" target="_blank">
      <img class="whatsapp-box-img" src="assets\img\ecommerce\whatsapp-icon.png" alt="Whatsapp">
    </a>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="mt-3 cart-box">
      <img class="cart-box-img" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd"src="assets\img\ecommerce\cart-icon.png" alt="Cart">
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
        <div class="offcanvas-header">
          <h5 id="offcanvasEndLabel" class="offcanvas-title">Carrito</h5>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
          <div class="cart-product-list">
            <!-- Producto 1 en el carrito -->
            <div class="card cart-product-item">
                <img src="assets/img/ecommerce/prod-1.png" alt="Helado 1L" class="cart-product-img">
                <div class="product-card-text">
                    <h5 class="product-name">Helado 1L</h5>
                    <div class="cart-product-variants-container text-center justify-content-center">
                      <small class="cart-product-variants">Dulce de leche - Menta granizada - Crema tramontana</small>
                    </div>
                    <p class="product-price">$730</p>
                </div>
            </div>
            <!-- Producto 2 en el carrito -->
            <div class="card cart-product-item">
                <img src="assets/img/ecommerce/prod-2.png" alt="Paleta Menta" class="cart-product-img">
                <div class="product-card-text">
                    <h5 class="product-name">Paleta Menta</h5>
                    <p class="product-price">$180</p>
                </div>
            </div>
            <!-- Producto 3 en el carrito -->
            <div class="card cart-product-item">
                <img src="assets/img/ecommerce/prod-3.png" alt="Paleta Franuí" class="cart-product-img">
                <div class="product-card-text">
                    <h5 class="product-name">Paleta Franuí</h5>
                    <p class="product-price">$180</p>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas-footer offcanvas-cart-footer">
      <div class="cart-total-price">
        <h6>Subtotal: $1090</h6>
        <h6>Envío: $90</h6>
        <h5 class="bold">Total: $1180</h5>
      </div>
      <button type="button" class="btn btn-label-secondary d-grid offcanvas-cart-button" data-bs-dismiss="offcanvas">Continuar comprando</button>
      <button type="button" class="btn btn-primary mb-2 d-grid offcanvas-cart-button">Finalizar compra</button>
    </div>
  </div>

</div>

</div>

</div>


<!-- Add to cart modal -->

<div class="col-lg-4 col-md-6">
  <div class="mt-3">
    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Helado 1L</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="add-to-cart-img-container justify-content-center text-center mb-5">
              <img src="assets\img\ecommerce\prod-1.png" alt="Helado 1L" class="add-to-cart-img">
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="mb-3">
                  <label for="roleEx" class="form-label">Sabor 1</label>
                  <select class="form-select" tabindex="0" id="roleEx">
                    <option value="">Dulce de leche</option>
                    <option value="">Menta granizada</option>
                    <option value="">Crema tramontana</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label for="roleEx" class="form-label">Sabor 2</label>
                  <select class="form-select" tabindex="0" id="roleEx">
                    <option value="">Dulce de leche</option>
                    <option value="">Menta granizada</option>
                    <option value="">Crema tramontana</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Añadir al carrito</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
