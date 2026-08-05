<x-admin.page-header title="Blog &amp; Articles" description='Table: <code>posts</code>'>
    <x-slot:actions>
        <a href="{{ route('admin_post_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> New Article
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="filters-bar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search post_title…">
        <select name="sub_category_id">
            <option value="">All sub_category_id</option>
        </select>
        <select name="language_id">
            <option value="">All language_id</option>
        </select>
    </div>
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>post_title</th>
                    <th>slug</th>
                    <th>sub_category_id</th>
                    <th>author_id</th>
                    <th>visitors</th>
                    <th>language_id</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->post_title }}</td>
                        <td>{{ $post->slug }}</td>
                        <td>{{ $post->sub_category_id }}</td>
                        <td>{{ $post->author_id }}</td>
                        <td>{{ $post->visitors ?? 0 }}</td>
                        <td>{{ $post->language_id }}</td>
                        <td>
                            <a href="{{ route('admin_post_edit', $post->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center">No posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($posts instanceof \Illuminate\Pagination\AbstractPaginator && $posts->hasPages())
        <div class="bc-pagination">{{ $posts->links() }}</div>
    @endif
</div>
