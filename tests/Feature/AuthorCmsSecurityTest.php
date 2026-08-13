<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\SubCategory;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorCmsSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_author_login(): void
    {
        $this->get(route('author_home'))
            ->assertRedirect(route('author_login'));
    }

    public function test_guest_cannot_store_or_update_posts(): void
    {
        $post = $this->makePost($this->makeAuthor('owner@test.com'));

        $this->post(route('author_post_store'), [
            'post_title' => 'Hacked',
            'post_detail' => 'Body',
        ])->assertRedirect(route('author_login'));

        $this->post(route('author_post_update', $post->id), [
            'post_title' => 'Hacked',
            'post_detail' => 'Body',
        ])->assertRedirect(route('author_login'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'post_title' => 'Owned Post',
        ]);
    }

    public function test_get_delete_routes_are_rejected(): void
    {
        $author = $this->makeAuthor();
        $post = $this->makePost($author);
        $tag = new Tag();
        $tag->post_id = $post->id;
        $tag->tag_name = 'forex';
        $tag->save();

        $this->actingAs($author, 'author')
            ->get('/author/post/delete/' . $post->id)
            ->assertStatus(405);

        $this->actingAs($author, 'author')
            ->get('/author/post/tag/delete/' . $tag->id . '/' . $post->id)
            ->assertStatus(405);

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    public function test_author_cannot_update_or_delete_another_authors_post(): void
    {
        $owner = $this->makeAuthor('owner@test.com');
        $intruder = $this->makeAuthor('intruder@test.com');
        $post = $this->makePost($owner);

        $this->actingAs($intruder, 'author')
            ->post(route('author_post_update', $post->id), [
                'post_title' => 'Stolen',
                'post_detail' => 'Body',
                'sub_category_id' => $post->sub_category_id,
                'is_share' => 1,
                'is_comment' => 1,
                'language_id' => $post->language_id,
            ])
            ->assertNotFound();

        $this->actingAs($intruder, 'author')
            ->delete(route('author_post_delete', $post->id))
            ->assertNotFound();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'post_title' => 'Owned Post',
            'author_id' => $owner->id,
        ]);
    }

    public function test_author_cannot_delete_tag_on_another_authors_post(): void
    {
        $owner = $this->makeAuthor('owner@test.com');
        $intruder = $this->makeAuthor('intruder@test.com');
        $post = $this->makePost($owner);
        $tag = new Tag();
        $tag->post_id = $post->id;
        $tag->tag_name = 'asic';
        $tag->save();

        $this->actingAs($intruder, 'author')
            ->delete(route('author_post_delete_tag', [$tag->id, $post->id]))
            ->assertNotFound();

        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    public function test_author_can_delete_own_post_with_delete_method(): void
    {
        $author = $this->makeAuthor();
        $post = $this->makePost($author);

        $this->actingAs($author, 'author')
            ->delete(route('author_post_delete', $post->id))
            ->assertRedirect(route('author_post_show'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_author_can_log_in_and_reach_home(): void
    {
        $this->makeAuthor('writer@test.com');

        $this->post(route('author_login_submit'), [
            'email' => 'writer@test.com',
            'password' => 'password',
        ])->assertRedirect(route('author_home'));

        $this->get(route('author_home'))->assertOk();
    }

    protected function makeAuthor(string $email = 'author@test.com'): Author
    {
        return Author::create([
            'name' => 'Test Author',
            'email' => $email,
            'password' => Hash::make('password'),
            'token' => '',
            'can_write' => true,
        ]);
    }

    protected function makePost(Author $author): Post
    {
        return Post::create([
            'sub_category_id' => $this->makeSubCategoryId(),
            'post_title' => 'Owned Post',
            'slug' => 'owned-post-' . $author->id,
            'post_detail' => 'Body',
            'post_photo' => 'test.jpg',
            'visitors' => 1,
            'author_id' => $author->id,
            'admin_id' => 0,
            'is_share' => 1,
            'is_comment' => 1,
            'language_id' => $this->makeLanguageId(),
        ]);
    }

    protected function makeLanguageId(): int
    {
        return Language::create([
            'name' => 'English',
            'short_name' => 'en-' . uniqid(),
            'is_default' => 'Yes',
        ])->id;
    }

    protected function makeSubCategoryId(): int
    {
        $categoryId = Category::create([
            'category_name' => 'News',
            'slug' => 'news-' . uniqid(),
            'show_on_menu' => 'Yes',
            'category_order' => '1',
            'language_id' => $this->makeLanguageId(),
        ])->id;

        return SubCategory::create([
            'sub_category_name' => 'Forex',
            'slug' => 'forex-' . uniqid(),
            'show_on_menu' => 'Show',
            'sub_category_order' => 1,
            'category_id' => $categoryId,
            'language_id' => $this->makeLanguageId(),
        ])->id;
    }
}
