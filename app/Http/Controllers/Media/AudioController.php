<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaRequest;
use App\Models\ContentView;
use App\Models\Media;
use App\Models\MediaReport;
use App\Models\Source;
use App\Models\Thematique;
use App\Support\MediaCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::isAudio()->idDescending();

        if ($request->get('status') === 'published') {
            $query->where('statut', 1);
        } elseif ($request->get('status') === 'draft') {
            $query->where('statut', 0);
        }

        $audios = $query->paginate(10)->withQueryString();

        return view('audio.index', compact('audios'));
    }

    public function create()
    {
        $sources = Source::all();
        $thematiques = Thematique::all();

        return view('audio.create', compact('sources', 'thematiques'));
    }

    public function store(MediaRequest $request)
    {
        $statut = $request->has('statut') ? 1 : 0;

        $audio = Media::create([
            'user_id' => Auth::id(),
            'thematique_id' => json_encode($request->thematique_id),
            'source_id' => $request->source_id,
            'description' => $request->description,
            'type' => 0,
            'statut' => $statut,
            'media' => $request->media,
            'title' => $request->title,
            'auteur' => $request->auteur,
            'code_media' => MediaCodeGenerator::generate(0),
        ]);

        return redirect()
            ->route('public.audios.show', $audio)
            ->with('message', 'Audio enregistré. Voici l\'aperçu tel qu\'il apparaîtra au public.');
    }

    public function show(Media $audio)
    {
        $viewCount = ContentView::where('viewable_type', Media::class)
            ->where('viewable_id', $audio->id)
            ->where('action', 'view')
            ->count();

        return view('audio.show', compact('audio', 'viewCount'));
    }

    public function edit($id)
    {
        $audio = Media::findOrFail($id);
        $sources = Source::all();
        $thematiques = Thematique::all();

        return view('audio.edit', compact('sources', 'thematiques', 'audio'));
    }

    public function update(MediaRequest $request, $id)
    {
        $audio = Media::findOrFail($id);
        $statut = $request->has('statut') ? 1 : 0;

        $audio->update([
            'user_id' => Auth::id(),
            'source_id' => $request->source_id,
            'thematique_id' => json_encode($request->thematique_id),
            'description' => $request->description,
            'type' => 0,
            'statut' => $statut,
            'media' => $request->media,
            'title' => $request->title,
            'auteur' => $request->auteur,
        ]);

        if (! $audio->code_media) {
            $audio->update(['code_media' => MediaCodeGenerator::generate(0)]);
        }

        return redirect()
            ->route('public.audios.show', $audio)
            ->with('message', 'Audio mis à jour. Voici l\'aperçu public.');
    }

    public function destroy(Request $request, $id)
    {
        Media::findOrFail($id)->delete();

        if ($request->input('redirect') === 'public') {
            return redirect()->route('home')->with('success', 'Audio supprimé.');
        }

        return redirect()->route('audios.index')->with('message', 'Audio supprimé');
    }

    public function desactivate($id)
    {
        Media::findOrFail($id)->update(['statut' => 0]);

        return back();
    }

    public function activate($id)
    {
        Media::findOrFail($id)->update(['statut' => 1]);

        return back();
    }

    public function localisationIndex($id)
    {
        $audio = Media::findOrFail($id);

        return view('audio.localisation', compact('audio'));
    }

    public function addLocalisation(Request $request)
    {
        $request->validate([
            'localisation_id' => 'required|integer|exists:media,id',
            'localisation' => 'required|string',
        ]);

        Media::where('id', $request->localisation_id)->update([
            'localisation' => $request->localisation,
        ]);

        return redirect()->route('audios.index')->with('message', 'Localisation ajoutée');
    }

    public function removeLocalisation($id)
    {
        Media::findOrFail($id)->update(['localisation' => null]);

        return redirect()->route('audios.index')->with('message', 'Localisation supprimée');
    }

    public function report(Request $request, $id)
    {
        $audio = Media::findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:10|max:2000',
        ]);

        MediaReport::create([
            'media_id' => $audio->id,
            'user_id' => Auth::id(),
            'reporter_name' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
            'reporter_email' => Auth::user()->email,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Signalement enregistré.');
    }
}
