<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Üst Başlık ve Yeni Ekle Butonu -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-map-location-dot text-indigo-600"></i>
                <span>{{ __('Adres Defterim') }}</span>
                <span class="text-sm font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                    {{ $addresses->count() }} {{ __('Adres') }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ __('Siparişlerinizde kullanacağınız teslimat ve fatura adreslerinizi buradan yönetebilirsiniz.') }}
            </p>
        </div>

        <button type="button" 
                wire:click="openCreateModal" 
                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-md shadow-indigo-600/25 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>{{ __('Yeni Adres Ekle') }}</span>
        </button>
    </div>

    <!-- Bildirim Mesajı -->
    @if($message)
        <div class="mt-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center justify-between gap-3 animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ $message }}</span>
            </div>
            <button type="button" wire:click="$set('message', null)" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Adres Kartları Izgarası -->
    @if($addresses->isEmpty())
        <div class="py-16 text-center">
            <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-indigo-50 border-2 border-indigo-100 flex items-center justify-center text-indigo-400 shadow-sm">
                <i class="fa-solid fa-house-chimney text-4xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('Kayıtlı Adresiniz Bulunmuyor') }}</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                {{ __('Sipariş verirken zaman kazanmak için ilk teslimat adresinizi hemen ekleyebilirsiniz.') }}
            </p>
            <button type="button" 
                    wire:click="openCreateModal" 
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('İlk Adresini Ekle') }}</span>
            </button>
        </div>
    @else
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($addresses as $address)
                <div class="bg-white rounded-3xl border {{ $address->is_default ? 'border-indigo-600 ring-2 ring-indigo-600/10 shadow-md' : 'border-slate-200/80 shadow-xs' }} p-6 flex flex-col justify-between transition-all hover:shadow-md relative">
                    
                    <div>
                        <!-- Başlık ve Rozet -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl {{ $address->is_default ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-sm">
                                    <i class="fa-solid {{ strtolower($address->title) === 'iş' ? 'fa-briefcase' : 'fa-house' }}"></i>
                                </div>
                                <h3 class="font-extrabold text-base text-slate-900 tracking-tight">
                                    {{ $address->title }}
                                </h3>
                            </div>

                            @if($address->is_default)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    {{ __('Varsayılan') }}
                                </span>
                            @endif
                        </div>

                        <!-- İl / İlçe -->
                        <div class="text-xs font-bold text-indigo-600 mb-2">
                            {{ $address->district }} / {{ $address->city }}
                        </div>

                        <!-- Açık Adres -->
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                            {{ $address->full_address }}
                        </p>
                    </div>

                    <!-- Aksiyon Butonları -->
                    <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs font-bold">
                        <div>
                            @if(! $address->is_default)
                                <button type="button" 
                                        wire:click="setDefault({{ $address->id }})" 
                                        class="text-slate-500 hover:text-indigo-600 transition flex items-center gap-1">
                                    <i class="fa-regular fa-star"></i>
                                    <span>{{ __('Varsayılan Yap') }}</span>
                                </button>
                            @else
                                <span class="text-emerald-600 flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i>
                                    <span>{{ __('Teslimat Adresi') }}</span>
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    wire:click="edit({{ $address->id }})" 
                                    class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                    title="{{ __('Düzenle') }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" 
                                    wire:click="delete({{ $address->id }})" 
                                    wire:confirm="{{ __('Bu adresi silmek istediğinize emin misiniz?') }}"
                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                    title="{{ __('Sil') }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- Ekle / Düzenle Modalı -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 animate-scale-up" @click.away="$wire.set('showModal', false)">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid {{ $editingId ? 'fa-pen-to-square' : 'fa-plus' }} text-indigo-600"></i>
                        <span>{{ $editingId ? __('Adresi Düzenle') : __('Yeni Adres Ekle') }}</span>
                    </h2>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    
                    <!-- Adres Başlığı -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Adres Başlığı') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="title" 
                               placeholder="{{ __('Örn: Evim, Ofis, Yazlık') }}" 
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 py-2.5">
                        @error('title') <span class="text-[11px] text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- İl ve İlçe Seçimi (Reaktif) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                {{ __('İl') }} <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="city" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 py-2.5">
                                <option value="">{{ __('İl Seçiniz') }}</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                            @error('city') <span class="text-[11px] text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                {{ __('İlçe') }} <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="district" {{ empty($availableDistricts) ? 'disabled' : '' }} class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 py-2.5 disabled:bg-slate-100 disabled:text-slate-400">
                                <option value="">{{ empty($availableDistricts) ? __('Önce il seçin') : __('İlçe Seçiniz') }}</option>
                                @foreach($availableDistricts as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            @error('district') <span class="text-[11px] text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Açık Adres -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Açık Adres') }} <span class="text-rose-500">*</span>
                        </label>
                        <textarea wire:model="full_address" 
                                  rows="3" 
                                  placeholder="{{ __('Mahalle, cadde, sokak, bina no, daire no...') }}" 
                                  class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 py-2.5"></textarea>
                        @error('full_address') <span class="text-[11px] text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Varsayılan Yap Checkbox -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_default" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <span class="text-xs font-bold text-slate-700">{{ __('Bu adresi varsayılan teslimat adresi olarak ayarla') }}</span>
                        </label>
                    </div>

                    <!-- Butonlar -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" 
                                wire:click="$set('showModal', false)" 
                                class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs transition">
                            {{ __('İptal') }}
                        </button>
                        <button type="submit" 
                                class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-md shadow-indigo-600/25 transition">
                            {{ __('Kaydet') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    @endif

</div>
