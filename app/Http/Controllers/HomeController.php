<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'hero' => Campaign::active()->with('category')
                ->orderByRaw('CASE WHEN image IS NOT NULL OR video IS NOT NULL THEN 0 ELSE 1 END')
                ->orderByDesc('is_urgent')->latest()->take(3)->get(),
            'categories' => Category::withCount(['campaigns' => fn ($q) => $q->active()])
                ->withSum(['campaigns as raised' => fn ($q) => $q->active()], 'collected')->get(),
            'campaigns' => Campaign::active()->with('category')->orderByDesc('is_urgent')->latest()->take(3)->get(),
            'articles' => Article::published()->latest('published_at')->take(3)->get(),
            'stats' => [
                'collected' => Campaign::sum('collected'),
                'donors' => Donation::where('status', 'paid')->count(),
                'campaigns' => Campaign::active()->count(),
            ],
        ]);
    }
}
