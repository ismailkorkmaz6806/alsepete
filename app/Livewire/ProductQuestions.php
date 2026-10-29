<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductQuestions extends Component
{
    public Product $product;

    public string $question = '';

    public bool $success = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    #[Computed]
    public function questions()
    {
        return $this->product->questions()
            ->where('is_approved', true)
            ->with(['user', 'seller'])
            ->latest()
            ->get();
    }

    public function askQuestion(): void
    {
        if (! Auth::check()) {
            redirect()->route('login');

            return;
        }

        $this->validate([
            'question' => 'required|string|min:6|max:500',
        ], [
            'question.required' => __('Lütfen bir soru yazınız.'),
            'question.min' => __('Sorunuz en az 6 karakter olmalıdır.'),
            'question.max' => __('Sorunuz 500 karakterden uzun olamaz.'),
        ]);

        ProductQuestion::create([
            'product_id' => $this->product->id,
            'user_id' => Auth::id(),
            'seller_id' => $this->product->seller_id,
            'question' => $this->question,
            'is_approved' => true,
        ]);

        $this->question = '';
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.product-questions');
    }
}
