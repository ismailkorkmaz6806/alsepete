<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kategori Yönetimi') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                + Yeni Kategori Ekle
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Kategori Adı</th>
                                <th class="px-6 py-4">Hiyerarşi / Üst Kategori</th>
                                <th class="px-6 py-4">Slug</th>
                                <th class="px-6 py-4">Komisyon Oranı</th>
                                <th class="px-6 py-4">Sıralama</th>
                                <th class="px-6 py-4 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        @if($category->parent_id)
                                            <span class="text-gray-400 mr-2">↳</span>
                                        @endif
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($category->parent)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $category->parent->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Ana Kategori
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">
                                        {{ $category->slug }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">
                                        %{{ number_format($category->commission_rate, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $category->sort }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                            Düzenle
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 font-medium text-sm ml-2">
                                                Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        Henüz kayıtlı bir kategori bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
