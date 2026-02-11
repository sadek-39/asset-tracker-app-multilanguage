<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class AssetController extends Controller
{
    public function index()
    {
        $assets = auth()->user()->assets;
        return view('assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_type' => 'required|in:dps,bond,investment,other',
            'asset_name' => 'required|max:100',
            'current_value' => 'required|numeric|min:0',
        ]);

        auth()->user()->assets()->create($request->all());

        return back()->with('success', 'Asset added to portfolio.');
    }
}
