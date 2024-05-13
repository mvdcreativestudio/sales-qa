<?php

namespace App\Repositories;

use App\Models\Coupon;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class CouponRepository
{
  /**
   * Obtiene todos los cupones.
   *
   * @return Collection
  */
  public function getAllCoupons(): Collection
  {
    return Coupon::all();
  }

  /**
   * Obtiene un cupón por su ID.
   *
   * @param int $id
   * @return Coupon|null
  */
  public function getCouponById(int $id): ?Coupon
  {
    return Coupon::find($id);
  }

  /**
   * Crea un nuevo cupón.
   *
   * @param array $data
   * @return array
  */
  public function createCoupon(array $data): array
  {
    $coupon = Coupon::create([
        'code' => $data['code'],
        'type' => $data['type'],
        'amount' => $data['amount'],
        'due_date' => $data['due_date'],
        'creator_id' => auth()->id(),
        'status' => 1,
    ]);

    return ['success' => true, 'message' => 'Cupón creado con éxito'];
  }

  /**
   * Actualiza un cupón existente.
   *
   * @param int $id
   * @param array $data
   * @return array
  */
  public function updateCoupon(int $id, array $data): array
  {
    $coupon = Coupon::find($id);

    if (!$coupon) {
        return ['success' => false, 'message' => 'El cupón no existe'];
    }

    try {
        $coupon->update([
            'code' => $data['code'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date'],
            'creator_id' => auth()->id(),
            'status' => 1,
        ]);

        return ['success' => true, 'message' => 'Cupón actualizado con éxito'];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'No se pudo actualizar el cupón: ' . $e->getMessage()];
    }
  }

  /**
   * Elimina un cupón por su ID.
   *
   * @param int $id
   * @return array
  */
  public function deleteCoupon(int $id): array
  {
    $coupon = Coupon::find($id);

    if (!$coupon) {
        return ['success' => false, 'message' => 'El cupón no existe'];
    }

    try {
        $coupon->delete();
        return ['success' => true, 'message' => 'Cupón eliminado correctamente'];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'No se pudo eliminar el cupón: ' . $e->getMessage()];
    }
  }

  /**
   * Elimina los cupones seleccionados por sus IDs.
   *
   * @param array $selectedIds
   * @return array
  */
  public function deleteSelectedCoupons(array $selectedIds): array
  {
    Coupon::whereIn('id', $selectedIds)->delete();
    return ['success' => true, 'message' => 'Cupones seleccionados eliminados correctamente'];
  }

  /**
   * Obtiene los datos para mostrar en la tabla de cupones.
   *
   * @return mixed
  */
  public function datatable(): mixed
  {
    $coupons = $this->getCouponsWithCreator();

    return Datatables::of($coupons)
      ->addColumn('creator_name', function($coupon){
          return $coupon->creator ? $coupon->creator->name : 'No registrado';
      })
      ->addColumn('action', function($coupon){
          return '<a href="'.route('coupons.edit', $coupon->id).'" class="btn btn-primary btn-sm">Editar</a>';
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  /**
   * Obtiene los cupones con su creador.
   *
   * @return Collection
  */
  public function getCouponsWithCreator(): Collection
  {
    return Coupon::with('creator')->get();
  }
}
