# Système de Gestion de Tâches - Full Stack

Un système complet de gestion de tâches collaboratif avec **Laravel 11** (Backend API) et **React 18** (Frontend).

## Fonctionnalités

### Backend Laravel
- **API RESTful** complète avec Laravel 11
- **Authentification** avec Laravel Sanctum
- **Gestion des rôles** (Admin/Utilisateur)
- **CRUD** pour Projets, Tâches et Commentaires
- **Statistiques admin** avancées
- **Tests** unitaires et d'intégration

### Frontend React
- **Interface moderne** avec Tailwind CSS
- **Authentification** complète
- **Tableau de bord** interactif
- **Design responsive**
- **TypeScript** pour la sécurité du code

## Stack Technique

### Backend
- **Laravel 11** - Framework PHP
- **Laravel Sanctum** - Authentification API
- **MySQL/PostgreSQL** - Base de données
- **PHP 8.2+** - Langage de programmation

### Frontend
- **React 18** - Framework JavaScript
- **TypeScript** - Typage statique
- **Tailwind CSS** - Framework CSS
- **React Router** - Navigation
- **Axios** - Client HTTP
- **Headless UI** - Composants d'interface

## Installation Rapide

### Prérequis
- PHP 8.2+
- Composer
- Node.js 16+
- MySQL/PostgreSQL

### 1. Backend Laravel

```bash
# Cloner le projet
git clone <repository-url>
cd gestion-taches

# Installer les dépendances
composer install

# Configuration
cp env.example .env
php artisan key:generate

# Base de données
# Modifier .env avec vos paramètres DB
php artisan migrate
php artisan db:seed

# Lancer le serveur
php artisan serve
```

Le backend sera accessible sur `http://localhost:8000`

### 2. Frontend React

```bash
# Aller dans le dossier frontend
cd frontend

# Installer les dépendances
npm install

# Lancer le serveur de développement
npm start
```

Le frontend sera accessible sur `http://localhost:3000`


## Structure du Projet

```
gestion-taches/
├── app/                    # Backend Laravel
│   ├── Http/
│   │   ├── Controllers/Api/ # Contrôleurs API
│   │   └── Middleware/      # Middlewares
│   └── Models/             # Modèles Eloquent
├── database/               # Migrations et Seeders
├── routes/                 # Routes API
├── tests/                  # Tests Backend
├── frontend/               # Frontend React
│   ├── src/
│   │   ├── components/     # Composants React
│   │   ├── pages/         # Pages de l'application
│   │   ├── services/      # Services API
│   │   └── types/         # Types TypeScript
│   └── package.json
└── README.md
```

## Configuration

### Backend (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Frontend (.env)
```env
REACT_APP_API_URL=http://localhost:8000/api
```

## API Endpoints

### Authentification
- `POST /api/register` - Inscription
- `POST /api/login` - Connexion
- `POST /api/logout` - Déconnexion

### Projets
- `GET /api/projects` - Liste des projets
- `POST /api/projects` - Créer un projet
- `GET /api/projects/{id}` - Détails d'un projet
- `PUT /api/projects/{id}` - Modifier un projet
- `DELETE /api/projects/{id}` - Supprimer un projet

### Tâches
- `GET /api/tasks` - Liste des tâches
- `POST /api/tasks` - Créer une tâche
- `GET /api/tasks/{id}` - Détails d'une tâche
- `PUT /api/tasks/{id}` - Modifier une tâche
- `DELETE /api/tasks/{id}` - Supprimer une tâche

### Administration
- `GET /api/admin/stats` - Statistiques
- `GET /api/admin/workload` - Charge de travail
- `GET /api/admin/users` - Liste des utilisateurs

## Tests

### Backend
```bash
php artisan test
```

### Frontend
```bash
cd frontend
npm test
```

## Déploiement

### Backend
1. Configurer les variables d'environnement de production
2. `php artisan optimize`
3. Configurer le serveur web (Apache/Nginx)

### Frontend
```bash
cd frontend
npm run build
```

## Fonctionnalités Avancées

### Implémentées
- Authentification complète
- Gestion des rôles et permissions
- CRUD complet pour toutes les entités
- Interface utilisateur moderne
- API RESTful documentée
- Tests de base

### À Implémenter
- Gestion complète des projets (CRUD)
- Gestion complète des tâches (CRUD)
- Système de commentaires
- Page d'administration complète
- Filtres et recherche
- Pagination
- Notifications en temps réel

### Bonus Possibles
- Drag & Drop des tâches (Trello-like)
- Graphiques et statistiques avancées

---

**Développé avec Laravel et React** 