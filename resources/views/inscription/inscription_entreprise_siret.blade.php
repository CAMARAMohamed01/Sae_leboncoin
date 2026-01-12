@extends('layouts.app')

@section('title', 'Inscription - Détails Entreprise')

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<div class="flex justify-center items-start min-h-[calc(100vh-200px)]">
    <div class="bg-white p-8 w-full max-w-2xl mt-8 rounded-lg shadow-xl border border-gray-200">
        
        <h3 class="text-2xl font-extrabold text-[#1f2d3d] mb-4 font-sans">
            Continuons avec plus d'informations
        </h3>
        
        <p class="text-gray-600 text-base mb-8 leading-relaxed">
            Vérifiez que toutes les informations sont correctes. Les champs pré-remplis peuvent être modifiés si nécessaire.
        </p>

        <form action="{{ route('inscription.entreprise.siret.store') }}" method="POST" class="space-y-6">
            @csrf
            
            {{-- 1. Affichage du SIRET --}}
            <div class="form-group">
                <label for="siret_display" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    SIRET <span class="text-lbc-blue">*</span>
                </label>
                <div class="relative">
                    {{-- Input affiché (désactivé) --}}
                    <input 
                    type="text" 
                    id="siret_display" 
                    value="{{ $siret ?? request('siret') ?? '' }}" 
                    disabled
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none"
                    >
                    {{-- Input caché qui sera envoyé au serveur --}}
                    <input 
                    type="hidden" 
                    name="siret" 
                    id="siret_hidden"
                    value="{{ $siret ?? request('siret') ?? '' }}" 
                    >
                    {{-- Loader --}}
                    <div id="siret-loader" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                        <i class="fas fa-spinner fa-spin text-lbc-blue"></i>
                    </div>
                </div>
                <p id="siret-status-message" class="mt-2 text-sm hidden"></p>
                @error('siret')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 2. Nom de la Société --}}
            <div class="form-group">
                <label for="societe" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Société <span class="text-lbc-blue">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Le nom de votre société
                            </div>
                        </div>
                </label>
                <input type="text" id="societe" name="societe" value="{{ old('societe') }}" required class="w-full px-4 py-3 rounded-lg border @error('societe') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue">
                @error('societe')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 3. Adresse --}}
            <div class="form-group">
                <label for="adresse" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Adresse <span class="text-lbc-blue">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                La voie et la rue de votre société
                            </div>
                        </div>
                </label>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" maxlength="50" required class="w-full px-4 py-3 rounded-lg border @error('adresse') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue">
                @error('adresse')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 4. Code Postal --}}
                <div class="form-group">
                    <label for="cp" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                        Code postal <span class="text-lbc-blue">*</span>
                        <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Le code postal de votre société
                            </div>
                        </div>
                    </label>
                    <input type="text" id="cp" name="cp" value="{{ old('cp') }}" required class="w-full px-4 py-3 rounded-lg border @error('cp') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue">
                    @error('cp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- 5. Ville --}}
                <div class="form-group">
                    <label for="ville" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                        Ville <span class="text-lbc-blue">*</span>
                        <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                La ville de votre société
                            </div>
                        </div>
                    </label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville') }}" required class="w-full px-4 py-3 rounded-lg border @error('ville') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue">
                    @error('ville')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            {{-- 6. Telephone --}}
            <div class="form-group">
                <label for="telephone" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Telephone <span class="text-lbc-blue">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Le numéro de téléphone de votre société au format 01 23 45 67 89
                            </div>
                        </div>
                </label>
                <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}" required class="w-full px-4 py-3 rounded-lg border @error('telephone') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue">
                @error('telephone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Email (Nécessaire pour l'Auth) --}}
            <div class="form-group">
                <label for="email" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Email <span class="text-lbc-blue">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Votre Email professionnel<br><br>(Nécessaire pour la connexion)
                            </div>
                        </div>
                </label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-3 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 7. Secteur d'activité --}}
            <div class="form-group">
                <label for="secteur" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Secteur d'activité <span class="text-lbc-blue">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Le dommaine dans lequel votre société est spécialisée
                            </div>
                        </div>
                </label>
                <div class="relative">
                    <select id="secteur" name="secteur" class="w-full px-4 py-3 rounded-lg border @error('secteur') border-red-500 @else border-gray-300 @enderror appearance-none bg-white" required>
                        <option value="" disabled @if (!old('secteur')) selected @endif hidden>Choisissez un secteur</option>
                        <option value="Véhicule" @if (old('secteur') == 'Véhicule') selected @endif>Véhicule</option>
                        {{-- ... autres options ... --}}
                         <option value="Immobilier" @if (old('secteur') == 'Immobilier') selected @endif>Immobilier</option>
                        <option value="Multimédia" @if (old('secteur') == 'Multimédia') selected @endif>Multimédia</option>
                        <option value="Maison" @if (old('secteur') == 'Maison') selected @endif>Maison</option>
                        <option value="Loisirs" @if (old('secteur') == 'Loisirs') selected @endif>Loisirs</option>
                        <option value="Services" @if (old('secteur') == 'Services') selected @endif>Services</option>
                        <option value="Matériel professionnel" @if (old('secteur') == 'Matériel professionnel') selected @endif>Matériel professionnel</option>
                        <option value="Emploi" @if (old('secteur') == 'Emploi') selected @endif>Emploi</option>
                        <option value="Vacances" @if (old('secteur') == 'Vacances') selected @endif>Vacances</option>
                        <option value="Mode" @if (old('secteur') == 'Mode') selected @endif>Mode</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
                @error('secteur')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 8. MDP --}}
            <div class="form-group">
                <label for="mdp" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Mot de passe <span class="text-lbc-blue">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                8 caractères minimum<br>Au moins 1 majuscule<br>Au moins 1 minuscule<br>Au moins 1 chiffre
                            </div>
                        </div>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="mdp" 
                        name="mdp" 
                        required 
                        minlength="8"
                        maxlength="50"
                        class="w-full px-4 py-3 pr-10 rounded-lg border @error('mdp') border-red-500 @else border-gray-300 @enderror focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue"
                        placeholder="Votre mot de passe sécurisé"
                    >
                    
                    <button 
                        type="button" 
                        id="togglePassword" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-lbc-blue focus:outline-none"
                        aria-label="Afficher/Masquer le mot de passe"
                    >
                        <i class="fas fa-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>
                
            </div>

            {{-- 9. Bouton de Soumission --}}
            <button type="submit" id="submit-button" class="w-full bg-lbc-blue hover:bg-lbc-blue_hover text-white font-bold py-3 px-4 rounded-lg transition duration-200 mt-8 flex justify-center items-center gap-2 disabled:bg-gray-400">
                Continuer <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // --- Récupération des éléments du DOM ---
        const siretDisplay = document.getElementById('siret_display');
        const societeInput = document.getElementById('societe');
        const adresseInput = document.getElementById('adresse');
        const cpInput = document.getElementById('cp');
        const villeInput = document.getElementById('ville');
        const loader = document.getElementById('siret-loader');
        const statusMsg = document.getElementById('siret-status-message');
        const submitButton = document.getElementById('submit-button');
        const siretHiddenInput = document.getElementById('siret_hidden');
        
        // --- Éléments du Mot de Passe ---
        const togglePassword = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('toggleIcon');
        const passwordInput = document.getElementById('mdp');

        // --- Gestion Afficher/Masquer Mot de Passe ---
        if (togglePassword && passwordInput && toggleIcon) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'text') {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                } else {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                }
            });
        }

        // --- Formatage du Téléphone (2 2 2 2 2) ---
        const phoneInput = document.getElementById('telephone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); 
                value = value.substring(0, 10);
                let formattedValue = '';
                
                for (let i = 0; i < value.length; i += 2) {
                    formattedValue += value.substring(i, i + 2) + (i + 2 < value.length ? ' ' : '');
                }

                e.target.value = formattedValue;
            });
        }

        // Nettoyage du SIRET (garde uniquement les chiffres)
        const siret = siretHiddenInput.value.replace(/\D/g, '').trim();

        // Fonction pour afficher des messages
        const updateStatus = (text, type) => {
            statusMsg.textContent = text;
            statusMsg.classList.remove('hidden', 'text-red-500', 'text-green-500', 'text-blue-500');
            if (type === 'success') statusMsg.classList.add('text-green-500');
            else if (type === 'error') statusMsg.classList.add('text-red-500');
            else statusMsg.classList.add('text-blue-500');
        };

        // --- FONCTIONNALITÉ SIRET (Autocomplétion) ---
        const fetchCompanyDetails = async (siret) => {
            if (!siret || siret.length !== 14) {
                updateStatus("Veuillez vérifier le SIRET.", 'error');
                return;
            }
            loader.classList.remove('hidden');
            submitButton.disabled = true;
            updateStatus("Recherche en cours...", 'default');

            try {
                const response = await fetch(`https://recherche-entreprises.api.gouv.fr/search?q=${siret}&per_page=1`);
                
                if (!response.ok) throw new Error(`Erreur HTTP ${response.status} lors de l'appel API.`);

                const data = await response.json();

                if (data.results && data.results.length > 0) {
                    const company = data.results[0];
                    const siege = company.siege;

                    societeInput.value = company.nom_complet || '';

                    // ✅ CORRECTION ICI : Construction intelligente de l'adresse
                    // On prend uniquement le numéro et le type de voie pour que ça rentre dans les 50 caractères
                    const numero = siege.numero_voie || '';
                    const typeVoie = siege.type_voie || '';
                    const libelleVoie = siege.libelle_voie || '';
                    
                    // On assemble l'adresse propre (ex: "4 BOULEVARD DE MONS")
                    let adressePropre = `${numero} ${typeVoie} ${libelleVoie}`.trim();
                    
                    // Fallback si jamais les champs détaillés sont vides
                    if (!adressePropre) {
                         // On prend l'adresse complète et on coupe avant le code postal
                         adressePropre = (siege.adresse || '').split(siege.code_postal)[0].trim();
                    }
                    
                    adresseInput.value = adressePropre; // C'est cette valeur courte qui ira en BDD

                    cpInput.value = siege.code_postal || '';
                    villeInput.value = siege.libelle_commune || '';

                    updateStatus("Entreprise trouvée et champs remplis !", 'success');
                } else {
                    updateStatus("Aucune entreprise trouvée pour ce SIRET. Saisissez manuellement.", 'error');
                }

            } catch (error) {
                console.error("Erreur API:", error);
                updateStatus(`Erreur : ${error.message}. Remplissez manuellement.`, 'error');
            } finally {
                loader.classList.add('hidden');
                submitButton.disabled = false;
            }
        };

        // DÉCLENCHE L'AUTOCOMPLÉTION AU CHARGEMENT si le SIRET est présent
        if (siret && siret.length === 14) {
            fetchCompanyDetails(siret);
        } else if (siret.length > 0) {
             updateStatus("SIRET transmis mais incomplet ou invalide.", 'error');
        }
    }); 
</script>
@endsection