<form action="{{ route('admin_logout') }}" method="POST" class="d-inline admin-logout-form">
    @csrf
    <button type="submit" class="{{ $class ?? 'dropdown-item has-icon text-danger border-0 bg-transparent w-100 text-left' }}">
        @if(!empty($icon))<i class="{{ $icon }}"></i>@endif
        {{ $label ?? 'Logout' }}
    </button>
</form>