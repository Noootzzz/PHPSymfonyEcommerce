
# PHPSymfonyEcommerce

Un projet de site e-commerce développé avec le framework Symfony, destiné à illustrer les bases d'une application de commerce en ligne.

## Fonctionnalités

- **Gestion des produits** : Ajout, modification et suppression de produits.
- **Gestion des utilisateurs** : Inscription, connexion et rôles utilisateurs.
- **Système de commande** : Création et suivi des commandes.
- **Environnement conteneurisé** : Utilisation de Docker pour simplifier la configuration.

## Prérequis

- **PHP** 8.0 ou supérieur.
- **Symfony CLI** installé.
- **Composer** pour la gestion des dépendances.
- **Docker** (optionnel) pour un déploiement conteneurisé.
- **Base de données PostgreSQL** ou autre configuration compatible.

## Installation

1. Clonez ce dépôt :
   ```bash
   git clone https://github.com/Noootzzz/PHPSymfonyEcommerce.git
   cd PHPSymfonyEcommerce
   ```

2. Installez les dépendances PHP avec Composer :
   ```bash
   composer install
   ```

3. Configurez les variables d'environnement :
   - Copiez le fichier `.env` en `.env.local` :
     ```bash
     cp .env .env.local
     ```
   - Mettez à jour les informations de connexion à la base de données.

4. Démarrez l'application :
   ```bash
   symfony server:start
   ```

5. (Optionnel) Lancez l'environnement Docker :
   ```bash
   docker-compose up -d
   ```

## Structure des fichiers

- `src/` : Code source de l'application.
- `templates/` : Fichiers Twig pour le rendu des pages.
- `public/` : Dossier public contenant les fichiers accessibles depuis le navigateur.
- `config/` : Configuration de l'application Symfony.

