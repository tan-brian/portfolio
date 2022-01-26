<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.registration');
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
            'name' =>'required|max:50|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
         
          ]);    

        $user = new User;
        $user->fill($request->all());
        $user->password = Hash::make($request->password);
        $user->save();

        Etudiant::create([
          'id' => $user->id,
          'nom' => $request->name, 
          'email' =>  $request->email,
          ]);

        return redirect('login');
    }

    public function customLogin(Request $request){

        $request->validate([
          'email' => 'required|email',
          'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){  
          session(['user' => Auth::user(),
          ]);
          return redirect()->intended('forum');
        }
        if(session()->get('locale') == 'fr') {
          $msg = 'Les informations de connexion ne sont pas valides!';
        }else {
          $msg = 'Login information is not valid!';
        }
        return redirect('login')->withInput()->withSuccess($msg);

  }




  public function logout(){

    $locale = Session::get('locale');
    Session::flush();
    Session::put('locale', $locale);
    Auth::logout();

    return redirect('login');
  }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
