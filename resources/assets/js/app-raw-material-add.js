document.addEventListener('DOMContentLoaded', function () {
  const inputImage = document.getElementById('imageUpload');
  const imagePreview = document.getElementById('image-preview');
  const unitSelect = document.getElementById('unit_of_measure');
  const unitExampleText = document.getElementById('unit_example');
  const rawMaterialNameInput = document.getElementById('raw-material-name');

  inputImage.onchange = evt => {
    const [file] = inputImage.files;
    if (file) {
      imagePreview.src = URL.createObjectURL(file);
      imagePreview.style.display = 'block';
    }
  };

  unitSelect.onchange = () => {
    const selectedUnit = unitSelect.options[unitSelect.selectedIndex].text;
    const rawMaterialName = rawMaterialNameInput.value || 'la materia prima';
    if (selectedUnit) {
      unitExampleText.innerHTML = `Ejemplo: 10 ${selectedUnit.toLowerCase()} de ${rawMaterialName}`;
      unitExampleText.style.display = 'block';
    } else {
      unitExampleText.style.display = 'none';
    }
  };

  // Actualizar el texto de ejemplo cuando cambia el nombre de la materia prima
  rawMaterialNameInput.addEventListener('input', () => {
    if (unitSelect.value) {
      unitSelect.onchange();
    }
  });
});
