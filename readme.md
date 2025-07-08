# TP3 - Plateforme de Blog MVC

Un système de blog moderne développé avec l'architecture MVC (Modèle-Vue-Contrôleur) en PHP, utilisant Twig comme moteur de templates et incluant un système d'authentification complet avec suivi des activités.

## 🚀 Fonctionnalités

### 📝 Gestion des Articles

-   **Création d'articles** : Interface intuitive pour rédiger de nouveaux articles
-   **Modification d'articles** : Édition complète du contenu existant
-   **Suppression d'articles** : Suppression sécurisée avec confirmation
-   **Affichage des articles** : Vue détaillée avec métadonnées (auteur, catégorie, date)
-   **Liste des articles** : Affichage avec aperçu du contenu

### 📁 Système de Catégories

-   **Gestion des catégories** : Création, modification et suppression
-   **Classification** : Organisation des articles par catégories
-   **Navigation** : Filtrage des articles par catégorie
-   **Protection** : Impossible de supprimer une catégorie contenant des articles

### 💬 Système de Commentaires

-   **Commentaires authentifiés** : Seuls les utilisateurs connectés peuvent commenter
-   **Gestion personnelle** : Chaque utilisateur peut supprimer ses propres commentaires
-   **Affichage chronologique** : Commentaires organisés par date de publication

### 🔐 Authentification & Autorisation

-   **Inscription sécurisée** : Création de compte avec validation complète
-   **Connexion/Déconnexion** : Système de session sécurisé
-   **Hachage des mots de passe** : Utilisation de `password_hash()` et `password_verify()`
-   **Protection des routes** : Accès restreint aux fonctionnalités selon l'état de connexion
-   **Gestion des privilèges** : Système de rôles (utilisateur/administrateur)

### 👥 Gestion des Utilisateurs

-   **Création d'utilisateurs** : Interface publique d'inscription
-   **Administration des utilisateurs** : Interface admin pour créer des comptes utilisateurs
-   **Gestion des privilèges** : Attribution de rôles et permissions

### 📊 Système de Suivi des Activités

-   **Journal d'activités** : Suivi détaillé des actions utilisateurs
-   **Traçabilité complète** : Enregistrement de l'IP, User-Agent, page visitée
-   **Interface d'administration** : Consultation et gestion des logs d'activité
-   **Nettoyage des logs** : Fonction de purge des anciens logs

### 🛡️ Sécurité Avancée

-   **Middleware de sécurité** : Protection automatique de toutes les requêtes
-   **Sessions sécurisées** : Configuration avancée des sessions PHP
-   **Validation des données** : Système de validation robuste pour tous les formulaires
-   **Protection CSRF** : (si implémentée)
-   **Audit de sécurité** : Logging automatique des actions sensibles

## 🏗️ Architecture Technique

### Structure MVC

-   **Modèles** (`models/`) : Gestion des données et logique métier
    -   `User.php` : Gestion des utilisateurs et authentification
    -   `Post.php` : Gestion des articles de blog
    -   `Category.php` : Gestion des catégories
    -   `Comment.php` : Gestion des commentaires
    -   `ActivityLog.php` : Suivi des activités utilisateurs
    -   `Privilege.php` : Gestion des rôles et permissions
    -   `CRUD.php` : Classe de base pour les opérations de base de données

-   **Vues** (`views/`) : Interface utilisateur avec Twig
    -   Templates modulaires et réutilisables
    -   Layouts partagés (header/footer)
    -   Vues spécialisées par fonctionnalité

-   **Contrôleurs** (`controllers/`) : Logique de contrôle
    -   `HomeController.php` : Page d'accueil
    -   `PostController.php` : Gestion des articles
    -   `CategoryController.php` : Gestion des catégories
    -   `CommentController.php` : Gestion des commentaires
    -   `UserController.php` : Gestion des utilisateurs
    -   `AuthController.php` : Authentification
    -   `ActivityLogController.php` : Administration des logs

### Fournisseurs de Services (`providers/`)

-   **Auth.php** : Service d'authentification
-   **SecurityMiddleware.php** : Middleware de sécurité global
-   **Validator.php** : Système de validation des données
-   **View.php** : Gestionnaire de vues Twig

