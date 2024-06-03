<?php

namespace App\Repositories;

use App\Models\Client;
use Yajra\DataTables\DataTables;

class ClientRepository
{
  /**
   * Obtiene todos los clientes para la tabla de datos.
   *
   * @return mixed
  */
  public function getClientsForDatatable(): mixed
  {
    $query = Client::select(['id', 'name', 'lastname', 'type', 'rut', 'ci', 'address', 'city', 'state', 'country', 'phone', 'email', 'website', 'logo']);
    return DataTables::of($query)->make(true);
  }

  /**
   * Crea un nuevo cliente.
   *
   * @param array $data
   * @return Client
  */
  public function createClient(array $data): Client
  {
    return Client::create($data);
  }

  /**
   * Obtiene un cliente por su ID.
   *
   * @param int $id
   * @return Client|null
  */
  public function getClientById(int $id): ?Client
  {
    return Client::find($id);
  }

  /**
   * Actualiza un cliente existente.
   *
   * @param int $id
   * @param array $data
   * @return bool
  */
  public function updateClient(int $id, array $data): bool
  {
    $client = Client::find($id);
    if ($client) {
        return $client->update($data);
    }
    return false;
  }

  /**
   * Elimina un cliente por su ID.
   *
   * @param int $id
   * @return bool
  */
  public function deleteClient(int $id): bool
  {
    $client = Client::find($id);
    if ($client) {
        return $client->delete();
    }
    return false;
  }
}
