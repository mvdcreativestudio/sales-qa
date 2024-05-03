<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Repositories\CouponRepository;

class CouponController extends Controller
{

    public function __construct(CouponRepository $couponRepository)
    {
      $this->couponRepository = $couponRepository;
    }

    public function index()
    {
      $coupon = Coupon::all();
      return view('content.e-commerce.backoffice.marketing.coupons.index', compact('coupon'));
    }

    public function show($id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            // Manejar el caso en que no se encuentre el cupón con el ID proporcionado
            return response()->json(['error' => 'Cupón no encontrado'], 404);
        }

        // Devolver los detalles del cupón en formato JSON
        return response()->json($coupon);
    }

    public function store(StoreCouponRequest $request)
    {
        $validatedData = $request->validated();

        $result = $this->couponRepository->createCoupon($validatedData);
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('content.e-commerce.backoffice.marketing.coupons.edit', compact('coupon'));
    }

    public function update(UpdateCouponRequest $request, $id)
    {
      $validatedData = $request->validated();

      $result = $this->couponRepository->updateCoupon($id, $validatedData);
      return response()->json($this->couponRepository->updateCoupon($id, $request->all()));
    }

    public function destroy($id)
    {
        return response()->json($this->couponRepository->deleteCoupon($id));
    }

    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->ids;
        return response()->json($this->couponRepository->deleteSelectedCoupons($selectedIds));
    }

    public function datatable()
    {
        $coupons = Coupon::with('creator')->get();

        return datatables()->of($coupons)
            ->addColumn('creator_name', function($coupon){
                return $coupon->creator ? $coupon->creator->name : 'No registrado';
            })
            ->addColumn('action', function($coupon){
                return '<a href="'.route('coupons.edit', $coupon->id).'" class="btn btn-primary btn-sm">Editar</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
