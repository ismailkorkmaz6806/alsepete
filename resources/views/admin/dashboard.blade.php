<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Yönetim Paneli') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-900 font-medium">Hoş geldiniz, Sistem Yöneticisi!</p>
                <div class="mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Kategori Yönetimi
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
