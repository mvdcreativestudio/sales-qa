<?php

namespace App\Repositories;

use App\Models\RawMaterial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile as HttpUploadedFile;

class RawMaterialRepository
{
    /**
     * Devuelve todas las Materias Primas.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(): Collection
    {
        return RawMaterial::all();
    }

    /**
     * Busca una Materia Prima por el ID.
     *
     * @param  int $id
     * @return RawMaterial|null
     */
    public function findById($id): ?RawMaterial
    {
        return RawMaterial::find($id);
    }

    /**
     * Guarda una nueva Materia Prima.
     *
     * @param  array $data
     * @return RawMaterial
     */
    public function create(array $data): RawMaterial
    {
        if (isset($data['image'])) {
            $data['image_url'] = $this->uploadImage($data['image']);
            unset($data['image']); // Elimina la imagen del array para evitar intentar guardarla como columna.
        }

        return RawMaterial::create($data);
    }

    /**
     * Actualiza una Materia Prima existente.
     *
     * @param  RawMaterial $rawMaterial
     * @param  array $data
     * @return RawMaterial
     */
    public function update(RawMaterial $rawMaterial, array $data): RawMaterial
    {
        if (isset($data['image'])) {
            if ($rawMaterial->image_url) {
                Storage::delete('public/' . $rawMaterial->image_url);
            }
            $data['image_url'] = $this->uploadImage($data['image']);
            unset($data['image']);
        }

        $rawMaterial->update($data);
        return $rawMaterial;
    }

    /**
     * Elimina una Materia Prima.
     *
     * @param  RawMaterial $rawMaterial
     * @return bool|null
     */
    public function delete(RawMaterial $rawMaterial): ?bool
    {
        return $rawMaterial->delete();
    }

    /**
     * Sube una imagen al servidor y devuelve el nombre del archivo.
     *
     * @param  \Illuminate\Http\UploadedFile $image
     * @return string
     */
    protected function uploadImage(HttpUploadedFile $image): string
    {
        $path = $image->store('public/assets/img/raw_materials');
        return basename($path);
    }
}