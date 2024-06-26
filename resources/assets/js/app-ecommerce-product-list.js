'use strict';

$(function () {
  let borderColor, bodyBg, headingColor;

  let currencySymbol = $('.datatables-products').data('symbol');

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  var dt_product_table = $('.datatables-products');

  if (dt_product_table.length) {
    var dt_products = dt_product_table.DataTable({
      ajax: dt_product_table.data('ajax-url'),
      columns: [
        { data: 'image' },
        { data: 'name' },
        { data: 'sku' },
        {
          data: 'description',
          render: function (data, type, row) {
            var div = document.createElement('div');
            div.innerHTML = data;
            return div.textContent || div.innerText || '';
          }
        },
        { data: 'type' },
        { data: 'old_price' },
        { data: 'price' },
        { data: 'category' },
        { data: 'store_name' },
        { data: 'status' },
        { data: 'stock' },
        { data: '' }
      ],
      columnDefs: [
        {
          targets: -1,
          title: 'Acciones',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a href="' +
              baseUrl +
              'admin/products/' +
              full['id'] +
              '/edit" class="btn btn-sm btn-icon edit-button"><i class="bx bx-edit"></i></a>' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded me-2"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="' +
              baseUrl +
              'admin/products/' +
              full['id'] +
              '/show" class="dropdown-item">Ver producto</a>' +
              '<a href="javascript:void(0);" class="dropdown-item switch-status" data-id="' +
              full['id'] +
              '">' +
              (full['status'] === 1 ? 'Desactivar' : 'Activar') +
              '</a>' +
              '<a href="javascript:void(0);" class="dropdown-item text-danger delete-button" data-id="' +
              full['id'] +
              '">Eliminar</a>' +
              '</div>' +
              '</div>'
            );
          }
        },
        {
          targets: 9,
          searchable: true,
          orderable: true,
          render: function (data, type, full, meta) {
            if (data === 1) {
              return '<span class="badge pill bg-success">Activo</span>';
            } else {
              return '<span class="badge pill bg-danger">Inactivo</span>';
            }
          }
        },
        {
          targets: 0,
          title: 'Imagen',
          render: function (data, type, full, meta) {
            return (
              '<img src="' +
              baseUrl +
              data +
              '" alt="Imagen del producto" style="max-width: 100px; max-height: 100px;">'
            );
          }
        },
        {
          targets: 5,
          render: function (data, type, full, meta) {
            return currencySymbol + parseFloat(data).toFixed(0);
          }
        },
        {
          targets: 6,
          render: function (data, type, full, meta) {
            if (data !== null) {
              return currencySymbol + parseFloat(data).toFixed(0);
            } else {
              return '-';
            }
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            if (data.toLowerCase() === 'configurable') {
              return 'Variable';
            } else {
              return data.charAt(0).toUpperCase() + data.slice(1);
            }
          }
        },
        {
          targets: 10,
          render: function (data, type, full, meta) {
            if (full.stock === 0) {
              return `<span class="badge bg-danger">${full.stock}</span>`;
            } else if (full.stock < 10) {
              return `<span class="badge bg-warning">${full.stock}</span>`;
            } else {
              return `<span class="badge bg-success">${full.stock}</span>`;
            }
          }
        }
      ],
      order: [2, 'asc'],
      dom:
        '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center pt-0"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 25, 50, 100],
      language: {
        search: '',
        searchPlaceholder: 'Buscar...',
        sLengthMenu: '_MENU_',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoFiltered: 'filtrados de _MAX_ productos',
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        },
        pagingType: 'full_numbers',
        emptyTable: 'No hay registros disponibles',
        dom: 'Bfrtip',
        renderer: 'bootstrap'
      },
      buttons: [
        {
          text: '<i class="bx bx-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Añadir producto</span>',
          className: 'add-new btn btn-primary',
          action: function () {
            window.location.href = baseUrl + 'admin/products/create';
          }
        }
      ],
      initComplete: function () {
        this.api()
          .columns(4)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductType" class="form-select"><option value="">Todos los tipos</option></select>'
            )
              .appendTo('.product_type')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
              });
          });

        this.api()
          .columns(7)
          .every(function () {
            var column = this;
            var select = $('<select class="form-select"><option value="">Todas las categorías</option></select>')
              .appendTo('.product_category')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
              });
          });

        this.api()
          .columns(8)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductStore" class="form-select"><option value="">Todos los locales</option></select>'
            )
              .appendTo('.product_store')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '</option>');
              });
          });
      }
    });

    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
    $('.dt-buttons').addClass('d-flex flex-wrap');
    $('.dataTables_length label select').addClass('form-select form-select-sm');
    $('.dataTables_filter label input').addClass('form-control');
  }

  $('.datatables-products tbody').on('click', '.delete-record', function () {
    dt_products.row($(this).parents('tr')).remove().draw();
  });

  $('.toggle-column').on('change', function () {
    var column = dt_products.column($(this).data('column'));
    column.visible(!column.visible());
  });

  dt_product_table.on('click', '.switch-status', function () {
    var button = $(this);
    var productId = button.data('id');
    var newStatus = button.text().trim() === 'Activar' ? 1 : 2;

    $.ajax({
      url: baseUrl + 'admin/products/' + productId + '/switchStatus',
      type: 'POST',
      data: {
        id: productId,
        status: newStatus,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        Swal.fire({
          title: '¡Correcto!',
          text: response.message,
          icon: 'success',
          confirmButtonText: 'OK'
        });
        dt_products.ajax.reload(null, false);
      },
      error: function (xhr, status, error) {
        Swal.fire({
          title: '¡Error!',
          text: xhr.responseText,
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  });

  $(document).on('click', '.delete-button', function () {
    var productId = $(this).data('id');

    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Una vez eliminado, no podrás recuperar este producto.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
          type: 'DELETE',
          url: baseUrl + 'admin/products/' + productId,
          data: {
            _token: csrfToken
          },
          success: function (response) {
            Swal.fire({
              title: '¡Eliminado!',
              text: 'El producto ha sido eliminado correctamente.',
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            });
            dt_products.ajax.reload(null, false);
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
            Swal.fire({
              title: 'Error',
              text: 'Hubo un error al intentar eliminar el producto.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
      }
    });
  });
});
