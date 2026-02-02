<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export Données Personnelles</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        h1 { color: #ec5a13; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        h2 { color: #2d3748; margin-top: 20px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .info-group { margin-bottom: 15px; }
        .label { font-weight: bold; color: #666; width: 150px; display: inline-block; }
        .value { color: #000; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <h1>Rapport de Données Personnelles (RGPD)</h1>
    <p>Export généré le {{ $date_export }} pour le compte #{{ $user->idutilisateur }}</p>

    <h2>1. Identité et Contact</h2>
    <div class="info-group">
        <span class="label">Nom d'affichage :</span>
        <span class="value">{{ $user->nom_affichage }}</span>
    </div>
    <div class="info-group">
        <span class="label">Email :</span>
        <span class="value">{{ $user->emailutilisateur }}</span>
    </div>
    <div class="info-group">
        <span class="label">Téléphone :</span>
        <span class="value">{{ $user->telutilisateur }}</span>
    </div>
    <div class="info-group">
        <span class="label">Date inscription :</span>
        <span class="value">{{ $user->date_creation ? $user->date_creation->format('d/m/Y') : 'N/A' }}</span>
    </div>

    @if($user->adresse)
    <div class="info-group">
        <span class="label">Adresse postale :</span>
        <span class="value">
            {{ $user->adresse->voie }} {{ $user->adresse->nomrue }}<br>
            {{ $user->adresse->ville->cpville ?? '' }} {{ $user->adresse->ville->nomville ?? '' }}
        </span>
    </div>
    @endif

    <h2>2. Activité sur la plateforme</h2>
    
    <h3>Annonces publiées ({{ $user->annonces->count() }})</h3>
    @if($user->annonces->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Ville</th>
                    <th>Date dépôt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->annonces as $annonce)
                <tr>
                    <td>#{{ $annonce->idannonce }}</td>
                    <td>{{ $annonce->titreannonce }}</td>
                    <td>{{ $annonce->ville->nomville ?? '-' }}</td>
                    <td>{{ $annonce->dateEnregistrement ? \Carbon\Carbon::parse($annonce->dateEnregistrement->dateacte)->format('d/m/Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune annonce publiée.</p>
    @endif

    <h3>Réservations effectuées ({{ $user->reservations->count() }})</h3>
    @if($user->reservations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Réf.</th>
                    <th>Logement</th>
                    <th>Dates</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->reservations as $resa)
                <tr>
                    <td>#{{ $resa->idreservation }}</td>
                    <td>{{ $resa->annonce->titreannonce ?? 'Supprimé' }}</td>
                    <td>Du {{ $resa->dateDebut->dateacte ?? '?' }} au {{ $resa->dateFin->dateacte ?? '?' }}</td>
                    <td>{{ $resa->statut_reservation }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune réservation effectuée.</p>
    @endif

    <div class="footer">
        Document généré automatiquement par Leoncoin - Ce document contient des données personnelles confidentielles.
    </div>

</body>
</html>