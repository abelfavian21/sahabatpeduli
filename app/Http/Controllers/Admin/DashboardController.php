<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'campaigns' => Campaign::count(),
            'active' => Campaign::active()->count(),
            'collected' => Campaign::sum('collected'),
            'pending' => Donation::where('status', 'pending')->count(),
            'latest' => Donation::with('campaign')->latest()->take(6)->get(),
        ]);
    }
}
