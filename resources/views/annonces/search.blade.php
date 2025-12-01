<h1>Rechercher une annonce</h1>

<form method="GET" action="{{ route('annonces.search') }}">
    <input type="text" name="localisation"
           placeholder="Où ? (Ville, CP...)"
           value="{{ $localisation }}">
    <button type="submit">Rechercher</button>
</form>

@if(isset($annonces))
    <h2>Résultats : {{ $annonces->count() }} annonces</h2>

    @foreach($annonces as $annonce)
        <div class="annonce-card">
            <a href="{{ route('annonces.show', $annonce->idannonce) }}">
                @if($annonce->photos->first())
                    <img src="{{ $annonce->photos->first()->lienurl }}" width="200">
                @endif

                <h3>{{ $annonce->titreannonce }}</h3>
                <p>{{ Str::limit($annonce->descriptionannonce, 100) }}</p>

                <p>
                    {{ $annonce->ville->nomville }}
                    ({{ $annonce->ville->cpville }})
                </p>
            </a>
        </div>
    @endforeach
@endif
