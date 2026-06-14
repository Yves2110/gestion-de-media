<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ContentView;
use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use App\Support\DocumentStorage;
use App\Support\VisitorActivity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query !== '') {
            return redirect()->route('catalogue.audios', ['q' => $query]);
        }

        $recentAudios = $this->publishedAudiosQuery($request)->with('source')->latest()->limit(6)->get();
        $recentVideos = $this->publishedVideosQuery($request)->with('source')->latest()->limit(6)->get();
        $recentDocuments = $this->publishedDocumentsQuery($request)->with('source')->latest()->limit(6)->get();

        return view('client.index', compact('recentAudios', 'recentVideos', 'recentDocuments', 'query'));
    }

    public function audios(Request $request)
    {
        $sources = Source::orderBy('label')->get();
        $thematiques = Thematique::orderBy('label')->get();
        $audios = $this->publishedAudiosQuery($request)->with('source')->paginate(6);

        return view('client.audios', compact('audios', 'sources', 'thematiques'));
    }

    public function videos(Request $request)
    {
        $sources = Source::orderBy('label')->get();
        $thematiques = Thematique::orderBy('label')->get();
        $videos = $this->publishedVideosQuery($request)->with('source')->paginate(6);

        return view('client.videos', compact('videos', 'sources', 'thematiques'));
    }

    public function documents(Request $request)
    {
        $sources = Source::orderBy('label')->get();
        $thematiques = Thematique::orderBy('label')->get();
        $documents = $this->publishedDocumentsQuery($request)->with('source')->paginate(6);

        return view('client.documents', compact('documents', 'sources', 'thematiques'));
    }

    public function show(string $type, string $uuid)
    {
        if ($type === 'audio') {
            $item = Media::with('source')->isAudio()->where('statut', 1)->where('uuid', $uuid)->firstOrFail();
            ContentView::record($item, 'view');
            VisitorActivity::recordModel($item, 'view');
            return view('client.show-media', ['item' => $item, 'type' => 'audio']);
        }

        if ($type === 'video') {
            $item = Media::with('source')->isvideo()->where('statut', 1)->where('uuid', $uuid)->firstOrFail();
            ContentView::record($item, 'view');
            VisitorActivity::recordModel($item, 'view');
            return view('client.show-media', ['item' => $item, 'type' => 'video']);
        }

        if ($type === 'document') {
            $item = Document::with('source')->where('statut_publication', 1)->where('uuid', $uuid)->firstOrFail();
            ContentView::record($item, 'view');
            VisitorActivity::recordModel($item, 'view');
            return view('client.show-document', ['document' => $item]);
        }

        abort(404);
    }

    public function downloadDocument(Document $document): StreamedResponse
    {
        abort_unless($document->statut_publication === 1, 403);
        abort_unless($document->file_doc && DocumentStorage::exists($document->file_doc), 404);

        ContentView::record($document, 'download');

        return DocumentStorage::download($document->file_doc, $document->file_doc);
    }

    private function publishedAudiosQuery(Request $request)
    {
        $query = Media::isAudio()->where('statut', 1);
        $this->applyMediaFilters($query, $request);

        return $query;
    }

    private function publishedVideosQuery(Request $request)
    {
        $query = Media::isvideo()->where('statut', 1);
        $this->applyMediaFilters($query, $request);

        return $query;
    }

    private function publishedDocumentsQuery(Request $request)
    {
        $query = Document::where('statut_publication', 1);

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('auteur', 'like', "%{$search}%")
                    ->orWhere('resume', 'like', "%{$search}%");
            });
        }

        if ($request->filled('source_id')) {
            $query->where('source_id', $request->get('source_id'));
        }

        if ($request->filled('thematique_id')) {
            $query->where('thematique_id', 'like', '%"' . $request->get('thematique_id') . '"%');
        }

        return $query;
    }

    private function applyMediaFilters($query, Request $request): void
    {
        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('auteur', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('source_id')) {
            $query->where('source_id', $request->get('source_id'));
        }

        if ($request->filled('thematique_id')) {
            $query->where('thematique_id', 'like', '%"' . $request->get('thematique_id') . '"%');
        }
    }
}
