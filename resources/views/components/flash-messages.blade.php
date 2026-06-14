@if ($message = Session::get('message'))
    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if ($success = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ $success }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if ($error = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
