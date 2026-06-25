<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AccountOption;
use App\Models\Broker;
use Illuminate\Http\Request;

class AccountOptionController extends Controller
{
   // Display all account options for a broker
   public function index($broker_id)
   {
       $broker = Broker::findOrFail($broker_id);
       $accountOptions = $broker->accountOptions; // Assuming relationship is defined in Broker model
       return view('admin.account_options.index', compact('broker', 'accountOptions'));
   }

   // Show the form to create a new account option
  
   public function create($broker_id)
   {
       $broker = Broker::findOrFail($broker_id); // Ensure the broker exists
       return view('admin.account_options.create', compact('broker')); // Pass broker details to the view
   }
   

   // Store a new account option
   public function store(Request $request, $broker_id)
   {
       $request->validate([
        'account_type' => 'required|string|max:255',
        'account_currency' => 'required|string|max:255',
        'min_deposit' => 'required|numeric',
        'max_leverage' => 'nullable|string',
        'spread_type' => 'required|string|max:255',
        'spread_value' => 'nullable|numeric',
        'is_demo_available' => 'nullable|boolean',
        'swap_free' => 'nullable|boolean',
        'min_trade_size' => 'nullable|string',
        'max_trade_size' => 'nullable|string',
        'margin_call_level' => 'nullable|numeric',
        'stop_out_level' => 'required|numeric',
        'max_open_positions' => 'nullable|string',
        'commission' => 'nullable|string',
        'interest_rate' => 'nullable|string',
        'access_to_pro_features' => 'nullable|boolean',
        'exclusive_offers' => 'nullable|string',
        'account_management' => 'nullable|boolean',
        'trading_instruments' => 'nullable|string',
        'risk_management_tools' => 'nullable|string',
        'bonus_eligibility' => 'nullable|boolean',
        'personalized_education' => 'nullable|boolean',
        'exclusive_webinars' => 'nullable|boolean',
        'maximum_daily_trade_volume' => 'nullable|numeric',
        'trading_hours' => 'nullable|string',
        'special_conditions' => 'nullable|string',
        'is_regulated' => 'required|boolean',
       ]);

       $broker = Broker::findOrFail($broker_id);

        $accountOption = new AccountOption();
        $accountOption->broker_id = $broker_id; // Foreign key to broker
        $accountOption->account_type = $request->account_type; // E.g., Standard, Premium
        $accountOption->account_currency = $request->account_currency; // E.g., USD, EUR
        $accountOption->min_deposit = $request->min_deposit; // Minimum deposit
        $accountOption->max_leverage = $request->max_leverage; // Maximum leverage
        $accountOption->spread_type = $request->spread_type; // E.g., Fixed, Variable
        $accountOption->spread_value = $request->spread_value; // Spread value
        $accountOption->is_demo_available = $request->has('is_demo_available'); // Whether demo is available
        $accountOption->features = $request->features ? json_encode($request->features) : null; // JSON-encoded features
        $accountOption->swap_free = $request->has('swap_free'); // Whether swap-free
        $accountOption->min_trade_size = $request->min_trade_size; // Minimum trade size
        $accountOption->max_trade_size = $request->max_trade_size; // Maximum trade size
        $accountOption->margin_call_level = $request->margin_call_level; // Margin call level
        $accountOption->stop_out_level = $request->stop_out_level; // Stop-out level
        $accountOption->max_open_positions = $request->max_open_positions; // Max open positions
        $accountOption->commission = $request->commission; // Commission per trade (nullable)
        $accountOption->interest_rate = $request->interest_rate; // Interest rate for overnight financing (nullable)
        $accountOption->access_to_pro_features = $request->has('access_to_pro_features'); // Access to professional features
        $accountOption->exclusive_offers = $request->exclusive_offers; // Special offers (nullable)
        $accountOption->account_management = $request->has('account_management'); // Dedicated account manager
        $accountOption->trading_instruments = $request->trading_instruments ?: null; // Store as plain text
        $accountOption->risk_management_tools = $request->risk_management_tools ?: null; // Store as plain text        
        $accountOption->bonus_eligibility = $request->has('bonus_eligibility'); // Whether eligible for bonuses
        $accountOption->personalized_education = $request->has('personalized_education'); // Personalized education
        $accountOption->exclusive_webinars = $request->has('exclusive_webinars'); // Exclusive webinars access
        $accountOption->maximum_daily_trade_volume = $request->maximum_daily_trade_volume; // Max daily trade volume (nullable)
        $accountOption->trading_hours = $request->trading_hours; // Trading hours (e.g., 24/5, 24/7)
        $accountOption->special_conditions = $request->special_conditions; // Special conditions (nullable)
        $accountOption->is_regulated = $request->has('is_regulated'); // Whether the account is regulated
        $accountOption->save();


       return redirect()->route('admin_account_options_index', $broker_id)->with('success', 'Account option created successfully');
   }

