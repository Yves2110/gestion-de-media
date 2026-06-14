<?php

namespace App\Support;

use App\Models\ContentView;
use App\Models\Document;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VisitorActivity
{
    private const SESSION_KEY = 'learning_visitor_activity';

    private const MAX_ITEMS = 20;

    public static function recordModel(Model $item, string $action = 'view'): void
    {
        if ($item instanceof Media && ! $item->statut) {
            return;
        }

        if ($item instanceof Document && ! $item->statut_publication) {
            return;
        }

        $type = self::resolveType($item);

        self::push([
            'type' => $type,
            'id' => $item->getKey(),
            'title' => $item->title,
            'thematique_ids' => self::thematiqueIds($item),
            'action' => $action,
            'at' => now()->toIso8601String(),
        ]);
    }

    public static function recordLearningVisit(Request $request): void
    {
        $query = trim((string) $request->get('q', ''));

        if ($query !== '') {
            self::pushInterest('search', $query);
        }

        foreach (array_filter((array) $request->input('thematique_id', [])) as $id) {
            self::pushInterest('thematique', (int) $id);
        }

        foreach (array_filter((array) $request->input('source_id', [])) as $id) {
            self::pushInterest('source', (int) $id);
        }

        foreach (array_filter((array) $request->input('types', [])) as $type) {
            if (in_array($type, ['audio', 'video', 'document'], true)) {
                self::pushInterest('type', $type);
            }
        }
    }

    public static function history(): array
    {
        $sessionItems = collect(session(self::SESSION_KEY . '.items', []));
        $userItems = collect(self::userHistoryItems());

        return $sessionItems
            ->merge($userItems)
            ->unique(fn (array $item) => ($item['type'] ?? '') . ':' . ($item['id'] ?? ''))
            ->sortByDesc('at')
            ->values()
            ->all();
    }

    public static function interests(): array
    {
        return session(self::SESSION_KEY . '.interests', []);
    }

    public static function viewedKeys(): array
    {
        return collect(self::history())
            ->map(fn (array $item) => ($item['type'] ?? '') . ':' . ($item['id'] ?? ''))
            ->filter()
            ->values()
            ->all();
    }

    private static function push(array $entry): void
    {
        $data = session(self::SESSION_KEY, ['items' => [], 'interests' => []]);
        $items = collect($data['items'] ?? []);

        $key = ($entry['type'] ?? '') . ':' . ($entry['id'] ?? '');
        $items = $items->reject(fn (array $item) => ($item['type'] ?? '') . ':' . ($item['id'] ?? '') === $key);

        $items->prepend($entry);

        session([
            self::SESSION_KEY => [
                'items' => $items->take(self::MAX_ITEMS)->values()->all(),
                'interests' => $data['interests'] ?? [],
            ],
        ]);
    }

    private static function pushInterest(string $kind, string|int $value): void
    {
        $data = session(self::SESSION_KEY, ['items' => [], 'interests' => []]);
        $interests = collect($data['interests'] ?? []);

        $interests->prepend([
            'kind' => $kind,
            'value' => $value,
            'at' => now()->toIso8601String(),
        ]);

        session([
            self::SESSION_KEY => [
                'items' => $data['items'] ?? [],
                'interests' => $interests->take(15)->values()->all(),
            ],
        ]);
    }

    private static function userHistoryItems(): array
    {
        if (! auth()->check()) {
            return [];
        }

        return ContentView::query()
            ->where('user_id', auth()->id())
            ->where('action', 'view')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (ContentView $view) {
                $item = $view->viewable;

                if (! $item instanceof Media && ! $item instanceof Document) {
                    return null;
                }

                if ($item instanceof Media && ! $item->statut) {
                    return null;
                }

                if ($item instanceof Document && ! $item->statut_publication) {
                    return null;
                }

                return [
                    'type' => self::resolveType($item),
                    'id' => $item->getKey(),
                    'title' => $item->title,
                    'thematique_ids' => self::thematiqueIds($item),
                    'action' => 'view',
                    'at' => $view->created_at?->toIso8601String(),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private static function resolveType(Model $item): string
    {
        if ($item instanceof Document) {
            return 'document';
        }

        return $item->type === 0 ? 'audio' : 'video';
    }

    private static function thematiqueIds(Model $item): array
    {
        $ids = json_decode($item->thematique_id ?? '[]', true);

        return is_array($ids) ? array_values(array_filter($ids)) : [];
    }
}
