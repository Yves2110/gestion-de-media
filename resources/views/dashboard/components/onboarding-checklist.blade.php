<div class="card border-info mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="card-title">Premiers pas</h5>
                <p class="text-muted small mb-2">Complétez ces étapes pour mettre en place votre catalogue.</p>
            </div>
            <form action="{{ route('dashboard.onboarding.dismiss') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">Masquer</button>
            </form>
        </div>
        <ul class="list-unstyled mb-0 onboarding-checklist">
            <li class="mb-2 d-flex align-items-start gap-2">
                @if ($onboarding['has_source'])
                    <x-feather-icon name="check-circle" :size="18" class="text-success flex-shrink-0 mt-1" />
                @else
                    <x-feather-icon name="circle" :size="18" class="text-muted flex-shrink-0 mt-1" />
                @endif
                <span>Créer une source : <a href="{{ route('source.index') }}">Gérer les sources</a></span>
            </li>
            <li class="mb-2 d-flex align-items-start gap-2">
                @if ($onboarding['has_thematique'])
                    <x-feather-icon name="check-circle" :size="18" class="text-success flex-shrink-0 mt-1" />
                @else
                    <x-feather-icon name="circle" :size="18" class="text-muted flex-shrink-0 mt-1" />
                @endif
                <span>Créer une thématique : <a href="{{ route('thematique.index') }}">Gérer les thématiques</a></span>
            </li>
            <li class="d-flex align-items-start gap-2">
                @if ($onboarding['has_published'])
                    <x-feather-icon name="check-circle" :size="18" class="text-success flex-shrink-0 mt-1" />
                @else
                    <x-feather-icon name="circle" :size="18" class="text-muted flex-shrink-0 mt-1" />
                @endif
                <span>Publier un contenu : <a href="{{ route('audios.create') }}">Ajouter un audio</a></span>
            </li>
        </ul>
    </div>
</div>
