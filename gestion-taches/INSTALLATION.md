# Guide d'Installation - Système de Gestion de Tâches

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL/PostgreSQL
- Node.js (pour le frontend React)

## Installation Backend Laravel

### 1. Cloner le projet
```bash
git clone <repository-url>
cd gestion-taches
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Configuration de l'environnement
```bash
# Copier le fichier d'exemple
cp env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configuration de la base de données
Modifier le fichier `.env` avec vos paramètres :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Créer la base de données
```sql
CREATE DATABASE task_management;
```

### 6. Exécuter les migrations
```bash
php artisan migrate
```

### 7. Seeder les données de test
```bash
php artisan db:seed
```

### 8. Lancer le serveur de développement
```bash
php artisan serve
```

Le serveur sera accessible sur `http://localhost:8000`

## Données de Test

Le seeder crée automatiquement :
- **Admin** : admin@example.com / password
- **Utilisateur 1** : john@example.com / password
- **Utilisateur 2** : jane@example.com / password

## Tests

```bash
# Lancer tous les tests
php artisan test

# Lancer un test spécifique
php artisan test --filter AuthTest
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

### Commentaires
- `GET /api/comments` - Liste des commentaires
- `POST /api/comments` - Créer un commentaire
- `PUT /api/comments/{id}` - Modifier un commentaire
- `DELETE /api/comments/{id}` - Supprimer un commentaire

### Administration (Admin uniquement)
- `GET /api/admin/stats` - Statistiques des projets
- `GET /api/admin/workload` - Charge de travail des utilisateurs
- `GET /api/admin/users` - Liste des utilisateurs avec stats

## Authentification

L'API utilise Laravel Sanctum pour l'authentification par token.

### Exemple d'utilisation avec cURL
```bash
# Connexion
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Utiliser le token
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Structure du Projet

```
gestion-taches/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/     # Contrôleurs API
│   │   └── Middleware/          # Middlewares
│   └── Models/                  # Modèles Eloquent
├── database/
│   ├── migrations/              # Migrations
│   ├── seeders/                 # Seeders
│   └── factories/               # Factories
├── routes/
│   └── api.php                  # Routes API
└── tests/                       # Tests
```

## Prochaines Étapes

1. **Frontend React** : Développer l'interface utilisateur
2. **Tests** : Ajouter plus de tests unitaires et d'intégration
3. **Documentation API** : Générer la documentation avec Swagger
4. **Déploiement** : Configurer pour la production

## Support

Pour toute question ou problème, consultez la documentation Laravel ou créez une issue dans le repository. 