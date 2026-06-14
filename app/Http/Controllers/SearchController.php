<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        $audios = collect();
        $videos = collect();
        $documents = collect();
        $sources = collect();
        $thematiques = collect();

        if ($query !== '') {
            $audios = Media::isAudio()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('auteur', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest()
                ->limit(20)
                ->get();

            $videos = Media::isvideo()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('auteur', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest()
                ->limit(20)
                ->get();

            $documents = Document::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('auteur', 'like', "%{$query}%")
                    ->orWhere('resume', 'like', "%{$query}%");
            })
                ->latest()
                ->limit(20)
                ->get();

            $sources = Source::where('label', 'like', "%{$query}%")->limit(10)->get();
            $thematiques = Thematique::where('label', 'like', "%{$query}%")->limit(10)->get();
        }

        return view('admin.search', compact('query', 'audios', 'videos', 'documents', 'sources', 'thematiques'));
    }
}