### Système de Routage

-   **Route.php** : Gestionnaire de routes simple et efficace
-   **web.php** : Définition de toutes les routes de l'application
-   Support des méthodes GET et POST
-   Dispatch automatique vers les contrôleurs

## 🛠️ Technologies Utilisées

-   **PHP 8+** : Langage de programmation principal
-   **Twig 3.21** : Moteur de templates moderne et sécurisé
-   **Composer** : Gestionnaire de dépendances PHP
-   **Architecture MVC** : Séparation claire des responsabilités
-   **PSR-4** : Autoloading standard PHP

## 📦 Installation

### Prérequis

-   PHP 8.0 ou supérieur
-   Serveur web (Apache/Nginx)
-   MySQL/MariaDB
-   Composer

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone [url-du-repo]
   cd mvc
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configuration**
   - Modifier `config.php` selon votre environnement
   - Configurer les constantes `BASE` et `ASSET`
   - Ajuster le fuseau horaire si nécessaire

4. **Base de données**
   - Créer une base de données MySQL
   - Importer le schéma SQL (fichier fourni séparément)
   - Configurer les paramètres de connexion

5. **Configuration du serveur web**
   - Pointer le DocumentRoot vers le dossier du projet
   - Activer la réécriture d'URL (mod_rewrite pour Apache)

## 🗂️ Structure du Projet

```
mvc/
├── controllers/          # Contrôleurs MVC
├── models/              # Modèles de données
├── views/               # Templates Twig
│   ├── layouts/         # Layouts partagés
│   ├── auth/           # Vues d'authentification
│   ├── post/           # Vues des articles
│   ├── category/       # Vues des catégories
│   ├── user/           # Vues utilisateurs
│   └── activity-log/   # Vues des logs d'activité
├── providers/           # Services et middleware
├── routes/             # Configuration des routes
├── public/             # Assets publics (CSS, JS, images)
├── vendor/             # Dépendances Composer
├── config.php          # Configuration de l'application
├── index.php           # Point d'entrée
└── composer.json       # Dépendances du projet
```

## 🔗 Routes Principales

### Articles
- `GET /posts` - Liste des articles
- `GET /post/show?id={id}` - Affichage d'un article
- `GET /post/create` - Formulaire de création
- `POST /post/store` - Création d'un article
- `GET /post/edit?id={id}` - Formulaire d'édition
- `POST /post/edit` - Mise à jour d'un article
- `POST /post/delete` - Suppression d'un article

### Catégories
- `GET /categories` - Liste des catégories
- `GET /category/show?id={id}` - Articles d'une catégorie
- `GET /category/create` - Formulaire de création
- `POST /category/store` - Création d'une catégorie

### Authentification
- `GET /login` - Formulaire de connexion
- `POST /login` - Traitement de la connexion
- `GET /logout` - Déconnexion
- `GET /user/create` - Inscription utilisateur
- `POST /user/create` - Traitement de l'inscription

### Administration
- `GET /activity-logs` - Journal d'activités (admin)
- `GET /user/admin-create` - Création d'utilisateur (admin)

## 🔒 Sécurité

Le projet implémente plusieurs mesures de sécurité :

-   **Hachage des mots de passe** avec `password_hash()`
-   **Sessions sécurisées** avec configuration avancée
-   **Middleware de sécurité** appliqué à toutes les requêtes
-   **Validation des données** sur tous les formulaires
-   **Traçabilité** des actions avec logging détaillé
-   **Protection des routes** selon les privilèges utilisateur

## 🚀 Utilisation

1. **Accéder à l'application** via votre navigateur
2. **S'inscrire** ou se connecter avec un compte existant
3. **Naviguer** dans les articles et catégories
4. **Créer du contenu** (articles, commentaires) si connecté
5. **Administrer** le site si vous avez les privilèges admin

## 📈 Fonctionnalités Avancées

-   **Suivi des activités** : Chaque action est enregistrée avec horodatage
-   **Gestion des sessions** : Système de session sécurisé et robuste
-   **Validation robuste** : Validation côté serveur pour tous les formulaires
-   **Architecture modulaire** : Code organisé et facilement extensible
-   **Templating avancé** : Utilisation de Twig pour des vues dynamiques
