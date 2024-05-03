<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class ClientController extends Controller
{
    public function index() {
        return view('content.clients.clients');
    }


    public function store(Request $request) {
      // Validación de los datos recibidos del formulario
      $validatedData = $request->validate([
          'name' => 'required|string|max:255',
          'type' => 'nullable|string|max:255',
          'rut' => 'nullable|string|max:255',
          'ci' => 'nullable|string|max:255',
          'address' => 'nullable|string|max:255',
          'city' => 'nullable|string|max:255',
          'state' => 'nullable|string|max:255',
          'country' => 'nullable|string|max:255',
          'phone' => 'nullable|string|max:255',
          'email' => 'required|string|email|max:255',
          'website' => 'nullable|url|max:255',
          'logo' => 'nullable|string|max:255',
      ]);

      // Crear y almacenar el nuevo cliente con los datos validados
      $client = new Client();
      $client->name = $validatedData['name'];
      $client->type = $validatedData['type'];
      $client->rut = $validatedData['rut'];
      $client->ci = $validatedData['ci'];
      $client->address = $validatedData['address'];
      $client->city = $validatedData['city'];
      $client->state = $validatedData['state'];
      $client->country = $validatedData['country'];
      $client->phone = $validatedData['phone'];
      $client->email = $validatedData['email'];
      $client->website = $validatedData['website'];
      $client->logo = $validatedData['logo'];
      $client->save();

      // Redireccionar al usuario a la lista de clientes con un mensaje de éxito
      return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
  }


    public function show($id) {
        $client = Client::find($id);
        return view('content.clients.show', compact('client'));
    }

    public function edit($id) {
        $client = Client::find($id);
        return view('content.clients.edit', compact('client'));
    }

    public function update(Request $request, $id) {
        $client = Client::find($id);
        $client->name = $request->name;
        $client->type = $request->type;
        $client->rut = $request->rut;
        $client->ci = $request->ci;
        $client->address = $request->address;
        $client->city = $request->city;
        $client->state = $request->state;
        $client->country = $request->country;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->website = $request->website;
        $client->logo = $request->logo;
        $client->save();
        return redirect()->route('clients.index');
    }

    public function destroy($id) {
        $client = Client::find($id);
        $client->delete();
        return redirect()->route('clients.index');
    }


    public function datatable()
    {
        $query = Client::select(['id', 'name', 'type', 'rut', 'ci', 'address', 'city', 'state', 'country', 'phone', 'email', 'website', 'logo']);
        return DataTables::of($query)
            ->make(true);
    }


}
