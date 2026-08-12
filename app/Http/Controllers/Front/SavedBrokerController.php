<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Services\UserSavedBrokerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedBrokerController extends Controller
{
    public function __construct(
        private UserSavedBrokerService $savedBrokers
    ) {}

    public function index()
    {
        $user = Auth::guard('web')->user();

        return response()->json([
            'broker_ids' => $this->savedBrokers->brokerIds($user),
            'count' => count($this->savedBrokers->brokerIds($user)),
        ]);
    }

    public function sync(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'broker_ids' => 'nullable|array',
            'broker_ids.*' => 'integer|exists:brokers,id',
        ]);

        $count = $this->savedBrokers->sync($user, $validated['broker_ids'] ?? []);

        return response()->json([
            'broker_ids' => $this->savedBrokers->brokerIds($user),
            'count' => $count,
        ]);
    }

    public function toggle(Broker $broker)
    {
        $user = Auth::guard('web')->user();
        $saved = $this->savedBrokers->toggle($user, $broker);

        return response()->json([
            'saved' => $saved,
            'broker_ids' => $this->savedBrokers->brokerIds($user),
        ]);
    }

    public function destroy(Broker $broker)
    {
        $user = Auth::guard('web')->user();
        $this->savedBrokers->remove($user, $broker);

        return response()->json([
            'saved' => false,
            'broker_ids' => $this->savedBrokers->brokerIds($user),
        ]);
    }
}
