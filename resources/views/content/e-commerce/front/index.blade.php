@extends('content.e-commerce.front.layouts.ecommerce-layout')

@section('title', 'Chelato')

@section('content')


<div class="ecommerce-background">

  <div class="vendors-container container mt-5">
    <div class="row text-center justify-content-center">
      <h4>Selecciona tu local más cercano</h4>
      <div class="d-flex justify-content-center">
        <div class="row gy-3 mt-0 col-12 col-md-8 justify-content-center">
          <div class="col-xl-3 col-md-5 col-sm-6 col-6">
            <div class="form-check custom-option custom-option-icon">
              <label class="form-check-label custom-option-content" for="basicPlanMain1">
                <span class="custom-option-body">
                  <i class="fa-solid fa-store"></i>
                  <span class="custom-option-title"> Tres Cruces </span>
                  <small> Bv. Gral. Artigas 1881 </small>
                </span>
                <input name="formValidationPlan" class="form-check-input" type="radio" value="" id="basicPlanMain1" checked />
              </label>
            </div>
          </div>
          <div class="col-xl-3 col-md-5 col-sm-6 col-6">
            <div class="form-check custom-option custom-option-icon">
              <label class="form-check-label custom-option-content" for="basicPlanMain2">
                <span class="custom-option-body">
                  <i class="fa-solid fa-store"></i>
                  <span class="custom-option-title"> Nuevo Centro </span>
                  <small>  Av. L.A de Herrera 3365 </small>
                </span>
                <input name="formValidationPlan" class="form-check-input" type="radio" value="" id="basicPlanMain2" />
              </label>
            </div>
          </div>
          <div class="col-xl-3 col-md-5 col-sm-6 col-6">
            <div class="form-check custom-option custom-option-icon">
              <label class="form-check-label custom-option-content" for="basicPlanMain3">
                <span class="custom-option-body">
                  <i class="fa-solid fa-store"></i>
                  <span class="custom-option-title"> Prado </span>
                  <small> Av. Joaquín Suárez 3225 </small>
                </span>
                <input name="formValidationPlan" class="form-check-input" type="radio" value="" id="basicPlanMain3" />
              </label>
            </div>
          </div>
          <div class="col-xl-3 col-md-5 col-sm-6 col-6">
            <div class="form-check custom-option custom-option-icon">
              <label class="form-check-label custom-option-content" for="basicPlanMain3">
                <span class="custom-option-body">
                  <i class="fa-solid fa-store"></i>
                  <span class="custom-option-title"> El Pinar </span>
                  <small> Av. Perez Butler M249 S2 </small>
                </span>
                <input name="formValidationPlan" class="form-check-input" type="radio" value="" id="basicPlanMain3" />
              </label>
            </div>
          </div>
        </div>
      </div>
      <button class="btn btn-primary col-md-3 col-6 mt-5">Continuar</button>
    </div>

  </div>
</div>




@endsection
