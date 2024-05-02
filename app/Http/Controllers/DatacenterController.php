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

    public function sales()
    {
      // Counts
      $storesCount = $this->datacenterRepo->countStores();
      $registredClients = $this->datacenterRepo->countClients();
      $productsCount = $this->datacenterRepo->countProducts();
      $categoriesCount = $this->datacenterRepo->countCategories();
      $ordersCount = $this->datacenterRepo->countOrders();

      // Incomes
      $ecommerceIncomes = $this->datacenterRepo->ecommerceIncomes();
      $physicalIncomes = $this->datacenterRepo->physicalIncomes();
      $totalIncomes = $this->datacenterRepo->totalIncomes();
      $averageMonthlySales = $this->datacenterRepo->averageMonthlySales();

      // Average Ticket
      $averageTicket = $this->datacenterRepo->averageTicket();

      // Tabla de Locales / Productos / Categorías
      $salesByStore = $this->datacenterRepo->getSalesPercentByStore();
      $salesByProduct = $this->datacenterRepo->getSalesPercentByProduct();



      return view('datacenter.datacenter-sales', compact('storesCount', 'registredClients', 'productsCount', 'categoriesCount', 'ordersCount', 'ecommerceIncomes', 'physicalIncomes', 'totalIncomes', 'averageTicket', 'salesByStore', 'averageMonthlySales', 'salesByProduct'));
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
