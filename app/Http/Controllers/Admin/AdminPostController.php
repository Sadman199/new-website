<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\Subscriber;
use App\Mail\Websitemail;
use App\Services\EditorialAssignmentService;
use Auth;
use DB;

class AdminPostController extends Controller
{
    public function show()
    {
        $posts = Post::with('rSubCategory.rCategory','rLanguage')->get();
        return view('admin.post_show', compact('posts'));
    }

    public function create()
    {
        $sub_categories = SubCategory::with('rCategory')->get();
        $editorialOptions = $this->editorialOptions();

        return view('admin.post_create', compact('sub_categories', 'editorialOptions'));
    }

public function store(Request $request)
{
    $request->validate([
            'post_title' => 'required',
            'post_detail' => 'required',
            'post_photo' => 'required|image|mimes:avif|max:18',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'written_assignee' => 'nullable|string',
            'edited_assignee' => 'nullable|string',
            'fact_checked_assignee' => 'nullable|string',
    ]);

    // Handle file upload and set $final_name for post_photo
    if ($request->hasFile('post_photo')) {
        $now = time(); // Current timestamp for uniqueness
        $ext = $request->file('post_photo')->extension(); // Get file extension
        $final_name = 'post_photo_' . $now . '.' . $ext; // Generate a unique file name
    
        // Move the uploaded file to the correct directory in production
        $request->file('post_photo')->move($_SERVER['DOCUMENT_ROOT'].'/uploads/', $final_name);
    }


    // Save Post to Database
    $post = new Post();
    $post->sub_category_id = $request->sub_category_id;
    $post->post_title = $request->post_title;
    $post->slug = $request->slug;
    $post->post_detail = $request->post_detail;
    $post->post_photo = $final_name;  // Use $final_name for the image
    $post->visitors = 1;
    $post->author_id = 0;
    $post->admin_id = Auth::guard('admin')->user()->id;
    $post->is_share = $request->is_share;
    $post->is_comment = $request->is_comment;
    $post->language_id = $request->language_id;
    // Add meta fields
    $post->meta_title = $request->meta_title;
    $post->meta_description = $request->meta_description;
    $post->meta_keywords = $request->meta_keywords;
    $post->author = $request->author;
    $this->applyEditorialAssignments($post, $request);
    $post->save();  // Save first to get the post ID

    // Get the ID of the newly created post
    $ai_id = $post->id;

    // Handle Tags
    if ($request->tags != '') {
        $tags_array_new = [];
        $tags_array = explode(',', $request->tags);
        for ($i = 0; $i < count($tags_array); $i++) {
            $tags_array_new[] = trim($tags_array[$i]);
        }
        $tags_array_new = array_values(array_unique($tags_array_new));

        foreach ($tags_array_new as $tag_name) {
            $tag = new Tag();
            $tag->post_id = $ai_id;  // Use the ID from the saved post
            $tag->tag_name = trim($tag_name);
            $tag->save();
        }
    }

    return redirect()->route('admin_post_show')->with('success', 'Data is added successfully.');
}


    public function edit($id)
    {
        $test = Post::where('id',$id)->where('admin_id',Auth::guard('admin')->user()->id)->count();
        if(!$test) {
            return redirect()->route('admin_home');
        }


        $sub_categories = SubCategory::with('rCategory')->get();
        $existing_tags = Tag::where('post_id',$id)->get();
        $post_single = Post::where('id',$id)->first();
        $editorialOptions = $this->editorialOptions();

        return view('admin.post_edit', compact('post_single','sub_categories','existing_tags', 'editorialOptions'));
    }


    
    
