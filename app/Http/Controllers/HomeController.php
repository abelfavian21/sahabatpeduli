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
            'categories' => Category::withCount(['campaigns' => fn ($q) => $q->active()])->get(),
            'campaigns' => Campaign::active()->with('category')->orderByDesc('is_urgent')->latest()->take(3)->get(),
            'articles' => Article::whereNotNull('published_at')->latest('published_at')->take(3)->get(),
            'stats' => [
                'collected' => Campaign::sum('collected'),
                'donors' => Donation::where('status', 'paid')->count(),
                'campaigns' => Campaign::active()->count(),
            ],
        ]);
    }
}
