<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name',     'like', '%' . $request->search . '%')
                  ->orWhere('last_name',    'like', '%' . $request->search . '%')
                  ->orWhere('email',        'like', '%' . $request->search . '%')
                  ->orWhere('transaction_id','like', '%' . $request->search . '%')
                  ->orWhere('campaign_name','like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        $donations = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => Donation::count(),
            'completed' => Donation::where('status', 'completed')->count(),
            'pending'   => Donation::where('status', 'pending')->count(),
            'failed'    => Donation::where('status', 'failed')->count(),
            'revenue'   => Donation::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.donations.index', compact('donations', 'stats'));
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
