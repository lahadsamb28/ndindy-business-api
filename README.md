# 🚀 Ndindy Business Company - API Backend Laravel - V1

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## 📋 Vue d'ensemble

Système complet de gestion de stocks pour **Ndindy Business Company** - Votre référence en matériel électriques.

### Fonctionnalités principales

✅ Gestion des produits (Téléphones, Accessoires, Informatique)  
✅ Suivi des arrivages depuis différents pays  
✅ Gestion détaillée des items (IMEI, condition, batterie)  
✅ Dashboard avec analytics et KPIs  
✅ Rapports détaillés (inventaire, ventes, rentabilité)  
✅ Authentification API avec Laravel Sanctum  

---

## 🛠️ Installation

### Prérequis

- PHP 8.2+
- Composer
- MySQL/MariaDB 8.0+
- Node.js (optionnel)

### 1. Cloner et configurer

```bash
# Cloner le projet
git clone <votre-repo> ndindy-backend
cd ndindy-backend

# Installer les dépendances
composer install

# Copier le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 2. Configurer la base de données

Éditer `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ndindy_business
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 3. Créer la base de données

```bash
# Créer la base de données
mysql -u root -p
CREATE DATABASE ndindy_business CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 4. Exécuter les migrations et seeders

```bash
# Exécuter les migrations
php artisan migrate

# Peupler la base avec des données de test
php artisan db:seed

# Ou tout en une commande
php artisan migrate:fresh --seed
```

### 5. Installer Laravel Sanctum

```bash
# Publier la configuration Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Ajouter dans app/Http/Kernel.php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

### 6. Configurer CORS

Éditer `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000', 'http://localhost:5173'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

### 7. Lancer le serveur

```bash
php artisan serve
# Serveur disponible sur http://localhost:8000
```

---

## 🔐 Authentification

### Comptes de test créés

| Email | Mot de passe | Rôle |
|-------|--------------|------|
| admin@ndindy.com | password123 | Admin |
| manager@ndindy.com | password123 | Manager |
| staff@ndindy.com | password123 | Staff |

### Connexion

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@ndindy.com",
    "password": "password123"
  }'
```

Réponse:
```json
{
  "message": "Connexion réussie",
  "user": {
    "id": 1,
    "name": "Admin Ndindy",
    "email": "admin@ndindy.com",
    "role": "admin"
  },
  "token": "1|laravel_sanctum_..."
}
```

**Important:** Utiliser le token dans toutes les requêtes:
```bash
-H "Authorization: Bearer VOTRE_TOKEN"
```

---

## 📚 Documentation des Endpoints

### Base URL
```
http://localhost:8000/api
```

### 🔑 Authentification (4 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/auth/register` | Inscription |
| POST | `/auth/login` | Connexion |
| POST | `/auth/logout` | Déconnexion |
| GET | `/auth/me` | Profil utilisateur |

### 📦 Produits (8 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/products` | Liste tous les produits |
| POST | `/products` | Créer un produit |
| GET | `/products/{id}` | Détail d'un produit |
| PUT | `/products/{id}` | Modifier un produit |
| DELETE | `/products/{id}` | Supprimer un produit |
| GET | `/products/low-stock` | Produits stock faible |
| GET | `/products/by-category/{id}` | Produits par catégorie |
| GET | `/products/stats` | Statistiques produits |

### 📂 Catégories (5 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/categories` | Liste des catégories |
| POST | `/categories` | Créer une catégorie |
| GET | `/categories/{id}` | Détail catégorie |
| PUT | `/categories/{id}` | Modifier une catégorie |
| DELETE | `/categories/{id}` | Supprimer une catégorie |

### 🌍 Pays (5 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/countries` | Liste des pays |
| POST | `/countries` | Ajouter un pays |
| GET | `/countries/{id}` | Détail d'un pays |
| PUT | `/countries/{id}` | Modifier un pays |
| DELETE | `/countries/{id}` | Supprimer un pays |

### 🚢 Arrivages (9 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/arrivals` | Liste tous les arrivages |
| POST | `/arrivals` | Créer un arrivage |
| GET | `/arrivals/{id}` | Détail d'un arrivage |
| PUT | `/arrivals/{id}` | Modifier un arrivage |
| DELETE | `/arrivals/{id}` | Supprimer un arrivage |
| GET | `/arrivals/by-status/{status}` | Par statut |
| GET | `/arrivals/by-country/{id}` | Par pays |
| POST | `/arrivals/{id}/update-status` | Changer statut |
| GET | `/arrivals/stats` | Statistiques |

**Statuts disponibles:** `pending`, `in_transit`, `arrived`, `completed`, `cancelled`

