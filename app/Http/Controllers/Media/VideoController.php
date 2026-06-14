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

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::isvideo()->idDescending();

        if ($request->get('status') === 'published') {
            $query->where('statut', 1);
        } elseif ($request->get('status') === 'draft') {
            $query->where('statut', 0);
        }

        $videos = $query->paginate(10)->withQueryString();

        return view('video.index', compact('videos'));
    }

    public function create()
    {
        $sources = Source::all();
        $thematiques = Thematique::all();

        return view('video.create', compact('sources', 'thematiques'));
    }

    public function store(MediaRequest $request)
    {
        $statut = $request->has('statut') ? 1 : 0;

        $video = Media::create([
            'user_id' => Auth::id(),
            'thematique_id' => json_encode($request->thematique_id),
            'source_id' => $request->source_id,
            'description' => $request->description,
            'type' => 1,
            'statut' => $statut,
            'media' => $request->media,
            'title' => $request->title,
            'auteur' => $request->auteur,
            'code_media' => MediaCodeGenerator::generate(1),
        ]);

        return redirect()
            ->route('public.videos.show', $video)
            ->with('message', 'Vidéo enregistrée. Voici l\'aperçu tel qu\'elle apparaîtra au public.');
    }

    public function show(Media $video)
    {
        $viewCount = ContentView::where('viewable_type', Media::class)
            ->where('viewable_id', $video->id)
            ->where('action', 'view')
            ->count();

        return view('video.show', compact('video', 'viewCount'));
    }

    public function edit($id)
    {
        $video = Media::findOrFail($id);
        $sources = Source::all();
        $thematiques = Thematique::all();

        return view('video.edit', compact('sources', 'thematiques', 'video'));
    }

    public function update(MediaRequest $request, $id)
    {
        $video = Media::findOrFail($id);
        $statut = $request->has('statut') ? 1 : 0;

        $video->update([
            'user_id' => Auth::id(),
            'source_id' => $request->source_id,
            'thematique_id' => json_encode($request->thematique_id),
            'description' => $request->description,
            'type' => 1,
            'statut' => $statut,
            'media' => $request->media,
            'title' => $request->title,
            'auteur' => $request->auteur,
        ]);

        if (! $video->code_media) {
            $video->update(['code_media' => MediaCodeGenerator::generate(1)]);
        }

        return redirect()
            ->route('public.videos.show', $video)
            ->with('message', 'Vidéo mise à jour. Voici l\'aperçu public.');
    }

    public function destroy(Request $request, $id)
    {
        Media::findOrFail($id)->delete();

        if ($request->input('redirect') === 'public') {
            return redirect()->route('home')->with('success', 'Vidéo supprimée.');
        }

        return redirect()->route('videos.index')->with('message', 'Vidéo supprimée');
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
        $video = Media::findOrFail($id);

        return view('video.localisation', compact('video'));
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

        return redirect()->route('videos.index')->with('message', 'Localisation ajoutée');
    }

    public function removeLocalisation($id)
    {
        Media::findOrFail($id)->update(['localisation' => null]);

        return redirect()->route('videos.index')->with('message', 'Localisation supprimée');
    }

    public function report(Request $request, $id)
    {
        $video = Media::findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:10|max:2000',
        ]);

        MediaReport::create([
            'media_id' => $video->id,
            'user_id' => Auth::id(),
            'reporter_name' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
            'reporter_email' => Auth::user()->email,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Signalement enregistré.');
    }
}
