@extends('layouts.app') 

@section('content')
    
    <div class="fiche-pro">
        
        <p>
        nom de la société : 
            {{$professionnel->nomprofessionnel}}
        </p>
        <p>num Siret: {{$professionnel->numerosiret}}</p>
        <p>
            secteur d'activité : {{$professionnel->secteuractivite}}
        </p>
        <p>adresse de la société : {{ $professionnel->adresse?->voie ?? 'Aucune adresse renseignée' }}  {{ $professionnel->adresse?->nomrue ?? 'Aucune adresse renseignée' }} </p>

        <p>ville : {{ $professionnel->adresse?->ville->nomville ?? 'Aucune adresse renseignée' }}        {{ $professionnel->adresse?->ville->cpville ?? 'Aucune adresse renseignée' }}</p> 

        <p>note :   {{ $professionnel->annoncedumemeproffessionnel->first()?->getNoteMoyenneAttributes() }}</p>


        @forelse($professionnel->annoncedumemeproffessionnel as $annonce)
        <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="group block bg-white rounded-lg shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden">
                <div class="relative h-48 bg-gray-200 overflow-hidden">
                    @if($annonce->photos->isNotEmpty())
                        <img src="{{ $annonce->photos->first()->lienurl }}" alt="{{ $annonce->titreannonce }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                            <i class="fa-regular fa-image text-3xl"></i>
                        </div>
                    @endif
                    
                    @if($annonce->photos->count() > 1)
                    <div class="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded-md">
                        <i class="fa-solid fa-camera mr-1"></i> {{ $annonce->photos->count() }}
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-gray-900 truncate group-hover:text-lbc-blue transition">{{ $annonce->titreannonce }}</h3>
                    
                    @if($annonce->prix_periodes_min_prix)
                        <p class="text-lbc-orange font-bold mt-1">
                            {{ number_format($annonce->prix_periodes_min_prix, 0, ',', ' ') }} € 
                            <span class="text-xs text-gray-500 font-normal">/nuit</span>
                        </p>
                    @else
                        <p class="text-gray-400 text-sm italic mt-1">Prix non renseigné</p>
                    @endif

                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $annonce->ville->nomville ?? 'Localisation' }}
                        </span>
                        <span>
                            {{ $annonce->dateEnregistrement->dateacte ?? 'Date inconnue' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500 border border-dashed rounded-lg border-gray-300 bg-gray-50 w-full">
                <p class="text-lg font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-triangle-exclamation mr-2 text-lbc-orange"></i>
                    Aucune annonce trouvée.
                </p>
                <p>Il n'y a actuellement aucune annonce correspondant à cette recherche ou à cette ville.</p>
                <p class="text-sm mt-3">Soyez le premier à déposer une annonce !</p>
            </div>
            @endforelse
    </div>

@endsection

<style>

.fiche-pro {
    background-color: #ffffff;
    max-width: 600px;          
    margin: 40px auto;         
    padding: 30px;             
    border-radius: 12px;       
    box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;               
    border: 1px solid #e0e0e0;
}


.fiche-pro p {
    margin: 0;                 
    padding: 15px 0;           
    border-bottom: 1px solid #f0f0f0; 
    font-size: 16px;
    line-height: 1.5;
    display: flex;             
    justify-content: space-between; 
    align-items: center;
}

.fiche-pro p:last-child {
    border-bottom: none;
}

.fiche-pro p:hover {
    background-color: #fafafa;
    padding-left: 10px; 
    padding-right: 10px;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.fiche-pro p::first-letter {
    text-transform: uppercase;
}
</style>