<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Flavor;
use Illuminate\Database\Eloquent\Collection;

class EcommerceRepository
{
  /**
   * Obtiene todas las tiendas.
   *
   * @return Collection
  */
  public function getAllStores(): Collection
  {
    return Store::all();
  }

  /**
   * Obtiene los datos necesarios para la página de una tienda específica.
   *
   * @param int $storeId
   * @return array
  */
  public function getStoreData(int $storeId): array
  {
    $store = Store::find($storeId);

    if (!$store) {
        return [
            'status' => 'error',
            'message' => 'La tienda no existe.'
        ];
    }

    $categories = ProductCategory::whereHas('products', function ($query) use ($storeId) {
        $query->where('status', '=', 1)
              ->where('store_id', $storeId)
              ->where('is_trash', '!=', 1);
    })->with(['products' => function ($query) use ($storeId) {
        $query->where('status', '=', 1)
              ->where('store_id', $storeId)
              ->where('is_trash', '!=', 1);
    }])->get();

    $flavors = Flavor::all();

    return [
        'status' => 'success',
        'categories' => $categories,
        'flavors' => $flavors,
        'store' => $store
    ];
  }
}
