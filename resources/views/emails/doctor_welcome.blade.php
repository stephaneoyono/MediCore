<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur {{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #1d4ed8, #a7c5f7 );
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            height: 60px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            margin-left: 10px
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
            margin-left: 10px
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669, #8ae6c7);
            color: white;
            text-decoration: none;  
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            margin: 10px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .credentials-panel {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 24px;
            margin: 25px 0;
        }
        
        .credentials-panel h3 {
            color: #0369a1;
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .credential-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .credential-icon {
            width: 20px;
            margin-right: 12px;
            color: #3b82f6;
        }
        
        .checklist {
            margin: 25px 0;
        }
        
        .checklist-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }
        
        .priority-high { border-left-color: #ef4444; }
        .priority-medium { border-left-color: #f59e0b; }
        .priority-low { border-left-color: #10b981; }
        
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }
        
        .feature-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .security-notice {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .security-notice h3 {
            color: #dc2626;
            margin-bottom: 12px;
        }
        
        .support-section {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .footer {
            background: #1f2937;
            color: #9ca3af;
            text-align: center;
            padding: 30px;
            font-size: 14px;
        }
        
        .footer a {
            color: #60a5fa;
            text-decoration: none;
        }
        
        @media (max-width: 600px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img :src="https://your-hospital-logo.com/logo-white.png" alt="{{ config('app.name') }}" class="logo">
            <h1>Bienvenue au sein de l'équipe médicale</h1>
            <p>Dr. {{ $name }}, votre parcours avec {{ config('app.name') }} commence maintenant</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2 style="color: #1f2937; margin-top: 15px;  margin-bottom: 10px; font-weight: 600;">
                    Votre portail numérique est prêt !
                </h2>
                <p style="color: #6b7280; margin-bottom: 25px;">
                    Nous sommes ravis de vous accueillir sur notre plateforme. Votre compte a été configuré avec tous les outils nécessaires pour offrir des soins de qualité.
                </p>
                <a href="{{ $loginUrl }}" class="cta-button">
                    🚀 Accéder au portail médecin
                </a>
            </div>
            
            <!-- Credentials -->
            <div class="credentials-panel">
                <h3>🔐 Vos identifiants d'accès</h3>
                <div class="credential-item">
                    <span class="credential-icon">🌐</span>
                    <div>
                        <strong>URL du portail :</strong><br>
                        <a href="{{ $loginUrl }}" style="color: #3b82f6;">{{ $loginUrl }}</a>
                    </div>
                </div>
                <div class="credential-item">
                    <span class="credential-icon">📧</span>
                    <div>
                        <strong>Courriel :</strong><br>
                        {{ $email }}
                    </div>
                </div>
                <div class="credential-item">
                    <span class="credential-icon">🔑</span>
                    <div>
                        <strong>Mot de passe temporaire :</strong><br>
                        <code style=" color: rgb(255, 0, 0); padding: 4px 8px; border-radius: 4px; font-weight: bold;">{{ $password }}</code>
                    </div>
                </div>
            </div>
            
            <!-- Checklist -->
            <div class="checklist">
                <h3 style="color: #1f2937; margin-bottom: 20px; font-weight: 600;">📋 Checklist d'intégration</h3>
                
                <div class="checklist-item priority-high">
                    <span style="margin-right: 12px;">1️⃣</span>
                    <div>
                        <strong>Se connecter et changer le mot de passe</strong><br>
                        <small style="color: #6b7280;">Sécurisez votre compte immédiatement</small>
                    </div>
                </div>
                
                <div class="checklist-item priority-high">
                    <span style="margin-right: 12px;">2️⃣</span>
                    <div>
                        <strong>Compléter le profil professionnel</strong><br>
                        <small style="color: #6b7280;">Ajoutez votre spécialisation, biographie et photo</small>
                    </div>
                </div>
                
                <div class="checklist-item priority-medium">
                    <span style="margin-right: 12px;">3️⃣</span>
                    <div>
                        <strong>Définir le planning de disponibilité</strong><br>
                        <small style="color: #6b7280;">Définissez vos heures de consultation</small>
                    </div>
                </div>
                
                <div class="checklist-item priority-low">
                    <span style="margin-right: 12px;">4️⃣</span>
                    <div>
                        <strong>Découvrez les fonctionnalités</strong><br>
                        <small style="color: #6b7280;">Familiarisez-vous avec les outils disponibles</small>
                    </div>
                </div>
            </div>
            
            <!-- Features -->
            <h3 style="color: #1f2937; margin-bottom: 20px; font-weight: 600;">💡 Points forts de la plateforme</h3>
            <div class="features-grid">
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">📅</span>
                    <strong>Planification intelligente</strong>
                    <small style="color: #6b7280; display: block;">Gérez les rendez-vous efficacement</small>
                </div>
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">👥</span>
                    <strong>Gestion des patients</strong>
                    <small style="color: #6b7280; display: block;">Dossiers patients complets</small>
                </div>
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">💬</span>
                    <strong>Messagerie sécurisée</strong>
                    <small style="color: #6b7280; display: block;">Communication conforme (RGPD)</small>
                </div>
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">📊</span>
                    <strong>Tableau de bord analytique</strong>
                    <small style="color: #6b7280; display: block;">Suivez les indicateurs de votre pratique</small>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <h3>⚠️ Sécurité avant tout</h3>
                <p style="color: #7f1d1d; margin-bottom: 10px;">
                    <strong>Important :</strong> Ce mot de passe temporaire doit être changé immédiatement après votre première connexion. Ne partagez jamais vos identifiants.
                </p>
                <p style="color: #7f1d1d; font-size: 14px;">
                    Notre équipe de support ne vous demandera jamais votre mot de passe par e-mail ou téléphone.
                </p>
            </div>
            
            <!-- Support -->
            <div class="support-section">
                <h3 style="color: #166534; margin-bottom: 15px;">🆘 Besoin d'aide ?</h3>
                <p style="color: #166534; margin-bottom: 15px;">
                    Notre équipe de support dédiée est là pour vous aider :
                </p>
                <div style="color: #166534;">
                    <strong>📞 Support :</strong> +237690XXXXXXX<br>
                    <strong>📧 Courriel :</strong> doctors-support@{{ config('app.name') }}.com<br>
                    <strong>🕒 Horaires :</strong> 8:00 - 22:00 (tous les jours)
                </div>
            </div>
            
            <!-- Closing -->
            <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                <p style="color: #6b7280; margin-bottom: 20px;">
                    Nous nous engageons à vous fournir les meilleurs outils pour offrir des soins d'exception.
                </p>
                <p style="font-weight: 600; color: #1f2937;">
                    Cordialement,<br>
                    <span style="color: #3b82f6;">Équipe opérations médicales</span><br>
                    <strong>{{ config('app.name') }}</strong>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Problème pour accéder à votre compte ?</strong><br>
                Copiez et collez cette URL : <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                Cet e-mail contient des informations sensibles. Veuillez en assurer la confidentialité.<br>
                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>