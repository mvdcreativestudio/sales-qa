<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Repositories\ClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
  /**
   * El repositorio de clientes.
   *
   * @var ClientRepository
  */
  protected ClientRepository $clientRepository;

  /**
   * Constructor para inyectar el repositorio.
   *
   * @param ClientRepository $clientRepository
   */
  public function __construct(ClientRepository $clientRepository)
  {
    $this->clientRepository = $clientRepository;
  }

  /**
   * Muestra la página de lista de clientes.
   *
   * @return View
  */
  public function index(): View
  {
    return view('content.clients.clients');
  }

  /**
   * Almacena un nuevo cliente en la base de datos.
   *
   * @param StoreClientRequest $request
   * @return RedirectResponse
  */
  public function store(StoreClientRequest $request): RedirectResponse
  {
    $validatedData = $request->validated();
    $this->clientRepository->createClient($validatedData);
    return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
  }

  /**
   * Muestra los detalles de un cliente específico.
   *
   * @param int $id
   * @return View
  */
  public function show(int $id): View
  {
    $client = $this->clientRepository->getClientById($id);
    return view('content.clients.show', compact('client'));
  }

  /**
   * Muestra el formulario para editar un cliente existente.
   *
   * @param int $id
   * @return View
  */
  public function edit(int $id): View
  {
    $client = $this->clientRepository->getClientById($id);
    return view('content.clients.edit', compact('client'));
  }

  /**
   * Actualiza un cliente existente en la base de datos.
   *
   * @param UpdateClientRequest $request
   * @param int $id
   * @return RedirectResponse
  */
  public function update(UpdateClientRequest $request, int $id): RedirectResponse
  {
    $validatedData = $request->validated();
    $this->clientRepository->updateClient($id, $validatedData);
    return redirect()->route('clients.index');
  }

  /**
   * Elimina un cliente específico de la base de datos.
   *
   * @param int $id
   * @return RedirectResponse
  */
  public function destroy(int $id): RedirectResponse
  {
    $this->clientRepository->deleteClient($id);
    return redirect()->route('clients.index');
  }

  /**
   * Obtiene los datos para mostrar en la tabla de clientes.
   *
   * @return mixed
  */
  public function datatable(): mixed
  {
    return $this->clientRepository->getClientsForDatatable();
  }
}
