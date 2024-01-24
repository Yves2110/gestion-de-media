<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('audio.index');
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
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'auteur'=>'required',
            'description'=>'required',
            'user_id'=>'required',
            'thematique_id'=>'required',
            'source_id'=>'required',
            'audio'=>'required',
            'code_media'=>'required',
            'statut'=>'required',
            'type'=>'required',
            
        ]);
      
    $audioPath = $request->file('audio')->store('audio');

    Media::create([
        'title' => $request->title,
        'description' => $request->description,
        'auteur' => $request->auteur,
        'user_id' => Auth::user()->id, // Utilisation de optional pour éviter une erreur si l'utilisateur n'est pas authentifié
        'thematique_id' => $request->thematique_id,
        'source_id' => $request->source_id,
        'audio' => $audioPath,
        'statut' => $request->input('statut', 0), // Valeur par défaut 0 si non fournie
        'type' => $request->type,
    ]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
