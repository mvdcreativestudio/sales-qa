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

  var dt_flavor_table = $('.datatables-flavors');

  if (dt_flavor_table.length) {
      var dt_flavors = dt_flavor_table.DataTable({
          ajax: 'products/flavors/datatable',
          columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'status'},
            { data: null, defaultContent: '' }
          ],
          columnDefs: [
            {
              // Estado
              targets: 2,
              searchable: true,
              orderable: true,
              render: function (data, type, full, meta) {
                if (data == 'active') {
                  return '<span class="badge pill bg-success">Activo</span>';
                } else {
                  return '<span class="badge pill bg-danger">Inactivo</span>';
                }
              }
            },
            {
              targets: -1,
              title: 'Acciones',
              orderable: false,
              searchable: false,
              render: function (data, type, full, meta) {
                return (
                    '<div class="d-flex justify-content-center align-items-center">' +
                    '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>' +
                    '<div class="dropdown-menu dropdown-menu-end m-0">' +
                    '<a href="javascript:void(0);" class="dropdown-item edit-record" data-id="' + full['id'] + '" data-name="' + full['name'].replace(/"/g, '&quot;') + '">Editar</a>' +
                    '<a href="javascript:void(0);" class="dropdown-item switch-status" data-id="' + full['id'] + '" data-status="' + full['status'] + '"' +
                        ' style="color:' + (full['status'] === 'active' ? 'red' : 'green') + ';">' +
                        (full['status'] === 'active' ? 'Desactivar' : 'Activar') + '</a>' +
                    '<a href="javascript:void(0);" class="dropdown-item delete-record" style="color: red;" data-id="' + full['id'] + '">Eliminar</a>' +
                    '</div>' +
                    '</div>'
                );
            }

            }
          ],

          order: [1, 'asc'],
          dom:
              '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>' +
              '>t' +
              '<"row mx-2"' +
              '<"col-sm-12 col-md-6"i>' +
              '<"col-sm-12 col-md-6"p>' +
              '>',
          lengthMenu: [10, 25, 50, 100],
          language: {
              infoEmpty: 'No hay sabores para mostrar',
              emptyTable: 'No existe ningún sabor',
              search: "",
              searchPlaceholder: 'Buscar...',
              sLengthMenu: '_MENU_',
              info: 'Mostrando _START_ a _END_ de _TOTAL_ sabores',
              infoFiltered: "(filtrados de _MAX_ sabores)",
              paginate: {
                  first: '<<',
                  last: '>>',
                  next: '>',
                  previous: '<'
              },
          },
          renderer: "bootstrap"
      });

      $('.dataTables_length label select').addClass('form-select form-select-sm');
      $('.dataTables_filter label input').addClass('form-control');


      $('.datatables-flavors tbody').on('click', '.delete-record', function () {
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
                      url: 'product-flavors/' + recordId + '/delete',
                      type: 'DELETE',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      success: function (result) {
                          if (result.success) {
                              Swal.fire(
                                  'Eliminado!',
                                  'El sabor ha sido eliminado.',
                                  'success'
                              );
                              dt_flavors.ajax.reload(null, false);
                          } else {
                              Swal.fire(
                                  'Error!',
                                  'No se pudo eliminar el sabor. Intente de nuevo.',
                                  'error'
                              );
                          }
                      },
                      error: function (xhr, ajaxOptions, thrownError) {
                          Swal.fire(
                              'Error!',
                              'No se pudo eliminar el sabor: ' + xhr.responseJSON.message,
                              'error'
                          );
                      }
                  });
              }
          });
      });
  }

  $('#editFlavorModal').on('click', '#updateFlavorBtn', function () {
    var recordId = $(this).data('id');
    var recordName = $(this).data('name');
    submitEditFlavor(recordId); //
  });

  $('.datatables-flavors tbody').on('click', '.edit-record', function () {
    var recordId = $(this).data('id');
    var recordName = $(this).data('name');
    $('#updateFlavorBtn').attr('data-id', recordId);
    $.ajax({
        url: 'flavors/' + recordId,
        type: 'GET',
        success: function (response) {
          $('#flavorName').val(recordName);  // Colocar el nombre del sabor en el input
          $('#editFlavorModal').modal('show');
        },
        error: function (xhr) {
            console.error('Error al obtener los detalles del sabor:', xhr);
            // Manejar el error si es necesario
        }
    });
  });


  function submitEditFlavor(recordId) {
    var formData = {
        'name': $('#editFlavorModal #flavorName').val(),
        '_token': $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: 'flavors/' + recordId,
        type: 'PUT',
        data: formData,
        success: function (response) {
            console.log('Cupón actualizado:', response);
            $('#editFlavorModal').modal('hide');
            dt_flavors.ajax.reload(null, false);

            // Mostrar SweetAlert de éxito
            Swal.fire({
                icon: 'success',
                title: 'Sabor actualizado',
                text: 'El sabor ha sido actualizado correctamente.'
            });
        },
        error: function (xhr) {
            console.error('Error al actualizar el sabor:', xhr);
            // Mostrar SweetAlert de error
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar el sabor',
                text: 'No se pudo actualizar el sabor. Intente nuevamente.'
            });
        }
    });
  }


  $('#addFlavorModal').on('click', '#submitFlavorBtn', function () {
      submitNewFlavor();
  });


  function submitNewFlavor() {
      var route = $(this).data('route');
      var formData = {
          'name': $('#flavorName').val(),
      };

      $.ajax({
        url: route,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            $('#addFlavorModal').modal('hide');
            $('.datatables-flavors').DataTable().ajax.reload();
            Swal.fire({
                icon: 'success',
                title: 'Sabor Agregado',
                text: response.message
            });
        },
        error: function (xhr) {
            var errorMessage = xhr.responseJSON && xhr.responseJSON.errors
                ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                : 'Error desconocido al guardar.';
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                text: errorMessage
            });
        }
      });
  }

  $('.datatables-flavors tbody').on('click', '.switch-status', function () {
    var recordId = $(this).data('id');
    var currentStatus = $(this).data('status');
    var newStatus = currentStatus === 'active' ? 'inactive' : 'active';  // Toggle status

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Estás por cambiar el estado del sabor.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            switchStatus(recordId, newStatus);  // Llamada a la función switchStatus
        }
    });
});

function switchStatus(recordId, newStatus) {
    $.ajax({
        url: `flavors/${recordId}/switch-status`,  // Asegúrate de que la URL está correcta
        method: 'PUT',
        data: {
            'status': newStatus,
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            Swal.fire(
                'Actualizado!',
                'El estado del sabor ha sido actualizado.',
                'success'
            );
            $('.datatables-flavors').DataTable().ajax.reload();  // Recargar datos de la DataTable
        },
        error: function (xhr, ajaxOptions, thrownError) {
            Swal.fire(
                'Error!',
                'No se pudo cambiar el estado: ' + xhr.responseJSON.message,
                'error'
            );
        }
    });
}

});
