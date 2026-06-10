CONTRIBUTING

Thank you for your interest in contributing to MediCore.
Merci pour votre intérêt à contribuer à MediCore.

🇬🇧 English

Introduction

MediCore is an open-source clinic management platform built to support healthcare facilities through modern, scalable and maintainable software solutions.
We welcome contributions from developers, designers, testers, healthcare professionals and technical writers.

Project Goals

The objective of MediCore is to provide a complete healthcare management platform covering:

Patient Management

Appointment Management

Consultations

Prescriptions

Laboratory Management

Pharmacy Management

Medical Imaging

Billing and Payments

Inventory Management

Human Resources

Reporting and Analytics

Telemedicine

AI-powered Healthcare Services

Technology Stack

Backend

PHP 8.4+

Laravel 12

Frontend

Vue.js 3

Composition API

Vite

Database

MySQL

Authentication

Laravel Sanctum

Quality Assurance

PHPUnit

Pest

Laravel Pint

Larastan

Version Control

Git

GitHub

Architecture Principles

All contributions should respect the following principles:

Modular Monolith

MediCore follows a modular monolith architecture.
Modules must remain independent and loosely coupled.

Domain Driven Design (DDD)

Business logic should remain inside its corresponding domain.
Avoid placing business rules inside controllers.

Clean Architecture

Respect separation of concerns.
Recommended layers:

Controllers

Services

Repositories

Models

Policies

Requests

Planned Business Domains

Core

Authentication

Authorization

User Management

Clinical

Patients

Consultations

Diagnoses

Treatments

Prescriptions

Medical Services

Laboratory

Pharmacy

Medical Imaging

Administrative

Billing

Payments

Insurance

Operations

Inventory

Procurement

Human Resources

Analytics

Reports

Dashboards

Statistics

Branch Strategy

Main Branches

main develop 

Feature Branches

feature/patient-management feature/appointments feature/pharmacy 

Bug Fixes

fix/login-validation fix/prescription-export 

Hotfixes

hotfix/security-patch 

Commit Convention

Use meaningful commit messages.
Examples:
feat: add patient registration module feat: implement appointment scheduling fix: resolve login validation issue refactor: improve consultation service docs: update contributing guide test: add patient feature tests 

Pull Request Guidelines

Before submitting a Pull Request:

Ensure code compiles

Ensure tests pass

Follow project conventions

Update documentation when required

Keep PRs focused on a single feature

Coding Standards

PHP

Follow:

PSR-12

Laravel Best Practices

Use:
declare(strict_types=1); 
when applicable.

Laravel

Prefer:

Form Requests

Policies

Service Classes

Resource Classes

Avoid:

Fat Controllers

Business Logic inside Views

Vue.js

Use:

Composition API

Reusable Components

Composables

Avoid:

Large Components

Duplicated Logic

Naming Conventions

Classes

PatientService AppointmentRepository PrescriptionController 

Models

Patient Doctor Appointment Prescription Invoice 

Vue Components

PatientTable.vue AppointmentForm.vue PrescriptionList.vue 

Database Guidelines

Use migrations

Use foreign keys

Use indexes where necessary

Avoid duplicated data

Maintain normalization when possible

Testing

All major features should include tests.

Backend

Unit Tests

Feature Tests

Frontend

Component Tests

Integration Tests

Security

Healthcare systems handle sensitive information.
Always consider:

Authentication

Authorization

Data Validation

Auditability

Data Privacy

Never expose confidential patient information.

Reporting Issues

When reporting an issue include:

Description

Expected behavior

Actual behavior

Screenshots (if applicable)

Steps to reproduce

Contribution Types

We welcome:

Bug fixes

New features

Documentation improvements

Performance improvements

Security enhancements

Testing improvements

🇫🇷 Français

Introduction

MediCore est une plateforme open source de gestion de cliniques conçue pour accompagner les établissements de santé grâce à des solutions modernes, évolutives et maintenables.
Nous accueillons les contributions des développeurs, designers, testeurs, professionnels de santé et rédacteurs techniques.

Objectifs du projet

MediCore vise à couvrir l'ensemble des activités d'une clinique :

Gestion des patients

Gestion des rendez-vous

Consultations médicales

