<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Language;
use App\Models\Post;
use App\Models\SubCategory;
use App\Services\EditorialAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorEditorialRolesTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'photo' => '',
            'token' => 'test-token',
        ]);
    }

    public function test_admin_can_create_author_with_editorial_roles(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin_author_store'), [
            'name' => 'Writer One',
            'email' => 'writer@test.com',
            'password' => 'secret123',
            'retype_password' => 'secret123',
            'can_write' => '1',
            'can_edit' => '1',
            'can_fact_check' => '0',
        ]);

        $response->assertRedirect(route('admin_author_show'));

        $this->assertDatabaseHas('authors', [
            'email' => 'writer@test.com',
            'can_write' => 1,
            'can_edit' => 1,
            'can_fact_check' => 0,
        ]);
    }

    public function test_author_list_shows_role_badges_and_counts(): void
    {
        $author = Author::create([
            'name' => 'Checker',
            'email' => 'checker@test.com',
            'password' => Hash::make('password'),
            'token' => '',
            'can_write' => true,
            'can_edit' => false,
            'can_fact_check' => true,
        ]);

        Post::create([
            'sub_category_id' => $this->makeSubCategoryId(),
            'post_title' => 'Sample Post',
            'slug' => 'sample-post',
            'post_detail' => 'Body',
            'post_photo' => 'test.jpg',
            'visitors' => 1,
            'author_id' => 0,
            'admin_id' => $this->admin->id,
            'is_share' => 1,
            'is_comment' => 1,
            'language_id' => $this->makeLanguageId(),
            'fact_checked_by_author_id' => $author->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin_author_show'));

        $response->assertOk();
        $response->assertSee('Written');
        $response->assertSee('Fact-Checked');
        $response->assertSee('Checker');
    }

    public function test_editorial_assignment_service_resolves_post_credits(): void
    {
        $writer = Author::create([
            'name' => 'Writer',
            'email' => 'writer2@test.com',
            'password' => Hash::make('password'),
            'token' => '',
            'can_write' => true,
        ]);

        $editor = Author::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => Hash::make('password'),
            'token' => '',
            'can_edit' => true,
        ]);

        $post = Post::create([
            'sub_category_id' => $this->makeSubCategoryId(),
            'post_title' => 'Credit Post',
            'slug' => 'credit-post',
            'post_detail' => 'Body',
            'post_photo' => 'test.jpg',
            'visitors' => 1,
            'author_id' => $writer->id,
            'admin_id' => 0,
            'is_share' => 1,
            'is_comment' => 1,
            'language_id' => $this->makeLanguageId(),
            'written_by_author_id' => $writer->id,
            'edited_by_author_id' => $editor->id,
            'edited_by_admin_id' => null,
        ]);

        $credits = EditorialAssignmentService::creditsForPost($post);

        $this->assertCount(2, $credits);
        $this->assertSame('Writer', $credits[0]['name']);
        $this->assertSame('Editor', $credits[1]['name']);
    }

    protected function makeLanguageId(): int
    {
        return Language::create([
            'name' => 'English',
            'short_name' => 'en',
            'is_default' => 'Yes',
        ])->id;
    }

    protected function makeSubCategoryId(): int
    {
        $categoryId = \App\Models\Category::create([
            'category_name' => 'News',
            'slug' => 'news',
            'show_on_menu' => 'Yes',
            'category_order' => '1',
            'language_id' => $this->makeLanguageId(),
        ])->id;

        return SubCategory::create([
            'sub_category_name' => 'Forex',
            'slug' => 'forex',
            'category_id' => $categoryId,
            'language_id' => $this->makeLanguageId(),
        ])->id;
    }
}
