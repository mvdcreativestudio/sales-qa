/**
 * App eCommerce Category List
 */

'use strict';

// Comment editor

const commentEditor = document.querySelector('.comment-editor');

if (commentEditor) {
  new Quill(commentEditor, {
    modules: {
      toolbar: '.comment-toolbar'
    },
    placeholder: 'Ingrese la descripción de la categoría',
    theme: 'snow'
  });
}

// Datatable (jquery)

$(function () {
    var quillEdit = new Quill('#edit_ecommerce-category-description', {
      modules: {
          toolbar: '.comment-toolbar'
      },
      placeholder: 'Ingrese la descripción de la categoría',
      theme: 'snow'
  });

  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for category list table
  var dt_category_list_table = $('.datatables-category-list');

  //select2 for dropdowns in offcanvas

  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') //for dynamic placeholder
      });
    });
  }

  // Customers List Datatable

  if (dt_category_list_table.length) {
    var dt_category = dt_category_list_table.DataTable({
      ajax: 'product-categories/datatable',
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'name' },
        { data: 'description' },
        { data: 'status'},
        { data: '' }
      ],
      columnDefs: [
        {
          // Actions
          targets: -1,
          title: 'Acciones',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded me-2"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:void(0);" class="dropdown-item edit-record" data-id="' + full['id'] +'">Editar</a>'+
              '<a href="javascript:void(0);" class="dropdown-item text-danger delete-button" data-id="' + full['id'] + '">Eliminar</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [1, 'asc'], // Cambiado de [2, 'desc'] a [1, 'asc'] para ordenar por nombre de categoría
      dom:
        '<"card-header d-flex flex-wrap py-0"' +
        '<"me-5 ms-n2 pe-5"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [7, 10, 20, 50, 70, 100], //for length of menu
      language: {
        search: '',
        searchPlaceholder: 'Buscar categoría...',
        sLengthMenu: '_MENU_',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ categorías',
        infoFiltered: "filtrados de _MAX_ categorías",
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        },
      },
      // Button for offcanvas
      buttons: [
        {
          text: '<i class="bx bx-plus me-0 me-sm-1"></i>Crear categoría',
          className: 'add-new btn btn-primary ms-2',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceCategoryList'
          }
        }
      ],
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
    $('.dt-buttons').addClass('d-flex flex-wrap');
    $('.dataTables_length label select').addClass('form-select form-select-sm');
    $('.dataTables_filter label input').addClass('form-control');

    $('.toggle-column').on('change', function() {
      var column = dt_category.column($(this).attr('data-column'));
      column.visible(!column.visible());
  });
  }

const commentEditor = new Quill('.comment-editor', {
    modules: {
        toolbar: '.comment-toolbar'
    },
    placeholder: 'Ingrese la descripción de la categoría',
    theme: 'snow'
});

$('#eCommerceCategoryListForm').submit(function(event) {
    event.preventDefault();
  
    var plainTextContent = commentEditor.getText();

    $('#hidden-description').val(plainTextContent);

    var statusValue = $('#statusSwitch').is(':checked') ? '1' : '0';
    $('input[name="status"]').val(statusValue);

    this.submit();
});



  // Borrar categoría
  $('.datatables-category-list tbody').on('click', '.delete-button', function () { 
    var recordId = $(this).data('id');
    Swal.fire({
      title: '¿Estás seguro?',
      text: "Esta acción no se puede deshacer",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl+'admin/product-categories/' + recordId + '/delete-selected',
          type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (result) {
            if (result.success) {
              Swal.fire(
                'Eliminado!',
                'La categoría ha sido eliminada.',
                'success'
              );
              setTimeout(() => {
                location.reload()
              }, 3000);                        
            } else {
              Swal.fire(
                'Error!',
                'No se pudo eliminar la categoría. Intente de nuevo.',
                'error'
              );
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            Swal.fire(
              'Error!',
              'No se pudo eliminar la categoría: ' + xhr.responseJSON.message,
              'error'
            );
          }
        });
      }
    });
  });


  // Función para cargar datos en el formulario de edición
