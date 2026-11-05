<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordonnance</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .footer { background-color: #f8f9fa; padding: 10px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MICLINIX</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $appointment->first_name }} {{ $appointment->last_name }},</p>
            <p>Votre médecin a émis une ordonnance pour votre rendez-vous (ID de réservation : {{ $appointment->booking_id }}).</p>
            <p>Veuillez trouver les détails de l'ordonnance en pièce jointe au format PDF.</p>
            <p>Si vous avez des questions, veuillez nous contacter.</p>
            <p>Cordialement, MICLINIX<br></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MICLINIX. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
