@extends('layouts/layoutMaster')

@section('title', 'Editar Materia Prima')

@section('page-script')
@vite([
  'resources/assets/js/app-raw-material-edit.js'
])
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Materias Primas /</span><span> Editar Materia Prima</span>
</h4>

<div class="app-ecommerce">

  <!-- Formulario para editar materia prima -->
  <form action="{{ route('raw-materials.update', $rawMaterial->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">

      <!-- Columna de información de la materia prima -->
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">Información de la Materia Prima</h5>
          </div>
          <div class="card-body">
            <!-- Nombre -->
            <div class="mb-3">
              <label class="form-label" for="raw-material-name">Nombre</label>
              <input type="text" class="form-control" id="raw-material-name" name="name" required placeholder="Nombre de la materia prima" value="{{ $rawMaterial->name }}">
            </div>

            <!-- Descripción -->
            <div class="mb-3">
              <label class="form-label">Descripción <span class="text-muted">(Opcional)</span></label>
              <textarea id="raw-material-description" class="form-control" name="description">{{ $rawMaterial->description }}</textarea>
            </div>

            <!-- Unidad de Medida -->
            <div class="mb-3">
              <label class="form-label" for="unit_of_measure">Unidad de Medida</label>
              <select class="form-select" id="unit_of_measure" name="unit_of_measure" required>
                <option value="">Seleccione una unidad</option>
                <option value="KG" @if($rawMaterial->unit_of_measure == 'KG') selected @endif>Kilogramos (KG)</option>
                <option value="Gramos" @if($rawMaterial->unit_of_measure == 'Gramos') selected @endif>Gramos (G)</option>
                <option value="Litros" @if($rawMaterial->unit_of_measure == 'Litros') selected @endif>Litros (L)</option>
                <option value="Mililitros" @if($rawMaterial->unit_of_measure == 'Mililitros') selected @endif>Mililitros (ML)</option>
                <option value="Unidades" @if($rawMaterial->unit_of_measure == 'Unidades') selected @endif>Unidades (U)</option>
              </select>
            </div>

            <!-- Carga de imagen actual y nueva -->
            <div class="mb-3">
              <label class="form-label">Imagen Actual</label>
              <div>
                <img src="{{ asset('storage/assets/img/raw_materials/' . $rawMaterial->image_url) }}" alt="Imagen actual" style="width: 100px; height: auto;">
              </div>
              <label class="form-label mt-3">Cambiar Imagen</label>
              <input type="file" class="form-control" id="image_upload" name="image" accept="image/*">
              <div class="mt-3">
                <img id="image-preview" src="#" alt="Vista previa de la imagen" class="img-fluid" style="display: none;"/>
              </div>
            </div>
            @if ($errors->any())
              @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                  {{ $error }}
                </div>
              @endforeach
            @endif
          </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Actualizar Materia Prima</button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection
