@php
    $isEdit = $post->exists;
    $tagString = old('tags', $isEdit ? $post->tags->pluck('tag_name')->implode(',') : '');
    $selectedBrokers = collect(old('broker_ids', $isEdit && $post->relationLoaded('brokers') ? $post->brokers->pluck('id')->all() : []))->map(fn ($id) => (int) $id);
    $selectedRelated = collect(old('related_post_ids', $isEdit && $post->relationLoaded('relatedPosts') ? $post->relatedPosts->pluck('id')->all() : []))->map(fn ($id) => (int) $id);
    $selectedCategories = collect(old('related_category_ids', $isEdit && $post->relationLoaded('relatedCategories') ? $post->relatedCategories->pluck('id')->all() : []))->map(fn ($id) => (int) $id);
    $seoOpen = $errors->hasAny([
        'meta_title', 'meta_description', 'meta_keywords', 'focus_keyword', 'canonical_url',
        'og_title', 'og_description', 'og_image', 'schema_type',
    ]);
    $status = old('status', $post->status ?: 'published');
@endphp

<nav class="ab-section-nav" aria-label="Blog sections">
    <a href="#basics" data-ab-nav class="is-active">Basics</a>
    <a href="#content" data-ab-nav>Content</a>
    <a href="#classification" data-ab-nav>Classification</a>
    <a href="#brokers" data-ab-nav>Brokers</a>
    <a href="#seo" data-ab-nav>SEO</a>
    <a href="#editorial" data-ab-nav>Editorial</a>
    <a href="#related" data-ab-nav>Related</a>
    <a href="#media" data-ab-nav>Media</a>
    <a href="#publish" data-ab-nav>Publish</a>
</nav>

<div class="ab-layout ab-layout--post">
<div class="ab-form-main">
    <section class="ab-section" id="basics">
        <div class="ab-section__head">
            <h2>A. Basic information</h2>
            <p>Title, unique slug, excerpt, language, and estimated reading time.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-8 form-group">
                    <label for="post_title">Blog title <span class="text-danger">*</span></label>
                    <input type="text" name="post_title" id="post_title" class="form-control @error('post_title') is-invalid @enderror" required
                           value="{{ old('post_title', $post->post_title) }}" placeholder="e.g. Best regulated brokers for beginners">
                    @error('post_title')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="slug">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $post->slug) }}" placeholder="auto from title"
                           @if($isEdit) data-autogen="off" @endif>
                    <small class="text-muted">Must be unique. You can edit the auto-generated slug.</small>
                    @error('slug')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-8 form-group">
                    <label for="excerpt">Short excerpt</label>
                    <textarea name="excerpt" id="excerpt" class="form-control" rows="4"
                              placeholder="One or two sentences for listings and search snippets">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="language_id">Language</label>
                    @if($formOptions['languages']->isEmpty())
                        @include('admin.partials.language_id_field')
                        <p class="ab-note mb-0">Using the current site language.</p>
                    @else
                    <select name="language_id" id="language_id" class="form-control">
                        @foreach($formOptions['languages'] as $language)
                            <option value="{{ $language->id }}" @selected((string) old('language_id', $post->language_id) === (string) $language->id)>
                                {{ $language->name }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                    @error('language_id')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Reading time</label>
                    <p class="ab-readonly" id="reading-time-preview">
                        {{ $post->reading_time ?: \App\Models\Post::estimateReadingTime($post->post_detail) }} min
                    </p>
                    <small class="text-muted">Calculated from content (~200 words/min).</small>
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="content">
        <div class="ab-section__head">
            <h2>B. Content</h2>
            <p>Full article body. Headings, lists, links, images, tables, and video embeds are supported.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group mb-0">
                <label for="post_detail">Article <span class="text-danger">*</span></label>
                <textarea name="post_detail" id="post_detail" class="form-control snote snote-article @error('post_detail') is-invalid @enderror" rows="18" data-editor-height="420" required>{{ old('post_detail', $post->post_detail) }}</textarea>
                @error('post_detail')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
        </div>
    </section>

    <section class="ab-section" id="classification">
        <div class="ab-section__head">
            <h2>C. Classification</h2>
            <p>Reuse the existing category / subcategory tree. Tags stay compatible with public tag pages.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-8 form-group">
                    <label for="sub_category_id">Category / subcategory <span class="text-danger">*</span></label>
                    <select name="sub_category_id" id="sub_category_id" class="form-control select2" required>
                        <option value="">Select subcategory</option>
                        @foreach($formOptions['subCategories'] as $item)
                            <option value="{{ $item->id }}" @selected((string) old('sub_category_id', $post->sub_category_id) === (string) $item->id)>
                                {{ $item->rCategory->category_name ?? 'Uncategorised' }} — {{ $item->sub_category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('sub_category_id')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="content_type">Content type</label>
                    <select name="content_type" id="content_type" class="form-control" data-tags="true" data-placeholder="Select or type a new type">
                        @foreach($formOptions['contentTypes'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('content_type', $post->content_type ?: 'article') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Type a new name and press Enter to add a content type.</small>
                </div>
                <div class="col-md-12 form-group mb-0">
                    <label for="tags">Tags</label>
                    <input type="text" name="tags" id="tags" class="form-control" value="{{ $tagString }}"
                           placeholder="Type a tag and press Enter">
                    <small class="text-muted">Press Enter or comma to add. Used on public tag pages.</small>
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="brokers">
        <div class="ab-section__head">
            <h2>D. Related brokers</h2>
            <p>Attach brokers mentioned in this article. Broker profile pages can later list “Latest articles about XM”.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group mb-0">
                <label for="broker_ids">Brokers</label>
                <select name="broker_ids[]" id="broker_ids" class="form-control select2" multiple data-placeholder="Search all brokers…">
                    @foreach($formOptions['brokers'] as $broker)
                        <option value="{{ $broker->id }}" @selected($selectedBrokers->contains((int) $broker->id))>
                            {{ $broker->name }}@if(!empty($broker->is_scam)) (scam flagged)@endif
                        </option>
                    @endforeach
                </select>
                @error('broker_ids')<small class="ab-error">{{ $message }}</small>@enderror
                @error('broker_ids.*')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
        </div>
    </section>

    <details class="ab-section ab-section--details" id="seo" @if($seoOpen) open @endif>
        <summary class="ab-section__head">
            <h2>E. SEO settings</h2>
            <p>Meta, Open Graph, robots, and schema. Existing meta title / description / keywords are reused.</p>
        </summary>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="meta_title">Meta title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" maxlength="255"
                           data-counter="60" value="{{ old('meta_title', $post->meta_title) }}" placeholder="Defaults to blog title">
                    <small class="ab-counter" data-counter-for="meta_title"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="focus_keyword">Focus keyword</label>
                    <input type="text" name="focus_keyword" id="focus_keyword" class="form-control" maxlength="120"
                           value="{{ old('focus_keyword', $post->focus_keyword) }}">
                </div>
                <div class="col-md-12 form-group">
                    <label for="meta_description">Meta description</label>
                    <textarea name="meta_description" id="meta_description" class="form-control" rows="3" maxlength="500" data-counter="160">{{ old('meta_description', $post->meta_description) }}</textarea>
                    <small class="ab-counter" data-counter-for="meta_description"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="meta_keywords">SEO keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                           value="{{ old('meta_keywords', $post->meta_keywords) }}" placeholder="comma-separated">
                </div>
                <div class="col-md-6 form-group">
                    <label for="canonical_url">Canonical URL</label>
                    <input type="url" name="canonical_url" id="canonical_url" class="form-control @error('canonical_url') is-invalid @enderror"
                           value="{{ old('canonical_url', $post->canonical_url) }}" placeholder="Leave blank to use the public blog URL">
                    @error('canonical_url')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="og_title">OG title</label>
                    <input type="text" name="og_title" id="og_title" class="form-control" maxlength="255" data-counter="60"
                           value="{{ old('og_title', $post->og_title) }}">
                    <small class="ab-counter" data-counter-for="og_title"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="schema_type">Schema type</label>
                    <select name="schema_type" id="schema_type" class="form-control">
                        @foreach($formOptions['schemaTypes'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('schema_type', $post->schema_type ?: 'Article') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label for="og_description">OG description</label>
                    <textarea name="og_description" id="og_description" class="form-control" rows="2" maxlength="500" data-counter="160">{{ old('og_description', $post->og_description) }}</textarea>
                    <small class="ab-counter" data-counter-for="og_description"></small>
                </div>
                <div class="col-md-4 form-group">
                    <label for="og_image">OG image</label>
                    <input type="file" name="og_image" id="og_image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/avif" data-preview="og-preview">
                    @if($post->photoUrl('og_image'))
                        <img src="{{ $post->photoUrl('og_image') }}" alt="" class="ab-thumb-preview" id="og-preview">
                    @else
                        <img src="" alt="" class="ab-thumb-preview is-empty" id="og-preview">
                    @endif
                </div>
                <div class="col-md-4 form-group">
                    <label>Robots index</label>
                    <select name="robots_index" class="form-control">
                        <option value="1" @selected((string) old('robots_index', $post->robots_index ?? true) === '1')>Index</option>
                        <option value="0" @selected((string) old('robots_index', $post->robots_index ?? true) === '0')>No index</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Robots follow</label>
                    <select name="robots_follow" class="form-control">
                        <option value="1" @selected((string) old('robots_follow', $post->robots_follow ?? true) === '1')>Follow</option>
                        <option value="0" @selected((string) old('robots_follow', $post->robots_follow ?? true) === '0')>No follow</option>
                    </select>
                </div>
            </div>
        </div>
    </details>

    <section class="ab-section" id="editorial">
        <div class="ab-section__head">
            <h2>F. Author &amp; editorial</h2>
            <p>Written / reviewed / fact-checked using the existing author and admin roles.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="author">Display author name (legacy)</label>
                    <input type="text" name="author" id="author" class="form-control"
                           value="{{ old('author', $post->getAttributes()['author'] ?? '') }}"
                           placeholder="Optional override shown if no writer is assigned">
                </div>
            </div>
            @include('admin.partials._editorial_fields', ['model' => $post, 'editorialOptions' => $formOptions['editorialOptions'] ?? null])
        </div>
    </section>

    <section class="ab-section" id="related">
        <div class="ab-section__head">
            <h2>G. Related content</h2>
            <p>Manually pick related articles (max 6) and extra categories. Brokers are in section D.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="related_post_ids">Related articles</label>
                    <select name="related_post_ids[]" id="related_post_ids" class="form-control select2" multiple data-placeholder="Search blogs…">
                        @foreach($formOptions['relatedPosts'] as $related)
                            <option value="{{ $related->id }}" @selected($selectedRelated->contains((int) $related->id))>{{ $related->post_title }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">The current blog cannot be selected.</small>
                    @error('related_post_ids')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="related_category_ids">Related categories</label>
                    <select name="related_category_ids[]" id="related_category_ids" class="form-control select2" multiple data-placeholder="Search categories…">
                        @foreach($formOptions['categories'] as $category)
                            <option value="{{ $category->id }}" @selected($selectedCategories->contains((int) $category->id))>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="media">
        <div class="ab-section__head">
            <h2>H. Media</h2>
            <p>Featured image is stored in the existing <code>post_photo</code> field. New social image is optional.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="post_photo">Featured image @if(!$isEdit)<span class="text-danger">*</span>@endif</label>
                    <input type="file" name="post_photo" id="post_photo" class="form-control @error('post_photo') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/gif,image/webp,image/avif" data-preview="photo-preview"
                           @if(!$isEdit) required @endif>
                    @error('post_photo')<small class="ab-error">{{ $message }}</small>@enderror
                    @if($post->photoUrl())
                        <img src="{{ $post->photoUrl() }}" alt="" class="ab-thumb-preview" id="photo-preview">
                    @else
                        <img src="" alt="" class="ab-thumb-preview is-empty" id="photo-preview">
                    @endif
                    <small class="text-muted">JPG, PNG, GIF, WebP, or AVIF. Max 4 MB.</small>
                </div>
                <div class="col-md-4 form-group">
                    <label for="image_alt">Image alt text</label>
                    <input type="text" name="image_alt" id="image_alt" class="form-control" maxlength="255"
                           value="{{ old('image_alt', $post->image_alt) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="image_caption">Image caption</label>
                    <input type="text" name="image_caption" id="image_caption" class="form-control" maxlength="255"
                           value="{{ old('image_caption', $post->image_caption) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="social_image">Social share image</label>
                    <input type="file" name="social_image" id="social_image" class="form-control"
                           accept="image/jpeg,image/png,image/gif,image/webp,image/avif" data-preview="social-preview">
                    @if($post->photoUrl('social_image'))
                        <img src="{{ $post->photoUrl('social_image') }}" alt="" class="ab-thumb-preview" id="social-preview">
                    @else
                        <img src="" alt="" class="ab-thumb-preview is-empty" id="social-preview">
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<aside class="ab-side" id="publish">
    <section class="ab-section">
        <div class="ab-section__head">
            <h2>I. Publishing</h2>
            <p>Status, schedule, sharing, and placement.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" data-status-toggle>
                    @foreach($formOptions['statuses'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="publish_at">Publish date</label>
                <input type="datetime-local" name="publish_at" id="publish_at" class="form-control"
                       value="{{ old('publish_at', optional($post->publish_at)->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="form-group" data-scheduled-field>
                <label for="scheduled_at">Scheduled date</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control"
                       value="{{ old('scheduled_at', optional($post->scheduled_at)->format('Y-m-d\TH:i')) }}">
                <small class="text-muted">Scheduled blogs stay hidden until this time.</small>
            </div>
            @if($isEdit)
                <p class="ab-note">Last updated {{ $post->updated_at?->format('M j, Y H:i') }}</p>
            @endif

            <h6>Visibility</h6>
            @foreach([
                'is_share' => 'Allow sharing',
                'show_author' => 'Show author',
                'show_related_posts' => 'Show related posts',
            ] as $name => $label)
                <label class="ab-check">
                    <input type="hidden" name="{{ $name }}" value="0">
                    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $post->{$name} ?? in_array($name, ['is_share','show_author','show_related_posts'], true)))>
                    {{ $label }}
                </label>
            @endforeach

            <h6>Featured placement</h6>
            @foreach([
                'is_featured' => 'Featured content',
                'featured_homepage' => 'Featured on homepage',
                'featured_blog' => 'Featured on blog page',
                'is_editors_pick' => "Editor's pick",
                'is_popular' => 'Popular article',
                'is_breaking' => 'Breaking / news flag',
            ] as $name => $label)
                <label class="ab-check">
                    <input type="hidden" name="{{ $name }}" value="0">
                    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $post->{$name} ?? false))>
                    {{ $label }}
                </label>
            @endforeach

            @if(!$isEdit)
                <div class="form-group mt-3">
                    <label class="ab-check">
                        <input type="hidden" name="subscriber_send_option" value="0">
                        <input type="checkbox" name="subscriber_send_option" value="1">
                        Email subscribers (if published)
                    </label>
                </div>
            @endif
        </div>
    </section>

    <div class="ab-save ab-save--side">
        <button type="submit" class="ab-btn ab-btn--primary">
            <i class="fas fa-save" aria-hidden="true"></i>
            {{ $isEdit ? 'Save changes' : 'Create Blog' }}
        </button>
        <a href="{{ route('admin_post_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
    </div>
</aside>
</div>
