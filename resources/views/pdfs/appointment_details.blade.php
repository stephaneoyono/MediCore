<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rendez-vous — {{ $appointment->booking_id }}</title>
    <style>
        @page { size: A4; margin: 22mm 18mm; }
        html, body { height: 100%; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #0f172a;
            line-height: 1.55;
            font-size: 12.5px;
            margin: 0;
            background: #fff;
        }
        * { box-sizing: border-box; }
        .container { position: relative; z-index: 0; }

        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-12deg);
            opacity: .06;
            z-index: 0;
            width: 70%;
            text-align: center;
            filter: grayscale(100%);
            pointer-events: none;
        }
        .watermark img { max-width: 100%; height: auto; }

        .header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px; margin-bottom: 16px;
            position: relative; z-index: 2;
            width: 100%;
        }
        .brand-logo { width: 40px; height: 40px; object-fit: contain; }
        .brand-title { font-weight: 800; letter-spacing: .2px; color: #1d4ed8; }
        .brand-subtitle { color: #64748b; font-size: 10.5px; }

        .meta {
            text-align: right;
            font-size: 11px;
            color: #334155;
        }

        .qr-container {
            position: absolute;
            top: 0; right: 0;
            width: 14px; height: 14px;
            overflow: hidden;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 10.5px; font-weight: 700;
            border: 1px solid currentColor;
            letter-spacing: .3px;
        }
        .badge--confirmed { color: #16a34a; }
        .badge--pending { color: #d97706; }
        .badge--cancelled { color: #dc2626; }

        .booking {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            margin: 10px 0 18px;
            width: 100%;
        }

        .small { color: #64748b; font-size: 11px; }
        .id { font-weight: 800; color: #1d4ed8; letter-spacing:.3px; }

        .section { margin-bottom: 16px; position: relative; z-index: 1; }
        .section-title {
            font-weight: 800; font-size: 12.5px; color: #1d4ed8;
            border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px;
            letter-spacing: .2px;
        }

        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 4px 6px; vertical-align: top; }
        .label { font-weight: 700; color: #0f172a; width: 135px; }
        .value { color: #334155; }

        .note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 10px 12px; border-radius: 10px;
            font-size: 11.5px; color: #713f12;
            margin: 14px 0;
        }

        .footer {
            margin-top: 16px; padding-top: 10px;
            border-top: 1px solid #e2e8f0; text-align: center; color: #64748b; font-size: 10.5px;
            position: relative; z-index: 2;
        }

        .nowrap { white-space: nowrap; }

        table { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    \Carbon\Carbon::setLocale('fr');
    $status = strtolower($appointment->status ?? 'pending');
    $badgeClass = [
        'confirmed' => 'badge badge--confirmed',
        'pending'   => 'badge badge--pending',
        'cancelled' => 'badge badge--cancelled',
    ][$status] ?? 'badge badge--pending';
    $statusLabel = [
        'confirmed' => 'Confirmé',
        'pending'   => 'En attente',
        'cancelled' => 'Annulé',
        'completed' => 'Terminé',
        'in_progress' => 'En cours',
    ][$status] ?? $status;
    $genderLabel = [
        'male' => 'Homme',
        'female' => 'Femme',
        'other' => 'Autre',
    ][strtolower($appointment->gender ?? '')] ?? ($appointment->gender ?: 'Non renseigné');
@endphp

<div class="watermark">
    <img src="{{ $watermarkLogoPath ?? (isset($logoPath) ? $logoPath : public_path('images/logo.png')) }}" alt="MICLINIX" />
</div>

<div class="container">
    <!-- Header -->
    <table class="header">
        <tr>
            <td style="width:70%; vertical-align:top;">
                <table>
                    <tr>
                        <td style="width:45px;">
                            <img class="brand-logo" src="{{ $logoPath ?? public_path('images/logo.png') }}" alt="MICLINIX Logo" />
                        </td>
                        <td>
                            <div class="brand-title">MICLINIX</div>
                            <div class="brand-subtitle">Confirmation de rendez-vous • Document officiel</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:30%; text-align:right; vertical-align:top;" class="meta">
                <div><strong>Date :</strong> {{ now()->locale('fr')->translatedFormat('j F Y') }}</div>
                <div><strong>Heure :</strong> {{ now()->format('H:i') }}</div>
                <div><span class="{{ $badgeClass }}">{{ $statusLabel }}</span></div>
            </td>
        </tr>
    </table>

    <div class="qr-container">
        @if(!empty($qrSvg))
            {!! $qrSvg !!}
        @elseif(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(14)->generate($appointment->booking_id) !!}
        @else
            <div class="qr-code" style="border:1px dashed #cbd5e1; padding:2px; text-align:center; font-size:6px;">
                Code QR
            </div>
        @endif
    </div>

    <!-- Booking Info -->
    <table class="booking">
        <tr>
            <td style="width:50%;">
                <div class="small">ID de réservation</div>
                <div class="id">{{ $appointment->booking_id }}</div>
            </td>
            <td style="text-align:right;">
                <div class="small">Veuillez apporter une pièce d'identité avec photo et arriver 15 minutes à l'avance.</div>
            </td>
        </tr>
    </table>

    <!-- Two-column layout using table -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:16px;">
        <tr valign="top">
            <!-- Patient Info -->
            <td style="width:50%; padding-right:10px;">
                <div class="section">
                    <div class="section-title">Informations du patient</div>
                    <table class="info-table">
                        <tr><td class="label">Nom complet</td><td class="value">{{ $appointment->first_name }} {{ $appointment->last_name }}</td></tr>
                        <tr><td class="label">Sexe</td><td class="value">{{ $genderLabel }}</td></tr>
                        <tr><td class="label">Âge</td><td class="value">{{ $appointment->age }}</td></tr>
                        <tr><td class="label">E-mail</td><td class="value">{{ $appointment->email }}</td></tr>
                        <tr><td class="label">Téléphone</td><td class="value">{{ $appointment->phone }}</td></tr>
                    </table>
                </div>
            </td>

            <!-- Appointment Info -->
            <td style="width:50%; padding-left:10px;">
                <div class="section">
                    <div class="section-title">Détails du rendez-vous</div>
                    <table class="info-table">
                        <tr><td class="label">Date souhaitée</td><td class="value">{{ \Carbon\Carbon::parse($appointment->preferred_date)->locale('fr')->translatedFormat('j F Y') }}</td></tr>
                        <tr><td class="label">Heure souhaitée</td><td class="value">{{ $appointment->preferred_time }}</td></tr>
                        <tr><td class="label">Spécialité</td><td class="value">{{ $appointment->speciality }}</td></tr>
                        <tr><td class="label">Statut</td><td class="value">{{ $statusLabel }}</td></tr>
                        @if($appointment->doctor_name)
                            <tr><td class="label">Médecin</td><td class="value">{{ $appointment->doctor_name }}</td></tr>
                        @endif
                        @if(!empty($appointment->additional_notes))
                            <tr><td class="label">Remarques</td><td class="value">{{ $appointment->additional_notes }}</td></tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Important Note -->
    <div class="note">
        <strong>Important :</strong> Veuillez présenter cette confirmation (imprimée ou numérique) ainsi qu'une pièce d'identité valide. Présentez-vous à l'accueil 15 minutes avant votre rendez-vous pour l'enregistrement.
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Document généré le {{ now()->locale('fr')->translatedFormat('j F Y') }} à {{ now()->format('H:i') }} <span class="nowrap">(Réservation : {{ $appointment->booking_id }})</span></div>
        <div>&copy; {{ date('Y') }} MICLINIX. Tous droits réservés.</div>
    </div>
</div>
</body>
</html>
