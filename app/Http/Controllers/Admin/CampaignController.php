<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        return view('admin.campaigns.index', ['campaigns' => Campaign::with('category')->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.campaigns.form', ['campaign' => new Campaign(['status' => 'active']), 'categories' => Category::all()]);
    }

    public function store(Request $r)
    {
        $d = $this->data($r);
        $d['slug'] = $this->uniqueSlug($d['title']);
        if ($r->hasFile('image')) {
            $d['image'] = $r->file('image')->store('campaigns', 'public');
        }
        if ($r->hasFile('video')) {
            $d['video'] = $r->file('video')->store('campaigns', 'public');
        }
        Campaign::create($d);

        return redirect()->route('admin.campaigns.index')->with('ok', 'Kampanye dibuat.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.form', ['campaign' => $campaign, 'categories' => Category::all()]);
    }

    public function update(Request $r, Campaign $campaign)
    {
        $d = $this->data($r);
        if ($r->hasFile('image')) {
            $this->deleteImage($campaign);
            $d['image'] = $r->file('image')->store('campaigns', 'public');
        } elseif ($r->boolean('remove_image')) {
            $this->deleteImage($campaign);
            $d['image'] = null;
        }
        if ($r->hasFile('video')) {
            $this->deleteFile($campaign->video);
            $d['video'] = $r->file('video')->store('campaigns', 'public');
        } elseif ($r->boolean('remove_video')) {
            $this->deleteFile($campaign->video);
            $d['video'] = null;
        }
        $campaign->update($d);

        return redirect()->route('admin.campaigns.index')->with('ok', 'Kampanye diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->deleteImage($campaign);
        $this->deleteFile($campaign->video);
        $campaign->delete();

        return back()->with('ok', 'Kampanye dihapus.');
    }

    private function data(Request $r): array
    {
        $d = $r->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:160',
            'organizer' => 'required|string|max:120',
            'excerpt' => 'required|string|max:255',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'video' => 'nullable|mimetypes:video/mp4,video/webm|max:20480',
            'target' => 'required|integer|min:10000',
            'collected' => 'nullable|integer|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:draft,active,closed',
        ]);
        unset($d['image'], $d['video']);
        $d['collected'] = $d['collected'] ?? 0;
        $d['is_urgent'] = $r->boolean('is_urgent');
        $d['is_verified'] = $r->boolean('is_verified');

        return $d;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'kampanye';
        $slug = $base;
        for ($i = 2; Campaign::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    private function deleteImage(Campaign $c): void
    {
        if ($c->image) {
            Storage::disk('public')->delete($c->image);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
