@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Sidebar Advertisements')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        @include('admin.ads._nav', ['active' => 'sidebar'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Advertisements</p>
                <h1 class="ab-header__title">Sidebar Ads</h1>
                <p class="ab-header__sub">Small banners in the article and review sidebars.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_sidebar_ad_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add Sidebar Ad
                </a>
            </div>
        </header>

        <div class="ab-panel">
            @if($sidebar_ad_data->isEmpty())
                <div class="ab-empty">
                    <h3>No sidebar ads yet</h3>
                    <p>Add a top or bottom sidebar banner for article pages.</p>
                    <a href="{{ route('admin_sidebar_ad_create') }}" class="ab-btn ab-btn--primary">Add Sidebar Ad</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Ad</th>
                                <th>URL</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sidebar_ad_data as $row)
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="">
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">Sidebar #{{ $row->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($row->sidebar_ad_url)
                                            <a href="{{ $row->sidebar_ad_url }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::limit($row->sidebar_ad_url, 48) }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $row->sidebar_ad_location }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_sidebar_ad_edit', $row->id) }}">Edit</a>
                                            <form action="{{ route('admin_sidebar_ad_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="sidebar ad #{{ $row->id }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete ad">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
