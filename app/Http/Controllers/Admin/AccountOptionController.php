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
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'broker_id' => $request->integer('broker_id') ?: '',
            'status' => (string) $request->get('status', ''),
        ];

        $query = AccountOption::with('broker')->orderBy('broker_id')->ordered();

        if ($filters['broker_id'] !== '') {
            $query->where('broker_id', $filters['broker_id']);
        }

        if ($filters['q'] !== '') {
            $term = $filters['q'];
            $query->where(function ($sub) use ($term) {
                $sub->where('account_type', 'like', '%'.$term.'%')
                    ->orWhere('account_currency', 'like', '%'.$term.'%')
                    ->orWhere('slug', 'like', '%'.$term.'%')
                    ->orWhereHas('broker', fn ($b) => $b->where('name', 'like', '%'.$term.'%'));
            });
        }

        if ($filters['status'] === 'active') {
            $query->where('is_active', true);
        } elseif ($filters['status'] === 'hidden') {
            $query->where(function ($sub) {
                $sub->where('is_active', false)->orWhereNull('is_active');
            });
        }

        $accountOptions = $query->paginate(20)->withQueryString();
        $brokers = Broker::orderBy('name')->get(['id', 'name']);

        $stats = [
            'total' => AccountOption::query()->count(),
            'active' => AccountOption::query()->where('is_active', true)->count(),
            'hidden' => AccountOption::query()->where(function ($sub) {
                $sub->where('is_active', false)->orWhereNull('is_active');
            })->count(),
            'brokers' => (int) AccountOption::query()->selectRaw('count(distinct broker_id) as aggregate')->value('aggregate'),
        ];

        return view('admin.account_options.all', compact('accountOptions', 'brokers', 'stats', 'filters'));
    }

    public function index($broker_id)
    {
        $broker = Broker::withCount('accountOptions')->findOrFail($broker_id);
        $accountOptions = $broker->accountOptions()->ordered()->get();

        $stats = [
            'total' => $accountOptions->count(),
            'active' => $accountOptions->where('is_active', true)->count(),
            'hidden' => $accountOptions->where('is_active', false)->count(),
            'swap_free' => $accountOptions->where('swap_free', true)->count(),
        ];

        return view('admin.account_options.index', compact('broker', 'accountOptions', 'stats'));
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

        try {
            $this->service->save(new AccountOption(), $request, (int) $broker_id);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create account option: '.$e->getMessage());
        }

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
        try {
            $accountOption = AccountOption::where('broker_id', $broker_id)->findOrFail($id);
            $this->service->save($accountOption, $request, (int) $broker_id);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not update account option: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_account_options_index', $broker_id)
            ->with('success', 'Account option updated successfully.');
    }

    public function delete($broker_id, $id)
    {
        try {
            AccountOption::where('broker_id', $broker_id)->findOrFail($id)->delete();
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Could not delete account option: '.$e->getMessage());
        }

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
