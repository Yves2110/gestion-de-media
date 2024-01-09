<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function store(Request $request)
    {
   
       
    $statut = $request->has('statut') ? 1 : 0;
     $input = $request->all();
      
    //   if ($video = $request->file('media')) {
    //         $destinationPath = 'video/';
    //         $media = date('YmdHis') . "." . $video->getClientOriginalExtension();
    //         $video->move($destinationPath, $media);
    //         $input['media'] = "$media";
    //     }

    Media::create([
        'user_id'=>$request->user_id,
        'source_id'=>$request->source_id,
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
    return redirect()->route('videos.index')->with('message','Enregistrement effectué avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
   
    
    public function update(Request $request, $id)
    {
        $video = Media::findOrFail($id);
    
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'source_id' => 'required|integer|exists:sources,id',
            'thematique_id' => 'required|array',
            'thematique_id.*' => 'integer|exists:thematiques,id',
            'description' => 'nullable|string|max:255',
            'type' => 'required', // Ajoutez ici les valeurs valides pour le champ type
            'statut' => 'boolean',
            'video' => 'required', // Ajoutez ici les formats de fichier audio autorisés
            'title' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'code_media' => 'required|string|max:255',
        ]);
    
        // Mettre à jour le statut
        $statut = $request->has('statut') ? 1 : 0;
    
        // Mettre à jour le fichier audio si un nouveau fichier est fourni
        if ($videoFile = $request->file('video')) {
            // Supprimer le fichier audio actuel s'il existe
            if (Storage::disk('public')->exists('video/' . $video->video)) {
                Storage::disk('public')->delete('video/' . $video->video);
            }
    
            // Enregistrer le nouveau fichier audio
            $destinationPath = 'video/';
            $media = date('YmdHis') . "." . $videoFile->getClientOriginalExtension();
            $videoFile->move($destinationPath, $media);
            $video->video = $media;
        }
    
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
}
