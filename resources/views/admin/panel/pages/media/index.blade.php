@php
    $activeMediaTab = $activeMediaTab ?? 'videos';
@endphp

<x-admin.page-header title="Videos &amp; Photos" description='Tables: <code>videos</code>, <code>photos</code>' />

<div class="bc-tabs" data-tab-group="media">
    <button type="button" class="bc-tab {{ $activeMediaTab === 'videos' ? 'active' : '' }}" data-tab="videos">videos</button>
    <button type="button" class="bc-tab {{ $activeMediaTab === 'photos' ? 'active' : '' }}" data-tab="photos">photos</button>
</div>

<div class="tab-panel {{ $activeMediaTab === 'videos' ? 'active' : '' }}" data-tab-panel="media" data-tab-id="videos">
    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>video_id</th>
                        <th>caption</th>
                        <th>language_id</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td>{{ $video->video_id }}</td>
                            <td>{{ $video->caption }}</td>
                            <td>{{ $video->language_id }}</td>
                            <td>
                                <a href="{{ route('admin_video_edit', $video->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">No videos found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="tab-panel {{ $activeMediaTab === 'photos' ? 'active' : '' }}" data-tab-panel="media" data-tab-id="photos">
    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>photo</th>
                        <th>caption</th>
                        <th>language_id</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($photos as $photo)
                        <tr>
                            <td>{{ $photo->photo }}</td>
                            <td>{{ $photo->caption }}</td>
                            <td>{{ $photo->language_id }}</td>
                            <td>
                                <a href="{{ route('admin_photo_edit', $photo->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">No photos found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
