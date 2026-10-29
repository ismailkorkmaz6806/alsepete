<?php

namespace App\Livewire;

use App\Services\AiShoppingAssistantService;
use Livewire\Component;

class AiShoppingAssistant extends Component
{
    public bool $isOpen = false;

    public string $userInput = '';

    /**
     * @var array<int, array{role: string, content: string, products: array<int, array>, time: string}>
     */
    public array $messages = [];

    /**
     * @var array<int, string>
     */
    public array $quickPrompts = [
        '☕ Bütçe dostu kahve makinesi öner',
        '🧹 Kablosuz dikey süpürge var mı?',
        '🍳 Kaliteli tava & tencere seti',
        '📦 Kargo ve iade şartları nedir?',
    ];

    public function mount(): void
    {
        if (empty($this->messages)) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => "Merhaba! Ben **AlSepete Akıllı Alışveriş Asistanı** 🤖.\n\nAradığınız bir ürün, bütçenize göre hediye tavsiyesi veya kargo/iade kurallarımız hakkında bana dilediğinizi sorabilirsiniz!",
                'products' => [],
                'time' => now()->format('H:i'),
            ];
        }
    }

    public function toggleChat(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function openChat(): void
    {
        $this->isOpen = true;
    }

    public function closeChat(): void
    {
        $this->isOpen = false;
    }

    public function quickAsk(string $prompt): void
    {
        $this->userInput = $prompt;
        $this->sendMessage();
    }

    public function sendMessage(): void
    {
        $trimmed = trim($this->userInput);
        if ($trimmed === '') {
            return;
        }

        // 1. Add user message
        $this->messages[] = [
            'role' => 'user',
            'content' => $trimmed,
            'products' => [],
            'time' => now()->format('H:i'),
        ];

        $this->userInput = '';

        // 2. Fetch AI response via service
        /** @var AiShoppingAssistantService $service */
        $service = app(AiShoppingAssistantService::class);
        $result = $service->respond($trimmed);

        // Map recommended products to lightweight array for frontend rendering
        $productCards = [];
        foreach ($result['products'] as $product) {
            $productCards[] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'brand' => $product->brand?->name ?? 'AlSepete',
                'category' => $product->category?->name ?? 'Genel',
                'price' => number_format($product->min_price, 2, ',', '.').' TL',
                'image' => $product->featured_image,
                'url' => route('products.show', $product->slug),
            ];
        }

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $result['message'],
            'products' => $productCards,
            'time' => now()->format('H:i'),
        ];

        if (! empty($result['suggestions'])) {
            $this->quickPrompts = $result['suggestions'];
        }

        $this->dispatch('scroll-chat-to-bottom');
    }

    public function resetChat(): void
    {
        $this->messages = [
            [
                'role' => 'assistant',
                'content' => 'Sohbet sıfırlandı. Size nasıl yardımcı olabilirim? 🛍️',
                'products' => [],
                'time' => now()->format('H:i'),
            ],
        ];

        $this->quickPrompts = [
            '☕ Bütçe dostu kahve makinesi öner',
            '🧹 Kablosuz dikey süpürge var mı?',
            '🍳 Kaliteli tava & tencere seti',
            '📦 Kargo ve iade şartları nedir?',
        ];
    }

    public function render()
    {
        return view('livewire.ai-shopping-assistant');
    }
}
