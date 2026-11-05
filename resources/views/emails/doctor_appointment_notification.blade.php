<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvelle notification de rendez-vous</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .appointment-details { background-color: white; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    @php
        \Carbon\Carbon::setLocale('fr');
    @endphp
    <div class="container">
        <div class="header">
            <h1>Nouveau rendez-vous réservé</h1>
        </div>
        <div class="content">
            <p>Cher Docteur,</p>
            <p>Un nouveau rendez-vous a été réservé avec vous. Voici les détails :</p>
            <div class="appointment-details">
                <h3>Détails du rendez-vous</h3>
                <p><strong>ID de réservation :</strong> {{ $appointment->booking_id }}</p>
                <p><strong>Nom du patient :</strong> {{ $appointment->first_name }} {{ $appointment->last_name }}</p>
                <p><strong>Courriel :</strong> {{ $appointment->email }}</p>
                <p><strong>Téléphone :</strong> {{ $appointment->phone }}</p>
                <p><strong>Date préférée :</strong> {{ \Carbon\Carbon::parse($appointment->preferred_date)->locale('fr')->translatedFormat('j F Y') }}</p>
                <p><strong>Heure préférée :</strong> {{ $appointment->preferred_time }}</p>
                <p><strong>Spécialité :</strong> {{ $appointment->speciality }}</p>
                @if($appointment->additional_notes)
                <p><strong>Notes supplémentaires :</strong> {{ $appointment->additional_notes }}</p>
                @endif
            </div>
            <p>Veuillez vous connecter à votre tableau de bord pour voir plus de détails et gérer le rendez-vous.</p>
            <p>Cordialement,<br>L'équipe MICLINIX</p>
        </div>
        <div class="footer">
            <p>Ceci est un message automatique. Merci de ne pas répondre à cet e-mail.</p>
        </div>
    </div>
</body>
</html>
