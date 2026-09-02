@php
    $active = $active ?? 'dashboard';
@endphp
<nav class="ab-tabs" aria-label="Prop firms admin">
    <a href="{{ route('admin_prop_firms_dashboard') }}" class="{{ $active === 'dashboard' ? 'is-active' : '' }}">
        <i class="fas fa-th-large" aria-hidden="true"></i> Dashboard
    </a>
    <a href="{{ route('admin_prop_firms_show') }}" class="{{ $active === 'firms' ? 'is-active' : '' }}">
        <i class="fas fa-building" aria-hidden="true"></i> Firms
    </a>
    <a href="{{ route('admin_prop_firm_categories_show') }}" class="{{ $active === 'categories' ? 'is-active' : '' }}">
        <i class="fas fa-tags" aria-hidden="true"></i> Categories
    </a>
    <a href="{{ route('admin_prop_firm_attributes_show') }}" class="{{ $active === 'attributes' ? 'is-active' : '' }}">
        <i class="fas fa-sliders-h" aria-hidden="true"></i> Attributes
    </a>
    <a href="{{ route('admin_prop_firm_programs_show') }}" class="{{ $active === 'programs' ? 'is-active' : '' }}">
        <i class="fas fa-layer-group" aria-hidden="true"></i> Programs
    </a>
    <a href="{{ route('admin_prop_firm_reviews_show') }}" class="{{ $active === 'reviews' ? 'is-active' : '' }}">
        <i class="fas fa-comments" aria-hidden="true"></i> Reviews
    </a>
    <a href="{{ route('admin_prop_firm_faqs_show') }}" class="{{ $active === 'faqs' ? 'is-active' : '' }}">
        <i class="fas fa-question-circle" aria-hidden="true"></i> FAQs
    </a>
    <a href="{{ route('admin_prop_firm_settings_edit') }}" class="{{ $active === 'settings' ? 'is-active' : '' }}">
        <i class="fas fa-cog" aria-hidden="true"></i> Settings
    </a>
</nav>
