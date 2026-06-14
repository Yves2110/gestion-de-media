<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Media;
use App\Support\LearningPersonalization;
use App\Support\LearningResourceSearch;
use App\Support\VisitorActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LearningSpaceController extends Controller
{
    public function __construct(private LearningResourceSearch $search)
    {
    }

    public function index(Request $request)
    {
        VisitorActivity::recordLearningVisit($request);

        $filters = $this->search->filterOptions();
        $results = $this->search->search($request);
        $curated = $this->search->curatedSuggestions();
        $activeFilterCount = $this->search->activeFilterCount($request);
        $hasActiveSearch = trim((string) $request->get('q', '')) !== '' || $activeFilterCount > 0;
        $suggestionTopics = $this->search->suggestionTopics();

        $stats = [
            'audios' => Media::isAudio()->where('statut', 1)->count(),
            'videos' => Media::isvideo()->where('statut', 1)->count(),
            'documents' => Document::where('statut_publication', 1)->count(),
        ];
        $stats['total'] = $stats['audios'] + $stats['videos'] + $stats['documents'];
        $personalized = app(LearningPersonalization::class)->suggest();

        return view('learning.index', compact(
            'filters',
            'results',
            'curated',
            'activeFilterCount',
            'hasActiveSearch',
            'stats',
            'suggestionTopics',
            'personalized'
        ));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $term = trim((string) $request->get('q', ''));

        if (mb_strlen($term) < 2) {
            $personalized = app(LearningPersonalization::class)->suggest();

            return response()->json([
                'mode' => $personalized['has_history'] ? 'personalized' : 'curated',
                'message' => $personalized['message'],
                'quick_searches' => collect($personalized['chips'])->pluck('label')->all(),
                'titles' => [],
                'authors' => [],
                'thematiques' => [],
                'sources' => [],
                'resources' => $personalized['resources'],
            ]);
        }

        return response()->json($this->search->suggestions($term));
    }
}
