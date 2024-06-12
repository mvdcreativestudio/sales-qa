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
  }

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
  

  // Editar categoría
  $(document).ready(function () {
    console.log('Edit button clicked');  // Añadir mensaje de consola

    $(document).on('click', '.edit-record', function () {
      var recordId = $(this).data('id');
      console.log('Record ID:', recordId);  // Añadir mensaje de consola


      // Realizar una solicitud AJAX para obtener los datos de la categoría
      $.ajax({
        url: 'product-categories/' + recordId + '/get-selected',
        type: 'GET',
        success: function (data) {
          console.log('Data received:', data);

          // Llenar el formulario con los datos de la categoría
          $('#ecommerce-category-title').val(data.name);
          $('#ecommerce-category-slug').val(data.slug);
          $('#ecommerce-category-description').val(data.description);
          $('#ecommerce-category-parent-category').val(data.parent_id).trigger('change');
          $('#statusSwitch').prop('checked', data.status == 1);

          // Establecer la acción del formulario dinámicamente
          $('#eCommerceCategoryListForm').attr('action', baseUrl + 'admin/product-categories/' + recordId + '/update-selected');

          // Mostrar el modal
          $('#editCategoryModal').modal('show');
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

    $('#updateCategoryBtn').click(function () {
      $('#eCommerceCategoryListForm').submit();
    });
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// Switch de estado de la categoría
document.addEventListener('DOMContentLoaded', function () {
  var statusSwitch = document.getElementById('statusSwitch');

  // Asegura que el valor inicial sea "1" cuando el switch está activado por defecto
  statusSwitch.value = statusSwitch.checked ? '1' : '2';

  statusSwitch.addEventListener('change', function() {
    this.value = this.checked ? '1' : '2';
  });
});
