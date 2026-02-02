@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="flex justify-center items-start min-h-[calc(100vh-200px)]">
    <div class="bg-white p-8 w-full max-w-2xl mt-8 rounded-lg shadow-sm border border-gray-200">
        
        <h1 class="text-3xl font-extrabold text-[#1f2d3d] mb-8 font-sans">Paramètres</h1>

        <form action="{{ route('inscription.perso.final') }}" method="POST" enctype="multipart/form-data">
        @csrf
    
    
    <input type="hidden" name="email" value="{{ $email }}">

    <h2 class="text-lg font-bold text-gray-500 mb-6 uppercase tracking-wide">
        Informations de compte
    </h2>

           
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-lg text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6 flex items-center gap-6">
                <div class="flex items-center">
                    <input type="radio" id="monsieur" name="civilite" value="monsieur" class="w-5 h-5 text-lbc-orange border-gray-300 focus:ring-lbc-orange" checked>
                    <label for="monsieur" class="ml-2 text-base font-medium text-[#1f2d3d] cursor-pointer">Monsieur</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" id="madame" name="civilite" value="madame" class="w-5 h-5 text-lbc-orange border-gray-300 focus:ring-lbc-orange">
                    <label for="madame" class="ml-2 text-base font-medium text-[#1f2d3d] cursor-pointer">Madame</label>
                </div>
                <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Votre civilité déclarée
                            </div>
                        </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group">
                    <label for="nom" class="block text-sm font-bold text-[#1f2d3d] mb-2">Nom <span class="text-lbc-orange">*</span>
                        <div class="group relative inline-block ml-2 align-middle">
                            <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                                <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                    Cette information sera publique, merci de respecter les lois sur l'usurpation d'identité
                                </div>
                            </div>
                    </label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="prenom" class="block text-sm font-bold text-[#1f2d3d] mb-2">Prénom <span class="text-lbc-orange">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Cette information sera publique, merci de respecter les lois sur l'usurpation d'identité
                                </div>
                            </div>
                    </label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" maxlength="50" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group mb-4">
                    <label for="date_naissance_input" class="block text-sm font-bold text-[#1f2d3d] mb-2">Date de naissance <span class="text-lbc-orange">*</span></label>
                    <div class="relative">
                        <input type="date" 
                            id="date_naissance_input" 
                            name="date_naissance" 
                            value="{{ old('date_naissance') }}" 
                            min="1905-01-01"
                            max="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" 
                            required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-regular fa-calendar text-gray-400"></i>
                        </div>
                </div>
            </div>

                {{-- AJOUT : CHAMP MOT DE PASSE (Indispensable) --}}
                <div class="form-group mb-8">
                    <label for="password" class="block text-sm font-bold text-[#1f2d3d] mb-2">Mot de passe <span class="text-lbc-orange">*</span></label>
    
                    <input type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" 
                        required>

                    {{-- MESSAGE DE STATUT (sera mis à jour par JavaScript) --}}
                    <span id="password-status" class="mt-2 text-sm font-semibold"></span>

                    {{-- CONSIGNES DE MOT DE PASSE --}}
                    <div id="password-requirements" class="mt-2 text-xs text-gray-600 space-y-1">
                        <p id="req-length" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i> 
                            Minimum 8 caractères
                        </p>
                        <p id="req-lowercase" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i> 
                            Au moins une minuscule
                        </p>
                        <p id="req-uppercase" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i> 
                            Au moins une majuscule
                        </p>
                        <p id="req-number" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i> 
                            Au moins un chiffre
                        </p>
                    </div>
                </div>
            </div>
            
            <hr class="my-10 border-gray-200">

            <h2 class="text-lg font-bold text-gray-500 mb-6 uppercase tracking-wide">
                Adresse
            </h2>


            <div class="form-group mb-6 relative"> 
                <label for="adresse" class="block text-sm font-bold text-[#1f2d3d] mb-2">Adresse <span class="text-lbc-orange">*</span></label>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $user->adresse->nomrue ?? '') }}" required autocomplete="off" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                
                <div id="address-suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
            </div>


            <div class="form-group mb-8">
                <label for="ville" class="block text-sm font-bold text-[#1f2d3d] mb-2">Ville ou code postal <span class="text-lbc-orange">*</span></label>
                <div class="relative">
                    <input type="text" 
                        id="ville" 
                        name="ville" 
                        value="{{ old('ville') }}" 
                        required 
                        class="js-autocomplete-input w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" 
                        placeholder="Où cherchez-vous ? (Ville)">
        
                    <div class="js-autocomplete-results absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto hidden">
                </div>
            </div>
        </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group">
                    <label for="cni_numero" class="block text-sm font-bold text-[#1f2d3d] mb-2">Numéro de la pièce <span class="text-lbc-orange">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                                <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                                    <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                        Le numero présent sur votre piece d'identitée
                                    </div>
                                </div>
                            </label>
                    <input type="text" id="cni_numero" name="cni_numero" value="{{ old('cni_numero') }}" required class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" placeholder="Ex: 123456789">
                </div>

                <div class="form-group">
                    <label for="cni_file" class="block text-sm font-bold text-[#1f2d3d] mb-2">Fichier (Recto/Verso) <span class="text-lbc-orange">*</span>
                        <div class="group relative inline-block ml-2 align-middle">
                            <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                                <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                    Une copie de votre piece d'identitée
                                </div>
                            </div>
                    </label>
                    <input type="file" id="cni_file" name="cni_file" required class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-3 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-orange-50 file:text-lbc-orange
                        hover:file:bg-orange-100
                        cursor-pointer border border-gray-300 rounded-lg bg-white
                    ">
                    <p class="mt-1 text-xs text-gray-500">PDF, JPG ou PNG (Max 4Mo)</p>
                </div>
            </div>

            <button type="submit" class="bg-lbc-orange hover:bg-lbc-orange_hover text-white font-bold py-3 px-8 rounded-2xl transition duration-200 shadow-sm w-full md:w-auto">
                Enregistrer et créer mon compte
            </button>
        </form>
        <p class="mt-6 text-xs text-gray-400 text-center">
            * Champ obligatoire
        </p>

    </div>
