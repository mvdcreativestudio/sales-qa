<!-- Modal Add New Flavor -->
<div class="modal fade" id="addFlavorModal" tabindex="-1" aria-labelledby="addFlavorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFlavorModalLabel">Agregar nuevo sabor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addNewFlavorForm" method="POST" action="{{ route('product-flavors.store') }}"> <!-- Asegúrate de que el formulario esté configurado correctamente -->
          <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required> <!-- Cambiado flavorName por name para que coincida con StoreFlavorRequest -->
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control"> <!-- Asegúrate que el ID y Name sean 'status' -->
              <option value="active" selected>Activo</option>
              <option value="inactive">Inactivo</option>
            </select>
          </div>
          @csrf <!-- Importante para la seguridad de formularios Laravel -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addNewFlavorForm').submit();">Guardar Sabor</button>
      </div>
    </div>
  </div>
</div>
