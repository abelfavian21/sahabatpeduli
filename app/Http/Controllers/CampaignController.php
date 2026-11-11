<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $r)
    {
        $campaigns = Campaign::active()->with('category')
            ->when($r->category, fn ($q, $c) => $q->whereHas('category', fn ($x) => $x->where('slug', $c)))
            ->when($r->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderByDesc('is_urgent')->latest()->paginate(9)->withQueryString();

        return view('campaigns.index', ['campaigns' => $campaigns, 'categories' => Category::all()]);
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('category');
        $donors = $campaign->donations()->where('status', 'paid')->latest()->take(10)->get();

        return view('campaigns.show', compact('campaign', 'donors'));
    }
}
