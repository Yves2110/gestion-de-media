@extends('layouts.marketing')
@section('title', 'Contribuer un document')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card shadow-sm border-0 contrib-form-compact">
                <div class="card-body">
                    <div class="mb-3">
                        <h3 class="contrib-form-title mb-1">Contribuer un document</h3>
                        @unless ($isAdmin)
                            <p class="text-muted contrib-form-lead mb-0">Déposez votre PDF. Un administrateur validera votre contribution avant publication.</p>
                        @endunless
                    </div>

                    <form action="{{ route('contrib.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-none" aria-hidden="true">
                            <label>Ne pas remplir</label>
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <x-contrib-submitter-fields :is-admin="$isAdmin" />

                        <div class="row g-2 contrib-form-row">
                            <div class="col-md-6">
                                <label class="form-label">Titre *</label>
                                <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Auteur *</label>
                                <input type="text" name="auteur" class="form-control form-control-sm" value="{{ old('auteur') }}" required>
                            </div>
                        </div>

                        <div class="contrib-form-row">
                            <label class="form-label">Description * <span class="text-muted small">(min. 250 mots)</span></label>
                            <textarea name="resume" class="form-control form-control-sm" rows="5" required>{{ old('resume') }}</textarea>
                            @error('resume')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-2 contrib-form-row">
                            @if ($sources->isNotEmpty())
                            <div class="col-md-4">
                                <label class="form-label">Source</label>
                                <select name="source_id" class="form-select form-select-sm">
                                    <option value="">Choisir...</option>
                                    @foreach ($sources as $source)
                                        <option value="{{ $source->id }}" @selected(old('source_id') == $source->id)>{{ $source->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            @if ($categories->isNotEmpty())
                            <div class="col-md-4">
                                <label class="form-label">Catégorie</label>
                                <select name="category_id" class="form-select form-select-sm">
                                    <option value="">Choisir...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-4">
                                <label class="form-label">Pages</label>
                                <input type="number" name="page" class="form-control form-control-sm" value="{{ old('page', 1) }}" min="1">
                            </div>
                        </div>

                        @if ($thematiques->isNotEmpty())
                        <div class="contrib-form-row">
                            <label class="form-label">Thématiques</label>
                            <x-thematique-checkboxes :thematiques="$thematiques" :selected="array_map('intval', old('thematique_id', []))" />
                        </div>
                        @endif

                        <div class="row g-2 contrib-form-row">
                            <div class="col-md-6">
                                <label class="form-label">Fichier PDF *</label>
                                <input type="file" name="file_doc" class="form-control form-control-sm" accept=".pdf" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Couverture</label>
                                <input type="file" name="picture" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>

                        <div class="contrib-form-actions">
                            <x-contrib-publish-option field="statut_publication" />
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    {{ $isAdmin ? 'Enregistrer' : 'Envoyer pour validation' }}
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
