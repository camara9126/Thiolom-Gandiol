<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Liste des utilisateurs
     */
    public function user() {
        $users= User::latest()->get();

        return view('profile.users', compact('users'));
    }


    /**
     * Ajout nouveau utilisateur
     */
     public function userAdd(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['string', 'max:250'],
        ]);
        //dd($request);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users')->with('success', 'Utilisateur ajouté avec succès');
    }


    /**
     *Changement de statut.
     */
     public function statut(User $id)
    {
        $user= user::findOrFail($id)->get();

        if($user->statut) {
            $user->update(['statut' => false]);
            return redirect()->route('users')->with('success', 'Utilisateur désactivé');
        }
        else {
            $user->update(['statut' => true]);
            return redirect()->route('users')->with('success', 'Utilisateur activé');
        }

    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Update the entreprise's profile information..
     */
    public function entrepriseUpdate(Request $request, string $entreprise)
    {
        $entreprise = Entreprise::FindOrFail($entreprise);

         $request->validate([
            'telephone' => 'nullable|string|max:50',
            'taux_tva' => 'numeric|max:100',
            'adresse' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ninea' => 'nullable',
        ]);

        // Gestion des logo
        if ($request->hasFile('logo')) {
            
            if($entreprise->logo){
                Storage::delete('public/logo/'.$entreprise->logo);
            }

            $filename = time().$request->file('logo')->getClientOriginalName();
            $path = $request->file('logo')->storeAs('logo', $filename, 'public');
            $request['logo'] = '/storage/' . $path;
           
        } else {
            $entreprise->logo;
        }

        $entreprise->update([
            'telephone' => $request->telephone,
            'taux_tva' => $request->taux_tva,
            'adresse' => $request->adresse,
            'logo' => $path  ?? $entreprise->logo,
            'ninea' => $request->ninea  ?? null,
        ]);


        // Lier l'utilisateur a l'entreprise
        $user= $request->user();
        $user->save();

        return redirect()->back()->with('success', 'Entreprise mise a jour avec success');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
