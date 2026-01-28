#  Data Center Resource Manager

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-000000?style=for-the-badge&logo=mysql&logoColor=white)

Une application web complète pour la gestion et la réservation des ressources physiques d'un Data Center (Serveurs, Routeurs, Postes de simulation). Ce projet a été conçu pour gérer les conflits de planning, valider les accès via une hiérarchie de rôles et visualiser l'occupation des machines en temps réel.

---

##  Fonctionnalités Clés

###  Cœur Métier Intelligent
- **Algorithme Anti-Chevauchement :** Un système robuste empêche toute double réservation sur un même créneau horaire via une logique SQL inversée.
- **Gestion des États :** Cycle de vie complet des demandes (`Pending` → `Approved` ou `Rejected` avec justification obligatoire).

###  Interface & UX (Sans Framework CSS)
- **Planning Visuel (Gantt Chart) :** Un diagramme de Gantt interactif développé "from scratch" (sans librairie externe) pour visualiser les disponibilités.
- **Responsive Design :** Interface adaptable mobile/desktop avec scroll horizontal pour les tableaux complexes.
- **Split-Screen :** Formulaire de réservation et état des ressources en temps réel sur la même page.

###  Sécurité & Rôles (RBAC)
- **Admin :** Gestion complète du parc, des utilisateurs et des logs système.
- **Manager :** Validation des demandes de réservation et gestion des incidents.
- **Utilisateur/Étudiant :** Réservation de matériel et suivi des demandes.
- **Invité :** Accès restreint (consultation uniquement).

###  Système de Notifications
- Notifications en temps réel stockées en base de données (JSON).
- Alertes pour les validations, refus et nouvelles demandes.

---

##  Demo du Projet
https://github.com/user-attachments/assets/94000697-70b0-4681-ab57-5788af34a0cb

---

##  Stack Technique

- **Backend :** Laravel 10/11 (PHP 8.2+)
- **Base de données :** MySQL
- **Frontend :** Blade, CSS3 (Custom Grid/Flexbox), JavaScript (Vanilla)
- **Outils :** Vite, Composer, Artisan

---

##  Installation

Suivez ces étapes pour lancer le projet en local :

### 1. Cloner le projet
```bash
git clone [https://github.com/votre-pseudo/reservation-ressources-data-center.git](https://github.com/votre-pseudo/reservation-ressources-data-center.git)
cd reservation-ressources-data-center
```
### 2. Installer les dépendancesBashcomposer install
    npm install
### 3. Configuration de l'environnement 
Dupliquez le fichier d'exemple et générez la clé d'application :
    ```cp .env.example .env
    php artisan key:generate```
N'oubliez pas de configurer vos accès BDD (DB_DATABASE, DB_USERNAME...) dans le fichier .env.
### 4. Base de données & Seeders
Créez les tables et injectez les données de test (Admin, Ressources, Catégories) :
    ```php artisan migrate:fresh --seed```
### 5. Lancer l'applicationCompilez les assets et lancez le serveur :
    npm run build
    php artisan serve
Rendez-vous sur http://localhost:8000.

## Comptes de Test (Seeders)
Une fois la commande ``migrate:fresh --seed`` lancée, vous pouvez utiliser ces comptes :
Role | Email | Password |
:--- | :--- | :--- |
Admin| chaimae@centrum.com | password123 |
Manager | yassine@centrum.com | password123 |
Etudiant | etudiant@gmail.com | password123 |

## L'Équipe Projet réalisé dans le cadre universitaire par :
- Chaimae : Architecte & Admin Système (Auth, Sécurité, Gestion Users).
- Alae : Gestionnaire de Parc (Inventaire, Catégories, Relations BDD).
- Yassine : Lead Backend (Algorithme de réservation, Logique métier, Notifications).
- Houssam : Lead Frontend & UI/UX (Design System, Gantt Chart, Dashboard).
