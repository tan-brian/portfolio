<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\EtudiantController;
use \App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/lang/{locale}', [LocalizationController::class, 'index']);



Route::get('/etudiants', [EtudiantController::class, 'index'])->name('etudiants');
Route::get('/etudiants/{etudiant}', [EtudiantController::class, 'show'])->name('etudiant.show');
Route::get('/etudiants/create/etudiant', [EtudiantController::class, 'create'])->middleware('admin')->name('etudiant.create');
Route::post('/etudiants/create/etudiant', [EtudiantController::class, 'store'])->middleware('auth')->name('etudiant.store');
Route::get('/etudiants/{etudiant}/edit', [EtudiantController::class, 'edit'])->middleware('auth')->name('etudiant.edit'); //shows edit post form
Route::put('/etudiants/{etudiant}/edit', [EtudiantController::class, 'update'])->middleware('auth')->name('etudiant.update');//commits edited post to the database 
Route::delete('/etudiants/{etudiant}', [EtudiantController::class, 'destroy'])->middleware('auth')->name('etudiant.destroy'); //deletes post from the database


Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('/registration', [CustomAuthController::class, 'create'])->name('registration');
Route::post('custom-registration', [CustomAuthController::class, 'store'])->name('register.custom');
Route::get('logout', [CustomAuthController::class, 'logout'])->name('logout');


Route::get('/forum', [ArticleController::class, 'index'])->middleware('auth')->name('forum');
Route::post('forum-create', [ArticleController::class, 'store'])->name('create.article')->middleware('auth');
Route::delete('/forum/{article}', [ArticleController::class, 'destroy'])->middleware('auth')->name('delete.article'); 
Route::get('/forum/{article}/edit', [ArticleController::class, 'edit'])->middleware('auth')->name('edit.article'); //shows edit post form
Route::put('/forum/{article}/edit', [ArticleController::class, 'update'])->middleware('auth')->name('update.article'); //commits edited post to the database 


Route::get('/documents', [DocumentController::class, 'index'])->middleware('auth')->name('documents');
Route::post('/upload-file', [DocumentController::class, 'store'])->name('fileUpload');
Route::get('download', [DocumentController::class,'download'])->name('fileDownload');
 Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->middleware('auth')->name('delete.document'); 
 Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->middleware('auth')->name('edit.document');  //shows edit post form
 Route::put('/document/{document}/edit', [DocumentController::class, 'update'])->middleware('auth')->name('update.document'); //commits edited post to the database 

 //https://laracasts.com/discuss/channels/general-discussion/create-middleware-to-auth-admin-users?page=0
 Route::get('admin', ['middleware' => 'admin']);