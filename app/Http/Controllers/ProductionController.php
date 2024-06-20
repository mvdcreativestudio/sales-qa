<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\RawMaterial;
use App\Repositories\ProductionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    /**
     * El repositorio para las producciones.
     *
     * @var ProductionRepository
     */
    protected $productionRepository;

    /**
     * Inyecta el repositorio en el controlador.
     *
     * @param ProductionRepository $productionRepository
     */
    public function __construct(ProductionRepository $productionRepository)
    {
        $this->middleware(['check_permission:access_productions', 'user_has_store'])->only(
            [
                'index',
                'create',
                'store',
                'show',
                'edit',
                'update',
                'destroy'
            ]
        );

        $this->productionRepository = $productionRepository;
    }

    /**
     * Muestra una lista de todas las producciones.
     *
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->productionRepository->getAllDataTable();
        }

        return view('productions.index');
    }

    /**
     * Muestra el formulario para crear una nueva producción.
     *
     * @return View
     */
    public function create(): View
    {
        return view('productions.create');
    }

    /**
     * Almacena una nueva producción en la base de datos.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
      $request->validate([
        'product_id' => 'nullable|exists:products,id',
        'flavor_id' => 'nullable|exists:flavors,id',
        'quantity' => 'required|integer|min:1',
      ]);

      if (Auth::user()->cannot('bypass_raw_material_check')) {
        $rawMaterials = RawMaterial::all();
      }

      $this->productionRepository->create($request->all());

      return redirect()->route('productions.index')->with('success', 'Producción creada correctamente.');
    }

    /**
     * Muestra una producción específica.
     *
     * @param  Production  $production
     * @return View
    */
    public function show(Production $production): View
    {
        return view('productions.show', compact('production'));
    }

    /**
     * Muestra el formulario para editar una producción existente.
     *
     * @param  Production  $production
     * @return View
     */
    public function edit(Production $production): View
    {
        return view('productions.edit', compact('production'));
    }

    /**
     * Actualiza una producción específica en la base de datos.
     *
     * @param  Request  $request
     * @param  Production  $production
     * @return RedirectResponse
     */
    public function update(Request $request, Production $production): RedirectResponse
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'flavor_id' => 'nullable|exists:flavors,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->productionRepository->update($production, $request->all());

        return redirect()->route('productions.index')->with('success', 'Producción actualizada correctamente.');
    }

    /**
     * Elimina una producción de la base de datos.
     *
     * @param  Production  $production
     * @return RedirectResponse
     */
    public function destroy(Production $production): RedirectResponse
    {
        $this->productionRepository->delete($production);
        return redirect()->route('productions.index')->with('success', 'Producción eliminada correctamente.');
    }
}
