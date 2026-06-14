<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicMediaSubmissionRequest;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use App\Models\User;
use App\Support\CoverImageStorage;
use App\Support\MediaCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaSubmissionController extends Controller
{
    public function createAudio()
    {
        return $this->form('audio');
    }

    public function createVideo()
    {
        return $this->form('video');
    }

    public function storeAudio(PublicMediaSubmissionRequest $request)
    {
        return $this->store($request, 0);
    }

    public function storeVideo(PublicMediaSubmissionRequest $request)
    {
        return $this->store($request, 1);
    }

    private function form(string $type)
    {
        $sources = Source::orderBy('label')->get();
        $thematiques = Thematique::orderBy('label')->get();
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

        return view('contrib.submit-media', compact('sources', 'thematiques', 'type', 'isAdmin'));
    }

    private function store(PublicMediaSubmissionRequest $request, int $type): \Illuminate\Http\RedirectResponse
    {
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $published = $isAdmin && $request->has('statut');
        $ownerId = $isAdmin ? Auth::id() : User::whereIn('role_id', [1, 2])->value('id');

        if (! $ownerId) {
            return back()->with('error', 'Impossible de soumettre pour le moment. Contactez l\'administrateur.')->withInput();
        }

        $pictureFile = $request->hasFile('picture')
            ? CoverImageStorage::store($request->file('picture'))
            : null;

        $media = Media::create([
            'user_id' => $ownerId,
            'thematique_id' => json_encode($request->thematique_id),
            'source_id' => $request->source_id,
            'description' => $request->description,
            'type' => $type,
            'statut' => $published ? 1 : 0,
            'media' => $request->media,
            'picture' => $pictureFile,
            'title' => $request->title,
            'auteur' => $request->auteur,
            'code_media' => MediaCodeGenerator::generate($type),
            'is_guest_submission' => ! $isAdmin,
            'submitter_name' => $isAdmin ? null : $request->submitter_name,
            'submitter_email' => $isAdmin ? null : $request->submitter_email,
        ]);

        if ($published) {
            return redirect()
                ->route($type === 0 ? 'public.audios.show' : 'public.videos.show', $media)
                ->with('message', 'Contenu publié avec succès.');
        }

        return redirect()
            ->route('home')
            ->with('success', 'Votre contribution a été envoyée. Elle sera visible après validation par un administrateur.');
    }
}