### 📱 Items (10 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/items` | Liste tous les items |
| POST | `/items` | Créer un item |
| GET | `/items/{id}` | Détail d'un item |
| PUT | `/items/{id}` | Modifier un item |
| DELETE | `/items/{id}` | Supprimer un item |
| GET | `/items/by-arrival/{id}` | Items par arrivage |
| GET | `/items/by-product/{id}` | Items par produit |
| GET | `/items/by-condition/{condition}` | Par condition |
| POST | `/items/bulk-create` | Créer en masse |
| GET | `/items/stats` | Statistiques |

**Conditions:** `neuf`, `excellent`, `tres_bon`, `bon`, `moyen`, `pour_pieces`

### 📊 Dashboard (10 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/dashboard/metrics` | Métriques générales |
| GET | `/dashboard/recent-arrivals` | Arrivages récents |
| GET | `/dashboard/recent-items` | Items récents |
| GET | `/dashboard/inventory-health` | Santé du stock |
| GET | `/dashboard/sales-analysis` | Analyse ventes |
| GET | `/dashboard/top-products` | Top produits |
| GET | `/dashboard/top-suppliers` | Top fournisseurs |
| GET | `/dashboard/top-countries` | Top pays |
| GET | `/dashboard/condition-distribution` | Distribution conditions |
| GET | `/dashboard/brand-analysis` | Analyse marques |

### 📈 Rapports (4 endpoints)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/reports/inventory` | Rapport inventaire |
| GET | `/reports/arrivals?start_date=...&end_date=...` | Rapport arrivages |
| GET | `/reports/sales?start_date=...&end_date=...` | Rapport ventes |
| GET | `/reports/profitability` | Rapport rentabilité |

---

## 📋 Exemples de Requêtes

### Créer un produit

```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "iPhone 15 Pro",
    "description": "Dernier iPhone",
    "category_id": 1,
    "totalPrice": 1299.99
  }'
```

### Créer un arrivage avec items

```bash
curl -X POST http://localhost:8000/api/arrivals \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "totalSpend": 50000,
    "purchaseCost": 42000,
    "shippingCost": 3000,
    "supplier": "TechGlobal USA",
    "country_id": 2,
    "totalItems": 50,
    "arrival_date": "2024-12-01",
    "status": "pending",
    "items": [
      {
        "product_id": 1,
        "cost": 950,
        "price": 1299,
        "brand": "Apple",
        "model": "iPhone 15 Pro",
        "condition": "neuf",
        "battery_health": 100
      }
    ]
  }'
```

### Obtenir les métriques dashboard

```bash
curl -X GET http://localhost:8000/api/dashboard/metrics \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Rechercher des produits

```bash
curl -X GET "http://localhost:8000/api/products?search=iPhone&category_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🗂️ Structure du Projet

```
ndindy-backend/
├── app/
│   ├── Enums/
│   │   ├── StatusArrival.php
│   │   ├── ECondition.php
│   │   └── UserRole.php
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php
│   │           ├── ProductController.php
│   │           ├── CategoryController.php
│   │           ├── CountryController.php
│   │           ├── ArrivalsController.php
│   │           ├── ItemsController.php
│   │           ├── DashboardController.php
│   │           └── ReportController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Country.php
│   │   ├── Arrivals.php
│   │   └── Items.php
│   └── Services/
│       ├── ProductService.php
│       ├── ArrivalsService.php
│       ├── ItemsService.php
│       ├── DashboardService.php
│       └── ReportService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── .env
```

---

## 🧪 Tests

```bash
# Créer un test
php artisan make:test ProductTest

# Exécuter les tests
php artisan test

# Tests avec couverture
php artisan test --coverage
```

---

## 🔧 Commandes Artisan Utiles

```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Voir toutes les routes
php artisan route:list

# Créer un contrôleur
php artisan make:controller Api/NomController --api

# Créer un modèle avec migration
php artisan make:model NomModel -m

# Créer un seeder
php artisan make:seeder NomSeeder

# Rafraîchir la DB
php artisan migrate:fresh --seed
```

---

## 📊 Statistiques du Projet

- **59 Endpoints** API RESTful
- **10 Tables** dans la base de données
- **8 Contrôleurs** API
- **5 Services** métier
- **3 Enums** pour les statuts
- **6 Seeders** avec données de test

---

## 🚀 Déploiement Production

### 1. Optimisations

```bash
# Cacher les configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimiser l'autoload
composer install --optimize-autoloader --no-dev
```

### 2. Variables d'environnement

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.ndindy.com

DB_CONNECTION=mysql
DB_HOST=votre_serveur
DB_DATABASE=ndindy_prod
```

### 3. Sécurité

- Activer HTTPS obligatoire
- Configurer les CORS correctement
- Utiliser des mots de passe forts
- Activer le rate limiting
- Monitorer les logs

---

## 📞 Support

- **Email:** scheikhabdou98@outlook.com
- **GitHub:** [github.com/lahadsamb28](https://github.com/lahadsamb28)

---

## 📄 Licence

MIT License - © 2024 Ndindy Business Company

---

**Développé avec ❤️ pour Ndindy Business Company**  
*Votre référence en matériel électriques*
