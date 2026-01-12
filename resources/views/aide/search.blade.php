@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 font-sans">
    
    <div class="bg-gray-900 py-12 px-4 shadow-sm">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('aide.index') }}" class="text-gray-400 hover:text-white flex items-center mb-4 transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Retour au centre d'aide
            </a>
            <h1 class="text-3xl font-bold text-white">
                Résultats pour : "{{ $query }}"
            </h1>
            <p class="mt-2 text-gray-400">
                {{ count($results) }} résultat(s) trouvé(s)
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(count($results) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @foreach($results as $faq)
                    <div class="border-b border-gray-100 last:border-0 p-6">
                        <div class="flex items-center mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $faq['category_color'] }}-100 text-{{ $faq['category_color'] }}-800 mr-3">
                                {{ ucfirst($faq['category_slug']) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $faq['q'] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $faq['r'] }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat</h3>
                <p class="mt-1 text-sm text-gray-500">Essaie avec d'autres mots clés.</p>
            </div>
        @endif
    </div>
</div>
@endsection