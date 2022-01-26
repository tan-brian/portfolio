<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::selectDocument();
        return view('document.index', [
           'documents' => $documents,
        ]); 
    }


   public function download( Request $request){
    // https://stackoverflow.com/questions/43315857/cannot-download-file-from-storage-folder-in-laravel-5-4

    return response()->download( Storage::path('public/'.$request->path));
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // https://www.positronx.io/laravel-file-upload-with-validation/
        $request->validate([
            'file' => 'required|mimes:doc,zip,pdf',
            'titre_fr' => 'required',
            'titre_en' => 'required',
            ]);
    
            $document = new Document;
    
            if($request->file()) {
                $fileName = time().'_'.$request->file->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
                $document->titre_en = $request->titre_en;
                $document->titre_fr = $request->titre_fr;
                $document->file =  $filePath;
                $document->etudiant_id = Auth::user()->id;
                $document->save();
    
                 if(session()->get('locale') == 'fr') {
                    $msg = 'Le fichier a été téléversé.';
                  }else {
                    $msg = 'File has been uploaded.';
                  }
                 return back()->with('success', $msg);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        if(Auth::user()->id != $document->etudiant_id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }

        return view('document.edit', [
            'document' => $document,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        if(Auth::user()->id != $document->etudiant_id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }

        $request->validate([
            'file' => 'mimes:doc,zip,pdf',
            'titre_fr' => 'required',
            'titre_en' => 'required',
            ]);
    
            if($request->file()) {

                if (File::exists(Storage::path('public/' . $document->file))) {
                    File::delete(Storage::path('public/' . $document->file));
                }
                $fileName = time().'_'.$request->file->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
                $document->file =  $filePath;
              
            }

            $document->update([
                'titre_fr' => $request->titre_fr,
                'titre_en' => $request->titre_en,
                'file' => $document->file,
            ]);

            if(session()->get('locale') == 'fr') {
                    $msg = 'Le document a été mise à jour.';
            }else {
                    $msg = 'Document has been updated.';
            }
            
            return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        if(Auth::user()->id != $document->etudiant_id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }
        
        // https://stackoverflow.com/questions/33842735/how-to-delete-file-from-public-folder-in-laravel-5-1
        if (File::exists(Storage::path('public/' . $document->file))) {
            File::delete(Storage::path('public/' . $document->file));
        }

        $document->delete();
        return redirect('documents');
    }
}
