@extends('layouts.marketing')
@section('title', 'Proposer un document')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-2">Proposer un document</h3>
                    <p class="text-muted mb-4">Sans compte : déposez votre PDF. Un administrateur validera votre contribution avant publication.</p>

                    <form action="{{ route('documents.submit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-none" aria-hidden="true">
                            <label>Ne pas remplir</label>
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Votre nom *</label>
                                <input type="text" name="submitter_name" class="form-control" value="{{ old('submitter_name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Votre email *</label>
                                <input type="email" name="submitter_email" class="form-control" value="{{ old('submitter_email') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Titre du document *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Auteur *</label>
                            <input type="text" name="auteur" class="form-control" value="{{ old('auteur') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description * <span class="text-muted small">(minimum 250 mots)</span></label>
                            <textarea name="resume" class="form-control" rows="8" required>{{ old('resume') }}</textarea>
                            @error('resume')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($sources->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label">Source</label>
                            <select name="source_id" class="form-select">
                                <option value="">Choisir...</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->id }}" @selected(old('source_id') == $source->id)>{{ $source->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if ($categories->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-select">
                                <option value="">Choisir...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if ($thematiques->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label">Thématiques</label>
                            <x-thematique-checkboxes :thematiques="$thematiques" :selected="array_map('intval', old('thematique_id', []))" />
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fichier PDF *</label>
                                <input type="file" name="file_doc" class="form-control" accept=".pdf" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Photo de couverture</label>
                                <input type="file" name="picture" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Envoyer pour validation</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
