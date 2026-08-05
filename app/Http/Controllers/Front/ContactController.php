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
use Illuminate\Support\Facades\RateLimiter;
use NoCaptcha\Facades\NoCaptcha;
use Illuminate\Support\Facades\Http;




class ContactController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        if(!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }    
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
        
        $page_data = Page::where('language_id',$current_language_id)->first();
        return view('front.pages.contact', compact('page_data'));
    }


    public function showForm()
    {
        // Assuming you want to get the same page data in the contact form
        Helpers::read_json();
    
        if (!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
    
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
        $page_data = Page::where('language_id', $current_language_id)->first();
    
        return view('contact', compact('page_data'));
    }
    
      public function submitForm(Request $request)
    {
        // Rate limiting to prevent spam submissions
        $key = 'contact_form:' . $request->ip();
        $maxAttempts = 2;
        $decaySeconds = 300; // 5 minutes
    
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return back()->withErrors([
                'message' => 'You are submitting too many requests. Please try again later.'
            ]);
        }
    
        RateLimiter::hit($key, $decaySeconds);
    
        // Honeypot check
        if (!empty($request->extra_field)) {
            return back()->withErrors([
                'message' => 'Invalid submission detected.'
            ]);
        }
    
        // Validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required|string',
        ]);
    
        // reCAPTCHA verify (safe version)
        $secret = config('services.recaptcha.secret');
        $responseToken = $request->input('g-recaptcha-response');
    
        try {
            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$responseToken}&remoteip={$request->ip()}"
            );
    
            $result = json_decode($response, true);
    
            if (!isset($result['success']) || $result['success'] !== true) {
                return back()->withErrors([
                    'captcha_error' => 'Please complete the reCAPTCHA.'
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'captcha_error' => 'reCAPTCHA verification failed. Try again.'
            ]);
        }
    
        // Send email (IMPORTANT: view must exist)
        Mail::to('info@brokerscourt.com')->send(
            new ContactFormMail(
                $request->name,
                $request->email,
                $request->message
            )
        );
    
        return back()->with('success', 'Your message has been sent successfully!');
    }
        

}
