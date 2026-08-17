<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionVerification;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SubscriberController extends Controller
{
    public function index()
    {
        return view('front.subscribe.index');
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:254'],
        ]);

        $email = Str::lower(trim($validated['email']));
        $subscriber = Subscriber::firstOrNew(['email' => $email]);

        if ($subscriber->exists && $subscriber->status === 'Active') {
            return back()->with('success', 'You are already subscribed to BrokersCourt updates.');
        }

        $subscriber->status = 'Pending';
        $subscriber->token = Str::random(60);
        $subscriber->save();

        try {
            Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber));
        } catch (Throwable $exception) {
            Log::error('Subscription verification email could not be sent.', [
                'subscriber_id' => $subscriber->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'We saved your request, but could not send the verification email. Please try again shortly.');
        }

        return back()->with('success', 'Please check your inbox and verify your subscription.');
    }

    public function verify(string $token, string $email)
    {
        $subscriber = Subscriber::where('email', $email)->where('token', $token)->first();

        if ($subscriber) {
            $subscriber->status = 'Active';
            $subscriber->save();

            return redirect()->route('home')->with('success', 'Subscription verified successfully!');
        }

        return redirect()->route('home')->with('error', 'Invalid verification link or token.');
    }
}
