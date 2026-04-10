<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::orderBy('sort_order');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title',   'like', '%' . $request->search . '%')
                  ->orWhere('snippet', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $campaigns = $query->paginate(15)->withQueryString();

        // Aggregate real donation totals per campaign (matched by title = campaign_name)
        $donationTotals = Donation::where('status', 'completed')
            ->selectRaw('campaign_name, SUM(amount) as total')
            ->groupBy('campaign_name')
            ->pluck('total', 'campaign_name');

        // Enrich each campaign with computed progress data
        $campaigns->through(function ($campaign) use ($donationTotals) {
            $campaign->total_donated = (float) ($donationTotals[$campaign->title] ?? 0);
            $campaign->goal_numeric  = (float) preg_replace('/[^0-9.]/', '', $campaign->goal_amount ?? '0');
            $campaign->computed_pct  = ($campaign->goal_numeric > 0)
                ? min(100, round(($campaign->total_donated / $campaign->goal_numeric) * 100))
                : 0;
            $campaign->remaining = ($campaign->goal_numeric > 0)
                ? max(0, $campaign->goal_numeric - $campaign->total_donated)
                : null;
            return $campaign;
        });

        $stats = [
            'total'    => Campaign::count(),
            'active'   => Campaign::where('is_active', true)->count(),
            'inactive' => Campaign::where('is_active', false)->count(),
        ];

        return view('admin.campaigns.index', compact('campaigns', 'stats'));
    }

    public function create()
    {
        $nextOrder = Campaign::max('sort_order') + 1;
        return view('admin.campaigns.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCampaign($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        Campaign::create($validated);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign "' . $validated['title'] . '" created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $this->validateCampaign($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        $campaign->update($validated);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign "' . $campaign->title . '" updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $title = $campaign->title;
        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign "' . $title . '" deleted.');
    }

    public function toggle(Campaign $campaign)
    {
        $campaign->update(['is_active' => ! $campaign->is_active]);

        return response()->json(['is_active' => $campaign->is_active]);
    }

    // ──────────────────────────────────────────────
    // Private
    // ──────────────────────────────────────────────

    private function validateCampaign(Request $request): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'icon'         => ['nullable', 'string', 'max:10'],
            'label'        => ['nullable', 'string', 'max:100'],
            'status_class' => ['nullable', 'string', 'max:50'],
            'goal_amount'  => ['nullable', 'string', 'max:50'],
            'goal_pct'     => ['nullable', 'integer', 'min:0', 'max:100'],
            'raised_amount'=> ['nullable', 'string', 'max:50'],
            'bar_style'    => ['nullable', 'string', 'max:255'],
            'subtitle'     => ['nullable', 'string', 'max:255'],
            'meta'         => ['nullable', 'string', 'max:255'],
            'snippet'      => ['nullable', 'string', 'max:500'],
            'story'        => ['nullable', 'string', 'max:255'],
            'story_full'   => ['nullable', 'string'],
            'goal_full'    => ['nullable', 'string', 'max:255'],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
            'is_active'    => ['boolean'],
        ]);
    }
}
