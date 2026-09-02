@php
    $active = $active ?? 'blogs';
@endphp
<nav class="ab-tabs" aria-label="Blog admin">
    <a href="{{ route('admin_post_show') }}" class="{{ $active === 'blogs' ? 'is-active' : '' }}">
        <i class="fas fa-newspaper" aria-hidden="true"></i> Blogs
    </a>
    <a href="{{ route('admin_category_show') }}" class="{{ $active === 'categories' ? 'is-active' : '' }}">
        <i class="fas fa-folder" aria-hidden="true"></i> Categories
    </a>
    <a href="{{ route('admin_sub_category_show') }}" class="{{ $active === 'subcategories' ? 'is-active' : '' }}">
        <i class="fas fa-folder-open" aria-hidden="true"></i> Subcategories
    </a>
    <a href="{{ route('admin_post_content_types_index') }}" class="{{ $active === 'types' ? 'is-active' : '' }}">
        <i class="fas fa-shapes" aria-hidden="true"></i> Content types
    </a>
</nav>
