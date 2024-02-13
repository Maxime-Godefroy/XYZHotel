XYZ Hôtel - Système de Gestion des Réservations

💽 Installation

🔌 Backend

Premier Terminal :

```bash
    docker compose up
```

Deuxième Terminal :

```bash
    cd .\ServiceBackEnd\api\
    composer require symfony/runtime
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    symfony serve
```

💡 Frontend

Troisième Terminal :

```bash
    cd .\ServiceFrontEnd\XYZUserHub\
    composer require symfony/runtime
    symfony serve
```

Quatrième Terminal :

```bash
    cd .\ServiceFrontEnd\XYZUserHub\
    npm install
    npm run watch
```
    
🍳 Login

    Créez votre propre compte pour accéder au système.

🧭 Routes

    /
    /chambre
    /connexion
    /inscription
    /compte

Design Stratégique
Ubiquitous Language
Problématique

Gestion Informatisée des Réservations pour XYZ Hôtel.
Concepts Métiers et Définitions

    Client
        Définition: Personne souhaitant réserver un séjour au XYZ Hôtel.
        Attributs: Nom complet, Adresse e-mail (unique), Numéro de téléphone.

    Compte Client
        Définition: Enregistrement des informations personnelles du client.
        Fonctionnalités: Création du compte, Génération d'identifiant, Accès aux services.

    Portefeuille Électronique
        Définition: Moyen de paiement pour les services de l'hôtel.
        Fonctionnalités: Alimentation en devises, Conversion en euros.

    Infos des Chambres
        Définition: Détails des catégories de chambres disponibles.
        Catégories: Chambre Standard, Chambre Supérieure, Suite.

    Réservation
        Définition: Sélection et réservation d'une chambre.
        Étapes: Choix des dates, Enregistrement, Paiement partiel.

    Confirmation de Réservation
        Définition: Finalisation de la réservation.
        Condition: Indépendante de la date de check-in.

    Annulation de Réservation
        Définition: Annulation par le client.
        Remarque: Pas de remboursement en cas d'annulation.

Bounded Contexts


![image](https://github.com/Maxime-Godefroy/XYZHotel/assets/129076718/7a1459f4-8f99-417a-91b6-d6b6f683cbb3)

Context Maps

![image](https://github.com/Maxime-Godefroy/XYZHotel/assets/129076718/37536ec1-89f4-4ce6-9374-1b8bb58ca9ed)

Core/Supporting/Generic Domains

![image](https://github.com/Maxime-Godefroy/XYZHotel/assets/129076718/453c0188-d88c-4da5-95fa-e0bd3090a495)

Design Tactique
Entities

    Client
        Attributs: Nom complet, E-mail, Téléphone, Identifiant.

    Compte Client
        Attributs: Infos du client, Identifiant, Solde du portefeuille.

    Portefeuille Électronique
        Attributs: Solde en Euros, Devises acceptées.

    Chambre
        Attributs: Catégorie, Prix, Capacité, Caractéristiques.

    Réservation
        Attributs: Client, Date de Check-in, Nuits, Chambres, Statut.

Value Objects

    Identifiant de Réservation
        Caractéristiques: Identifiant unique.

    Devise
        Caractéristiques: Type de devise.

    Prix
        Caractéristiques: Montant en devise.

    Durée du Séjour
        Caractéristiques: Nombre de nuits.

    Statut de Réservation
        Caractéristiques: Statuts (Enregistrée, Confirmée, Annulée).
