'use strict';

// Datatable (jquery)
$(document).ready(function() {

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

  // Variable declaration for table
  var dt_customer_table = $('.datatables-customers'),
    select2 = $('.select2'),
    customerView = baseUrl + 'app/ecommerce/customer/details/overview';
  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'United States ',
      dropdownParent: $this.parent()
    });
  }

  // customers datatable
  if (dt_customer_table.length) {
    var dt_customer = dt_customer_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: 'clients/datatable',
      columns: [
        { data: 'id', className: 'col-1' },
        { data: 'name', className: 'col-3' },
        { data: 'address', className: 'col-5' },
        { data: 'city', className: 'col-1' },
        { data: 'state', className: 'col-1' }
      ],

      columnDefs: [
        {
          // Modificación para mostrar nombre completo
          targets: 1,
          render: function(data, type, row) {
            return `${row.name} ${row.lastname}`;
          }
        }
      ],

      order: [[2, 'desc']],
      dom:
        '<"card-header d-flex flex-wrap py-3"' +
        '<"me-5 ms-n2"f>' +
        '<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end gap-3 gap-sm-2 flex-wrap flex-sm-nowrap"lB>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',

      language: {
        search: '',
        searchPlaceholder: 'Buscar...',
        sLengthMenu: '_MENU_',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoFiltered: "filtrados de _MAX_ productos",
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        },
        emptyTable: 'No hay registros disponibles',
        pagingType: "full_numbers",
        dom: 'Bfrtip',
        renderer: "bootstrap"
      },
      // Buttons with Dropdown
      buttons: [
        {
          text: '<i class="bx bx-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Crear cliente</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceCustomerAdd'
          }
        }
      ]
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-2');
    $('.dt-action-buttons').addClass('pt-0');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
    $('.dt-buttons').addClass('d-flex flex-wrap');
    $('.toggle-column').on('change', function() {
      var column = dt_customer.column($(this).attr('data-column'));
      column.visible(!column.visible());
  });
  }

  // Delete Record
  $('.datatables-customers tbody').on('click', '.delete-record', function () {
    dt_customer.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
    $('.dataTables_length label select').addClass('form-select form-select');
    $('.dataTables_filter label input').addClass('form-control');
  }, 300);
});

// Validation & Phone mask
(function () {
  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    eCommerceCustomerAddForm = document.getElementById('eCommerceCustomerAddForm');

  // Phone Number
  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
  // Add New customer Form Validation
  const fv = FormValidation.formValidation(eCommerceCustomerAddForm, {
    fields: {
      customerName: {
        validators: {
          notEmpty: {
            message: 'Please enter fullname '
          }
        }
      },
      customerEmail: {
        validators: {
          notEmpty: {
            message: 'Please enter your email'
          },
          emailAddress: {
            message: 'The value is not a valid email address'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });
})();

// Campo CI o RUT dependiendo si es CF o Empresa
$(document).ready(function() {
  // Escucha cambios en los botones de radio del tipo de cliente
  $('input[type=radio][name=type]').change(function() {
    if (this.value == 'individual') {
      $("#ciField").show();
      $("#rutField").hide();
    } else if (this.value == 'company') {
      $("#ciField").hide();
      $("#rutField").show();
    }
  });
});
