<?php

namespace App\Support;

use App\Models\Category;
use App\Models\ContentView;
use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LearningResourceSearch
{
    private const PER_PAGE = 12;

    public function filterOptions(): array
    {
        return [
            'sources' => Source::orderBy('label')->get(),
            'thematiques' => Thematique::orderBy('label')->get(),
            'categories' => Category::orderBy('label')->get(),
        ];
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $types = $this->resolveTypes($request);
        $sort = $this->resolveSort($request);
        $query = trim((string) $request->get('q', ''));

        $entries = collect();

        if (in_array('audio', $types, true)) {
            $entries = $entries->merge(
                $this->mapMediaEntries($this->filteredMediaQuery($request, true)->get(), 'audio', $query, $sort)
            );
        }

        if (in_array('video', $types, true)) {
            $entries = $entries->merge(
                $this->mapMediaEntries($this->filteredMediaQuery($request, false)->get(), 'video', $query, $sort)
            );
        }

        if (in_array('document', $types, true)) {
            $entries = $entries->merge(
                $this->mapDocumentEntries($this->filteredDocumentsQuery($request)->get(), $query, $sort)
            );
        }

        $entries = $this->sortEntries($entries, $sort, $query)->values();

        return $this->paginateEntries($entries, $request);
    }

    public function suggestions(string $term, int $limit = 8): array
    {
        $term = trim($term);

        if (mb_strlen($term) < 2) {
            return $this->buildCuratedSuggestionResponse();
        }

        $like = '%' . $term . '%';

        $titles = collect()
            ->merge(
                Media::isAudio()->where('statut', 1)->where('title', 'like', $like)->limit($limit)->pluck('title')
            )
            ->merge(
                Media::isvideo()->where('statut', 1)->where('title', 'like', $like)->limit($limit)->pluck('title')
            )
            ->merge(
                Document::where('statut_publication', 1)->where('title', 'like', $like)->limit($limit)->pluck('title')
            )
            ->unique()
            ->take($limit)
            ->values()
            ->all();

        $authors = collect()
            ->merge(Media::where('statut', 1)->where('auteur', 'like', $like)->distinct()->limit(5)->pluck('auteur'))
            ->merge(Document::where('statut_publication', 1)->where('auteur', 'like', $like)->distinct()->limit(5)->pluck('auteur'))
            ->filter()
            ->unique()
            ->take(5)
            ->values()
            ->all();

        $thematiques = Thematique::where('label', 'like', $like)
            ->orderBy('label')
            ->limit(5)
            ->get(['id', 'label'])
            ->map(fn (Thematique $item) => ['id' => $item->id, 'label' => $item->label])
            ->all();

        $sources = Source::where('label', 'like', $like)
            ->orderBy('label')
            ->limit(5)
            ->get(['id', 'label'])
            ->map(fn (Source $item) => ['id' => $item->id, 'label' => $item->label])
            ->all();

        $resources = $this->search($this->requestWithQuery($term))
            ->getCollection()
            ->take(5)
            ->map(fn (array $item) => [
                'type' => $item['type'],
                'title' => $item['title'],
                'auteur' => $item['auteur'],
                'url' => $item['url'],
            ])
            ->all();

        return [
            'mode' => 'search',
            'quick_searches' => [],
            'titles' => $titles,
            'authors' => $authors,
            'thematiques' => $thematiques,
            'sources' => $sources,
            'resources' => $resources,
        ];
    }

    private function buildCuratedSuggestionResponse(): array
    {
        $curated = $this->curatedSuggestions();

        $resources = collect($curated['recent'])
            ->merge($curated['popular'])
            ->unique(fn (array $item) => $item['url'])
            ->take(6)
            ->map(fn (array $item) => [
                'type' => $item['type'],
                'title' => $item['title'],
                'auteur' => $item['auteur'],
                'url' => $item['url'],
            ])
            ->values()
            ->all();

        return [
            'mode' => 'curated',
            'quick_searches' => $curated['quick_searches'],
            'titles' => [],
            'authors' => [],
            'thematiques' => $curated['featured_thematiques']
                ->map(fn (Thematique $item) => ['id' => $item->id, 'label' => $item->label])
                ->all(),
            'sources' => Source::orderBy('label')
                ->limit(6)
                ->get(['id', 'label'])
                ->map(fn (Source $item) => ['id' => $item->id, 'label' => $item->label])
                ->all(),
            'resources' => $resources,
        ];
    }

    public function suggestionTopics(): array
    {
        return [
            ['label' => 'Intelligence artificielle', 'q' => 'intelligence artificielle', 'types' => ['video']],
            ['label' => 'Machine learning', 'q' => 'machine learning', 'types' => ['video']],
            ['label' => 'Méthode scientifique', 'q' => 'méthode scientifique', 'types' => ['audio', 'document']],
            ['label' => 'Conseils nutrition', 'q' => 'nutrition', 'types' => ['audio', 'document']],
            ['label' => 'Changement climatique', 'q' => 'climat', 'types' => ['audio']],
            ['label' => 'Technologie', 'q' => 'technologie', 'types' => ['video']],
        ];
    }

    public function curatedSuggestions(): array
    {
        return [
            'quick_searches' => $this->popularThematiqueLabels(6),
            'featured_thematiques' => Thematique::orderBy('label')->limit(8)->get(['id', 'label']),
            'recent' => $this->search($this->requestWithSort('recent'))->getCollection()->take(4)->all(),
            'popular' => $this->search($this->requestWithSort('popular'))->getCollection()->take(4)->all(),
        ];
    }

    public function activeFilterCount(Request $request): int
    {
        $count = 0;

        if (trim((string) $request->get('q', '')) !== '') {
            $count++;
        }

        $types = $this->resolveTypes($request);
        if (count($types) < 3) {
            $count++;
        }

        $count += count(array_filter((array) $request->input('source_id', [])));
        $count += count(array_filter((array) $request->input('thematique_id', [])));
        $count += count(array_filter((array) $request->input('category_id', [])));

        if ($this->resolveSort($request) !== 'recent') {
            $count++;
        }

        return $count;
    }

    private function resolveTypes(Request $request): array
    {
        $all = ['audio', 'video', 'document'];
        $types = array_values(array_filter((array) $request->input('types', [])));

        $types = array_values(array_intersect($types, $all));

        return $types !== [] ? $types : $all;
    }

    private function resolveSort(Request $request): string
    {
        $sort = (string) $request->get('sort', 'recent');

        return in_array($sort, ['recent', 'relevance', 'popular'], true) ? $sort : 'recent';
    }

    private function filteredMediaQuery(Request $request, bool $audio): Builder
    {
        $query = $audio
            ? Media::isAudio()->where('statut', 1)
            : Media::isvideo()->where('statut', 1);

        $this->applySearch($query, $request, ['title', 'auteur', 'description']);
        $this->applySourceFilter($query, $request);
        $this->applyThematiqueFilter($query, $request);

        return $query->with('source');
    }

    private function filteredDocumentsQuery(Request $request): Builder
    {
        $query = Document::where('statut_publication', 1);

        $this->applySearch($query, $request, ['title', 'auteur', 'resume']);
        $this->applySourceFilter($query, $request);
        $this->applyThematiqueFilter($query, $request);
        $this->applyCategoryFilter($query, $request);

        return $query->with(['source', 'category']);
    }

    private function applySearch(Builder $query, Request $request, array $columns): void
    {
        $search = trim((string) $request->get('q', ''));

        if ($search === '') {
            return;
        }

        $query->where(function (Builder $builder) use ($search, $columns) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $builder->{$method}($column, 'like', '%' . $search . '%');
            }
        });
    }

    private function applySourceFilter(Builder $query, Request $request): void
    {
        $sourceIds = array_values(array_filter((array) $request->input('source_id', [])));

        if ($sourceIds !== []) {
            $query->whereIn('source_id', $sourceIds);
        }
    }

    private function applyThematiqueFilter(Builder $query, Request $request): void
    {
        $thematiqueIds = array_values(array_filter((array) $request->input('thematique_id', [])));

        if ($thematiqueIds === []) {
            return;
        }

        $query->where(function (Builder $builder) use ($thematiqueIds) {
            foreach ($thematiqueIds as $index => $id) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $builder->{$method}(function (Builder $nested) use ($id) {
                    $nested->whereHas('thematiques', fn (Builder $relation) => $relation->where('thematiques.id', $id))
                        ->orWhere('thematique_id', 'like', '%"' . $id . '"%');
                });
            }
        });
    }

    private function applyCategoryFilter(Builder $query, Request $request): void
    {
        $categoryIds = array_values(array_filter((array) $request->input('category_id', [])));

        if ($categoryIds !== []) {
            $query->whereIn('category_id', $categoryIds);
        }
    }

    private function mapMediaEntries(Collection $items, string $type, string $query, string $sort): Collection
    {
        return $items->map(function (Media $item) use ($type, $query, $sort) {
            return [
                'type' => $type,
                'item' => $item,
                'title' => $item->title,
                'auteur' => $item->auteur,
                'source' => $item->source?->label,
                'description' => $type === 'video'
                    ? Str::limit(strip_tags($item->description ?? ''), 160)
                    : null,
                'date' => $item->created_at,
                'thumbnail' => $item->thumbnail_url,
                'url' => route('public.' . $type . 's.show', $item),
                'score' => $this->relevanceScore($query, $item->title, $item->auteur, $item->description),
                'views' => $this->viewCount($item),
            ];
        });
    }

    private function mapDocumentEntries(Collection $items, string $query, string $sort): Collection
    {
        return $items->map(function (Document $item) use ($query) {
            return [
                'type' => 'document',
                'item' => $item,
                'title' => $item->title,
                'auteur' => $item->auteur,
                'source' => $item->source?->label,
                'description' => Str::limit(strip_tags($item->resume ?? ''), 160),
                'date' => $item->created_at,
                'thumbnail' => $item->picture ? asset('storage/picture/' . $item->picture) : null,
                'url' => route('public.documents.show', $item),
                'score' => $this->relevanceScore($query, $item->title, $item->auteur, $item->resume),
                'views' => $this->viewCount($item),
            ];
        });
    }

    private function sortEntries(Collection $entries, string $sort, string $query): Collection
    {
        return match ($sort) {
            'relevance' => $entries->sortByDesc(fn (array $item) => $item['score'])->values(),
            'popular' => $entries->sortByDesc(fn (array $item) => $item['views'])->values(),
            default => $entries->sortByDesc(fn (array $item) => $item['date'])->values(),
        };
    }

    private function paginateEntries(Collection $entries, Request $request): LengthAwarePaginator
    {
        $pageName = 'page';
        $page = Paginator::resolveCurrentPage($pageName);
        $perPage = self::PER_PAGE;
        $slice = $entries->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
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

    private function relevanceScore(string $query, ?string $title, ?string $author, ?string $body): int
    {
        if ($query === '') {
            return 0;
        }

        $needle = mb_strtolower($query);
        $score = 0;

        $titleLower = mb_strtolower((string) $title);
        $authorLower = mb_strtolower((string) $author);
        $bodyLower = mb_strtolower(strip_tags((string) $body));

        if ($titleLower === $needle) {
            $score += 100;
        } elseif (str_contains($titleLower, $needle)) {
            $score += 50;
        }

        if (str_contains($authorLower, $needle)) {
            $score += 30;
        }

        if (str_contains($bodyLower, $needle)) {
            $score += 10;
        }

        return $score;
    }

    private function viewCount(object $item): int
    {
        return ContentView::query()
            ->where('viewable_type', get_class($item))
            ->where('viewable_id', $item->getKey())
            ->where('action', 'view')
            ->count();
    }

    private function popularThematiqueLabels(int $limit): array
    {
        $labels = Thematique::orderBy('label')->limit($limit)->pluck('label')->all();

        if ($labels !== []) {
            return $labels;
        }

        return array_slice([
            'Technologie',
            'Éducation',
            'Santé',
            'Environnement',
            'Intelligence artificielle',
            'Méthode scientifique',
        ], 0, $limit);
    }

    private function requestWithQuery(string $query): Request
    {
        return Request::create('/', 'GET', ['q' => $query, 'sort' => 'relevance']);
    }

    private function requestWithSort(string $sort): Request
    {
        return Request::create('/', 'GET', ['sort' => $sort]);
    }
}
