# TP2 - Plateforme de Blog MVC

Un système de blog moderne développé avec l'architecture MVC (Modèle-Vue-Contrôleur) en PHP, utilisant Twig comme moteur de templates et incluant un système d'authentification complet.

## 🚀 Fonctionnalités

### 📝 Gestion des Articles

-   **Création d'articles** : Interface intuitive pour rédiger de nouveaux articles
-   **Modification d'articles** : Édition complète du contenu existant
-   **Suppression d'articles** : Suppression sécurisée avec confirmation
-   **Affichage des articles** : Vue détaillée avec métadonnées (auteur, catégorie, date)
-   **Liste des articles** : Affichage paginé avec aperçu du contenu

### 📁 Système de Catégories

-   **Gestion des catégories** : Création, modification et suppression
-   **Classification** : Organisation des articles par catégories
-   **Navigation** : Filtrage des articles par catégorie
-   **Protection** : Impossible de supprimer une catégorie contenant des articles

### 💬 Système de Commentaires

-   **Commentaires authentifiés** : Seuls les utilisateurs connectés peuvent commenter
-   **Gestion personnelle** : Chaque utilisateur peut supprimer ses propres commentaires
-   **Affichage hiérarchique** : Commentaires organisés par date de publication

### 🔐 Authentification & Autorisation

-   **Inscription sécurisée** : Création de compte avec validation
-   **Connexion/Déconnexion** : Système de session sécurisé
-   **Hachage des mots de passe** : Utilisation de `password_hash()` et `password_verify()`
-   **Protection des routes** : Accès restreint aux fonctionnalités selon l'état de connexion
