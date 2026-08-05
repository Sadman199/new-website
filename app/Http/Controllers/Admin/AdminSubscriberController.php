<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Mail\Websitemail;

class AdminSubscriberController extends Controller
{
  
 // Display all subscribers
 public function show_all()
 {
     $subscribers = Subscriber::all(); 
     return view('admin.subscriber_all', compact('subscribers'));
 }

 // Accept subscriber
 public function accept($id)
 {
     $subscriber = Subscriber::findOrFail($id);
     $subscriber->status = 'active';
     $subscriber->save();

     return redirect()->back()->with('success', 'Subscriber accepted.');
 }

 // Decline subscriber
 public function decline($id)
 {
     $subscriber = Subscriber::findOrFail($id);
     $subscriber->status = 'inactive';
     $subscriber->save();

     return redirect()->back()->with('success', 'Subscriber declined.');
 }

 public function delete($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }


    public function send_email()
    {
        return view('admin.subscriber_send_email');
    }

    public function send_email_submit(Request $request)
{
    $request->validate([
        'subject' => 'required',
        'message' => 'required'
    ]);

    $subject = $request->subject;
    $message = $request->message;
    $subscribers = Subscriber::where('status', 'Active')->get();

    foreach ($subscribers as $subscriber) {
        // Use the SubscriptionVerification mailable class here
        \Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber));
    }
    return redirect()->back()->with('success', 'Email is sent successfully.');
}



}