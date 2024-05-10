<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // Filtra según el rango de fechas o periodo predeterminado (año, mes, semana, hoy)
        $period = $request->input('period', 'year'); // 'year', 'month', 'week', 'today', o 'custom'
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Obtén el rango de fechas basado en el periodo seleccionado
        list($startDate, $endDate) = $this->datacenterRepo->getDateRange($period, $startDate, $endDate);

        // Filtra los datos con las fechas correspondientes
        $storesCount = $this->datacenterRepo->countStores();
        $registredClients = $this->datacenterRepo->countClients($startDate, $endDate);
        $productsCount = $this->datacenterRepo->countProducts($startDate, $endDate);
        $categoriesCount = $this->datacenterRepo->countCategories();
        $ordersCount = $this->datacenterRepo->countOrders($startDate, $endDate);

        // Ingresos
        $ecommerceIncomes = $this->datacenterRepo->ecommerceIncomes($startDate, $endDate);
        $physicalIncomes = $this->datacenterRepo->physicalIncomes($startDate, $endDate);
        $totalIncomes = $this->datacenterRepo->totalIncomes($startDate, $endDate);
        $averageMonthlySales = $this->datacenterRepo->averageMonthlySales($startDate, $endDate);

        // Ticket Medio
        $averageTicket = $this->datacenterRepo->averageTicket($startDate, $endDate);

        // Tabla de Locales / Productos
        $salesByStore = $this->datacenterRepo->getSalesPercentByStore($startDate, $endDate);
        $salesByProduct = $this->datacenterRepo->getSalesPercentByProduct($startDate, $endDate);

        return view('datacenter.datacenter-sales', compact('storesCount', 'registredClients', 'productsCount', 'categoriesCount', 'ordersCount', 'ecommerceIncomes', 'physicalIncomes', 'totalIncomes', 'averageTicket', 'salesByStore', 'averageMonthlySales', 'salesByProduct', 'period', 'startDate', 'endDate'));
    }

    // Gráfica de lineas - GMV Mensual
    public function monthlyIncome()
    {
        $currentYear = date('Y');
        $monthlyIncome = $this->datacenterRepo->getMonthlyIncomeData($currentYear);
        return response()->json($monthlyIncome);
    }

    // Gráfica de torta - Ventas por Local en porcentaje
    public function salesByStore() {
      $salesByStore = $this->datacenterRepo->getSalesByStoreData();
      return response()->json($salesByStore);
    }

}
