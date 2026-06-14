<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContentView;
use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'audios_published' => Media::isAudio()->where('statut', 1)->count(),
            'audios_draft' => Media::isAudio()->where('statut', 0)->count(),
            'videos_published' => Media::isvideo()->where('statut', 1)->count(),
            'videos_draft' => Media::isvideo()->where('statut', 0)->count(),
            'documents_published' => Document::where('statut_publication', 1)->count(),
            'documents_draft' => Document::where('statut_publication', 0)->count(),
            'active_users' => User::where('statut', 1)->count(),
            'clients' => User::where('role_id', 3)->count(),
        ];

        $recentActivity = collect()
            ->merge(
                Media::with('user')->latest()->limit(5)->get()->map(fn ($m) => [
                    'type' => $m->type === 0 ? 'Audio' : 'Vidéo',
                    'title' => $m->title,
                    'date' => $m->created_at,
                    'user' => $m->user?->firstname,
                    'edit_route' => $m->type === 0
                        ? route('audios.edit', $m->id)
                        : route('videos.edit', $m->id),
                ])
            )
            ->merge(
                Document::with('user')->latest()->limit(5)->get()->map(fn ($d) => [
                    'type' => 'Document',
                    'title' => $d->title,
                    'date' => $d->created_at,
                    'user' => $d->user?->firstname,
                    'edit_route' => route('documents.edit', $d->id),
                ])
            )
            ->sortByDesc('date')
            ->take(8)
            ->values();

        $draftsToPublish = collect()
            ->merge(
                Media::where('statut', 0)->latest()->limit(3)->get()->map(fn ($m) => [
                    'type' => $m->type === 0 ? 'Audio' : 'Vidéo',
                    'title' => $m->title,
                    'edit_route' => $m->type === 0
                        ? route('audios.edit', $m->id)
                        : route('videos.edit', $m->id),
                ])
            )
            ->merge(
                Document::where('statut_publication', 0)->latest()->limit(3)->get()->map(fn ($d) => [
                    'type' => 'Document',
                    'title' => $d->title,
                    'edit_route' => route('documents.edit', $d->id),
                ])
            )
            ->take(5)
            ->values();

        $weeklyPublications = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek();
            $end = Carbon::now()->subWeeks($i)->endOfWeek();
            $weeklyPublications[] = [
                'label' => 'S' . $start->weekOfYear,
                'count' => Media::where('statut', 1)->whereBetween('updated_at', [$start, $end])->count()
                    + Document::where('statut_publication', 1)->whereBetween('updated_at', [$start, $end])->count(),
            ];
        }

        $onboarding = [
            'has_source' => Source::count() > 0,
            'has_thematique' => Thematique::count() > 0,
            'has_published' => Media::where('statut', 1)->exists()
                || Document::where('statut_publication', 1)->exists(),
        ];
        $showOnboarding = !($onboarding['has_source'] && $onboarding['has_thematique'] && $onboarding['has_published'])
            && !$request->session()->get('onboarding_dismissed');

        $viewsThisMonth = ContentView::where('action', 'view')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $topContent = ContentView::query()
            ->selectRaw('viewable_type, viewable_id, count(*) as views_count')
            ->where('action', 'view')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('viewable_type', 'viewable_id')
            ->orderByDesc('views_count')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $model = app($row->viewable_type)->find($row->viewable_id);
                $row->viewable_title = $model?->title ?? 'Contenu supprimé';
                return $row;
            });

        return view('dashboard.index', compact(
            'stats',
            'recentActivity',
            'draftsToPublish',
            'weeklyPublications',
            'onboarding',
            'showOnboarding',
            'viewsThisMonth',
            'topContent'
        ));
    }

    public function dismissOnboarding(Request $request)
    {
        $request->session()->put('onboarding_dismissed', true);

        return redirect()->route('dashboard');
    }
}
