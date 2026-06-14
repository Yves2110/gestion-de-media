<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private const PUBLIC_PER_PAGE = 8;

    public function index(Request $request)
    {
        $typeFilter = $this->resolveTypeFilter($request);

        $stats = [
            'audios' => Media::isAudio()->where('statut', 1)->count(),
            'videos' => Media::isvideo()->where('statut', 1)->count(),
            'documents' => Document::where('statut_publication', 1)->count(),
            'clients' => User::where('role_id', 3)->where('statut', 1)->count(),
        ];

        $publications = $this->paginatePublications($request, $typeFilter);

        $heroImages = $this->buildHeroImages();

        $sectionTitle = match ($typeFilter) {
            'documents' => 'Documents',
            'videos' => 'Ressources vidéos',
            'audios' => 'Ressources audios',
            default => 'Les dernières publications',
        };

        $sectionCount = match ($typeFilter) {
            'documents' => $stats['documents'],
            'videos' => $stats['videos'],
            'audios' => $stats['audios'],
            default => $stats['audios'] + $stats['videos'] + $stats['documents'],
        };

        return view('home.index', compact(
            'stats',
            'publications',
            'typeFilter',
            'sectionTitle',
            'sectionCount',
            'heroImages'
        ));
    }

    private function resolveTypeFilter(Request $request): ?string
    {
        $type = $request->query('type');

        return in_array($type, ['documents', 'videos', 'audios'], true) ? $type : null;
    }

    private function paginatePublications(Request $request, ?string $typeFilter): LengthAwarePaginator
    {
        if ($typeFilter === 'documents') {
            return $this->paginateDocuments($request);
        }

        if ($typeFilter === 'videos') {
            return $this->paginateVideos($request);
        }

        if ($typeFilter === 'audios') {
            return $this->paginateAudios($request);
        }

        return $this->paginateLatestPublications($request);
    }

    private function paginateDocuments(Request $request): LengthAwarePaginator
    {
        $paginator = Document::where('statut_publication', 1)
            ->with('source')
            ->latest()
            ->paginate(self::PUBLIC_PER_PAGE, ['*'], 'page')
            ->withQueryString();

        return $paginator->through(fn (Document $document) => [
            'type' => 'document',
            'item' => $document,
            'title' => $document->title,
            'auteur' => $document->auteur,
            'date' => $document->created_at,
            'thumbnail' => $document->picture
                ? asset('storage/picture/' . $document->picture)
                : null,
            'url' => route('public.documents.show', $document->id),
        ]);
    }

    private function paginateVideos(Request $request): LengthAwarePaginator
    {
        $paginator = Media::isvideo()
            ->where('statut', 1)
            ->with('source')
            ->latest()
            ->paginate(self::PUBLIC_PER_PAGE, ['*'], 'page')
            ->withQueryString();

        return $paginator->through(fn (Media $video) => [
            'type' => 'video',
            'item' => $video,
            'title' => $video->title,
            'auteur' => $video->auteur,
            'date' => $video->created_at,
            'thumbnail' => $video->thumbnail_url,
            'description' => Str::limit(strip_tags($video->description ?? ''), 160),
            'url' => route('public.videos.show', $video->id),
        ]);
    }

    private function paginateAudios(Request $request): LengthAwarePaginator
    {
        $paginator = Media::isAudio()
            ->where('statut', 1)
            ->with('source')
            ->latest()
            ->paginate(self::PUBLIC_PER_PAGE, ['*'], 'page')
            ->withQueryString();

        return $paginator->through(fn (Media $audio) => [
            'type' => 'audio',
            'item' => $audio,
            'title' => $audio->title,
            'auteur' => $audio->auteur,
            'date' => $audio->created_at,
            'thumbnail' => $audio->thumbnail_url,
            'url' => route('public.audios.show', $audio->id),
        ]);
    }

    private function paginateLatestPublications(Request $request): LengthAwarePaginator
    {
        $perPage = self::PUBLIC_PER_PAGE;
        $pageName = 'page';
        $page = Paginator::resolveCurrentPage($pageName);

        $entries = collect()
            ->merge(
                Media::isAudio()->where('statut', 1)->select('id', 'created_at')->get()
                    ->map(fn ($item) => ['type' => 'audio', 'id' => $item->id, 'date' => $item->created_at])
            )
            ->merge(
                Media::isvideo()->where('statut', 1)->select('id', 'created_at')->get()
                    ->map(fn ($item) => ['type' => 'video', 'id' => $item->id, 'date' => $item->created_at])
            )
            ->merge(
                Document::where('statut_publication', 1)->select('id', 'created_at')->get()
                    ->map(fn ($item) => ['type' => 'document', 'id' => $item->id, 'date' => $item->created_at])
            )
            ->sortByDesc('date')
            ->values();

        $slice = $entries->slice(($page - 1) * $perPage, $perPage)->values();
        $publications = $this->hydratePublications($slice);

        return new LengthAwarePaginator(
            $publications,
            $entries->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'pageName' => $pageName,
                'query' => $request->query(),
            ]
        );
    }

    private function hydratePublications(Collection $entries): Collection
    {
        $items = collect();

        foreach ($entries as $entry) {
            if ($entry['type'] === 'audio') {
                $audio = Media::with('source')->isAudio()->find($entry['id']);
                if (! $audio) {
                    continue;
                }
                $items->push([
                    'type' => 'audio',
                    'item' => $audio,
                    'title' => $audio->title,
                    'auteur' => $audio->auteur,
                    'date' => $audio->created_at,
                    'thumbnail' => $audio->thumbnail_url,
                    'url' => $this->publicationUrl('audio', $audio->id),
                ]);
            } elseif ($entry['type'] === 'video') {
                $video = Media::with('source')->isvideo()->find($entry['id']);
                if (! $video) {
                    continue;
                }
                $items->push([
                    'type' => 'video',
                    'item' => $video,
                    'title' => $video->title,
                    'auteur' => $video->auteur,
                    'date' => $video->created_at,
                    'thumbnail' => $video->thumbnail_url,
                    'description' => Str::limit(strip_tags($video->description ?? ''), 160),
                    'url' => $this->publicationUrl('video', $video->id),
                ]);
            } else {
                $document = Document::with('source')->find($entry['id']);
                if (! $document) {
                    continue;
                }
                $items->push([
                    'type' => 'document',
                    'item' => $document,
                    'title' => $document->title,
                    'auteur' => $document->auteur,
                    'date' => $document->created_at,
                    'thumbnail' => $document->picture
                        ? asset('storage/picture/' . $document->picture)
                        : null,
                    'url' => $this->publicationUrl('document', $document->id),
                ]);
            }
        }

        return $items;
    }

    private function buildHeroImages(): array
    {
        $placeholders = $this->heroPlaceholders();

        $covers = collect()
            ->merge(Document::where('statut_publication', 1)->latest()->limit(6)->get())
            ->merge(Media::where('statut', 1)->whereNotNull('picture')->where('picture', '!=', '')->latest()->limit(6)->get())
            ->sortByDesc('created_at')
            ->take(6)
            ->filter(fn ($item) => $item->picture && Storage::disk('public')->exists('picture/' . $item->picture))
            ->map(fn ($item) => asset('storage/picture/' . $item->picture))
            ->values()
            ->all();

        $images = [];
        for ($i = 0; $i < 6; $i++) {
            $images[] = $covers[$i] ?? $placeholders[$i];
        }

        return $images;
    }

    private function heroPlaceholders(): array
    {
        return array_map(
            fn ($index) => asset('assets/images/hero/hero-' . $index . '.svg'),
            range(1, 6)
        );
    }

    private function publicationUrl(string $type, int $id): string
    {
        return match ($type) {
            'document' => route('public.documents.show', $id),
            'audio' => route('public.audios.show', $id),
            'video' => route('public.videos.show', $id),
            default => route('home'),
        };
    }
}
