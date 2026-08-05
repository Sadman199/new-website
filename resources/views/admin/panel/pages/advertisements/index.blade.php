@php
    $activeTab = $activeTab ?? 'ads';
@endphp

<x-admin.page-header
    title="Advertisements"
    description='Tables: <code>ads</code> + legacy <code>home_advertisements</code>, <code>sidebar_advertisements</code>, <code>top_advertisements</code>'
>
    <x-slot:actions>
        <a href="{{ route('admin_ads_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Create Ad
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-tabs" data-tab-group="ads">
    <button type="button" class="bc-tab {{ $activeTab === 'ads' ? 'active' : '' }}" data-tab="ads-new">ads (new)</button>
    <button type="button" class="bc-tab {{ $activeTab === 'home' ? 'active' : '' }}" data-tab="ads-home">home_advertisements</button>
    <button type="button" class="bc-tab {{ $activeTab === 'sidebar' ? 'active' : '' }}" data-tab="ads-sidebar">sidebar_advertisements</button>
    <button type="button" class="bc-tab {{ $activeTab === 'top' ? 'active' : '' }}" data-tab="ads-top">top_advertisements</button>
</div>

<div class="tab-panel {{ $activeTab === 'ads' ? 'active' : '' }}" data-tab-panel="ads" data-tab-id="ads-new">
    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>title</th>
                        <th>type</th>
                        <th>position</th>
                        <th>trigger_type</th>
                        <th>trigger_value</th>
                        <th>is_active</th>
                        <th>priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ads ?? [] as $ad)
                        <tr>
                            <td>{{ $ad->title }}</td>
                            <td>{{ $ad->type }}</td>
                            <td>{{ $ad->position }}</td>
                            <td>{{ $ad->trigger_type }}</td>
                            <td>{{ $ad->trigger_value }}</td>
                            <td>
                                <div class="toggle-switch {{ $ad->is_active ? 'on' : '' }}"></div>
                            </td>
                            <td>{{ $ad->priority }}</td>
                            <td>
                                <a href="{{ route('admin_ads_edit', $ad->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted text-center">No ads found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="tab-panel {{ $activeTab === 'home' ? 'active' : '' }}" data-tab-panel="ads" data-tab-id="ads-home">
    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>above_search_ad</th>
                        <th>above_search_ad_url</th>
                        <th>above_search_ad_status</th>
                        <th>above_footer_ad</th>
                        <th>above_footer_ad_url</th>
                        <th>above_footer_ad_status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homeAds ?? [] as $homeAd)
                        <tr>
                            <td>{{ $homeAd->above_search_ad ?? '—' }}</td>
                            <td>{{ $homeAd->above_search_ad_url ?? '—' }}</td>
                            <td>{{ $homeAd->above_search_ad_status ?? '—' }}</td>
                            <td>{{ $homeAd->above_footer_ad ?? '—' }}</td>
                            <td>{{ $homeAd->above_footer_ad_url ?? '—' }}</td>
                            <td>{{ $homeAd->above_footer_ad_status ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center">
                                <a href="{{ route('admin_home_ad_show') }}">Manage home advertisements</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="tab-panel {{ $activeTab === 'sidebar' ? 'active' : '' }}" data-tab-panel="ads" data-tab-id="ads-sidebar">
    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>sidebar_ad</th>
                        <th>sidebar_ad_url</th>
                        <th>sidebar_ad_location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sidebarAds ?? [] as $sidebarAd)
                        <tr>
                            <td>{{ $sidebarAd->sidebar_ad }}</td>
                            <td>{{ $sidebarAd->sidebar_ad_url }}</td>
                            <td>{{ $sidebarAd->sidebar_ad_location }}</td>
                            <td>
                                <a href="{{ route('admin_sidebar_ad_edit', $sidebarAd->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">
                                <a href="{{ route('admin_sidebar_ad_show') }}">Manage sidebar advertisements</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="tab-panel {{ $activeTab === 'top' ? 'active' : '' }}" data-tab-panel="ads" data-tab-id="ads-top">
    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>top_ad</th>
                        <th>top_ad_url</th>
                        <th>top_ad_status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topAds ?? [] as $topAd)
                        <tr>
                            <td>{{ $topAd->top_ad ?? ($topAd->top_ad_image ?? '—') }}</td>
                            <td>{{ $topAd->top_ad_url ?? '—' }}</td>
                            <td>{{ $topAd->top_ad_status ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin_top_ad_show') }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">
                                <a href="{{ route('admin_top_ad_show') }}">Manage top advertisements</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
