<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\{
    DashboardController,
    AccountingController,
    CartController,
    CheckoutController,
    ClientController,
    CrmController,
    EcommerceController,
    InvoiceController,
    OmnichannelController,
    OrderController,
    ProductCategoryController,
    ProductController,
    RawMaterialController,
    RoleController,
    StoreController,
    SupplierController,
    SupplierOrderController,
    WhatsAppController,
    CouponController,
    CompanySettingsController,
    DatacenterController,
    MercadoPagoController,
};

// Middleware de autenticación y verificación de email
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Data Tables
    Route::get('/clients/datatable', [ClientController::class, 'datatable'])->name('clients.datatable');
    Route::get('/products/datatable', [ProductController::class, 'datatable'])->name('products.datatable');
    Route::get('/product-categories/datatable', [ProductCategoryController::class, 'datatable'])->name('product-categories.datatable');
    Route::get('/orders/datatable', [OrderController::class, 'datatable'])->name('orders.datatable');
    Route::get('/orders/{order}/datatable', [OrderController::class, 'orderProductsDatatable'])->name('order-products.datatable');
    Route::get('/marketing/coupons/datatable', [CouponController::class, 'datatable'])->name('coupons.datatable');
    Route::get('/products/flavors/datatable', [ProductController::class, 'flavorsDatatable'])->name('products.flavors.datatable');

    // Recursos con acceso autenticado
    Route::resources([
        'stores' => StoreController::class,
        'roles' => RoleController::class,
        'raw-materials' => RawMaterialController::class,
        'suppliers' => SupplierController::class,
        'supplier-orders' => SupplierOrderController::class,
        'clients' => ClientController::class,
        'products' => ProductController::class,
        'product-categories' => ProductCategoryController::class,
        'orders' => OrderController::class,
        'invoices' => InvoiceController::class,
        'marketing/coupons' => CouponController::class,
        'company-settings' => CompanySettingsController::class,
        'clients' => ClientController::class,
    ]);

    // Datacenter
    Route::get('/datacenter-sales', [DatacenterController::class, 'sales'])->name('datacenter.sales');
    Route::get('/api/monthly-income', [DatacenterController::class, 'monthlyIncome']);
    Route::get('/api/sales-by-store', [DatacenterController::class, 'salesByStore']);
    Route::get('/sales-by-store', [DatacenterController::class, 'showSalesByStore'])->name('sales.by.store');

    // Gestión de Productos
    Route::get('products/{id}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    Route::post('products/{id}/switchStatus', [ProductController::class, 'switchStatus'])->name('products.switchStatus');

    // Gestión de Tiendas
    Route::prefix('stores/{store}')->name('stores.')->group(function () {
        Route::get('manage-users', [StoreController::class, 'manageUsers'])->name('manageUsers');
        Route::get('manage-hours', [StoreController::class, 'manageHours'])->name('manageHours');
        Route::post('associate-user', [StoreController::class, 'associateUser'])->name('associateUser');
        Route::post('disassociate-user', [StoreController::class, 'disassociateUser'])->name('disassociateUser');
        Route::post('save-hours', [StoreController::class, 'saveHours'])->name('saveHours');
        Route::post('toggle-store-status', [StoreController::class, 'toggleStoreStatus'])->name('toggle-status');
    });

    // Gestión de Roles
    Route::prefix('roles/{role}')->name('roles.')->group(function () {
        Route::get('manage-users', [RoleController::class, 'manageUsers'])->name('manageUsers');
        Route::post('associate-user', [RoleController::class, 'associateUser'])->name('associateUser');
        Route::post('disassociate-user', [RoleController::class, 'disassociateUser'])->name('disassociateUser');
        Route::get('manage-permissions', [RoleController::class, 'managePermissions'])->name('managePermissions');
        Route::post('assign-permissions', [RoleController::class, 'assignPermissions'])->name('assignPermissions');
    });

    // Gestión de Sabores de Productos
    Route::get('product-flavors', [ProductController::class, 'flavors'])->name('product-flavors');
    Route::post('product-flavors', [ProductController::class, 'storeFlavors'])->name('product-flavors.store');
    Route::post('/product-flavors/multiple', [ProductController::class, 'storeMultipleFlavors'])->name('product-flavors.store-multiple');
    Route::delete('product-flavors/{id}/delete', [ProductController::class, 'destroyFlavor'])->name('product-flavors.destroy');
    Route::put('flavors/{id}/switch-status', [ProductController::class, 'switchFlavorStatus'])->name('flavors.switch-status');

    // CRM y Contabilidad
    Route::get('crm', [CrmController::class, 'index'])->name('crm');
    Route::get('receipts', [AccountingController::class, 'receipts'])->name('receipts');
    Route::get('entries', [AccountingController::class, 'entries'])->name('entries');
    Route::get('entrie', [AccountingController::class, 'entrie'])->name('entrie');

    // Ajustes de Comercio Electrónico
    Route::get('/ecommerce/marketing', [EcommerceController::class, 'marketing'])->name('marketing');
    Route::get('/ecommerce/settings', [EcommerceController::class, 'settings'])->name('settings');

    // Detalles de Ordenes
    Route::get('/orders/{order}/show', [OrderController::class, 'show'])->name('orders.show');

    // Gestión de Cupones
    Route::post('marketing/coupons/delete-selected', [CouponController::class, 'deleteSelected'])->name('coupons.deleteSelected');
    Route::get('coupons/{id}', [CouponController::class, 'show'])->name('coupons.show');

    // Edición de Sabores
    Route::get('/flavors/{id}', [ProductController::class, 'editFlavor'])->name('flavors.edit');
    Route::put('/flavors/{id}', [ProductController::class, 'updateFlavor'])->name('flavors.update');

    // Órdenes a Proveedores
    Route::group(['prefix' => 'supplier-orders'], function () {
        Route::get('/{id}/pdf', [SupplierOrderController::class, 'generatePdf'])->name('supplier-orders.generatePdf');
    });

    // Omnicanalidad
    Route::group(['prefix' => 'omnichannel'], function () {
        // Configuración de WhatsApp
        Route::post('/update-meta-business-id', [OmnichannelController::class, 'updateMetaBusinessId'])->name('omnichannel.update.meta.business.id');
        Route::post('/update-admin-token', [OmnichannelController::class, 'updateMetaAdminToken'])->name('omnichannel.update.admin.token');

        // Asociar / Desasociar números de teléfono
        Route::post('/associate-phone', [OmnichannelController::class, 'associatePhoneNumberToStore'])->name('omnichannel.associate.phone');
        Route::post('/disassociate/{phone_id}', [OmnichannelController::class, 'disassociatePhoneNumberFromStore'])->name('omnichannel.disassociate');

        // Configuración
        Route::get('/settings', [OmnichannelController::class, 'settings'])->name('omnichannel.settings');

        // Chat
        Route::get('/', [OmnichannelController::class, 'chats'])->name('omnichannel.chat');
        Route::get('/fetch-messages', [WhatsAppController::class, 'fetchMessages'])->name('omnichannel.fetch.messages');
    });
});

