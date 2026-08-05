<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountOptionRequest;
use App\Models\AccountOption;
use App\Models\Broker;
use App\Services\AccountOptionAdminService;
use Illuminate\Http\Request;

class AccountOptionController extends Controller
{
    public function __construct(
        protected AccountOptionAdminService $service
    ) {}

    public function all(Request $request)
    {
        $query = AccountOption::with('broker')->orderBy('broker_id')->ordered();

        if ($request->filled('broker_id')) {
            $query->where('broker_id', $request->integer('broker_id'));
        }

        if ($request->filled('q')) {
            $term = $request->string('q');
            $query->where(function ($sub) use ($term) {
                $sub->where('account_type', 'like', '%' . $term . '%')
                    ->orWhereHas('broker', fn ($b) => $b->where('name', 'like', '%' . $term . '%'));
            });
        }

        $accountOptions = $query->paginate(20)->withQueryString();
        $brokers = Broker::orderBy('name')->get(['id', 'name']);

        return view('admin.account_options.all', compact('accountOptions', 'brokers'));
    }

    public function index($broker_id)
    {
        $broker = Broker::withCount('accountOptions')->findOrFail($broker_id);
        $accountOptions = $broker->accountOptions()->ordered()->get();

        return view('admin.account_options.index', compact('broker', 'accountOptions'));
    }

    public function create($broker_id)
    {
        $broker = Broker::withCount('accountOptions')->findOrFail($broker_id);
        $formOptions = $this->formOptions();

        return view('admin.account_options.create', compact('broker', 'formOptions'));
    }

    public function store(AccountOptionRequest $request, $broker_id)
    {
        Broker::findOrFail($broker_id);

        $option = new AccountOption();
        $this->service->save($option, $request, (int) $broker_id);

        return redirect()
            ->route('admin_account_options_index', $broker_id)
            ->with('success', 'Account option created successfully.');
    }

    public function edit($broker_id, $id)
    {
        $broker = Broker::withCount('accountOptions')->findOrFail($broker_id);
        $accountOption = AccountOption::where('broker_id', $broker_id)->findOrFail($id);
        $formOptions = $this->formOptions();

        return view('admin.account_options.edit', compact('broker', 'accountOption', 'formOptions'));
    }

    public function update(AccountOptionRequest $request, $broker_id, $id)
    {
        $accountOption = AccountOption::where('broker_id', $broker_id)->findOrFail($id);
        $this->service->save($accountOption, $request, (int) $broker_id);

        return redirect()
            ->route('admin_account_options_index', $broker_id)
            ->with('success', 'Account option updated successfully.');
    }

    public function delete($broker_id, $id)
    {
        AccountOption::where('broker_id', $broker_id)->findOrFail($id)->delete();

        return redirect()
            ->route('admin_account_options_index', $broker_id)
            ->with('success', 'Account option deleted successfully.');
    }

    protected function formOptions(): array
    {
        return [
            'accountTypes' => AccountOptionAdminService::accountTypePresets(),
            'spreadTypes' => AccountOptionAdminService::spreadTypes(),
            'executionModels' => AccountOptionAdminService::executionModels(),
            'featureTags' => AccountOptionAdminService::featureTags(),
        ];
    }
}
