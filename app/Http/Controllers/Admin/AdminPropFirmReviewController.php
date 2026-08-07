<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropFirmReviewRequest;
use App\Models\PropFirm;
use App\Models\PropFirmReview;
use Illuminate\Http\Request;

class AdminPropFirmReviewController extends Controller
{
    public function show(Request $request)
    {
        $query = PropFirmReview::with('propFirm');

        if ($request->filled('prop_firm_id')) {
            $query->where('prop_firm_id', $request->integer('prop_firm_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%');
            });
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $propFirms = PropFirm::orderBy('name')->get(['id', 'name']);

        return view('admin.prop-firms.reviews.show', compact('reviews', 'propFirms'));
    }

    public function create()
    {
        return view('admin.prop-firms.reviews.create', [
            'review' => new PropFirmReview(),
            'propFirms' => PropFirm::orderBy('name')->get(),
        ]);
    }

    public function store(PropFirmReviewRequest $request)
    {
        PropFirmReview::create($request->validated());

        return redirect()->route('admin_prop_firm_reviews_show')->with('success', 'Review created successfully.');
    }

    public function edit(int $id)
    {
        $review = PropFirmReview::findOrFail($id);

        return view('admin.prop-firms.reviews.edit', [
            'review' => $review,
            'propFirms' => PropFirm::orderBy('name')->get(),
        ]);
    }

    public function update(PropFirmReviewRequest $request, int $id)
    {
        PropFirmReview::findOrFail($id)->update($request->validated());

        return redirect()->route('admin_prop_firm_reviews_show')->with('success', 'Review updated successfully.');
    }

    public function delete(int $id)
    {
        PropFirmReview::findOrFail($id)->delete();

        return redirect()->route('admin_prop_firm_reviews_show')->with('success', 'Review deleted successfully.');
    }
}
