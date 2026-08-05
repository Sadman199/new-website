<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Mail\SubscriptionVerification;
use Illuminate\Support\Str;


class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        // Create the new subscriber
        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->status = 'Pending';  // Set status to Inactive initially
        $subscriber->token = Str::random(60);  // Use Str::random() for generating the token
        $subscriber->save();

        // Send the subscription verification email
        \Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber));

        return redirect()->back()->with('success', 'Subscription successful! Please check your email to verify your subscription.');
    }

    public function verify($token, $email)
    {
        // Find the subscriber by email and token
        $subscriber = Subscriber::where('email', $email)->where('token', $token)->first();
    
        if ($subscriber) {
            // Update the status to 'Active' after successful verification
            $subscriber->status = 'Active';
            $subscriber->save();
    
            return redirect()->route('home')->with('success', 'Subscription verified successfully!');
        }
    
        return redirect()->route('home')->with('error', 'Invalid verification link or token.');
    }
    
}
