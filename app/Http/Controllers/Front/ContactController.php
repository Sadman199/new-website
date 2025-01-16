<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Websitemail;
use App\Models\Page;
use App\Models\Admin;
use App\Models\Language;
use App\Helper\Helpers;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail; 


class ContactController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        if(!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default','Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }    
        $current_language_id = Language::where('short_name',$current_short_name)->first()->id;
        
        $page_data = Page::where('language_id',$current_language_id)->first();
        return view('front.contact', compact('page_data'));
    }


    public function showForm()
    {
        // Assuming you want to get the same page data in the contact form
        Helpers::read_json();
    
        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }
    
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
        $page_data = Page::where('language_id', $current_language_id)->first();
    
        return view('contact', compact('page_data'));
    }
    
    public function submitForm(Request $request)
    {
        // Validate the form inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
    
        // Send the email using the ContactFormMail class
        Mail::to('info@brokerscourt.com')->send(new ContactFormMail(
            $validated['name'],
            $validated['email'],
            $validated['message']
        ));
    
        // Flash success message to session
        session()->flash('success', 'Your message has been sent successfully!');
    
        // Redirect back to the same page with success message
        return redirect()->back();
    }
    
    

}