Ordonnances

Gestion du laboratoire

Gestion de la pharmacie

Imagerie médicale

Facturation et paiements

Gestion des stocks

Ressources humaines

Rapports et statistiques

Télémédecine

Services de santé basés sur l'intelligence artificielle

Stack technique

Backend

PHP 8.4+

Laravel 12

Frontend

Vue.js 3

Composition API

Vite

Base de données

MySQL

Authentification

Laravel Sanctum

Qualité du code

PHPUnit

Pest

Laravel Pint

Larastan

Gestion de versions

Git

GitHub

Principes d'architecture

Toutes les contributions doivent respecter les principes suivants.

Monolithe modulaire

MediCore adopte une architecture de monolithe modulaire.
Chaque module doit rester indépendant et faiblement couplé.

Domain Driven Design (DDD)

La logique métier doit rester dans son domaine fonctionnel.
Évitez de placer les règles métier dans les contrôleurs.

Clean Architecture

Respecter la séparation des responsabilités.
Couches recommandées :

Contrôleurs

Services

Repositories

Modèles

Policies

Requests

Domaines métiers prévus

Noyau

Authentification

Autorisation

Gestion des utilisateurs

Domaine clinique

Patients

Consultations

Diagnostics

Traitements

Ordonnances

Services médicaux

Laboratoire

Pharmacie

Imagerie médicale

Domaine administratif

Facturation

Paiements

Assurances

Domaine opérationnel

Stocks

Approvisionnement

Ressources humaines

Domaine analytique

Rapports

Tableaux de bord

Statistiques

Stratégie de branches

Branches principales

main develop 

Nouvelles fonctionnalités

feature/patient-management feature/appointments feature/pharmacy 

Corrections

fix/login-validation fix/prescription-export 

Correctifs urgents

hotfix/security-patch 

Convention des commits

Exemples :
feat: ajout du module patient feat: implémentation de la gestion des rendez-vous fix: correction de la validation de connexion refactor: amélioration du service de consultation docs: mise à jour de la documentation test: ajout des tests du module patient 

Règles des Pull Requests

Avant de soumettre une Pull Request :

Vérifier que le projet compile

Vérifier que les tests passent

Respecter les conventions du projet

Mettre à jour la documentation si nécessaire

Limiter chaque PR à une seule fonctionnalité

Standards de développement

PHP

Respecter :

PSR-12

Bonnes pratiques Laravel

Utiliser :
declare(strict_types=1); 
lorsque cela est pertinent.

Laravel

Privilégier :

Form Requests

Policies

Services

API Resources

Éviter :

Contrôleurs volumineux

Logique métier dans les vues

Vue.js

Utiliser :

Composition API

Composables

Composants réutilisables

Éviter :

Composants trop volumineux

Duplication de logique

Conventions de nommage

Classes

PatientService AppointmentRepository PrescriptionController 

Modèles

Patient Doctor Appointment Prescription Invoice 

Composants Vue

PatientTable.vue AppointmentForm.vue PrescriptionList.vue 

Base de données

Utiliser les migrations

Utiliser les clés étrangères

Créer les index nécessaires

Éviter les duplications

Respecter la normalisation lorsque possible

Tests

Toutes les fonctionnalités importantes doivent être testées.

Backend

Tests unitaires

Tests fonctionnels

Frontend

Tests des composants

Tests d'intégration

Sécurité

Les systèmes de santé manipulent des données sensibles.
Toujours prendre en compte :

Authentification

Autorisation

Validation des données

Traçabilité

Confidentialité

Ne jamais exposer les informations confidentielles des patients.

Signalement des problèmes

Lors de la création d'une issue, fournir :

Description

Comportement attendu

Comportement observé

Captures d'écran si nécessaire

Étapes de reproduction

Types de contributions acceptées

Nous acceptons :

Corrections de bugs

Nouvelles fonctionnalités

Améliorations de la documentation

Optimisations des performances

Renforcements de sécurité

Améliorations des tests

Final Note | Note Finale

MediCore aims to become a reliable, maintainable and scalable healthcare platform.
MediCore a pour ambition de devenir une plateforme de santé fiable, maintenable et évolutive.
Every contribution matters.
Chaque contribution compte.