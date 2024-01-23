<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::IdDescending()->paginate(4);
       return view('document.index',compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sources = Source::all();
        $thematiques = Thematique::all();
       return view('document.create',compact('sources','thematiques'));
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
            'file_doc' => 'required|mimes:pdf|max:30000',
            'user_id' => 'required|integer',
            'source_id' => 'required|integer',
            'thematique_id.*' => 'integer',
            'categorie' => 'required|string',
            'page' => 'required|integer',
            'edition' => 'nullable|string',
            'publication_date' => 'required|date',
            'code_document' => 'nullable|string',
            'title' => 'required|string',
            'auteur' => 'required|string'
        ]);
    $statut_publication = $request->has('statut_publication') ? 1 : 0;
    $ask_form = $request->has('ask_form') ? 1 : 0;
    
     
     if ($document = $request->file('file_doc')) {
           $destinationPath = 'document/';
           $documentFile = date('YmdHis') . "." . $document->getClientOriginalExtension();
           $document->move($destinationPath, $documentFile);
           $input['file_doc'] = "$documentFile";
       }

       if ($picture = $request->file('picture')) {
        $destinationPath = 'picture/';
        $pictureFile = date('YmdHis') . "." . $picture->getClientOriginalExtension();
        $picture->move($destinationPath, $pictureFile);
        $input['picture'] = "$pictureFile";
    }

   Document::create([
       'user_id'=>$request->user_id,
       'source_id'=>$request->source_id,
       'thematique_id'=>json_encode($request->thematique_id),
       'source_id'=>$request->source_id,
       'resume'=>$request->resume,
       'categorie'=>$request->categorie,
       'picture'=>$pictureFile,
       'page'=>$request->page,
       'edition'=>$request->edition,
       'publication_date'=>$request->publication_date,
       'file_doc'=>$documentFile,
       'statut_publication'=>$statut_publication,
       'ask_form'=>$ask_form,
       'code_document'=>$request->code_document,
       'title'=>$request->title,
       'auteur'=>$request->auteur,
       'code_media'=>$request->code_media,
   ]);
   return redirect()->route('documents.index')->with('message','Document Enregistré');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        return view('document.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::find($id);
        $sources = Source::all();
        $thematiques = Thematique::all();
        return view('document.edit',compact('sources','thematiques','document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Récupérer le document existant par son ID
        $document = Document::findOrFail($id);
    
        // Valider les données du formulaire
        $request->validate([
            'file_doc' => 'nullable|mimes:pdf|max:40000',
            'user_id' => 'required|integer',
            'source_id' => 'required|integer',
            'thematique_id.*' => 'integer',
            'resume' => 'nullable|string',
            'categorie' => 'required|string',
            'page' => 'nullable|integer',
            'edition' => 'nullable|string',
            'publication_date' => 'nullable|date',
            'title' => 'required|string',
            'auteur' => 'required|string',
           
        ]);
    
        // Déterminer le statut de publication et la demande de formulaire
        $statut_publication = $request->has('statut_publication') ? 1 : 0;
        $ask_form = $request->has('ask_form') ? 1 : 0;
    
        // Gérer le fichier de document s'il est présent
        $documentFile = $document->file_doc;
        if ($docs = $request->file('file_doc')) {
            $destinationPath = 'document/';
            $documentFile = date('YmdHis') . "." . $docs->getClientOriginalExtension();
            $docs->move($destinationPath, $documentFile);
        }
    
        // Gérer le fichier d'image s'il est présent
        $pictureFile = $document->picture;
        if ($picture = $request->file('picture')) {
            $destinationPath = 'picture/';
            $pictureFile = date('YmdHis') . "." . $picture->getClientOriginalExtension();
            $picture->move($destinationPath, $pictureFile);
        }
    
        // Mettre à jour les propriétés du document
        $document->user_id = $request->user_id;
        $document->source_id = $request->source_id;
        $document->thematique_id = json_encode($request->thematique_id);
        $document->resume = $request->resume;
        $document->categorie = $request->categorie;
        $document->picture = $pictureFile;
        $document->page = $request->page;
        $document->edition = $request->edition;
        $document->publication_date = $request->publication_date;
        $document->file_doc = $documentFile; // Utilisation correcte du nom du champ
        $document->statut_publication = $statut_publication;
        $document->ask_form = $ask_form;
        $document->code_document = $request->code_document;
        $document->title = $request->title;
        $document->auteur = $request->auteur;
    
        // Sauvegarder les modifications dans la base de données
        $document->save();
    
        // Retourner une confirmation
        return redirect()->route('documents.index')->with('message','Document mis à jours');
    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::find($id);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('message', 'document supprimée!!!');
    }

    public function desactivateDocument($id){
        $document = Document::find($id);
        $document->update([
            'statut_publication'=> 0
        ]);
        return back();
    }
    public function activateDocument($id){
        $document = Document::find($id);
        $document->update([
            'statut_publication'=> 1
        ]);
        return back();
    }

    public function localisationIndex($id){
        $document = Document::find($id);
        return view('document.localisation', compact('document'));
    }

    public function addLocalisation(Request $request){
       $getLocalisationId = $request->localisation_id;
       Document::where('id',$getLocalisationId)->update([
        'localisation'=>$request->localisation
      ]);
      return redirect()->route('documents.index')->with('message','Localisation Ajoutée !!');

    }

    public function removeLocalisation(Request $request)
    {
        $getLocalisationId = $request->localisation_id;
        Document::where('id',$getLocalisationId)->update([
        'localisation'=> null
      ]);

        return redirect()->route('documents.index')
            ->with('message', 'localisation supprimée!!!');
    }
}
