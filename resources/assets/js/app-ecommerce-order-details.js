$(function () {
  // Variable declaration for table
  var dt_details_table = $('.datatables-order-details');
  var products = window.orderProducts;

  // E-commerce Products datatable
  if (dt_details_table.length) {
    var dt_products = dt_details_table.DataTable({
      data: products,
      columns: [
        {
          // Image
          data: 'image',
          render: function(data, type, full, meta) {
            var imagePath = '/chelatoapp/public/' + data;
            return '<img src="' + imagePath + '" style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px;" />';
          }
        },
        {
          // Nombre del producto con sabores
          data: 'name',
          render: function(data, type, row, meta) {
              var flavors = row.flavors ? '<br><small>' + row.flavors + '</small>' : '';
              return '<span>' + data + flavors + '</span>';
          }
        },
        { data: 'price' },
        { data: 'quantity' },
        {
          data: null,
          render: function (data, type, row, meta) {
            return '$' + (row.price * row.quantity);
          }
        }
      ],
      columnDefs: [
        {
          // Price
          targets: 2,
          render: function (data, type, full, meta) {
            return '$' + data;
          }
        },
        {
          // Total Product
          targets: -1,
          render: function (data, type, full, meta) {
            return '$' + (full.price * full.quantity);
          }
        }
      ],
      order: [2, ''],
      dom: 't'
    });
  }
});