   // Show the form to edit an existing account option
   public function edit($broker_id, $id)
   {
       $broker = Broker::findOrFail($broker_id);
       $accountOption = AccountOption::findOrFail($id);
       return view('admin.account_options.edit', compact('broker', 'accountOption'));
   }

   
   // Update an existing account option
   public function update(Request $request, $broker_id, $id)
   {
       $request->validate([
        'account_type' => 'required|string|max:255',
        'account_currency' => 'required|string|max:255',
        'min_deposit' => 'required|numeric',
        'max_leverage' => 'nullable|string',
        'spread_type' => 'required|string|max:255',
        'spread_value' => 'nullable|numeric',
        'is_demo_available' => 'nullable|boolean',
        'swap_free' => 'nullable|boolean',
        'min_trade_size' => 'nullable|string',
        'max_trade_size' => 'nullable|string',
        'margin_call_level' => 'nullable|numeric',
        'stop_out_level' => 'required|numeric',
        'max_open_positions' => 'nullable|string',
        'commission' => 'nullable|string',
        'interest_rate' => 'nullable|string',
        'access_to_pro_features' => 'nullable|boolean',
        'exclusive_offers' => 'nullable|string',
        'account_management' => 'nullable|boolean',
        'trading_instruments' => 'nullable|string',
        'risk_management_tools' => 'nullable|string',
        'bonus_eligibility' => 'nullable|boolean',
        'personalized_education' => 'nullable|boolean',
        'exclusive_webinars' => 'nullable|boolean',
        'maximum_daily_trade_volume' => 'nullable|numeric',
        'trading_hours' => 'nullable|string',
        'special_conditions' => 'nullable|string',
        'is_regulated' => 'required|boolean',
       ]);

       $accountOption = AccountOption::findOrFail($id);
       $accountOption->account_type = $request->account_type; // E.g., Standard, Premium
       $accountOption->account_currency = $request->account_currency; // E.g., USD, EUR
       $accountOption->min_deposit = $request->min_deposit; // Minimum deposit
       $accountOption->max_leverage = $request->max_leverage; // Maximum leverage
       $accountOption->spread_type = $request->spread_type; // E.g., Fixed, Variable
       $accountOption->spread_value = $request->spread_value; // Spread value
       $accountOption->is_demo_available = $request->has('is_demo_available'); // Whether demo is available
       $accountOption->features = $request->features ? json_encode($request->features) : null; // JSON-encoded features
       $accountOption->swap_free = $request->has('swap_free'); // Whether swap-free
       $accountOption->min_trade_size = $request->min_trade_size; // Minimum trade size
       $accountOption->max_trade_size = $request->max_trade_size; // Maximum trade size
       $accountOption->margin_call_level = $request->margin_call_level; // Margin call level
       $accountOption->stop_out_level = $request->stop_out_level; // Stop-out level
       $accountOption->max_open_positions = $request->max_open_positions; // Max open positions
       $accountOption->commission = $request->commission; // Commission per trade (nullable)
       $accountOption->interest_rate = $request->interest_rate; // Interest rate for overnight financing (nullable)
       $accountOption->access_to_pro_features = $request->has('access_to_pro_features'); // Access to professional features
       $accountOption->exclusive_offers = $request->exclusive_offers; // Special offers (nullable)
       $accountOption->account_management = $request->has('account_management'); // Dedicated account manager
       $accountOption->trading_instruments = $request->trading_instruments ?: null; // Store as plain text
       $accountOption->risk_management_tools = $request->risk_management_tools ?: null; // Store as plain text 
       $accountOption->bonus_eligibility = $request->has('bonus_eligibility'); // Whether eligible for bonuses
       $accountOption->personalized_education = $request->has('personalized_education'); // Personalized education
       $accountOption->exclusive_webinars = $request->has('exclusive_webinars'); // Exclusive webinars access
       $accountOption->maximum_daily_trade_volume = $request->maximum_daily_trade_volume; // Max daily trade volume (nullable)
       $accountOption->trading_hours = $request->trading_hours; // Trading hours (e.g., 24/5, 24/7)
       $accountOption->special_conditions = $request->special_conditions; // Special conditions (nullable)
       $accountOption->is_regulated = $request->input('is_regulated', false); // Default to false if not set

       $accountOption->save();

       return redirect()->route('admin_account_options_index', $broker_id)
       ->with('success', 'Account option created successfully.');}

   // Delete an account option
   public function delete($broker_id, $id)
   {
       $accountOption = AccountOption::findOrFail($id);
       $accountOption->delete();

       return redirect()->route('admin_account_options_index', $broker_id)->with('success', 'Account option deleted successfully');
   }
}