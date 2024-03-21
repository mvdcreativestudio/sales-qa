document.addEventListener('DOMContentLoaded', function () {
  $('#image_upload').on('change', function () {
    if (this.files && this.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#image-preview').attr('src', e.target.result).css('display', 'block');
      };
      reader.readAsDataURL(this.files[0]);
    }
  });

  $('#unit_of_measure').on('change', function () {
    var selectedUnit = $(this).find('option:selected').text();
    var rawMaterialName = $('#raw-material-name').val() || 'la materia prima';
    if (selectedUnit) {
      $('#unit_example')
        .html(`Ejemplo: 10 ${selectedUnit.toLowerCase()} de ${rawMaterialName}`)
        .css('display', 'block');
    } else {
      $('#unit_example').css('display', 'none');
    }
  });

  $('#raw-material-name').on('input', function () {
    if ($('#unit_of_measure').val()) {
      $('#unit_of_measure').change();
    }
  });
});