</div>

<script src="/js/Autocompleteur.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputDateNaissance = document.getElementById('date_naissance_input');

        if (inputDateNaissance) {
            const today = new Date().toISOString().split('T')[0];

            inputDateNaissance.min = "1905-01-01";
            inputDateNaissance.max = today; 
        }
        
        // --- AUTOCOMPLÉTION ADRESSE ---
        const addressInput = document.getElementById('adresse');
        const cityInput = document.getElementById('ville');
        const suggestionsBox = document.getElementById('address-suggestions');
        let timeout = null;

        if (addressInput) {
            addressInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value;

                if (query.length > 3) {
                    timeout = setTimeout(() => {
                        fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5&autocomplete=1`)
                            .then(response => response.json())
                            .then(data => {
                                suggestionsBox.innerHTML = '';
                                if (data.features && data.features.length > 0) {
                                    suggestionsBox.classList.remove('hidden');
                                    
                                    data.features.forEach(feature => {
                                        const div = document.createElement('div');
                                        div.className = 'px-4 py-2 cursor-pointer hover:bg-orange-50 text-sm text-gray-700 border-b border-gray-100 last:border-0';
                                        div.textContent = feature.properties.label; 
                                        
                                        div.addEventListener('click', function() {
                                            addressInput.value = feature.properties.name; 
                                            
                                            if(cityInput) {
                                                cityInput.value = `${feature.properties.city} (${feature.properties.postcode})`;
                                            }

                                            suggestionsBox.classList.add('hidden');
                                        });
                                        
                                        suggestionsBox.appendChild(div);
                                    });
                                } else {
                                    suggestionsBox.classList.add('hidden');
                                }
                            });
                    }, 300);
                } else {
                    suggestionsBox.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!addressInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.classList.add('hidden');
                }
            });
        }

        // --- FORMAT DATE 
        const dateInput = document.getElementById('date');
        if(dateInput) {
            dateInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); 
                value = value.substring(0, 8);
                let formattedValue = '';
                if (value.length > 0) formattedValue += value.substring(0, 2);
                if (value.length > 2) formattedValue += '/' + value.substring(2, 4);
                if (value.length > 4) formattedValue += '/' + value.substring(4, 8);
                e.target.value = formattedValue;
            });
        }

        // --- MOT DE PASSE 
        const passwordInput = document.getElementById('password');
        const statusText = document.getElementById('password-status');
        const reqLength = document.getElementById('req-length');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqNumber = document.getElementById('req-number');

        if (passwordInput && statusText) { 
            const validatePassword = () => {
                const password = passwordInput.value;
                const rules = {
                    length: { element: reqLength, regex: /.{8,}/ },
                    lowercase: { element: reqLowercase, regex: /[a-z]/ },
                    uppercase: { element: reqUppercase, regex: /[A-Z]/ },
                    number: { element: reqNumber, regex: /[0-9]/ }
                };

                let allValid = true;

                Object.keys(rules).forEach(key => {
                    const rule = rules[key];
                    if(!rule.element) return;
                    
                    const isValid = rule.regex.test(password);
                    const icon = rule.element.querySelector('i');
                    if (isValid) {
                        icon.className = 'fas fa-check-circle text-green-500 mr-2';
                    } else {
                        icon.className = 'fas fa-times-circle text-red-500 mr-2';
                        allValid = false;
                    }
                });
                
                if (password.length === 0) {
                    statusText.textContent = '';
                    passwordInput.classList.remove('border-green-500', 'border-red-500');
                } else if (allValid) {
                    statusText.textContent = 'Mot de passe correct.';
                    statusText.className = 'mt-2 text-sm font-semibold text-green-500';
                    passwordInput.classList.add('border-green-500');
                    passwordInput.classList.remove('border-red-500');
                } else {
                    statusText.textContent = 'Mot de passe incorrect.';
                    statusText.className = 'mt-2 text-sm font-semibold text-red-500';
                    passwordInput.classList.add('border-red-500');
                    passwordInput.classList.remove('border-green-500');
                }
            };

            passwordInput.addEventListener('input', validatePassword);
        }
    });
</script>
@endsection