<?php

namespace App\Repositories;

use App\Models\Store;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatacenterRepository
{
    // Método para obtener el rango de fechas
    public function getDateRange($period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today();
        $start = $startDate ? Carbon::parse($startDate) : $today;
        $end = $endDate ? Carbon::parse($endDate) : $today;

        switch ($period) {
            case 'today':
                return [$today, $today->copy()->endOfDay()];
            case 'week':
                // Asegúrate de que el primer día de la semana sea lunes u otro día según tus preferencias.
                $weekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
                $weekEnd = $today->copy()->endOfWeek(Carbon::SUNDAY);
                return [$weekStart, $weekEnd];
            case 'month':
                $monthStart = $today->copy()->startOfMonth();
                $monthEnd = $today->copy()->endOfMonth();
                return [$monthStart, $monthEnd];
            case 'year':
                $yearStart = $today->copy()->startOfYear();
                $yearEnd = $today->copy()->endOfYear();
                return [$yearStart, $yearEnd];
            case 'custom':
                // Asegura que se devuelvan las fechas proporcionadas para el filtro personalizado.
                return [$start->startOfDay(), $end->endOfDay()];
            default:
                // Por defecto, usar el rango del año actual.
                return [$today->copy()->startOfYear(), $today->copy()->endOfYear()];
        }
    }

    // Contar la cantidad de locales (sin filtro de fecha)
    public function countStores()
    {
        return Store::count();
    }

    // Contar la cantidad de clientes con filtro
    public function countClients($startDate, $endDate)
    {
        return Client::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    // Contar la cantidad de productos con filtro
    public function countProducts($startDate, $endDate)
    {
        return Product::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    // Contar la cantidad de categorías (sin filtro de fecha)
    public function countCategories()
    {
        return ProductCategory::count();
    }

    // Contar la cantidad de órdenes entregadas con filtro
    public function countOrders($startDate, $endDate)
    {
        $deliveredOrders = Order::whereBetween('date', [$startDate, $endDate])->where('shipping_status', 'delivered')->count();
        $shippedOrders = Order::whereBetween('date', [$startDate, $endDate])->where('shipping_status', 'shipped')->count();
        $pendingOrders = Order::whereBetween('date', [$startDate, $endDate])->where('shipping_status', 'pending')->count();
        $cancelledOrders = Order::whereBetween('date', [$startDate, $endDate])->where('shipping_status', 'cancelled')->count();

        return [
            'delivered' => $deliveredOrders,
            'shipped' => $shippedOrders,
            'pending' => $pendingOrders,
            'cancelled' => $cancelledOrders
        ];
    }

    // Ingresos E-Commerce con filtro
    public function ecommerceIncomes($startDate, $endDate)
    {
        $totalPaidOrders = Order::whereBetween('date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->where('origin', 'ecommerce')
            ->sum('total');

        return number_format($totalPaidOrders, 0, ',', '.');
    }

    // Ingresos físicos con filtro
    public function physicalIncomes($startDate, $endDate)
    {
        $totalPaidOrders = Order::whereBetween('date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->where('origin', 'physical')
            ->sum('total');

        return number_format($totalPaidOrders, 0, ',', '.');
    }

    // Ingresos totales con filtro
    public function totalIncomes($startDate, $endDate)
    {
        $totalPaidOrders = Order::whereBetween('date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('total');

        return number_format($totalPaidOrders, 0, ',', '.');
    }

    // Media mensual (sin filtro ya que es un cálculo histórico)
    public function averageMonthlySales()
    {
        $monthlySales = Order::select(DB::raw('SUM(total) as total'), DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'))
            ->where('payment_status', 'paid')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        if ($monthlySales->isEmpty()) {
            return 0;
        }

        $totalSales = $monthlySales->sum('total');
        $countMonths = $monthlySales->count();
        $averageMonthlySales = $totalSales / $countMonths;

        return number_format($averageMonthlySales, 0, ',', '.');
    }

    // Ticket medio con filtro
    public function averageTicket($startDate, $endDate)
    {
        $totalPaidOrders = Order::whereBetween('date', [$startDate, $endDate])->where('payment_status', 'paid')->sum('total');
        $totalPaidOrdersCount = Order::whereBetween('date', [$startDate, $endDate])->where('payment_status', 'paid')->count();

        return number_format($totalPaidOrders / $totalPaidOrdersCount, 0, ',', '.');
    }

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

    // Porcentaje de ventas por local para tabla con filtro
    public function getSalesPercentByStore($startDate, $endDate)
    {
        $totalPaidOrders = Order::whereBetween('date', [$startDate, $endDate])->where('payment_status', 'paid')->sum('total');
        $stores = Store::with(['orders' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])->where('payment_status', 'paid');
        }])->get();

        $data = [];
        foreach ($stores as $store) {
            $storeTotal = $store->orders->sum('total');
            $percent = $totalPaidOrders > 0 ? ($storeTotal / $totalPaidOrders) * 100 : 0;
            $data[] = [
                'store' => $store->name,
                'percent' => round($percent, 2),
                'storeTotal' => number_format($storeTotal, 0, ',', '.'),
            ];
        }

        usort($data, function ($a, $b) {
            return $b['storeTotal'] <=> $a['storeTotal'];
        });

        return $data;
    }

    // Porcentaje de ventas por producto para tabla con filtro
    public function getSalesPercentByProduct($startDate, $endDate)
    {
        $orders = Order::whereBetween('date', [$startDate, $endDate])->where('payment_status', 'paid')->get();

        $productSales = [];

        foreach ($orders as $order) {
            $products = json_decode($order->products, true);

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

        $totalSales = array_sum(array_map(function ($product) {
            return $product['total'];
        }, $productSales));

        $data = [];
        foreach ($productSales as $name => $info) {
            $percent = $totalSales > 0 ? ($info['total'] / $totalSales) * 100 : 0;
            $data[] = [
                'product' => $name,
                'percent' => round($percent, 2),
                'productTotal' => $info['total'],
            ];
        }

        usort($data, function ($a, $b) {
            return $b['productTotal'] <=> $a['productTotal'];
        });

        return $data;
    }
}
