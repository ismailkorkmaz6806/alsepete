<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerApplicationRequest;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerApplicationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $seller = $user->seller;

        if ($seller && $seller->status === 'onayli') {
            return redirect()->route('seller.dashboard');
        }

        return view('seller-application', compact('user', 'seller'));
    }

    public function store(SellerApplicationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Benzersiz slug üret
        $baseSlug = Str::slug($request->validated('store_name'));
        $slug = $baseSlug;
        $counter = 1;
        while (Seller::where('slug', $slug)->where('user_id', '!=', $user->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        Seller::updateOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => $request->validated('store_name'),
                'slug' => $slug,
                'tax_number' => $request->validated('tax_number'),
                'iban' => strtoupper(str_replace(' ', '', $request->validated('iban'))),
                'status' => 'beklemede',
                'commission_rate' => 10.00,
            ]
        );

        return redirect()->route('seller.apply')
            ->with('success', 'Satıcı başvurunuz başarıyla alındı! Sistem yöneticisi onayladığında mağaza paneliniz aktif edilecektir.');
    }
}
