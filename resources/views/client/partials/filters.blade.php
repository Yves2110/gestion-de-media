<form method="GET" class="filter-sidebar mb-4">
    <h6>Filtres</h6>
    <div class="mb-2">
        <label class="form-label">Recherche</label>
        <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}">
    </div>
    <div class="mb-2">
        <label class="form-label">Source</label>
        <select name="source_id" class="form-select form-select-sm">
            <option value="">Toutes</option>
            @foreach ($sources as $source)
                <option value="{{ $source->id }}" @selected(request('source_id') == $source->id)>{{ $source->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label class="form-label">Thématique</label>
        <select name="thematique_id" class="form-select form-select-sm">
            <option value="">Toutes</option>
            @foreach ($thematiques as $thematique)
                <option value="{{ $thematique->id }}" @selected(request('thematique_id') == $thematique->id)>{{ $thematique->label }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-sm btn-primary w-100">Appliquer</button>
</form>
