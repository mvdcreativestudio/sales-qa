<?php

namespace App\Repositories;

use App\Models\Production;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;

class ProductionRepository
{
    /**
     * Devuelve todas las producciones.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Production::all();
    }

    /**
     * Devuelve todas las producciones en formato DataTable.
     *
     * @return \Yajra\DataTables\DataTableAbstract
    */
    public function getAllDataTable()
    {
        $query = Production::with(['product', 'flavor'])->select('productions.*');

        return DataTables::of($query)
            ->addColumn('product_name', function ($production) {
                return $production->product ? $production->product->name : 'N/A';
            })
            ->addColumn('flavor_name', function ($production) {
                return $production->flavor ? $production->flavor->name : 'N/A';
            })
            ->addColumn('actions', function ($production) {
                return '<div class="d-inline-block text-nowrap">' .
                       '<a href="' . route('productions.edit', $production->id) . '" class="btn btn-sm btn-icon edit-button"><i class="bx bx-edit"></i></a>' .
                       '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded me-2"></i></button>' .
                       '<div class="dropdown-menu dropdown-menu-end m-0">' .
                       '<a href="' . route('productions.show', $production->id) . '" class="dropdown-item">Ver elaboración</a>' .
                       '<a href="javascript:void(0);" class="dropdown-item delete-button" data-id="' . $production->id . '">Eliminar</a>' .
                       '</div>' .
                       '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Busca una producción por el ID.
     *
     * @param  int $id
     * @return Production|null
     */
    public function findById($id): ?Production
    {
        return Production::find($id);
    }

    /**
     * Guarda una nueva producción.
     *
     * @param  array $data
     * @return Production
     */
    public function create(array $data): Production
    {
        return Production::create($data);
    }

    /**
     * Actualiza una producción existente.
     *
     * @param  Production $production
     * @param  array $data
     * @return Production
     */
    public function update(Production $production, array $data): Production
    {
        $production->update($data);
        return $production;
    }

    /**
     * Elimina una producción.
     *
     * @param  Production $production
     * @return bool|null
     */
    public function delete(Production $production): ?bool
    {
        return $production->delete();
    }
}
