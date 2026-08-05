<?php

namespace App\Http\Controllers\Admin\Panel;

use App\Models\Author;

class PanelAuthorsController extends PanelBaseController
{
    public function index()
    {
        $authors = Author::query()
            ->withCount([
                'postsWritten as written_posts_count',
                'postsEdited as edited_posts_count',
                'postsFactChecked as fact_checked_posts_count',
            ])
            ->orderBy('name')
            ->get();

        return $this->render('admin.panel.pages.authors.index', [
            'title' => 'Authors',
            'pageTitle' => 'Authors',
            'authors' => $authors,
        ]);
    }
}
