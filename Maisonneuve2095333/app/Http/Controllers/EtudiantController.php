<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class EtudiantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $etudiants = Etudiant::selectEtudiant();

	    return view('etudiants.index', [
            'etudiants' => $etudiants,        
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $villes = Ville::selectVille(); 
        return view('etudiants.create', [
            'villes' =>$villes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|max:50',
            'adresse' => 'required|max:100',
            'phone' =>  'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/',
            'email' =>  'required|email',
            'date_naissance' =>  'required',
            'ville_id' =>  'required|exists:villes,id',
            'password' => 'required|min:6',
          ]);

        $request->date_naissance =  date('Y-m-d', strtotime($request->date_naissance));
        
        $user = User::create([
            'name' => $request->nom,
            'email' =>  $request->email,
            'password' => Hash::make($request->password),
        ]);
        Etudiant::create([
            'id' => $user->id,
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'phone' =>  $request->phone,
            'email' =>  $request->email,
            'date_naissance' =>  $request->date_naissance,
            'ville_id' =>  $request->ville_id
        ]);

        return redirect('etudiants/' . $user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function show(Etudiant $etudiant)
    {
     
        if(isset($etudiant->date_naissance)) {
            $etudiant->date_naissance =  date('d-m-Y', strtotime($etudiant->date_naissance));
        }
        return view('etudiants.show', [
            'etudiant' => $etudiant,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function edit(Etudiant $etudiant)
    {
        if(Auth::user()->id != $etudiant->id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }

        $villes = Ville::selectVille(); 

        if(isset($etudiant->date_naissance)) {
            $etudiant->date_naissance =  date('d-m-Y', strtotime($etudiant->date_naissance));
        }
        
        return view('etudiants.edit', [
            'etudiant' => $etudiant,
            'villes' => $villes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        if(Auth::user()->id != $etudiant->id && Auth::user()->is_admin != 1) {
            redirect('etudiants');
        }
        $request->validate([
            'nom' => 'required|max:50',
            'adresse' => 'required|max:100',
            'phone' =>  'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/',
            'email' =>  'required|email',
            'date_naissance' =>  'required',
            'ville_id' =>  'required|exists:villes,id'
          ]);

        $request->date_naissance =  date('Y-m-d', strtotime($request->date_naissance));
        
        $etudiant->update([
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'phone' =>  $request->phone,
            'email' =>  $request->email,
            'date_naissance' =>  $request->date_naissance,
            'ville_id' =>  $request->ville_id
        ]);

        return redirect('etudiants/' . $etudiant->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Etudiant $etudiant)
    {
        if(Auth::user()->id != $etudiant->id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }

        $admin = false;
        
        if( Auth::user()->is_admin == 1) {
            $admin = true;
        }

        $user = User::find($etudiant->id);

        $user->delete();
        $etudiant->delete();
  
        if(!$admin) { 
            $locale = Session::get('locale');
            Session::flush();
            Session::put('locale', $locale);;
            Auth::logout();
        
            return redirect('login');
        }else {
            return redirect('etudiants');
        }
    }
}
