<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaRequest;
use App\Models\Audio;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $audios = Media::IsAudio()->IdDescending()->paginate(4);
        return view('audio.index',compact('audios'));
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
        return view('audio.create',compact('sources','thematiques'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MediaRequest $request)
    {
     
        $statut = $request->has('statut') ? 1 : 0;

    Media::create([
        'user_id'=>$request->user_id,
        'thematique_id'=>json_encode($request->thematique_id),
        'source_id'=>$request->source_id,
        'description'=>$request->description,
        'type'=>$request->type,
        'statut'=>$statut,
        'media'=>$request->media,
        'title'=>$request->title,
        'auteur'=>$request->auteur,
        'code_media'=>$request->code_media,
    ]);
    return redirect()->route('audios.index')->with('message','Enregistrement effectué avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Media $audio)
    {
        return view('audio.show', compact('audio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $audio = Media::find($id);
        $sources = Source::all();
        $thematiques = Thematique::all();
        return view('audio.edit',compact('sources','thematiques','audio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    
    public function update(MediaRequest $request, $id)
    {
        $audio = Media::findOrFail($id);
    
        // $request->validate([
        //     'user_id' => 'required|integer|exists:users,id',
        //     'source_id' => 'required|integer|exists:sources,id',
        //     'thematique_id' => 'required|array',
        //     'thematique_id.*' => 'integer|exists:thematiques,id',
        //     'description' => 'nullable|string|max:255',
        //     'type' => 'required', // Ajoutez ici les valeurs valides pour le champ type
        //     'statut' => 'boolean',
        //     'audio' => 'string', // Ajoutez ici les formats de fichier audio autorisés
        //     'title' => 'required|string|max:255',
        //     'auteur' => 'required|string|max:255',
        //     'code_media' => 'required|string|max:255',
        // ]);
    
        // Mettre à jour le statut
        $statut = $request->has('statut') ? 1 : 0;
    

        // Mettre à jour les autres champs du modèle
        $audio->user_id = $request->user_id;
        $audio->source_id = $request->source_id;
        $audio->thematique_id = json_encode($request->thematique_id);
        $audio->description = $request->description;
        $audio->type = $request->type;
        $audio->statut = $statut;
        $audio->title = $request->title;
        $audio->auteur = $request->auteur;
        $audio->code_media = $request->code_media;
    
        // Enregistrer les modifications dans la base de données
        $audio->save();
    
        return redirect()->route('audios.index')->with('message', 'Mise à jour effectuée avec succès');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $audio = Media::find($id);
        $audio->delete();

        return redirect()->route('audios.index')
            ->with('message', 'Audio supprimée!!!');
    }

    public function desactivate($id){
        $audio = Media::find($id);
        $audio->update([
            'statut'=> 0
        ]);
        return back();
    }
    public function activate($id){
        $audio = Media::find($id);
        $audio->update([
            'statut'=> 1
        ]);
        return back();
    }

    public function localisationIndex($id){
        $audio = Media::find($id);
        return view('audio.localisation', compact('audio'));
    }

    public function addLocalisation(Request $request){
       $getLocalisationId = $request->localisation_id;
       Media::where('id',$getLocalisationId)->update([
        'localisation'=>$request->localisation
      ]);
      return redirect()->route('audios.index')->with('message','Localisation Ajouter !!');

    }

    public function removeLocalisation(Request $request)
    {
        $getLocalisationId = $request->localisation_id;
       Media::where('id',$getLocalisationId)->update([
        'localisation'=> null
      ]);

        return redirect()->route('audios.index')
            ->with('message', 'localisation supprimée!!!');
    }

}
