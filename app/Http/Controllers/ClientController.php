<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {

        $clients = Client::latest()->paginate(10);

        return view('dashboard.clients.index', compact('clients'));
    }

    public function search(Request $request)
    {
        $search = $request->query('search');

        $clients = Client::when($search, function ($query, $search) {

                $query->where('nom', 'like', "%{$search}%");

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=;

        return view('dashboard.clients.index', compact('clients','search'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
        ]);

        Client::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'entreprise_id' => $request->user()->entreprise_id,
        ]);

        return redirect()->back()->with('success', 'Client ajouté');
    }


    public function edit(Client $client)
    {

        return view('dashboard.clients.edit', compact('client'));
    }
    

    public function update(Request $request, Client $client)
    {

        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
        ]);

        $client->update($request->only(
            'nom',
            'telephone',
            'email',
            'adresse'
        ));

        return redirect()->back()->with('success', 'Client modifié');

    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $client= Client::findOrFail($id);

         $client->destroy($id);

        return redirect()->route('clients.index')->with('success', ' client supprimé avec succès');        

    }

    // Creation nouveau client depuis la section 'Vente'
    public function storeAjax(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        Client::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'entreprise_id' => $request->user()->entreprise_id,
        ]);

        return redirect()->route('ventes.create')->with('success', 'Nouveau client ajouté');
        //return response()->json($client);
    }

}