  public function update(Request $request, $id)
{
    // Validate basic post details
    $request->validate([
        'post_title' => 'required',
        'post_detail' => 'required',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'author' => 'nullable|string|max:255',
        'written_assignee' => 'nullable|string',
        'edited_assignee' => 'nullable|string',
        'fact_checked_assignee' => 'nullable|string',
    ]);

    $post = Post::findOrFail($id); // Ensure the post exists

    // Handle image upload if a new photo is provided
    if ($request->hasFile('post_photo')) {
        // Validate the uploaded image
        $request->validate([
            'post_photo' => 'image|mimes:jpg,jpeg,png,gif'
        ]);

        // Check if there's an old image and delete it
        if (!empty($post->post_photo)) {
            $oldImagePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $post->post_photo;

            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
        }

        // Reuse the old filename if it exists, otherwise create a new one
        $ext = $request->file('post_photo')->extension();
        $final_name = !empty($post->post_photo) 
                      ? basename($post->post_photo) 
                      : 'post_photo_' . time() . '.' . $ext;

        // Upload the new image
        $request->file('post_photo')->move($_SERVER['DOCUMENT_ROOT'] . '/uploads/', $final_name);

        // Update the post_photo field in the database
        $post->post_photo = $final_name;
    }

    // Update post details
    $post->sub_category_id = $request->sub_category_id;
    $post->post_title = $request->post_title;
    $post->slug = $request->slug;
    $post->post_detail = $request->post_detail;
    $post->is_share = $request->is_share;
    $post->is_comment = $request->is_comment;
    $post->language_id = $request->language_id;
    // Add meta fields
    $post->meta_title = $request->meta_title;
    $post->meta_description = $request->meta_description;
    $post->meta_keywords = $request->meta_keywords;
    $post->author = $request->author;
    $this->applyEditorialAssignments($post, $request);
    $post->save(); // Save the post

    // Handle Tags
    if (!empty($request->tags)) {
        $tags_array = explode(',', $request->tags);

        // Remove existing tags first (optional but cleaner)
        Tag::where('post_id', $id)->delete();

        // Add new tags
        foreach ($tags_array as $tag_name) {
            $tag_name = trim($tag_name);
            if (!empty($tag_name)) {
                $tag = new Tag();
                $tag->post_id = $id;
                $tag->tag_name = $tag_name;
                $tag->save();
            }
        }
    }

    return redirect()->route('admin_post_show')->with('success', 'Data is updated successfully.');
}



    

    public function delete_tag($id,$id1)
    {
        $tag = Tag::where('id',$id)->first();
        $tag->delete();
        return redirect()->route('admin_post_edit',$id1)->with('success', 'Data is deleted successfully.');
    }

    public function delete($id)
    {
        $test = Post::where('id', $id)->where('admin_id', Auth::guard('admin')->user()->id)->count();
        if(!$test) {
            return redirect()->route('admin_home');
        }
        
        $post = Post::where('id', $id)->first();
    
        // Check if the file exists before trying to delete it
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$post->post_photo;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file if it exists
        }
    
        // Delete the post record from the database
        $post->delete();
    
        // Delete related tags
        Tag::where('post_id', $id)->delete();
    
        return redirect()->route('admin_post_show')->with('success', 'Data is deleted successfully.');
    }

    protected function editorialOptions(): array
    {
        return [
            EditorialAssignmentService::ROLE_WRITTEN => EditorialAssignmentService::assigneeOptions(EditorialAssignmentService::ROLE_WRITTEN),
            EditorialAssignmentService::ROLE_EDITED => EditorialAssignmentService::assigneeOptions(EditorialAssignmentService::ROLE_EDITED),
            EditorialAssignmentService::ROLE_FACT_CHECKED => EditorialAssignmentService::assigneeOptions(EditorialAssignmentService::ROLE_FACT_CHECKED),
        ];
    }

    protected function applyEditorialAssignments(Post $post, Request $request): void
    {
        EditorialAssignmentService::applyAssignee($post, EditorialAssignmentService::ROLE_WRITTEN, $request->input('written_assignee'));
        EditorialAssignmentService::applyAssignee($post, EditorialAssignmentService::ROLE_EDITED, $request->input('edited_assignee'));
        EditorialAssignmentService::applyAssignee($post, EditorialAssignmentService::ROLE_FACT_CHECKED, $request->input('fact_checked_assignee'));

        if ($post->written_by_author_id) {
            $post->author_id = $post->written_by_author_id;
            $post->admin_id = 0;
        } elseif ($post->written_by_admin_id) {
            $post->author_id = 0;
            $post->admin_id = $post->written_by_admin_id;
        }
    }

}
