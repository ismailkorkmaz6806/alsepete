<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerManagementController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'beklemede');

        $query = Seller::with('user')->withCount('products');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $sellers = $query->latest()->paginate(15)->withQueryString();
        $pendingCount = Seller::where('status', 'beklemede')->count();

        return view('admin.sellers.index', compact('sellers', 'status', 'pendingCount'));
    }

    public function approve(Seller $seller): RedirectResponse
    {
        $seller->update(['status' => 'onayli']);

        if ($seller->user && ! $seller->user->hasRole('seller')) {
            $seller->user->assignRole('seller');
        }

        return redirect()->back()->with('success', "{$seller->store_name} mağaza başvurusu başarıyla onaylandı ve satıcı paneli erişimi açıldı.");
    }

    public function reject(Seller $seller): RedirectResponse
    {
        $seller->update(['status' => 'reddedildi']);

        return redirect()->back()->with('info', "{$seller->store_name} mağaza başvurusu reddedildi.");
    }
}
