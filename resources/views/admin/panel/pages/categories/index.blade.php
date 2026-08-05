<x-admin.page-header title="Categories &amp; Subcategories" description='Tables: <code>categories</code>, <code>sub_categories</code>'>
    <x-slot:actions>
        <a href="{{ route('admin_category_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid-2">
    <div class="bc-card">
        <div class="bc-card-header"><h3>categories</h3></div>
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>category_name</th>
                        <th>slug</th>
                        <th>show_on_menu</th>
                        <th>category_order</th>
                        <th>language_id</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <a href="{{ route('admin_category_edit', $category->id) }}">{{ $category->category_name }}</a>
                            </td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->show_on_menu ? 'Show' : 'Hide' }}</td>
                            <td>{{ $category->category_order }}</td>
                            <td>{{ $category->language_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center">No categories.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bc-card">
        <div class="bc-card-header"><h3>sub_categories</h3></div>
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>sub_category_name</th>
                        <th>slug</th>
                        <th>category_id</th>
                        <th>show_on_menu</th>
                        <th>show_on_home</th>
                        <th>sub_category_order</th>
                        <th>language_id</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subCategories as $subCategory)
                        <tr>
                            <td>
                                <a href="{{ route('admin_sub_category_edit', $subCategory->id) }}">{{ $subCategory->sub_category_name }}</a>
                            </td>
                            <td>{{ $subCategory->slug }}</td>
                            <td>{{ $subCategory->category_id }}</td>
                            <td>{{ $subCategory->show_on_menu ? 'Show' : 'Hide' }}</td>
                            <td>{{ $subCategory->show_on_home ? 'Show' : 'Hide' }}</td>
                            <td>{{ $subCategory->sub_category_order }}</td>
                            <td>{{ $subCategory->language_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted text-center">No subcategories.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
