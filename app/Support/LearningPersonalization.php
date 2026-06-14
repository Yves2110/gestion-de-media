<?php

namespace App\Support;

use App\Models\Document;
use App\Models\Media;
use App\Models\Thematique;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LearningPersonalization
{
    public function __construct(private LearningResourceSearch $search)
    {
    }

    public function suggest(): array
    {
        $history = VisitorActivity::history();
        $interests = VisitorActivity::interests();

        if ($history === [] && $interests === []) {
            return $this->fallback();
        }

        $preferredType = $this->preferredType($history, $interests);
        $preferredThematiqueId = $this->preferredThematiqueId($history, $interests);
        $searchTerms = $this->searchTerms($history, $interests);
        $excludeKeys = VisitorActivity::viewedKeys();

        $resources = $this->findSimilarResources(
            $preferredType,
            $preferredThematiqueId,
            $excludeKeys
        );

        if ($resources->isEmpty() && $searchTerms !== []) {
            $resources = $this->findSimilarResources(
                $preferredType,
                null,
                $excludeKeys,
                $searchTerms[0]
            );
        }

        if ($resources->isEmpty()) {
            return $this->fallback();
        }

        return [
            'has_history' => true,
            'message' => $this->buildMessage($history, $preferredType, $preferredThematiqueId),
            'resources' => $resources->take(6)->values()->all(),
            'chips' => $this->buildChips($preferredType, $preferredThematiqueId, $searchTerms),
        ];
    }

    private function fallback(): array
    {
        $curated = $this->search->curatedSuggestions();
        $resources = collect($curated['popular'])
            ->whenEmpty(fn () => collect($curated['recent']))
            ->take(4)
            ->values()
            ->all();

        return [
            'has_history' => false,
            'message' => 'Découvrez les ressources les plus consultées sur la plateforme.',
            'resources' => $resources,
            'chips' => collect($curated['quick_searches'])
                ->take(4)
                ->map(fn (string $label) => [
                    'label' => $label,
                    'url' => route('learning.index', ['q' => $label]),
                ])
                ->values()
                ->all(),
        ];
    }

    private function preferredType(array $history, array $interests): ?string
    {
        $typeCounts = collect($history)->countBy('type');

        foreach ($interests as $interest) {
            if (($interest['kind'] ?? '') === 'type') {
                $type = (string) ($interest['value'] ?? '');
                $typeCounts[$type] = ($typeCounts[$type] ?? 0) + 2;
            }
        }

        if ($typeCounts->isEmpty()) {
            return null;
        }

        return $typeCounts->sortDesc()->keys()->first();
    }

    private function preferredThematiqueId(array $history, array $interests): ?int
    {
        $counts = collect();

        foreach ($history as $item) {
            foreach ($item['thematique_ids'] ?? [] as $id) {
                $counts[(int) $id] = ($counts[(int) $id] ?? 0) + 1;
            }
        }

        foreach ($interests as $interest) {
            if (($interest['kind'] ?? '') === 'thematique') {
                $id = (int) ($interest['value'] ?? 0);
                $counts[$id] = ($counts[$id] ?? 0) + 2;
            }
        }

        if ($counts->isEmpty()) {
            return null;
        }

        return (int) $counts->sortDesc()->keys()->first();
    }

    private function searchTerms(array $history, array $interests): array
    {
        $terms = collect();

        foreach ($interests as $interest) {
            if (($interest['kind'] ?? '') === 'search') {
                $terms->push((string) $interest['value']);
            }
        }

        foreach ($history as $item) {
            $terms->push((string) ($item['title'] ?? ''));
        }

        return $terms
            ->filter()
            ->flatMap(fn (string $text) => Str::of($text)->lower()->explode(' '))
            ->filter(fn (string $word) => mb_strlen($word) >= 4)
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(3)
            ->values()
            ->all();
    }

    private function findSimilarResources(?string $type, ?int $thematiqueId, array $excludeKeys, ?string $term = null): Collection
    {
        $types = $type ? [$type] : ['audio', 'video', 'document'];
        $results = collect();

        foreach ($types as $contentType) {
            $query = match ($contentType) {
                'audio' => Media::isAudio()->where('statut', 1),
                'video' => Media::isvideo()->where('statut', 1),
                default => Document::where('statut_publication', 1),
            };

            if ($thematiqueId) {
                $query->where(function ($builder) use ($thematiqueId) {
                    $builder->whereHas('thematiques', fn ($relation) => $relation->where('thematiques.id', $thematiqueId))
                        ->orWhere('thematique_id', 'like', '%' . $thematiqueId . '%');
                });
            }

            if ($term) {
                $columns = $contentType === 'document'
                    ? ['title', 'auteur', 'resume']
                    : ['title', 'auteur', 'description'];

                $query->where(function ($builder) use ($term, $columns) {
                    foreach ($columns as $index => $column) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $builder->{$method}($column, 'like', '%' . $term . '%');
                    }
                });
            }

            $items = $query->latest()->limit(12)->get();

            foreach ($items as $item) {
                $mappedType = $item instanceof Document ? 'document' : ($item->type === 0 ? 'audio' : 'video');
                $key = $mappedType . ':' . $item->getKey();

                if (in_array($key, $excludeKeys, true)) {
                    continue;
                }

                $results->push($this->mapResource($item, $mappedType));
            }
        }

        return $results->unique('url')->take(8)->values();
    }

    private function mapResource(Media|Document $item, string $type): array
    {
        if ($type === 'document') {
            return [
                'type' => 'document',
                'title' => $item->title,
                'auteur' => $item->auteur,
                'url' => route('public.documents.show', $item),
            ];
        }

        return [
            'type' => $type,
            'title' => $item->title,
            'auteur' => $item->auteur,
            'url' => route('public.' . $type . 's.show', $item),
        ];
    }

    private function buildMessage(array $history, ?string $type, ?int $thematiqueId): string
    {
        $lastTitle = $history[0]['title'] ?? null;
        $typeLabel = match ($type) {
            'audio' => 'des audios',
            'video' => 'des vidéos',
            'document' => 'des documents',
            default => 'des ressources',
        };

        $thematique = $thematiqueId
            ? Thematique::find($thematiqueId)?->label
            : null;

        if ($lastTitle && $thematique) {
            return 'Parce que vous avez consulté « ' . Str::limit($lastTitle, 40) . ' », voici d\'autres contenus sur ' . $thematique . '.';
        }

        if ($lastTitle) {
            return 'Parce que vous avez consulté « ' . Str::limit($lastTitle, 40) . ' », voici d\'autres ' . $typeLabel . ' qui pourraient vous intéresser.';
        }

        if ($thematique) {
            return 'Basé sur vos visites récentes, voici des ressources autour de ' . $thematique . '.';
        }

        return 'Basé sur votre navigation récente, voici des ressources sélectionnées pour vous.';
    }

    private function buildChips(?string $type, ?int $thematiqueId, array $terms): array
    {
        $chips = collect();

        if ($type) {
            $chips->push([
                'label' => 'Plus de ' . match ($type) {
                    'audio' => 'audios',
                    'video' => 'vidéos',
                    default => 'documents',
                },
                'url' => route('learning.index', ['types' => [$type]]),
            ]);
        }

        if ($thematiqueId) {
            $label = Thematique::find($thematiqueId)?->label;
            if ($label) {
                $chips->push([
                    'label' => $label,
                    'url' => route('learning.index', ['thematique_id' => [$thematiqueId]]),
                ]);
            }
        }

        foreach ($terms as $term) {
            $chips->push([
                'label' => ucfirst($term),
                'url' => route('learning.index', ['q' => $term]),
            ]);
        }

        return $chips->take(4)->values()->all();
    }
}
