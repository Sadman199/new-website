<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TradingToolRequest;
use App\Models\Broker;
use App\Models\TradingTool;
use App\Support\TradingToolCategories;

class AdminTradingToolController extends Controller
{
    public function index()
    {
        $tools = TradingTool::orderBy('sort_order')->orderBy('id')->get();
        $stats = [
            'total' => $tools->count(),
            'active' => $tools->where('is_active', true)->count(),
            'hidden' => $tools->where('is_active', false)->count(),
        ];

        return view('admin.trading_tools.index', compact('tools', 'stats'));
    }

    public function edit($id)
    {
        $tool = TradingTool::findOrFail($id);
        $categories = TradingToolCategories::all();
        $otherTools = TradingTool::query()
            ->where('id', '!=', $tool->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'name', 'slug']);
        $brokers = Broker::query()
            ->where('is_scam', false)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('admin.trading_tools.edit', compact('tool', 'categories', 'otherTools', 'brokers'));
    }

    public function update(TradingToolRequest $request, $id)
    {
        $tool = TradingTool::findOrFail($id);
        $data = $request->validated();
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $tool->update($data);

        return redirect()->route('admin_trading_tools_index')->with('success', 'Tool updated successfully.');
    }

    public function toggle($id)
    {
        $tool = TradingTool::findOrFail($id);
        $tool->is_active = ! $tool->is_active;
        $tool->save();

        return redirect()->back()->with('success', $tool->is_active ? 'Tool activated.' : 'Tool hidden.');
    }
}
