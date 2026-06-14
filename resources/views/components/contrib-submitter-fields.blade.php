@props(['isAdmin' => false])

@if (! $isAdmin)
    <div class="row g-2 contrib-form-row">
        <div class="col-md-6">
            <label class="form-label">Votre nom *</label>
            <input type="text" name="submitter_name" class="form-control form-control-sm"
                   value="{{ old('submitter_name', auth()->user() ? trim(auth()->user()->firstname . ' ' . auth()->user()->lastname) : '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Votre email *</label>
            <input type="email" name="submitter_email" class="form-control form-control-sm"
                   value="{{ old('submitter_email', auth()->user()?->email ?? '') }}" required>
        </div>
    </div>
@endif
