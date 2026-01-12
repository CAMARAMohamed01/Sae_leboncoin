@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 font-sans">

    {{-- Header Dynamique --}}
    <div class="bg-{{ $categoryData['color'] }}-600 py-12 px-4 shadow-sm">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('aide.index') }}" class="inline-flex items-center text-white opacity-80 hover:opacity-100 mb-6 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à l'accueil
            </a>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $categoryData['title'] }}</h1>
            <p class="text-white opacity-90">Consultez toutes les questions fréquentes pour cette catégorie.</p>
        </div>
    </div>

    {{-- Liste des questions --}}
    <div class="max-w-4xl mx-auto px-4 -mt-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8 space-y-4">
                
                @foreach($categoryData['faqs'] as $faq)
                    <details class="group border border-gray-200 rounded-lg [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex items-center justify-between p-4 cursor-pointer bg-gray-50 hover:bg-{{ $categoryData['color'] }}-50 hover:text-{{ $categoryData['color'] }}-700 transition-colors">
                            <h3 class="font-medium text-gray-900">{{ $faq['q'] }}</h3>
                            <span class="ml-4 flex-shrink-0 text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </summary>
                        <div class="px-4 pb-4 pt-2 text-gray-600 leading-relaxed border-t border-gray-100">
                            {{ $faq['r'] }}
                        </div>
                    </details>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection