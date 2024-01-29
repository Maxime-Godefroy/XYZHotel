💽 Installation
🔌 Backend
  1er Terminal :
    docker compose up

  2nd Terminal :
    cd .\ServiceBackEnd\api\
    symfony serve

💡 Frontend
  3ième Teminal :
    cd .\ServiceFrontEnd\XYZUserHub\
    symfony serve
    
  4ième Teminal :
    cd .\ServiceFrontEnd\XYZUserHub\
    npm run watch

🍳 Login
À vous de créer votre propre compte

🧭 Routes
/
/chambre
/connexion
/inscription
/compte



Design stratégique

Ubiquitous language

Problématique : Gestion Informatisée des Réservations pour XYZ Hôtel
Concepts Métiers et Définitions :
1.	Client :
o	Définition : Une personne souhaitant réserver un séjour au sein de XYZ Hôtel.
o	Attributs :
	Nom complet
	Adresse e-mail (unique au sein du système)
	Numéro de téléphone
2.	Compte Client :
o	Définition : Un enregistrement dans le système contenant les informations personnelles du client.
o	Fonctionnalités :
	Création avec les informations du client.
	Génération d'un identifiant aléatoire.
	Utilisation de l'identifiant pour accéder aux services de l'hôtel.
3.	Portefeuille Électronique :
o	Définition : Un moyen exclusif de paiement pour les services de XYZ Hôtel.
o	Fonctionnalités :
	Alimentation avec différentes devises (Euro, Dollar, Livre Sterling, Yen, Franc Suisse).
	Conversion des devises au solde en euros.
4.	Infos des Chambres :
o	Définition : Les détails concernant les différentes catégories de chambres disponibles.
o	Catégories et Caractéristiques :
	Chambre Standard : 50€ par nuit, Lit 1 place, Wifi, TV.
	Chambre Supérieure : 100€ par nuit, Lit 2 places, Wifi, TV écran plat, Minibar, Climatiseur.
	Suite : 200€ par nuit, Lit 2 places, Wifi, TV écran plat, Minibar, Climatiseur, Baignoire, Terrasse.
5.	Réservation :
o	Définition : L'acte de sélectionner et réserver une chambre pour un séjour spécifique.
o	Étapes :
	Choix de la date de check-in, du nombre de nuits et de la/les chambres.
	Enregistrement de la réservation dans le système.
	Débit de 50% de la somme totale.
	Paiement du reste le jour même.
6.	Confirmation de Réservation :
o	Définition : L'action de finaliser la réservation en payant la moitié restante.
o	Condition : La date de check-in n'a pas d'impact sur la confirmation.
7.	Annulation de Réservation :
o	Définition : La possibilité pour le client d'annuler une réservation, qu'elle soit effectuée ou confirmée.
o	Remarque : Aucun remboursement n'est effectué par l'hôtel en cas d'annulation.


Bounded contexts

![image](https://github.com/Maxime-Godefroy/XYZHotel/assets/129076718/7a1459f4-8f99-417a-91b6-d6b6f683cbb3)


Context maps

![image](https://github.com/Maxime-Godefroy/XYZHotel/assets/129076718/37536ec1-89f4-4ce6-9374-1b8bb58ca9ed)


Core/Supporting/Generic domains

![image](https://github.com/Maxime-Godefroy/XYZHotel/assets/129076718/453c0188-d88c-4da5-95fa-e0bd3090a495)



Design tactique

Entities

1.	Client :
o	Attributs :
	Nom complet
	Adresse e-mail (unique au sein du système)
	Numéro de téléphone
	Identifiant (généré aléatoirement)
2.	Compte Client :
o	Attributs :
	Informations du Client
	Identifiant du Client
	Solde du Portefeuille Électronique
3.	Portefeuille Électronique :
o	Attributs :
	Solde en Euros
	Liste des devises acceptées (Euro, Dollar, Livre Sterling, Yen, Franc Suisse)
4.	Chambre :
o	Attributs :
	Catégorie (Standard, Supérieure, Suite)
	Prix par nuit
	Capacité (pour une personne)
	Caractéristiques spécifiques à la catégorie (ex. Wifi, TV, Minibar, Climatiseur, Baignoire, Terrasse)
5.	Réservation :
o	Attributs :
	Client associé
	Date de Check-in
	Nombre de Nuits
	Chambres réservées
	Statut (Enregistrée, Confirmée, Annulée)


Value Objects

1.	Identifiant de Réservation :
o	Caractéristiques :
	Identifiant unique généré aléatoirement pour chaque réservation.
2.	Devise :
o	Caractéristiques :
	Type de devise (Euro, Dollar, Livre Sterling, Yen, Franc Suisse).
3.	Prix :
o	Caractéristiques :
	Montant du prix dans la devise spécifiée.
4.	Durée du Séjour :
o	Caractéristiques :
	Nombre de nuits réservées.
5.	Statut de Réservation :
o	Caractéristiques :
	Statuts possibles (Enregistrée, Confirmée, Annulée).



