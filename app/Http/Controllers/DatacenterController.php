<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Repositories\DatacenterRepository;

class DatacenterController extends Controller
{
    protected $datacenterRepo;

    public function __construct(DatacenterRepository $datacenterRepo)
    {
        $this->datacenterRepo = $datacenterRepo;
    }

    public function sales(Request $request)
    {
        // Filtra según el rango de fechas o periodo predeterminado (año, mes, semana, hoy, siempre)
        $period = $request->input('period', 'year'); // 'year', 'month', 'week', 'today', 'always', o 'custom'
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $storeId = $request->input('store_id'); // Nuevo filtro por local

        // Obtén el rango de fechas basado en el periodo seleccionado
        list($startDate, $endDate) = $this->datacenterRepo->getDateRange($period, $startDate, $endDate);

        // Filtra los datos con las fechas correspondientes y el filtro por local
        $storesCount = $this->datacenterRepo->countStores();
        $registredClients = $this->datacenterRepo->countClients($startDate, $endDate, $storeId);
        $productsCount = $this->datacenterRepo->countProducts($startDate, $endDate, $storeId);
        $categoriesCount = $this->datacenterRepo->countCategories();
        $ordersCount = $this->datacenterRepo->countOrders($startDate, $endDate, $storeId);

        // Ingresos
        $ecommerceIncomes = $this->datacenterRepo->ecommerceIncomes($startDate, $endDate, $storeId);
        $physicalIncomes = $this->datacenterRepo->physicalIncomes($startDate, $endDate, $storeId);
        $totalIncomes = $this->datacenterRepo->totalIncomes($startDate, $endDate, $storeId);
        $averageMonthlySales = $this->datacenterRepo->averageMonthlySales($storeId);

        // Ticket Medio
        $averageTicket = $this->datacenterRepo->averageTicket($startDate, $endDate, $storeId);

        // Tabla de Locales / Productos / Cupones
        $salesByStore = $this->datacenterRepo->getSalesPercentByStore($startDate, $endDate, $storeId);
        $salesByProduct = $this->datacenterRepo->getSalesPercentByProduct($startDate, $endDate, $storeId);
        $salesByCategory = $this->datacenterRepo->getSalesPercentByCategory($startDate, $endDate, $storeId);
        $couponUsage = $this->datacenterRepo->getCouponsUsage($startDate, $endDate, $storeId);

        // Métodos de Pago
        $paymentMethods = $this->datacenterRepo->getPaymentMethodsData($startDate, $endDate, $storeId);

        // Obtener todos los locales para el filtro por local
        $stores = Store::all();

        // Datos para la gráfica de promedio de pedidos por hora
        $averageOrdersByHour = $this->datacenterRepo->getAverageOrdersByHour($startDate, $endDate);

        return view('datacenter.datacenter-sales', compact(
            'storesCount',
            'registredClients',
            'productsCount',
            'categoriesCount',
            'ordersCount',
            'ecommerceIncomes',
            'physicalIncomes',
            'totalIncomes',
            'averageTicket',
            'salesByStore',
            'averageMonthlySales',
            'salesByProduct',
            'salesByCategory',
            'couponUsage',
            'paymentMethods',
            'period',
            'startDate',
            'endDate',
            'storeId', // Pasamos el storeId a la vista
            'stores', // Pasamos los locales a la vista para el filtro
            'averageOrdersByHour' // Pasamos los datos de la gráfica a la vista
        ));
    }

    // Gráfica de línea - GMV Mensual
    public function monthlyIncome(Request $request)
    {
        $currentYear = date('Y');
        $storeId = $request->input('store_id');
        $monthlyIncome = $this->datacenterRepo->getMonthlyIncomeData($storeId);
        return response()->json($monthlyIncome);
    }

    // Gráfica de torta - Ventas por Local en porcentaje
    public function salesByStore(Request $request)
    {
        $storeId = $request->input('store_id');
        $salesByStore = $this->datacenterRepo->getSalesByStoreData($storeId);
        return response()->json($salesByStore);
    }

    // Gráfica de torta - Métodos de Pago
    public function paymentMethodsData(Request $request)
    {
        $period = $request->input('period', 'year'); // 'year', 'month', 'week', 'today', 'always', o 'custom'
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $storeId = $request->input('store_id'); // Nuevo filtro por local

        list($startDate, $endDate) = $this->datacenterRepo->getDateRange($period, $startDate, $endDate);

        $paymentMethods = $this->datacenterRepo->getPaymentMethodsData($startDate, $endDate, $storeId);
        return response()->json($paymentMethods);
    }


}
