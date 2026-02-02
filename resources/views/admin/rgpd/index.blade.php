@extends('layouts.app')

@section('title', 'Espace DPO - RGPD')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="p-3 bg-gray-800 text-white rounded-xl">
            <i class="fa-solid fa-user-shield text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Conformité RGPD</h1>
            <p class="text-gray-500 mt-1">Sélection et anonymisation des comptes (Droit à l'oubli).</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- COLONNE GAUCHE : RECHERCHE --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                <h2 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-filter text-lbc-blue"></i> Critères
                </h2>
                
                <form action="{{ route('admin.rgpd.index') }}" method="GET" class="space-y-4">
                    
                    {{-- Filtre Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Par Email</label>
                        <input type="text" name="email_search" value="{{ request('email_search') }}" placeholder="Ex: jean.dupont@" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-sm">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Filtre Date Création --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Créé avant le</label>
                        <input type="date" 
                               id="date_limite"
                               name="date_limite" 
                               value="{{ request('date_limite') }}" 
                               min="1905-01-01" 
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-sm text-gray-600">
                    </div>

                    {{-- Filtre Date Connexion --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Inactif depuis le</label>
                        <input type="date" 
                               id="date_connexion"
                               name="date_connexion" 
                               value="{{ request('date_connexion') }}" 
                               min="1905-01-01"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-sm text-gray-600">
                        <p class="text-[10px] text-gray-400 mt-1 italic">Dernière connexion antérieure à cette date.</p>
                    </div>

                    <button type="submit" class="w-full bg-lbc-blue text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition flex justify-center items-center gap-2 shadow-sm mt-4">
                        <i class="fa-solid fa-search"></i> Rechercher
                    </button>
                    
                    @if(request()->hasAny(['email_search', 'date_limite', 'date_connexion']))
                        <a href="{{ route('admin.rgpd.index') }}" class="block text-center text-xs text-gray-500 hover:text-red-500 underline mt-2">
                            Réinitialiser les filtres
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- COLONNE DROITE : RÉSULTATS --}}
        <div class="lg:col-span-3">
            @if($hasSearch)
                
                <form action="{{ route('admin.rgpd.anonymiser') }}" method="POST" id="form-anonymisation">
                    @csrf
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <div>
                                <h3 class="font-bold text-gray-900">Résultats de la recherche</h3>
                                <p class="text-xs text-gray-500">Sélectionnez les comptes à traiter.</p>
                            </div>
                            <span class="bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">
                                {{ $usersCibles->count() }} comptes trouvés
                            </span>
                        </div>

                        @if($usersCibles->isEmpty())
                            <div class="p-12 text-center text-gray-500">
                                <i class="fa-solid fa-check-circle text-4xl mb-3 text-green-500"></i>
                                <p>Aucun compte ne correspond à vos critères.</p>
                            </div>
                        @else
                            <div class="max-h-[500px] overflow-y-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-100 text-gray-800 font-bold uppercase text-xs sticky top-0 z-10 shadow-sm">
                                        <tr>
                                            <th class="px-6 py-3 w-10 text-center">
                                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer w-4 h-4">
                                            </th>
                                            <th class="px-6 py-3">Utilisateur</th>
                                            <th class="px-6 py-3">Rôle</th>
                                            <th class="px-6 py-3">Dates (Création / Dernière co.)</th>
                                            <th class="px-6 py-3 text-right">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($usersCibles as $user)
                                            <tr class="hover:bg-red-50 transition cursor-pointer row-click-target">
                                                <td class="px-6 py-3 text-center">
                                                    <input type="checkbox" name="selected_users[]" value="{{ $user->idutilisateur }}" class="user-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4">
                                                </td>
                                                <td class="px-6 py-3">
                                                    <div class="font-bold text-gray-900">{{ $user->nom_affichage }}</div>
                                                    <div class="text-xs text-gray-400">{{ $user->emailutilisateur }}</div>
                                                </td>
                                                <td class="px-6 py-3">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $user->Role ?? 'Utilisateur' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 font-mono text-xs">
                                                    <div>Créé: {{ $user->date_creation ? $user->date_creation->format('d/m/Y') : 'N/A' }}</div>
                                                    <div class="text-gray-400">Vu: {{ $user->date_derniere_connexion ? $user->date_derniere_connexion->format('d/m/Y') : 'Jamais' }}</div>
                                                </td>
                                                <td class="px-6 py-3 text-right">
                                                    {{-- LOGIQUE DE STATUT MISE À JOUR --}}
                                                    @if(!$user->statut_rgpd)
                                                        <span class="text-red-500 font-bold text-xs">Déjà anonymisé</span>
                                                    @elseif($user->date_derniere_connexion && $user->date_derniere_connexion->lt(now()->subYears(2)))
                                                        {{-- Si dernière connexion > 2 ans --}}
                                                        <span class="text-orange-500 font-bold text-xs bg-orange-100 px-2 py-1 rounded">Inactif (> 2 ans)</span>
                                                    @else
                                                        <span class="text-green-600 font-bold text-xs">Actif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    @if($usersCibles->isNotEmpty())
                        <div class="bg-red-50 border border-red-100 rounded-xl p-6 shadow-sm">
                            <h3 class="font-bold text-red-800 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i> Zone de Danger
                            </h3>
                            
                            <label class="flex items-start gap-3 mb-6 cursor-pointer p-3 hover:bg-red-100 rounded-lg transition border border-transparent hover:border-red-200">
                                <input type="checkbox" name="confirmation" required class="mt-1 w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                <span class="text-sm text-red-800">
                                    <strong>Je confirme vouloir traiter les comptes sélectionnés.</strong><br>
                                    <span class="text-xs opacity-80">Cette action est irréversible. Assurez-vous d'avoir vérifié votre sélection.</span>
                                </span>
                            </label>

                            <div class="flex flex-col sm:flex-row justify-end gap-4">
                                {{-- BOUTON 1 : ANONYMISATION (Route par défaut du formulaire) --}}
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="fa-solid fa-user-secret"></i> ANONYMISER (Garder Stats)
                                </button>

                                {{-- BOUTON 2 : SUPPRESSION TOTALE (Nouvelle route via formaction) --}}
                                <button type="submit" 
                                        formaction="{{ route('admin.rgpd.supprimer') }}" 
                                        onclick="return confirm('⚠️ ALERTE SUPPRESSION TOTALE ⚠️\n\nVous allez supprimer DÉFINITIVEMENT ces comptes et TOUTES les données liées (Annonces, Réservations, Historique...).\n\nRien ne sera conservé. Confirmer ?');"
                                        class="bg-gray-800 hover:bg-black text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="fa-solid fa-trash-can"></i> SUPPRIMER TOTALEMENT
                                </button>
                            </div>
                        </div>
                    @endif
                </form>

            @else
                {{-- État initial sans recherche --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center h-full flex flex-col justify-center items-center">
                    <div class="bg-blue-50 p-6 rounded-full mb-6">
                        <i class="fa-solid fa-magnifying-glass text-5xl text-blue-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Recherche de comptes</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        Utilisez les filtres à gauche pour trouver des utilisateurs par <strong>email</strong>, par <strong>date de création</strong> ou par <strong>inactivité</strong>.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const rows = document.querySelectorAll('.row-click-target');

        const dateLimiteInput = document.getElementById('date_limite');
        const dateConnexionInput = document.getElementById('date_connexion');

        if(dateLimiteInput && dateConnexionInput) {

            dateLimiteInput.addEventListener('change', function() {
                if(this.value) {
                    dateConnexionInput.min = this.value;
                } else {
                    dateConnexionInput.min = "1905-01-01";
                }
            });
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.type === 'checkbox') return;
                const checkbox = this.querySelector('.user-checkbox');
                if(checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            });
        });
    });
</script>
@endsection