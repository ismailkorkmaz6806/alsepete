<div x-data="{ mobileMenuOpen: false, megaMenuOpen: false, activeCatId: null, userMenuOpen: false, langMenuOpen: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <!-- 1. Üst Bilgi Çubuğu (Top Bar) -->
    <div class="border-b border-slate-100 bg-slate-50 text-[11px] py-1.5 px-4 text-slate-500">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ __('Onaylı Mağazalar & Orijinal Ürünler') }}
                </span>
                <span class="hidden sm:inline text-slate-400">
                    <i class="fa-solid fa-truck-fast text-indigo-500 mr-1"></i> {{ __('Çoklu Satıcı Kargo Takibi') }}
                </span>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ url('/satici') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1.5 font-semibold text-indigo-600">
                    <i class="fa-solid fa-store text-[11px]"></i> {{ __("AlSepete'de Satıcı Ol") }}
                </a>
                <span class="text-slate-300">|</span>
                
                <!-- Dil Seçici (Language Switcher TR / EN) -->
                <div class="relative">
                    <button @click="langMenuOpen = !langMenuOpen" @click.away="langMenuOpen = false" class="flex items-center gap-1.5 font-bold text-slate-700 hover:text-indigo-600 focus:outline-none bg-white px-2 py-0.5 rounded border border-slate-200 text-xs shadow-xs">
                        <i class="fa-solid fa-globe text-indigo-600 text-[11px]"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-slate-400"></i>
                    </button>
                    <div x-show="langMenuOpen" x-cloak class="absolute right-0 mt-1.5 w-28 bg-white border border-slate-200 rounded-xl shadow-xl py-1 z-50 text-xs">
                        <a href="{{ route('locale.switch', 'tr') }}" class="flex items-center justify-between px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ app()->getLocale() === 'tr' ? 'font-bold text-indigo-600 bg-indigo-50/50' : 'text-slate-700' }}">
                            <span>🇹🇷 Türkçe</span>
                            @if(app()->getLocale() === 'tr') <i class="fa-solid fa-check text-[10px] text-indigo-600"></i> @endif
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}" class="flex items-center justify-between px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ app()->getLocale() === 'en' ? 'font-bold text-indigo-600 bg-indigo-50/50' : 'text-slate-700' }}">
                            <span>🇬🇧 English</span>
                            @if(app()->getLocale() === 'en') <i class="fa-solid fa-check text-[10px] text-indigo-600"></i> @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Ana Header (Logo, Arama, Sepet, Hesap) -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4 md:gap-8">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-600 flex items-center justify-center text-white text-lg font-black shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-basket-shopping text-base"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 flex items-center leading-none">
                    Al<span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Sepete</span>
                </span>
                <span class="text-[9px] uppercase tracking-widest font-bold text-slate-400 mt-0.5">{{ __('Çok Satıcılı Alışveriş') }}</span>
            </div>
        </a>

        <!-- Arama Kutusu (Search Box) -->
        <div class="hidden md:flex flex-1 max-w-2xl relative">
            <form action="{{ route('home') }}" method="GET" class="w-full relative group">
                <input type="text" name="q" placeholder="{{ __('Ürün, marka, kategori veya satıcı ara...') }}" 
                       class="w-full bg-slate-100 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-2.5 pl-11 pr-24 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                <button type="submit" class="absolute right-1.5 top-1.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-semibold px-4 py-1.5 rounded-xl transition-all shadow-sm">
                    {{ __('Ara') }}
                </button>
            </form>
        </div>

        <!-- Sağ Aksiyonlar (Favoriler, Sepet, Hesap Menüsü) -->
        <div class="flex items-center space-x-2 sm:space-x-4 shrink-0">
            <!-- Favoriler -->
            <a href="#" class="hidden sm:flex relative p-2 rounded-xl hover:bg-slate-100 text-slate-600 hover:text-rose-500 transition-colors">
                <i class="fa-regular fa-heart text-lg"></i>
                <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center shadow-sm">0</span>
            </a>

            <!-- Sepet İkonu (Cart with Badge) -->
            <a href="#" class="flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3.5 py-2 rounded-xl font-bold text-xs transition-colors">
                <div class="relative">
                    <i class="fa-solid fa-bag-shopping text-sm text-indigo-600"></i>
                    <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[9px] font-bold rounded-full w-3.5 h-3.5 flex items-center justify-center">0</span>
                </div>
                <span class="hidden sm:inline">{{ __('Sepetim') }}</span>
            </a>

            <!-- Hesap Menüsü (Account Dropdown) -->
            @guest
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-indigo-600 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors">
                        {{ __('Giriş Yap') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-2 rounded-xl transition-colors shadow-sm">
                        {{ __('Kayıt Ol') }}
                    </a>
                </div>
            @else
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-700 hover:text-indigo-600 transition-colors focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-[10px] text-slate-400 font-medium">{{ __('Hesabım') }}</span>
                            <span class="text-xs font-bold text-slate-800 leading-tight max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden sm:inline"></i>
                    </button>

                    <!-- Açılır Menü -->
                    <div x-show="userMenuOpen" x-cloak class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50 text-xs">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-chart-pie w-4 text-slate-400"></i> {{ __('Kontrol Paneli') }}
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-user-gear w-4 text-slate-400"></i> {{ __('Profil Ayarları') }}
                            </a>
                        </div>

                        @if(Auth::user()->hasRole('admin'))
                            <div class="py-1 border-t border-slate-100 bg-amber-50/50">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 font-bold text-amber-700 hover:bg-amber-100 transition-colors">
                                    <i class="fa-solid fa-shield-halved w-4 text-amber-600"></i> {{ __('Admin Yönetim Paneli') }}
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-amber-100 hover:text-amber-800 transition-colors pl-8">
                                    <i class="fa-solid fa-layer-group w-4 text-slate-400"></i> {{ __('Kategori Yönetimi') }}
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->hasRole('seller'))
                            <div class="py-1 border-t border-slate-100 bg-emerald-50/50">
                                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 px-4 py-2 font-bold text-emerald-700 hover:bg-emerald-100 transition-colors">
                                    <i class="fa-solid fa-store w-4 text-emerald-600"></i> {{ __('Satıcı Mağaza Paneli') }}
                                </a>
                            </div>
                        @endif

                        <div class="py-1 border-t border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-rose-600 hover:bg-rose-50 transition-colors font-semibold">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-rose-400"></i> {{ __('Çıkış Yap') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest

            <!-- Mobil Menü Butonu -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 md:hidden rounded-xl text-slate-600 hover:bg-slate-100">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
    </header>

    <!-- 3. Dinamik Mega Menü Çubuğu (Kategoriler DB'den) -->
    <nav class="border-t border-slate-100 bg-white relative z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                
                <!-- "Tüm Kategoriler" Mega Menü Butonu -->
                <div class="relative py-2.5" @mouseleave="megaMenuOpen = false">
                    <button @click="megaMenuOpen = !megaMenuOpen" 
                            @mouseenter="megaMenuOpen = true"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:shadow-md hover:shadow-indigo-500/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-bars-staggered text-xs"></i>
                        <span>{{ __('Tüm Kategoriler') }}</span>
                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="{ 'rotate-180': megaMenuOpen }"></i>
                    </button>

                    <!-- Büyük Mega Menü Paneli (All Categories Flyout) -->
                    <div x-show="megaMenuOpen" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 top-full mt-1 w-[700px] max-w-[90vw] bg-white border border-slate-200 rounded-3xl shadow-2xl p-6 z-50">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <span class="font-black text-slate-900 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-indigo-600"></i> {{ __('Kategorileri Keşfet') }}
                            </span>
                            <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-2.5 py-1 rounded-full">
                                {{ __('15 Kategori Aktif') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                            @if(isset($globalCategories))
                                @foreach($globalCategories as $category)
                                    <div class="space-y-2">
                                        <a href="{{ url('/kategori/' . $category->slug) }}" 
                                           class="font-black text-slate-900 hover:text-indigo-600 text-xs block transition-colors border-b border-slate-100 pb-1 flex items-center justify-between group">
                                            <span>{{ __($category->name) }}</span>
                                            <i class="fa-solid fa-arrow-right text-[9px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all"></i>
                                        </a>

                                        @if($category->children->count() > 0)
                                            <ul class="space-y-1.5 pl-1">
                                                @foreach($category->children as $child)
                                                    <li>
                                                        <a href="{{ url('/kategori/' . $child->slug) }}" 
                                                           class="text-xs text-slate-500 hover:text-indigo-600 hover:font-semibold transition-colors flex items-center gap-1.5">
                                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                            <span>{{ __($child->name) }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Yatay Hızlı Kategori Bağlantıları & Açılır Menüler (Hover & Click Dropdowns) -->
                <div class="hidden lg:flex items-center space-x-1 py-2 text-xs font-semibold text-slate-700">
                    @if(isset($globalCategories))
                        @foreach($globalCategories as $category)
                            <div class="relative" 
                                 @mouseenter="activeCatId = {{ $category->id }}" 
                                 @mouseleave="activeCatId = null">
                                
                                <a href="{{ url('/kategori/' . $category->slug) }}" 
                                   class="inline-flex items-center gap-1.5 py-2 px-3 rounded-xl hover:text-indigo-600 hover:bg-slate-50 transition-all whitespace-nowrap {{ $category->children->count() > 0 ? 'font-bold' : '' }}">
                                    <span>{{ __($category->name) }}</span>
                                    @if($category->children->count() > 0)
                                        <i class="fa-solid fa-chevron-down text-[8px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': activeCatId === {{ $category->id }} }"></i>
                                    @endif
                                </a>

                                <!-- Bireysel Kategori Dropdown Açılır Paneli -->
                                @if($category->children->count() > 0)
                                    <div x-show="activeCatId === {{ $category->id }}"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-1"
                                         class="absolute left-0 top-full mt-1 w-64 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50">
                                        
                                        <div class="px-2 pb-2 mb-2 border-b border-slate-100 flex items-center justify-between">
                                            <span class="font-extrabold text-slate-900 text-xs">{{ __($category->name) }}</span>
                                            <a href="{{ url('/kategori/' . $category->slug) }}" class="text-[10px] text-indigo-600 hover:underline font-bold">{{ __('Tümünü Gör') }}</a>
                                        </div>

                                        <div class="space-y-1">
                                            @foreach($category->children as $child)
                                                <a href="{{ url('/kategori/' . $child->slug) }}" 
                                                   class="flex items-center justify-between px-2.5 py-1.5 rounded-xl hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 text-xs transition-colors">
                                                    <span>{{ __($child->name) }}</span>
                                                    <i class="fa-solid fa-angle-right text-[9px] text-slate-300"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Sağ Taraf: Yardım & İletişim -->
                <div class="hidden sm:flex items-center text-xs text-slate-500 font-medium">
                    <a href="#" class="hover:text-indigo-600 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-question text-slate-400"></i>
                        <span>{{ __('Yardım') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 4. Mobil Menü Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-4 shadow-xl">
        <!-- Mobil Arama -->
        <form action="{{ route('home') }}" method="GET" class="relative">
            <input type="text" name="q" placeholder="{{ __('Ürün, marka, kategori veya satıcı ara...') }}" 
                   class="w-full bg-slate-100 border border-slate-200 rounded-xl py-2 pl-10 pr-4 text-xs">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
        </form>

        <!-- Mobil Kategoriler -->
        <div class="space-y-1">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-2 mb-1">{{ __('Kategoriler') }}</div>
            @if(isset($globalCategories))
                @foreach($globalCategories as $category)
                    <div x-data="{ subOpen: false }">
                        <div class="flex items-center justify-between px-2 py-1.5 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <a href="{{ url('/kategori/' . $category->slug) }}">{{ __($category->name) }}</a>
                            @if($category->children->count() > 0)
                                <button @click="subOpen = !subOpen" class="p-1 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-[10px]" :class="{ 'rotate-180': subOpen }"></i>
                                </button>
                            @endif
                        </div>
                        @if($category->children->count() > 0)
                            <div x-show="subOpen" class="pl-4 space-y-1 py-1">
                                @foreach($category->children as $child)
                                    <a href="{{ url('/kategori/' . $child->slug) }}" class="block px-2 py-1 text-xs text-slate-500 hover:text-indigo-600">
                                        {{ __($child->name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
