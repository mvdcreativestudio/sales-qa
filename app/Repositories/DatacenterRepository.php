<?php

namespace App\Repositories;

use App\Models\Store;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


class DatacenterRepository
{
  // Sales Datacenter

    //Counts

    // Contar la cantidad de locales
    public function countStores()
    {
        return Store::count();
    }

    // Contar la cantidad de clientes
    public function countClients()
    {
        return Client::count();
    }

    // Contar la cantidad de productos
    public function countProducts()
    {
        return Product::count();
    }

    // Contar la cantidad de categorías
    public function countCategories()
    {
        return ProductCategory::count();
    }

    // Contar la cantidad de órdenes entregadas
    public function countOrders()
    {
        $deliveredOrders = Order::where('shipping_status', 'delivered')->count();
        $shippedOrders = Order::where('shipping_status', 'shipped')->count();
        $pendingOrders = Order::where('shipping_status', 'pending')->count();
        $cancelledOrders = Order::where('shipping_status', 'cancelled')->count();

        return [
            'delivered' => $deliveredOrders,
            'shipped' => $shippedOrders,
            'pending' => $pendingOrders,
            'cancelled' => $cancelledOrders
        ];
    }

    // Montos

    // Ingresos E-Commerce
    public function ecommerceIncomes()
    {
        $totalPaidOrders = Order::where('payment_status', 'paid')
        ->where('origin', 'ecommerce')
        ->sum('total');

        return number_format($totalPaidOrders, 0, ',', '.');
    }

    // Ingresos físicos
    public function physicalIncomes()
    {
        $totalPaidOrders = Order::where('payment_status', 'paid')
        ->where('origin', 'physical')
        ->sum('total');

        return number_format($totalPaidOrders, 0, ',', '.');
    }

    // Ingresos totales
    public function totalIncomes()
    {
        $totalPaidOrders = Order::where('payment_status', 'paid')->sum('total');

        return number_format($totalPaidOrders, 0, ',', '.');
    }

    // Media mensual
    public function averageMonthlySales()
    {
    // Selecciona el total de ventas pagadas por mes
    $monthlySales = Order::select(DB::raw('SUM(total) as total'), DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'))
                         ->where('payment_status', 'paid')
                         ->groupBy('year', 'month')
                         ->orderBy('year', 'asc')
                         ->orderBy('month', 'asc')
                         ->get();

    if ($monthlySales->isEmpty()) {
        return 0; // Retorna 0 si no hay ventas
    }

    // Suma todos los totales mensuales
    $totalSales = $monthlySales->sum('total');
    // Cuenta el número de meses con ventas
    $countMonths = $monthlySales->count();

    // Calcula la media mensual de ventas
    $averageMonthlySales = $totalSales / $countMonths;

    return number_format($averageMonthlySales, 0, ',', '.');
  }

    // KPI'S

    // Ticket Medio
    public function averageTicket()
    {
        $totalPaidOrders = Order::where('payment_status', 'paid')->sum('total');
        $totalPaidOrdersCount = Order::where('payment_status', 'paid')->count();

        return number_format($totalPaidOrders / $totalPaidOrdersCount, 0, ',', '.');
    }

    // Gráficas

    // Ventas por mes
    public function getMonthlyIncomeData() {
      return Order::select(DB::raw('SUM(total) as total'), DB::raw('MONTH(date) as month'))
                  ->where('payment_status', 'paid')
                  ->groupBy('month')
                  ->orderBy('month', 'asc')
                  ->get();
    }

    // Ventas por local en porcentaje para gráfica de torta
    public function getSalesByStoreData()
    {
        $stores = Store::all();
        $totalPaidOrders = Order::where('payment_status', 'paid')->sum('total');

        $data = [];

        foreach ($stores as $store) {
            $storeOrders = Order::where('store_id', $store->id)->where('payment_status', 'paid')->sum('total');
            $percent = ($storeOrders / $totalPaidOrders) * 100;

            $data[] = [
                'store' => $store->name,
                'percent' => number_format($percent, 2, ',', '.')
            ];
        }

        return $data;
    }

    // Porcentaje de ventas por local para tabla
    public function getSalesPercentByStore()
    {
        $totalPaidOrders = Order::where('payment_status', 'paid')->sum('total');
        $stores = Store::with(['orders' => function ($query) {
            $query->where('payment_status', 'paid');
        }])->get();

        $data = [];
        foreach ($stores as $store) {
            $storeTotal = $store->orders->sum('total');
            $percent = $totalPaidOrders > 0 ? ($storeTotal / $totalPaidOrders) * 100 : 0; // Calcula el porcentaje
            $data[] = [
                'store' => $store->name,
                'percent' => round($percent, 2), // Redondea el porcentaje a dos decimales
                'storeTotal' => number_format($storeTotal, 0, ',', '.'),
            ];
        }

        // Ordena los datos por el total de ventas de forma descendente
        usort($data, function($a, $b) {
            return $b['storeTotal'] <=> $a['storeTotal'];
        });

        return $data;
    }


    public function getSalesPercentByProduct()
    {
        // Primero, obtiene todas las órdenes pagadas
        $orders = Order::where('payment_status', 'paid')->get();

        // Preparar un array para almacenar el total de ventas por producto
        $productSales = [];

        // Iterar sobre cada orden para procesar los productos
        foreach ($orders as $order) {
            // Decodificar el JSON de los productos
            $products = json_decode($order->products, true);

            // Sumar las ventas de cada producto
            foreach ($products as $product) {
                if (!isset($productSales[$product['name']])) {
                    $productSales[$product['name']] = [
                        'total' => 0,
                        'count' => 0
                    ];
                }
                $productSales[$product['name']]['total'] += $product['price'] * $product['quantity'];
                $productSales[$product['name']]['count'] += $product['quantity'];
            }
        }

        // Calcular el total de ingresos de todas las ventas
        $totalSales = array_sum(array_map(function ($product) {
            return $product['total'];
        }, $productSales));

        // Preparar los datos finales para la respuesta
        $data = [];
        foreach ($productSales as $name => $info) {
            $percent = $totalSales > 0 ? ($info['total'] / $totalSales) * 100 : 0;
            $data[] = [
                'product' => $name,
                'percent' => round($percent, 2),
                'productTotal' => $info['total'],
            ];
        }

        // Ordena los datos por el total de ventas de forma descendente
        usort($data, function($a, $b) {
            return $b['productTotal'] <=> $a['productTotal'];
        });

        return $data;
    }



}
