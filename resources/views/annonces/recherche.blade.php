@foreach($annonces as $annonce)
    <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="annonce-card">
        <div>
            @if($annonce->photos->first())
                <img src="{{ $annonce->photos->first()->lienurl }}" width="200">
            @endif
            <h3>{{ $annonce->titreannonce }}</h3>
            <p>{{ Str::limit($annonce->descriptionannonce, 80) }}</p>
            <p>{{ $annonce->ville->nomville }} ({{ $annonce->ville->cpville }})</p>
        </div>
    </a>
@endforeach
