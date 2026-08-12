<x-admin.page-header title="Tags" description='Table: <code>tags</code> — linked to posts via post_id'>
    <x-slot:actions>
        <button class="btn-bc btn-bc-primary" type="button">
            <i class="fas fa-plus"></i> Add Tag
        </button>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="filters-bar">
        <select name="post_id">
            <option value="">All post_id</option>
            @foreach($tags->pluck('post_id')->unique()->filter() as $postId)
                <option value="{{ $postId }}" @selected(request('post_id') == $postId)>{{ $postId }}</option>
            @endforeach
        </select>
    </div>
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>post_id</th>
                    <th>tag_name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                    <tr>
                        <td>{{ $tag->post_id }}</td>
                        <td>{{ $tag->tag_name }}</td>
                        <td>
                            <button class="btn-bc btn-bc-ghost btn-bc-sm" type="button">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($tag->post_id && $tag->id)
                                <form action="{{ route('admin_post_delete_tag', [$tag->id, $tag->post_id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this tag?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-bc btn-bc-ghost btn-bc-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-muted text-center">No tags found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