// Recursos con acceso público
Route::resources([
    'checkout' => CheckoutController::class,
]);

// E-Commerce
Route::get('/', [EcommerceController::class, 'index'])->name('shop'); // Seleccionar Tienda
Route::get('store/{slug}', [EcommerceController::class, 'store'])->name('store'); // Tienda
Route::post('/cart/select-store', [CartController::class, 'selectStore'])->name('cart.selectStore'); // Seleccionar Tienda en el Carrito
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add'); // Agregar al Carrito
Route::post('/cart/remove-item', [CartController::class, 'removeItem'])->name('cart.removeItem'); // Eliminar del Carrito
Route::get('/session/clear', [CartController::class, 'clearSession'])->name('session.clear'); // Limpiar Sesión
Route::get('/checkout/{orderId}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment'); // Pago de Orden
Route::get('/checkout/success/{order:uuid}', [CheckoutController::class, 'success'])->name('checkout.success'); // Pago Exitoso
Route::get('/checkout/pending/{order:uuid}', [CheckoutController::class, 'pending'])->name('checkout.pending'); // Pago Pendiente
Route::get('/checkout/failure/{order:uuid}', [CheckoutController::class, 'failure'])->name('checkout.failure'); // Pago Fallido
Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('apply.coupon'); // Aplicar Cupón

// MercadoPago WebHooks
Route::post('/mpagohook', [MercadoPagoController::class, 'webhooks'])->name('mpagohook');

// Cambio de idioma
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
