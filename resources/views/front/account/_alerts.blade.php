@if(session('success'))
    <div class="ua-alert ua-alert--success" role="status">
        <i class="fas fa-check-circle" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="ua-alert ua-alert--error" role="alert">
        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if($errors->any() && !session('error'))
    <div class="ua-alert ua-alert--error" role="alert">
        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif
