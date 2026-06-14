@if ($errors->any())
    <div class="alert alert-danger validation-summary mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
            <span class="validation-summary-icon text-danger" aria-hidden="true">
                <x-feather-icon name="alert-circle" :size="20" />
            </span>
            <div>
                <strong class="d-block mb-1">Le formulaire contient {{ $errors->count() }} erreur{{ $errors->count() > 1 ? 's' : '' }}</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
