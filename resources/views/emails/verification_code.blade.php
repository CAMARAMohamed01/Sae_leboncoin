<!DOCTYPE html>
<html>
<head>
    <title>Code de vérification</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <div style="text-align: center; padding: 20px;">
        <h1 style="color: #ec5a13;">Bienvenue sur leboncoin !</h1>
        <p>Voici votre code de vérification pour continuer votre inscription :</p>
        
        <div style="background-color: #f4f6f7; padding: 15px; border-radius: 8px; display: inline-block; margin: 20px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px;">{{ $code }}</span>
        </div>
        
        <p>Ce code est valable pendant 10 minutes.</p>
        <p style="font-size: 12px; color: #888;">Si vous n'avez pas demandé ce code, ignorez cet email.</p>
    </div>
</body>
</html>