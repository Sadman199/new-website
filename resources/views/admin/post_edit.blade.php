@extends('admin.layout.app')

@section('heading', 'Edit Post')

@section('button')
<a href="{{ route('admin_post_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Post</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_post_update', $post_single->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <ul class="nav nav-tabs mb-4" id="postTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Post Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab" aria-controls="seo" aria-selected="false">SEO Meta Information</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="postTabsContent">
                                <!-- Post Information Tab -->
                                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Post Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="post_title" value="{{ old('post_title', $post_single->post_title) }}" placeholder="Enter post title" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Post Slug</label>
                                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $post_single->slug) }}" placeholder="e.g., post-title" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Select Category <span class="text-danger">*</span></label>
                                                <select name="sub_category_id" class="form-control select2" required>
                                                    @foreach($sub_categories as $item)
                                                        <option value="{{ $item->id }}" @if($item->id == $post_single->sub_category_id) selected @endif>
                                                            {{ $item->sub_category_name }} @if($item->rCategory)({{ $item->rCategory->category_name }})@else(No Category)@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Existing Tags</label>
                                                <table class="table table-bordered table-sm">
                                                    @forelse($existing_tags as $item)
                                                        <tr>
                                                            <td>{{ $item->tag_name }}</td>
                                                            <td class="text-center">
                                                                <form action="{{ route('admin_post_delete_tag', [$item->id, $post_single->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Tag">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="text-center text-muted">No tags available</td>
                                                        </tr>
                                                    @endforelse
                                                </table>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">New Tags</label>
                                                <input type="text" class="form-control" name="tags" value="" placeholder="e.g., tag1, tag2, tag3">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Existing Post Photo <span class="text-danger">*</span></label>
                                                <div class="mb-3">
                                                    <img src="{{ asset('Uploads/'.$post_single->post_photo) }}" alt="Post Thumbnail" class="img-fluid rounded" style="max-width: 150px; max-height: 100px; object-fit: cover;">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Change Post Photo</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="post_photo" name="post_photo">
                                                    <label class="custom-file-label" for="post_photo">Choose file</label>
                                                </div>
                                            </div>
                                            @include('admin.partials.language_id_field', ['language_id' => $post_single->language_id])
                                            <div class="form-group">
                                                <label class="font-weight-bold">Is Sharable?</label>
                                                <select name="is_share" class="form-control custom-select">
                                                    <option value="1" @if($post_single->is_share == 1) selected @endif>Yes</option>
                                                    <option value="0" @if($post_single->is_share == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Is Comment?</label>
                                                <select name="is_comment" class="form-control custom-select">
                                                    <option value="1" @if($post_single->is_comment == 1) selected @endif>Yes</option>
                                                    <option value="0" @if($post_single->is_comment == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Post Detail <span class="text-danger">*</span></label>
                                        <textarea name="post_detail" class="form-control snote" rows="10" required>{{ old('post_detail', $post_single->post_detail ?? '') }}</textarea>
                                    </div>

                                    @include('admin.posts._editorial_fields', ['post' => $post_single ?? null, 'editorialOptions' => $editorialOptions ?? []])
                                </div>
                                <!-- SEO Meta Information Tab -->
                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Meta Title</label>
                                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $post_single->meta_title) }}" placeholder="Enter meta title">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Meta Keywords</label>
                                                <input type="text" class="form-control" name="meta_keywords" value="{{ old('meta_keywords', $post_single->meta_keywords) }}" placeholder="e.g., keyword1, keyword2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Meta Description</label>
                                                <textarea name="meta_description" class="form-control" rows="5" placeholder="Enter meta description">{{ old('meta_description', $post_single->meta_description) }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Author Name</label>
                                                <input type="text" class="form-control" name="author" value="{{ old('author', $post_single->author) }}" placeholder="Enter author name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection