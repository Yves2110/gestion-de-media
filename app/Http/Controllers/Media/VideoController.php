<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Api\V2010\Account\Message\MediaPage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Media::Isvideo()->IdDescending()->paginate(4);
        return view('video.index',compact('videos'));
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
        return view('video.create',compact('sources','thematiques'));
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
        'source_id'=>$request->source_id,
        'thematique_id'=>json_encode($request->thematique_id),
        'description'=>$request->description,
        'type'=>$request->type,
        'statut'=>$statut,
        'media'=>$request->media,
        'title'=>$request->title,
        'auteur'=>$request->auteur,
        'code_media'=>$request->code_media,
    ]);
    return redirect()->route('videos.index')->with('message','Enregistrement effectué avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Media $video)
    {
        return view('video.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Media::find($id);
        $sources = Source::all();
        $thematiques = Thematique::all();
        return view('video.edit',compact('sources','thematiques','video'));
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
        $video = Media::findOrFail($id);
    
        // $request->validate([
        //     'user_id' => 'required|integer|exists:users,id',
        //     'source_id' => 'required|integer|exists:sources,id',
        //     'thematique_id' => 'required|array',
        //     'thematique_id.*' => 'integer|exists:thematiques,id',
        //     'description' => 'nullable|string|max:255',
        //     'type' => 'required', // Ajoutez ici les valeurs valides pour le champ type
        //     'statut' => 'boolean',
        //     'video' => 'required', // Ajoutez ici les formats de fichier audio autorisés
        //     'title' => 'required|string|max:255',
        //     'auteur' => 'required|string|max:255',
        //     'code_media' => 'required|string|max:255',
        // ]);
    
        // Mettre à jour le statut
        $statut = $request->has('statut') ? 1 : 0;
    
    
       
        // Mettre à jour les autres champs du modèle
        $video->user_id = $request->user_id;
        $video->source_id = $request->source_id;
        $video->thematique_id = json_encode($request->thematique_id);
        $video->description = $request->description;
        $video->type = $request->type;
        $video->statut = $statut;
        $video->title = $request->title;
        $video->auteur = $request->auteur;
        $video->code_media = $request->code_media;
    
        // Enregistrer les modifications dans la base de données
        $video->save();
    
        return redirect()->route('videos.index')->with('message', 'Mise à jour effectuée avec succès');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Media::find($id);
        $video->delete();

        return redirect()->route('videos.index')
            ->with('message', 'video supprimée!!!');
    }

    public function desactivate($id){
        $video = Media::find($id);
        $video->update([
            'statut'=> 0
        ]);
        return back();
    }
    public function activate($id){
        $video = Media::find($id);
        $video->update([
            'statut'=> 1
        ]);
        return back();
    }

    public function localisationIndex($id){
        $video = Media::find($id);
        return view('video.localisation', compact('video'));
    }

    public function addLocalisation(Request $request){
       $getLocalisationId = $request->localisation_id;
       Media::where('id',$getLocalisationId)->update([
        'localisation'=>$request->localisation
      ]);
      return redirect()->route('videos.index')->with('message','Localisation Ajoutée !!');

    }

    public function removeLocalisation(Request $request)
    {
        $getLocalisationId = $request->localisation_id;
       Media::where('id',$getLocalisationId)->update([
        'localisation'=> null
      ]);

        return redirect()->route('videos.index')
            ->with('message', 'localisation supprimée!!!');
    }

}
