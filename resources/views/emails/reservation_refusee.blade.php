<!DOCTYPE html>
<html>
<head>
    <title>Réservation refusée</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #e3342f; text-align: center;">Réservation refusée</h2>
        
        <p>Bonjour {{ $reservation->locataire->nom_affichage ?? 'Client' }},</p>
        
        <p>Le propriétaire du logement <strong>{{ $reservation->annonce->titreannonce }}</strong> ne peut malheureusement pas accepter votre demande pour les dates du {{ \Carbon\Carbon::parse($reservation->dateDebut->dateacte)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($reservation->dateFin->dateacte)->format('d/m/Y') }}.</p>
        
        @if($motif)
            <div style="background-color: #fff5f5; border-left: 4px solid #e3342f; padding: 15px; margin: 20px 0;">
                <strong>Message du propriétaire :</strong><br>
                <em style="color: #555;">"{{ $motif }}"</em>
            </div>
        @endif

        <p>Aucun montant n'a été débité de votre compte (l'empreinte bancaire a été libérée).</p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('recherche.index') }}" style="background-color: #3490dc; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Chercher un autre logement
            </a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #888; text-align: center;">
            À bientôt sur Leoncoin.
        </p>
    </div>
</body>
</html>