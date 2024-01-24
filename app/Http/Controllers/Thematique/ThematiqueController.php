<?php

namespace App\Http\Controllers\Thematique;

use App\Http\Controllers\Controller;
use App\Models\Thematique;
use Illuminate\Http\Request;

class ThematiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $thematiques = Thematique::idDescending()->paginate(4);
        return view('thematiqueManage.index',compact('thematiques'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('thematiqueManage.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $thematique =$request->validate([
            'label'=> 'required|string'
        ]);
        Thematique::create($thematique);
        return redirect()->route('thematique.index')->with('message','La source à été ajouté !!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Thematique $thematique)
    {
        return view('thematiqueManage.show',compact('thematique'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Thematique $thematique)
    {
        return view('thematiqueManage.edit',compact('thematique'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thematique $thematique)
    {
        $request->validate([
            'label' => 'required',
        ]);
      
        $thematique->update($request->all());
      
        return redirect()->route('thematique.index')
                        ->with('message','Thématique mis à jour !!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thematique $thematique)
    {
        $thematique->delete();
       
        return redirect()->route('thematique.index')
                        ->with('message','thematique supprimée!!!');
    }
}
