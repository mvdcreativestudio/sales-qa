@extends('layouts/layoutMaster')

@section('title', 'Agregar Materia Prima')

@section('page-script')
@vite([
  'resources/assets/js/app-raw-material-add.js'
])
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Materias Primas /</span><span> Crear Materia Prima</span>
  </h4>

<div class="app-ecommerce">
    <!-- Formulario para agregar materia prima -->
    <form action="{{ route('raw-materials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                        <input type="text" class="form-control" id="raw-material-name" name="name" required placeholder="Nombre de la materia prima">
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label" for="raw-material-description">Descripción <span class="text-muted">(Opcional)</span></label>
                        <textarea class="form-control" id="raw-material-description" name="description" rows="4" placeholder="Descripción de la materia prima"></textarea>
                    </div>

                    <!-- Unidad de Medida -->
                    <div class="mb-3">
                        <label class="form-label" for="unit_of_measure">Unidad de Medida</label>
                        <select class="form-select" id="unit_of_measure" name="unit_of_measure" required>
                            <option value="">Seleccione una unidad</option>
                            <option value="KG">Kilogramos (KG)</option>
                            <option value="Gramos">Gramos</option>
                            <option value="Litros">Litros</option>
                            <option value="Mililitros">Mililitros (ml)</option>
                            <option value="Unidades">Unidades</option>
                        </select>
                    </div>

                    <div id="unit_example" class="mt-2 mb-2 text-muted" style="display: none;">
                        
                    </div>

                    <!-- Carga de imagen -->
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imageUpload" name="image" accept="image/*">
                        <div class="mt-3">
                        <img id="image-preview" src="#" alt="Vista previa de la imagen" class="img-fluid" style="display: none;"/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Guardar Materia Prima</button>
            </div>
            </div>
        </div>
    </form>
</div>
@endsection