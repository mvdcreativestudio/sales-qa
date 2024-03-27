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
        $client = new Client();
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
