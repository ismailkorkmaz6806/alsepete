<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $seller = Auth::user()->seller;
        $questions = $seller ? $seller->questions()->with(['product', 'user'])->latest()->paginate(15) : collect();

        return view('seller.questions.index', compact('questions'));
    }

    public function update(Request $request, ProductQuestion $question): RedirectResponse
    {
        $seller = Auth::user()->seller;
        abort_unless($seller && $question->seller_id === $seller->id, 403, 'Bu soruya sadece mağaza sahibi cevap verebilir.');

        $request->validate([
            'answer' => 'required|string|min:4|max:1000',
        ], [
            'answer.required' => 'Lütfen bir cevap yazınız.',
        ]);

        $question->update([
            'answer' => $request->input('answer'),
            'answered_at' => now(),
        ]);

        return back()->with('status', 'Soru başarıyla cevaplandı ve ürün sayfasında yayınlandı.');
    }
}
