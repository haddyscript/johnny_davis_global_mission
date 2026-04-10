<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'latest');

        // Base query — join donor totals subquery when sorting by top donors
        if ($sort === 'top_donors') {
            $donorTotalsSubquery = Donation::selectRaw('email AS donor_email, SUM(amount) AS donor_total')
                ->where('status', 'completed')
                ->groupBy('email');

            $query = Donation::select('donations.*')
                ->leftJoinSub($donorTotalsSubquery, 'dt', 'donations.email', '=', 'dt.donor_email')
                ->orderByDesc('dt.donor_total')
                ->orderByDesc('donations.created_at');
        } else {
            $query = Donation::latest();
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('donations.first_name',     'like', '%' . $request->search . '%')
                  ->orWhere('donations.last_name',    'like', '%' . $request->search . '%')
                  ->orWhere('donations.email',        'like', '%' . $request->search . '%')
                  ->orWhere('donations.transaction_id','like', '%' . $request->search . '%')
                  ->orWhere('donations.campaign_name','like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('donations.status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('donations.payment_method', $request->payment_method);
        }

        if ($request->filled('frequency')) {
            $query->where('donations.frequency', $request->frequency);
        }

        $donations = $query->paginate(15)->withQueryString();

        // New donor detection: emails appearing only once across all donations
        $newDonorEmails = Donation::selectRaw('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) = 1')
            ->pluck('email')
            ->flip();

        // Per-donor cumulative totals (completed) for the top-donor sort view
        $donorTotals = ($sort === 'top_donors')
            ? Donation::where('status', 'completed')
                ->selectRaw('email, SUM(amount) as total')
                ->groupBy('email')
                ->pluck('total', 'email')
            : collect();

        $stats = [
            'total'     => Donation::count(),
            'completed' => Donation::where('status', 'completed')->count(),
            'pending'   => Donation::where('status', 'pending')->count(),
            'failed'    => Donation::where('status', 'failed')->count(),
            'revenue'   => Donation::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.donations.index', compact('donations', 'stats', 'newDonorEmails', 'donorTotals', 'sort'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Donation::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }
        if ($request->filled('frequency')) {
            $query->where('frequency', $request->input('frequency'));
        }
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('first_name',      'like', "%{$term}%")
                  ->orWhere('last_name',     'like', "%{$term}%")
                  ->orWhere('email',         'like', "%{$term}%")
                  ->orWhere('campaign_name', 'like', "%{$term}%")
                  ->orWhere('transaction_id','like', "%{$term}%");
            });
        }

        $donations = $query->get();
        $filename  = 'donations-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($donations) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'First Name', 'Last Name', 'Email',
                'Campaign', 'Amount (USD)', 'Frequency',
                'Payment Method', 'Card Brand', 'Last 4', 'Exp Month', 'Exp Year',
                'Transaction ID', 'Status', 'Date',
            ]);

            foreach ($donations as $d) {
                fputcsv($handle, [
                    $d->id,
                    $d->first_name,
                    $d->last_name,
                    $d->email,
                    $d->campaign_name,
                    number_format((float) $d->amount, 2),
                    $d->frequency,
                    $d->payment_method,
                    $d->card_brand ?? '',
                    $d->card_last_four ?? '',
                    $d->card_exp_month ?? '',
                    $d->card_exp_year ?? '',
                    $d->transaction_id ?? '',
                    $d->status,
                    $d->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function followup(Donation $donation, EmailService $emailService): JsonResponse
    {
        if ($donation->status === 'completed') {
            return response()->json([
                'error' => 'This donation is already completed — no follow-up needed.',
            ], 422);
        }

        // 24-hour cooldown guard
        if ($donation->isInFollowUpCooldown()) {
            $nextAllowed = $donation->follow_up_sent_at->addHours(24)->format('M j, Y \a\t g:i A');
            return response()->json([
                'error'       => "A follow-up was sent recently. Next allowed after {$nextAllowed}.",
                'in_cooldown' => true,
            ], 429);
        }

        $template = EmailTemplate::where('type', 'payment_followup')
            ->where('is_active', true)
            ->first();

        if (! $template) {
            return response()->json([
                'error' => 'Payment Follow-up email template not found. Run: sail artisan db:seed --class=EmailTemplateSeeder',
            ], 404);
        }

        $log = $emailService->sendTemplate($template, $donation->email, $donation->full_name, [
            'donor_name'      => $donation->full_name,
            'campaign_name'   => $donation->campaign_name,
            'donation_amount' => '$' . number_format((float) $donation->amount, 2),
            'donate_link'     => url('/donate'),
        ]);

        if ($log->isFailed()) {
            return response()->json([
                'error' => 'Follow-up email could not be delivered. Check your mail configuration.',
            ], 500);
        }

        // Record the follow-up
        $newCount = $donation->follow_up_count + 1;
        $donation->update([
            'follow_up_sent_at' => now(),
            'follow_up_count'   => $newCount,
        ]);

        return response()->json([
            'success'          => true,
            'message'          => 'Follow-up email sent to ' . $donation->email,
            'follow_up_count'  => $newCount,
            'follow_up_sent_at'=> now()->format('M j, Y g:i A'),
        ]);
    }

    public function show(Donation $donation)
    {
        return response()->json([
            'id'              => $donation->id,
            'full_name'       => $donation->full_name,
            'first_name'      => $donation->first_name,
            'last_name'       => $donation->last_name,
            'email'           => $donation->email,
            'campaign_name'   => $donation->campaign_name,
            'amount'          => number_format((float) $donation->amount, 2),
            'frequency'       => $donation->frequency,
            'payment_method'  => $donation->payment_method,
            'card_brand'      => $donation->card_brand,
            'card_last_four'  => $donation->card_last_four,
            'card_exp_month'  => $donation->card_exp_month,
            'card_exp_year'   => $donation->card_exp_year,
            'transaction_id'  => $donation->transaction_id,
            'status'          => $donation->status,
            'created_at'      => $donation->created_at?->format('M j, Y g:i A'),
            'updated_at'      => $donation->updated_at?->format('M j, Y g:i A'),
        ]);
    }
}
