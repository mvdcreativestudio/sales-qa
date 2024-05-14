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
   * @param string $storeId
   * @return array
  */
  public function getStoreData(string $slug): array
  {
    $store = Store::where('slug', $slug)->first();

    if (!$store) {
        return [
            'status' => 'error',
            'message' => 'La tienda no existe.'
        ];
    }

    $categories = ProductCategory::whereHas('products', function ($query) use ($store) {
        $query->where('status', '=', 1)
              ->where('store_id', $store->id)
              ->where('is_trash', '!=', 1);
    })->with(['products' => function ($query) use ($store) {
        $query->where('status', '=', 1)
              ->where('store_id', $store->id)
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
