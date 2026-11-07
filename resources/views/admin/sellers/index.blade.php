<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-indigo-600"></i>
                    {{ __('Satıcı Başvuruları & Mağaza Yönetimi') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">{{ __('Platformdaki satıcı başvurularını inceleyin, onaylayın veya reddedin.') }}</p>
            </div>

            <!-- Tab Seçicisi -->
            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                <a href="{{ route('admin.sellers.index', ['status' => 'beklemede']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'beklemede' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('Onay Bekleyenler') }}
                    @if($pendingCount > 0)
                        <span class="ml-1 px-1.5 py-0.2 bg-amber-500 text-white rounded-full text-[10px]">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.sellers.index', ['status' => 'onayli']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'onayli' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('Onaylı Satıcılar') }}
                </a>
                <a href="{{ route('admin.sellers.index', ['status' => 'all']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $status === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('Tümü') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-slate-500 text-sm"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Mağaza Adı & Kullanıcı</th>
                            <th class="px-6 py-4">Vergi No</th>
                            <th class="px-6 py-4">IBAN</th>
                            <th class="px-6 py-4">Komisyon</th>
                            <th class="px-6 py-4">Durum</th>
                            <th class="px-6 py-4">Tarih</th>
                            <th class="px-6 py-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($sellers as $seller)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                        <span>{{ $seller->store_name }}</span>
                                        <a href="{{ route('stores.show', $seller->slug) }}" target="_blank" class="text-slate-400 hover:text-indigo-600 text-xs">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $seller->user->name ?? 'Kullanıcı Silinmiş' }} &bull; {{ $seller->user->email ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-600">
                                    {{ $seller->tax_number }}
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                                    {{ $seller->iban }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    %{{ number_format($seller->commission_rate, 0) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($seller->status === 'onayli')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase">
                                            <i class="fa-solid fa-check"></i> {{ __('Onaylı') }}
                                        </span>
                                    @elseif($seller->status === 'beklemede')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 uppercase animate-pulse">
                                            <i class="fa-solid fa-clock"></i> {{ __('Beklemede') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 uppercase">
                                            <i class="fa-solid fa-xmark"></i> {{ __('Reddedildi') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-[11px]">
                                    {{ $seller->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($seller->status !== 'onayli')
                                            <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                                                    {{ __('Onayla') }}
                                                </button>
                                            </form>
                                        @endif

                                        @if($seller->status !== 'reddedildi')
                                            <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" onsubmit="return confirm('Bu satıcı başvurusunu reddetmek istediğinizden emin misiniz?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 rounded-lg text-xs font-bold transition cursor-pointer">
                                                    {{ __('Reddet') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                    {{ __('Bu filtreye uygun satıcı başvurusu bulunamadı.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sellers->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $sellers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
