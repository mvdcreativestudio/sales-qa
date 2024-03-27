<div class="row">
  <form action="{{ route('orders.store') }}" method="POST">
    @csrf <!-- Token CSRF para seguridad -->
    <label for="date">Date:</label>
    <input type="date" id="date" name="date"><br><br>

    <label for="origin">Origin:</label>
    <input type="text" id="origin" name="origin"><br><br>

    <label for="client_id">Client ID:</label>
    <input type="number" id="client_id" name="client_id"><br><br>

    <label for="store_id">Store ID:</label>
    <input type="number" id="store_id" name="store_id"><br><br>

    <!-- Ejemplo de cómo capturar productos en formato JSON -->
    <label for="products">Products (JSON Format):</label>
    <textarea id="products" name="products"></textarea><br><br>

    <label for="subtotal">Subtotal:</label>
    <input type="text" id="subtotal" name="subtotal"><br><br>

    <label for="tax">Tax:</label>
    <input type="text" id="tax" name="tax"><br><br>

    <label for="shipping">Shipping:</label>
    <input type="text" id="shipping" name="shipping"><br><br>

    <label for="coupon_id">Coupon ID:</label>
    <input type="number" id="coupon_id" name="coupon_id"><br><br>

    <label for="coupon_amount">Coupon Amount:</label>
    <input type="text" id="coupon_amount" name="coupon_amount"><br><br>

    <label for="discount">Discount:</label>
    <input type="text" id="discount" name="discount"><br><br>

    <label for="total">Total:</label>
    <input type="text" id="total" name="total"><br><br>

    <label for="payment_status">Payment Status:</label>
    <input type="text" id="payment_status" name="payment_status"><br><br>

    <label for="shipping_status">Shipping Status:</label>
    <input type="text" id="shipping_status" name="shipping_status"><br><br>

    <label for="payment_method">Payment Method:</label>
    <input type="text" id="payment_method" name="payment_method"><br><br>

    <label for="shipping_method">Shipping Method:</label>
    <input type="text" id="shipping_method" name="shipping_method"><br><br>

    <label for="shipping_tracking">Shipping Tracking:</label>
    <input type="text" id="shipping_tracking" name="shipping_tracking"><br><br>

    <input type="submit" value="Submit">
</form>
</div>
