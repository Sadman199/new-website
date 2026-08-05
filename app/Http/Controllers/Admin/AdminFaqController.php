<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Broker;

class AdminFaqController extends Controller
{
    public function show()
    {
        $faq_data = Faq::get();
        return view('admin.faq_show', compact('faq_data'));
    }

    public function create()
    {
        return view('admin.faq_create');
    }

    public function store(Request $request)
    {
         // Validate the incoming request
    $validated = $request->validate([
        'faq_title' => 'required|string|max:255',
        'faq_detail' => 'required|string',
        'language_id' => 'required|exists:languages,id', // Assuming you have languages table
        'broker_id' => 'required|exists:brokers,id', // Ensure broker_id exists in brokers table
    ]);

    // Create the FAQ object
    $faq = new Faq();
    $faq->faq_title = $validated['faq_title'];
    $faq->faq_detail = $validated['faq_detail'];
    $faq->language_id = $validated['language_id'];
    $faq->broker_id = $validated['broker_id'];  // Set the broker_id
    $faq->save();


        return redirect()->route('admin_faq_show')->with('success', 'Data is added successfully.');
    }

    public function edit($id)
    {
       // Fetch the FAQ data and brokers
    $faq_data = Faq::where('id', $id)->first();
    $brokers = Broker::all(); // Fetch all brokers

    // Return the edit view with faq_data and brokers
    return view('admin.faq_edit', compact('faq_data', 'brokers'));
    }

    public function update(Request $request,$id) 
    {
       // Validate the incoming request
    $request->validate([
        'faq_title' => 'required|string|max:255',
        'faq_detail' => 'required|string',
        'language_id' => 'required|exists:languages,id', // Assuming you have languages table
        'broker_id' => 'required|exists:brokers,id', // Ensure broker_id exists in brokers table
    ]);

    // Find the FAQ to update
    $faq = Faq::where('id', $id)->first();

    // Update the FAQ fields
    $faq->faq_title = $request->faq_title;
    $faq->faq_detail = $request->faq_detail;
    $faq->language_id = $request->language_id;
    $faq->broker_id = $request->broker_id; // Set the broker_id
    $faq->save(); // Save the updated FAQ

    // Redirect to the FAQ list with success message
    return redirect()->route('admin_faq_show')->with('success', 'FAQ updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
    
        // Preserve pagination
        $currentPage = $request->input('page', 1);
    
        return redirect()->route('admin_faq_show', ['page' => $currentPage])
                         ->with('success', 'FAQ deleted successfully.');
    }


}