$(document).on('click', '.edit-record', function () {
  var recordId = $(this).data('id');
  console.log('Record ID:', recordId);

  // Aquí puedes cargar los datos en el formulario de edición, si es necesario
  // Por ejemplo, haciendo una solicitud AJAX para obtener los datos y llenar los campos del formulario
  $.ajax({
      url: 'product-categories/' + recordId + '/get-selected',
      type: 'GET',
      success: function (data) {
          console.log('Data received:', data);
          $('#edit_ecommerce-category-title').val(data.name);
          $('#edit_ecommerce-category-slug').val(data.slug);
          $('#edit_ecommerce-category-parent-category').val(data.parent_id).trigger('change');
          $('#edit-statusSwitch').prop('checked', data.status == 1);
          
          // Set Quill's content
          quillEdit.root.innerHTML = data.description;

          // Actualizar el atributo data-id del botón #editCategoryButton
          $('#editCategoryButton').data('id', recordId);

          // Mostrar el offcanvas para editar
          var editOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasEcommerceCategoryEdit'));
          editOffcanvas.show();
      },
      error: function (xhr) {
          console.log('Error occurred:', xhr);
          Swal.fire({
              icon: 'error',
              title: 'Error al cargar la categoría',
              text: 'No se pudo cargar la categoría. Intente nuevamente.'
          });
      }
  });
});

// POST para editar la categoría en la base de datos
function submitEditProductCategory(recordId) {


  var formData = new FormData();
    
  formData.append('name', $('#edit_ecommerce-category-title').val());
  formData.append('slug', $('#edit_ecommerce-category-slug').val());
  formData.append('description', $('#edit_ecommerce-category-description').text()); 
  formData.append('parent_id', $('#edit_ecommerce-category-parent-category').val());
  formData.append('status', $('#edit-statusSwitch').is(':checked') ? 1 : 0);
  // Agregar la imagen al FormData, si existe
  var imageFile = $('#edit_ecommerce-category-image')[0].files[0];
  if (imageFile) {
      formData.append('image', imageFile);
  }

  formData.append('_token', $('meta[name="csrf-token"]').attr('content'));


$.ajax({
    url: `product-categories/${recordId}/update-selected`,
    method: 'POST',
    contentType: 'application/json', 
    data: formData, 
    contentType: false, 
    processData: false,
    success: function (response) {
          console.log('Categoría actualizada:', response);
          dt_category.ajax.reload(null, false);
          $('#offcanvasEcommerceCategoryEdit').offcanvas('hide');
          Swal.fire({
              icon: 'success',
              title: 'Categoría actualizada',
              text: 'La categoría ha sido actualizada correctamente.'
          }).then((result) => {
              window.location.reload();
          });

      },
      error: function (xhr) {
          console.error('Error al actualizar la categoría:', xhr);
          // Mostrar SweetAlert de error
          $('#offcanvasEcommerceCategoryEdit').offcanvas('hide');
          Swal.fire({
              icon: 'error',
              title: 'Error al actualizar la categoría',
              text: 'No se pudo actualizar la categoría. Intente nuevamente.'
          });
      }
  });
}



// Asociar el clic del botón a la función submitEditProductCategory con el recordId del botón
$(document).on('click', '#editCategoryButton', function () {
  var recordId = $(this).data('id'); // Obtener el recordId del botón
  console.log('recordId:', recordId);
  submitEditProductCategory(recordId); // Llamar a submitEditProductCategory con el recordId
});



  // Guardar el contenido de Quill en un campo oculto antes de enviar el formulario de edición
  $('#editECommerceCategoryListForm').on('submit', function() {
    $('#hidden-edit-description').val(quillEdit.root.innerHTML);
  });
});
  
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);


// Switch de estado de la categoría
document.addEventListener('DOMContentLoaded', function () {
  var statusSwitch = document.getElementById('statusSwitch');

  statusSwitch.value = statusSwitch.checked ? '1' : '0';

    statusSwitch.addEventListener('change', function() {
        this.value = this.checked ? '1' : '0';
    });
});
