<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle demande de contact</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hey Martin, t'as un nouveau message !</h2>
    
    <p><strong>Sujet :</strong> {{ $data['subject'] }}</p>
    <p><strong>De :</strong> {{ $data['email'] }}</p>
    
    <hr>
    
    <p><strong>Message :</strong></p>
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px;">
        {!! nl2br(e($data['message'])) !!}
    </div>
</body>
</html>