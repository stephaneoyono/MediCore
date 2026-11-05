<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de rendez-vous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .appointment-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .booking-id {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    @php
        \Carbon\Carbon::setLocale('fr');
        $status = strtolower($appointment->status ?? 'pending');
        $statusLabel = [
            'confirmed' => 'Confirmé',
            'pending' => 'En attente',
            'cancelled' => 'Annulé',
            'completed' => 'Terminé',
            'in_progress' => 'En cours',
        ][$status] ?? $status;
    @endphp
    <div class="header">
        <h1>Rendez-vous confirmé</h1>
        <p>Votre rendez-vous a été réservé avec succès</p>
    </div>

    <div class="content">
        <p>Cher/Chère {{ $appointment->first_name }} {{ $appointment->last_name }},</p>

        <p>Merci d'avoir pris rendez-vous avec MICLINIX. Les détails de votre rendez-vous sont confirmés et sont joints à cet e-mail au format PDF pour vos dossiers.</p>

        <div class="appointment-details">
            <div class="booking-id">Référence de réservation : {{ $appointment->booking_id }}</div>
            <p><strong>Nom du patient :</strong> {{ $appointment->first_name }} {{ $appointment->last_name }}</p>
            <p><strong>Courriel :</strong> {{ $appointment->email }}</p>
            <p><strong>Téléphone :</strong> {{ $appointment->phone }}</p>
            <p><strong>Date souhaitée :</strong> {{ \Carbon\Carbon::parse($appointment->preferred_date)->locale('fr')->translatedFormat('j F Y') }}</p>
            <p><strong>Heure souhaitée :</strong> {{ $appointment->preferred_time }}</p>
            <p><strong>Spécialité :</strong> {{ $appointment->speciality }}</p>
            @if($appointment->doctor_name)
            <p><strong>Médecin :</strong> {{ $appointment->doctor_name }}</p>
            @endif
            @if($appointment->additional_notes)
            <p><strong>Notes supplémentaires :</strong> {{ $appointment->additional_notes }}</p>
            @endif
            <p><strong>Statut :</strong> {{ $statusLabel }}</p>
        </div>

        <p>Veuillez conserver cette référence de réservation pour vos références. Vous pouvez l'utiliser pour suivre le statut de votre rendez-vous ou pour effectuer des modifications.</p>

        <p>Si vous avez des questions ou devez replanifier, veuillez nous contacter à reservation@miclinix.com ou appelez-nous au 237674000000.</p>

        <p>Au plaisir de vous accueillir !</p>

        <p>Cordialement,<br>
        L'équipe MICLINIX</p>
    </div>

    <div class="footer">
        <p>Ceci est un e-mail automatique. Merci de ne pas répondre à ce message.</p>
        <p>&copy; {{ date('Y') }} MICLINIX. Tous droits réservés.</p>
    </div>
</body>
</html>
