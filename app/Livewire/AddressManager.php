<?php

namespace App\Livewire;

use App\Models\Address;
use App\Services\LocationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Adres Defterim - AlSepete')]
class AddressManager extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $city = '';

    public string $district = '';

    public string $full_address = '';

    public bool $is_default = false;

    public array $availableDistricts = [];

    public ?string $message = null;

    public function updatedCity(string $val): void
    {
        $this->availableDistricts = LocationService::getDistricts($val);
        $this->district = '';
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'title', 'city', 'district', 'full_address', 'is_default']);
        $this->availableDistricts = [];
        $this->is_default = Auth::user()->addresses()->count() === 0;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $address = Address::findOrFail($id);
        $this->authorize('update', $address);

        $this->editingId = $address->id;
        $this->title = $address->title;
        $this->city = $address->city;
        $this->availableDistricts = LocationService::getDistricts($address->city);
        $this->district = $address->district;
        $this->full_address = $address->full_address;
        $this->is_default = (bool) $address->is_default;

        $this->showModal = true;
    }

    public function save(): void
    {
        $cities = LocationService::getCities();

        $this->validate([
            'title' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', Rule::in($cities)],
            'district' => ['required', 'string'],
            'full_address' => ['required', 'string', 'min:10', 'max:500'],
            'is_default' => ['boolean'],
        ], [
            'title.required' => __('Lütfen adres başlığı (örn: Ev, İş) giriniz.'),
            'city.required' => __('Lütfen il seçiniz.'),
            'city.in' => __('Lütfen geçerli bir il seçiniz.'),
            'district.required' => __('Lütfen ilçe seçiniz.'),
            'full_address.required' => __('Lütfen açık adresinizi yazınız.'),
            'full_address.min' => __('Açık adres en az 10 karakter olmalıdır.'),
        ]);

        $validDistricts = LocationService::getDistricts($this->city);
        if (! in_array($this->district, $validDistricts)) {
            $this->addError('district', __('Seçilen ilçe, seçilen ile ait değildir.'));

            return;
        }

        $user = Auth::user();

        // Eğer ilk adresi ekliyorsa veya varsayılan işaretlendiyse
        $isFirst = $user->addresses()->where('id', '!=', $this->editingId ?? 0)->count() === 0;
        $shouldBeDefault = $this->is_default || $isFirst;

        if ($shouldBeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        if ($this->editingId) {
            $address = Address::findOrFail($this->editingId);
            $this->authorize('update', $address);

            $address->update([
                'title' => $this->title,
                'city' => $this->city,
                'district' => $this->district,
                'full_address' => $this->full_address,
                'is_default' => $shouldBeDefault,
            ]);

            $this->message = __('Adresiniz başarıyla güncellendi.');
        } else {
            $user->addresses()->create([
                'title' => $this->title,
                'city' => $this->city,
                'district' => $this->district,
                'full_address' => $this->full_address,
                'is_default' => $shouldBeDefault,
            ]);

            $this->message = __('Yeni adresiniz başarıyla kaydedildi.');
        }

        $this->showModal = false;
        $this->reset(['editingId', 'title', 'city', 'district', 'full_address', 'is_default']);
    }

    public function setDefault(int $id): void
    {
        $address = Address::findOrFail($id);
        $this->authorize('update', $address);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        $this->message = __(':title adresi varsayılan teslimat adresi olarak ayarlandı.', ['title' => $address->title]);
    }

    public function delete(int $id): void
    {
        $address = Address::findOrFail($id);
        $this->authorize('delete', $address);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Auth::user()->addresses()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        $this->message = __('Adres başarıyla silindi.');
    }

    public function render()
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->latest()->get();
        $cities = LocationService::getCities();

        return view('livewire.address-manager', compact('addresses', 'cities'));
    }
}
