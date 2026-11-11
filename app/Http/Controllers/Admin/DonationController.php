<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $r)
    {
        $donations = Donation::with('campaign')
            ->when($r->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.donations.index', compact('donations'));
    }

    public function update(Request $r, Donation $donation)
    {
        $donation->markStatus($r->validate(['status' => 'required|in:pending,paid,failed,expired'])['status']);

        return back()->with('ok', 'Status donasi diperbarui.');
    }
}
