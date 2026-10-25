<footer class="border-t border-slate-200 bg-white mt-auto text-slate-500 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="space-y-3 col-span-2 md:col-span-1">
            <a href="{{ route('home') }}" class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white text-xs shadow-sm">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <span>Pazar<span class="text-indigo-600">Yeri</span></span>
            </a>
            <p class="text-slate-500 text-xs leading-relaxed">
                {{ __('Çok satıcılı, modern ve güvenilir e-ticaret pazaryeri ekosistemi.') }}
            </p>
            <div class="flex space-x-3 text-slate-400 pt-1 text-sm">
                <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>

        <div>
            <h4 class="font-bold text-slate-900 mb-3 text-sm">{{ __('Kurumsal') }}</h4>
            <ul class="space-y-2 text-slate-500">
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Hakkımızda') }}</a></li>
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Kariyer & İletişim') }}</a></li>
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Gizlilik & Güvenlik Politikası') }}</a></li>
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Mesafeli Satış Sözleşmesi') }}</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-bold text-slate-900 mb-3 text-sm">{{ __('Müşteri Hizmetleri') }}</h4>
            <ul class="space-y-2 text-slate-500">
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Sıkça Sorulan Sorular') }}</a></li>
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Canlı Destek & İletişim') }}</a></li>
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Kolay İade & Değişim') }}</a></li>
                <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('Kargo & Teslimat Takibi') }}</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-bold text-slate-900 mb-3 text-sm">{{ __('Satıcı Ekosistemi') }}</h4>
            <p class="mb-3 text-slate-500 leading-relaxed text-xs">{{ __("PazarYeri'nde mağazanızı açın, yüz binlerce müşteriye hemen ulaşın.") }}</p>
            <a href="{{ url('/satici') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl text-xs transition-colors shadow-sm">
                <i class="fa-solid fa-store text-xs"></i>
                <span>{{ __('Satıcı Başvurusu Yap') }}</span>
            </a>
        </div>
    </div>

    <div class="border-t border-slate-100 py-6 text-center text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
            <span>&copy; {{ date('Y') }} {{ __('PazarYeri E-Ticaret Platformu. Tüm hakları saklıdır.') }}</span>
            <div class="flex items-center gap-4 text-slate-400">
                <span><i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> {{ __('256-Bit SSL Güvenli Ödeme') }}</span>
                <span><i class="fa-solid fa-truck text-indigo-500 mr-1"></i> {{ __('Çoklu Satıcı Kargo') }}</span>
            </div>
        </div>
    </div>
</footer>
