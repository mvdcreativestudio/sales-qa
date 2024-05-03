<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{

    public function createCoupon(array $data)
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

    public function updateCoupon($id, array $data)
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

    public function deleteCoupon($id)
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

    public function deleteSelectedCoupons(array $selectedIds)
    {
        Coupon::whereIn('id', $selectedIds)->delete();
        return ['success' => true, 'message' => 'Cupones seleccionados eliminados correctamente'];
    }
}
