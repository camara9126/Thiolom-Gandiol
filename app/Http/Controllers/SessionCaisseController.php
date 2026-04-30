<?php

namespace App\Http\Controllers;

use App\Models\Session_caisse;
use App\Models\User;
use App\Models\Vente;

class SessionCaisseController extends Controller
{

    // Point de vente
    public function pdv() {

        // Tous les Users
        $users= User::latest()->get();
        $userId= request()->user()->id;

        $session= Session_caisse::where('user_id', $userId)->whereNull('closed_at')->first();


        return view('dashboard.commandes.pdv',compact('users','session'));
    }




    // Ouverture caisse
    public function ouvrirCaisse()
    {
        $userId= request()->user()->id;

        $session= Session_caisse::where('user_id', $userId)->whereNull('closed_at')->first();

        if(!$session) {
            $session= Session_caisse::create([
                'user_id' => $userId,
                'opened_at' => now()
            ]);
            
            return redirect()->route('commandes.index')->with('success', 'Caisse ouverte !');
        }

        return back()->with('success', 'Caisse deja ouverte');
    }


    // Fermeture Caisse
    public function fermerCaisse()
    {
        $userId= request()->user()->id;

        $session= Session_caisse::where('user_id', $userId)->whereNull('closed_at')->first();

        if(!$session) {

            return back()->with('success', 'Aucun session ouverte');
        }

        $ventes= Vente::where('session_caisse_id', $session->id)->get();

        $session->update([
            'closed_at' => now(),
            'nombre_ventes' => $ventes->count(),
            'total_ventes' => $ventes->sum('total_ttc'),
            'total_encaisse' => $ventes->sum('montant_paye'),
        ]);
        //dd($session);

        return redirect()->route('commandes.pdv')->with('success', 'Caisse fermee avec succes', compact('session'));
    }

}
