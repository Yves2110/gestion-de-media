@props([
    'item',
    'isPublished',
    'editUrl',
    'destroyUrl',
    'activateUrl',
    'deactivateUrl',
    'reportUrl',
    'backUrl',
    'modalId',
    'localisationUrl' => null,
    'localisationDestroyUrl' => null,
    'previewUrl' => null,
])

<div class="admin-content-action-bar card border mb-3">
    <div class="card-body py-3 d-flex flex-wrap align-items-center gap-2">
        @if ($previewUrl)
            <a href="{{ $previewUrl }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener">
                <i data-feather="eye"></i> Aperçu public
            </a>
        @endif
        <a href="{{ $editUrl }}" class="btn btn-outline-primary btn-sm">
            <i data-feather="edit-2"></i> Modifier
        </a>
        @if ($isPublished)
            <form action="{{ $deactivateUrl }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="eye-off"></i> Dépublier
                </button>
            </form>
        @else
            <form action="{{ $activateUrl }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm">
                    <i data-feather="check-circle"></i> Publier
                </button>
            </form>
        @endif
        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
            <i data-feather="flag"></i> Signaler
        </button>
        <form action="{{ $destroyUrl }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement ce contenu ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i data-feather="trash-2"></i> Supprimer
            </button>
        </form>
        @if ($localisationUrl)
            <a href="{{ $localisationUrl }}" class="btn btn-outline-info btn-sm">
                <i data-feather="map-pin"></i> Localisation
            </a>
        @endif
        @if ($localisationDestroyUrl)
            <form action="{{ $localisationDestroyUrl }}" method="POST" class="d-inline" onsubmit="return confirm('Retirer la localisation ?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i data-feather="x-circle"></i> Retirer localisation
                </button>
            </form>
        @endif
        <a href="{{ $backUrl }}" class="btn btn-link btn-sm text-muted ms-auto">Retour à la liste</a>
    </div>
</div>

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $reportUrl }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Signaler ce contenu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Motif du signalement</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Décrivez le problème constaté..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
