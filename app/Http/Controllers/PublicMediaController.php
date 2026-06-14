<?php

namespace App\Http\Controllers;

use App\Models\ContentView;
use App\Models\Media;
use App\Models\MediaReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicMediaController extends Controller
{
    public function showAudio(Media $media)
    {
        abort_unless($media->type === 0, 404);

        return $this->render($media, 'audio');
    }

    public function showVideo(Media $media)
    {
        abort_unless($media->type === 1, 404);

        return $this->render($media, 'video');
    }

    public function report(Request $request, Media $media)
    {
        abort_unless(in_array($media->type, [0, 1], true), 404);
        abort_unless($media->statut, 404);

        $request->validate([
            'message' => 'required|string|min:10|max:2000',
            'reporter_name' => 'nullable|string|max:120',
            'reporter_email' => 'nullable|email|max:190',
        ]);

        MediaReport::create([
            'media_id' => $media->id,
            'user_id' => Auth::id(),
            'reporter_name' => Auth::check()
                ? Auth::user()->firstname . ' ' . Auth::user()->lastname
                : $request->reporter_name,
            'reporter_email' => Auth::check() ? Auth::user()->email : $request->reporter_email,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Signalement enregistré. Merci pour votre retour.');
    }

    private function render(Media $media, string $type)
    {
        $this->authorizeView($media);

        $media->load('source');

        if ($media->statut) {
            ContentView::record($media, 'view');
        }

        return view('home.media-show', [
            'item' => $media,
            'type' => $type,
            'isPreview' => ! $media->statut,
        ]);
    }

    private function authorizeView(Media $media): void
    {
        if ($media->statut) {
            return;
        }

        abort_unless(Auth::check() && Auth::user()->isAdmin(), 404);
    }
}
