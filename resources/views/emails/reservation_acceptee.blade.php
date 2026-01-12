<!DOCTYPE html>
<html>
<head>
    <title>Réservation confirmée</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #2d9d78; text-align: center;">C'est validé ! 🎉</h2>
        
        <p>Bonjour <strong>{{ $reservation->locataire->nom_affichage ?? 'Voyageur' }}</strong>,</p>
        
        <p>Le propriétaire a accepté votre demande de réservation pour le logement :</p>
        <p style="font-size: 18px; font-weight: bold;">{{ $reservation->annonce->titreannonce }}</p>

        <div style="background-color: #f0fdf4; padding: 15px; border-radius: 8px; border-left: 4px solid #2d9d78; margin: 20px 0;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li>📅 <strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($reservation->dateDebut->dateacte)->format('d/m/Y') }}</li>
                <li>📅 <strong>Départ :</strong> {{ \Carbon\Carbon::parse($reservation->dateFin->dateacte)->format('d/m/Y') }}</li>
                <li>👥 <strong>Voyageurs :</strong> {{ $reservation->nbadulte }}</li>
            </ul>
        </div>

        <p>Votre paiement a été validé et le montant a été débité.</p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('reservations.mes_locations') }}" style="background-color: #ec5a13; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Voir ma réservation
            </a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #888; text-align: center;">
            Bon séjour avec Leoncoin !
        </p>
    </div>
</body>
</html>