document.addEventListener('DOMContentLoaded', function () {
  const inputImage = document.getElementById('image_url');
  const imagePreview = document.getElementById('image-preview');
  const unitOfMeasureSelect = document.getElementById('unit_of_measure');
  const rawMaterialNameInput = document.getElementById('raw-material-name');
  const exampleText = document.getElementById('example-text');

  // Previsualizar imagen seleccionada
  inputImage.onchange = evt => {
    const [file] = inputImage.files;
    if (file) {
      imagePreview.src = URL.createObjectURL(file);
      imagePreview.style.display = 'block';
    }
  };

  // Actualizar texto de ejemplo al cambiar la unidad de medida
  unitOfMeasureSelect.addEventListener('change', function () {
    const unit = this.options[this.selectedIndex].text;
    const name = rawMaterialNameInput.value || 'nombre de la materia prima';
    exampleText.textContent = `Ejemplo: 10 ${unit} de ${name}`;
  });

  // Actualizar texto de ejemplo al cambiar el nombre de la materia prima
  rawMaterialNameInput.addEventListener('input', function () {
    const unit = unitOfMeasureSelect.options[unitOfMeasureSelect.selectedIndex].text;
    const name = this.value || 'nombre de la materia prima';
    exampleText.textContent = `Ejemplo: 10 ${unit} de ${name}`;
  });
});
