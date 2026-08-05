<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradingTool;
use Illuminate\Http\Request;

class AdminTradingToolController extends Controller
{
    public function index()
    {
        $tools = TradingTool::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.trading_tools.index', compact('tools'));
    }

    public function edit($id)
    {
        $tool = TradingTool::findOrFail($id);

        return view('admin.trading_tools.edit', compact('tool'));
    }

    public function update(Request $request, $id)
    {
        $tool = TradingTool::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:80',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0|max:999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
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
