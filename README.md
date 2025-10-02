# Horizon

Un framework PHP moderne et léger avec une architecture MVC, un ORM intégré, un système de migrations et un moteur de templates personnalisé.

## 📋 À propos

Horizon est un framework PHP conçu pour offrir une expérience de développement moderne avec des outils intégrés pour gérer facilement vos applications web. Il combine simplicité et puissance avec une CLI intuitive et des composants modulaires.

## ✨ Fonctionnalités Principales

### 🎯 Architecture MVC
- **Controllers** : Gestion de la logique applicative
- **Models** : Interaction avec la base de données via l'ORM Mystic
- **Views** : Moteur de templates Lucid avec syntaxe intuitive

### 🗄️ ORM Mystic
- Requêtes simplifiées avec une syntaxe fluide
- Génération automatique de models depuis la base de données
- Support des relations et filtres avancés
- Retour des données au format JSON

### 🔄 Système de Migrations
- Gestion de version de votre schéma de base de données
- Blueprint pour définir vos tables de manière expressive
- Rollback facile des migrations
- Support complet des types de colonnes MySQL

### 🎨 Moteur de Templates Lucid
- Syntaxe claire et expressive : `{{ $variable }}`
- Directives de contrôle : `{@if}`, `{@foreach}`, `{@include}`
- Composants réutilisables
- Héritage de layouts

### ⚡ CLI Horizon
- Serveur de développement intégré
- Générateurs de code automatiques
- Gestion des migrations et logs
- Commandes extensibles

### 🛣️ Routing Flexible
- Support Web et API
- Routes avec paramètres dynamiques
- Méthodes HTTP (GET, POST, PUT, DELETE)
- Middleware support

## 🚀 Installation

### Prérequis
- PHP 7.2.5 ou supérieur
- Composer
- MySQL/MariaDB
- Extensions PHP : PDO, PDO_MySQL

### Installation rapide

```bash
# Cloner le projet
git clone https://github.com/votre-repo/horizon.git
cd horizon

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env

# Éditer .env avec vos paramètres
nano .env
```

### Configuration de la base de données

```env
APP_NAME=Horizon

# DATABASE CONNECTION
DB_CONNECT=true
DB_HOST=localhost
DB_PORT=3306
DB_NAME=horizon_db
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

## 📖 Guide de Démarrage

### 1. Démarrer le serveur de développement

```bash
php Horizon serv:run
# ou spécifier un port
php Horizon serv:run 127.0.0.1 8000
```

### 2. Créer votre première migration

```bash
# Créer une migration
php Horizon migration:new CreateUsersTable

# Éditer la migration dans database/migrations/
# Exemple :
```

```php
<?php

namespace Horizon\Database\Migrations;

use Horizon\Core\Commands\Migrations\AbstractMigration;
use Horizon\Core\Commands\Migrations\Schema;

class Version20241024CreateUsersTable extends AbstractMigration {

    public function up(): void {
        Schema::newTable('users', function($table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->json('role')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropTable('users');
    }
}
```

```bash
# Exécuter la migration
php Horizon migration:run
```

### 3. Générer les Models automatiquement

```bash
# Génère tous les models depuis vos tables
php Horizon entity:make
```

Cela créera automatiquement vos models dans `app/models/` avec tous les getters et setters.

### 4. Créer une route

Dans `routes/web.php` :

```php
<?php

use Horizon\App\Controllers\HomeController;
use Horizon\Core\Router\Routes;

Routes::get('/', [HomeController::class => 'renderHome']);
Routes::get('/users', [UserController::class => 'index']);
Routes::post('/users', [UserController::class => 'store']);
```

### 5. Créer un Controller

Dans `app/controllers/UserController.php` :

```php
<?php

namespace Horizon\App\Controllers;

use Horizon\Core\CoreController;
use Horizon\App\Models\User;

class UserController extends CoreController {
    public function index() {
        $users = User::all();
        $this->render("Users", ['users' => $users]);
    }
    
    public function store() {
        // Logique de création
    }
}
```

### 6. Utiliser l'ORM Mystic

```php
<?php

use Horizon\App\Models\User;

// Récupérer tous les utilisateurs
$users = User::all();

// Rechercher avec conditions
$users = User::findBy(['role' => 'admin'])
    ->orderBy(['created_at' => 'DESC'])
    ->limit(10)
    ->get();

// Trouver un seul enregistrement
$user = User::findOneBy(['email' => 'user@example.com']);

// Rechercher tous les enregistrements correspondants
$admins = User::findAllBy(['role' => 'admin']);
```

### 7. Créer une vue Lucid

Dans `src/views/layouts/Users.lucid.php` :

```php
<div class="users-container">
    <h1>Liste des utilisateurs</h1>
    
    {@if (count($users) > 0)}
        <ul>
            {@foreach ($users as $user)}
                <li>
                    <strong>{{ $user->getEmail() }}</strong>
                    <span>Inscrit le : {{ $user->getCreated_at() }}</span>
                </li>
            {@endforeach}
        </ul>
    {@else}
        <p>Aucun utilisateur trouvé.</p>
    {@endif}
</div>
```

## 🛠️ Commandes CLI

### Serveur de développement
```bash
php Horizon serv:run [host] [port]
# Exemple : php Horizon serv:run 127.0.0.1 8888
```

### Migrations
```bash
# Créer une nouvelle migration
php Horizon migration:new [NomDeLaMigration]

# Exécuter toutes les migrations en attente
php Horizon migration:run

# Annuler la dernière migration
php Horizon migration:revert

# Annuler une migration spécifique
php Horizon migration:revert Version20241024CreateUsersTable
```

### Génération de Models
```bash
# Générer tous les models depuis la base de données
php Horizon entity:make
```

### Gestion des Logs
```bash
# Afficher tous les logs
php Horizon logs:show

# Filtrer les logs par niveau
php Horizon logs:filter SUCCESS
php Horizon logs:filter ERROR
php Horizon logs:filter INFO

# Effacer les logs
php Horizon logs:clear
```

## 📁 Structure du Projet

```
horizon/
├── app/
│   ├── controllers/        # Controllers de l'application
│   └── models/            # Models générés automatiquement
├── core/                  # Core du framework
│   ├── commands/          # Commandes CLI
│   ├── database/          # Gestionnaire de base de données
│   ├── env/              # Loader de variables d'environnement
│   ├── logs/             # Système de logging
│   ├── lucid/            # Moteur de templates
│   ├── mystic/           # ORM
│   └── router/           # Système de routing
├── database/
│   └── migrations/        # Fichiers de migration
├── routes/
│   ├── web.php           # Routes web
│   └── api/api.php       # Routes API
├── src/
│   └── views/            # Templates Lucid
│       ├── Index.php     # Layout principal
│       └── layouts/      # Vues de l'application
├── storage/
│   └── logs/             # Fichiers de logs
├── .env                   # Configuration (à créer)
├── .env.example          # Exemple de configuration
├── Index.php             # Point d'entrée web
├── Horizon               # CLI du framework
└── composer.json         # Dépendances
```

## 🎨 Syntaxe du Moteur Lucid

### Variables
```php
{{ $variable }}
{@ $variable }
```

### Structures de contrôle
```php
{@if ($condition)}
    <!-- contenu -->
{@elseif ($autre_condition)}
    <!-- contenu -->
{@else}
    <!-- contenu -->
{@endif}
```

### Boucles
```php
{@foreach ($items as $item)}
    <p>{{ $item->getName() }}</p>
{@endforeach}
```

### Inclusion de composants
```php
{@include 'header'}
{@include 'footer'}
```

## 💾 Blueprint - Définition de Tables

Types de colonnes disponibles :

```php
$table->id();                           // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
$table->bigInteger('column');           // BIGINT
$table->integer('column');              // INT
$table->string('column', 255);          // VARCHAR
$table->text('column');                 // TEXT
$table->boolean('column');              // TINYINT(1)
$table->date('column');                 // DATE
$table->dateTime('column');             // DATETIME
$table->timestamp('column');            // TIMESTAMP
$table->json('column');                 // JSON
$table->timestamps();                   // created_at + updated_at
```

Modificateurs de colonnes :

```php
$table->string('email')->nullable();
$table->string('username')->unique();
$table->integer('count')->default(0);
$table->bigInteger('user_id')->unsigned();
```

## 🔍 Système de Logs

Niveaux de log disponibles :

```php
use Horizon\Core\Logs\Log;

Log::info("Message d'information");
Log::success("Opération réussie");
Log::alert("Alerte importante");
Log::error("Erreur détectée");
Log::danger("Situation critique");
Log::test("Log de test");
```

Format des logs :
```
[2024-10-25 14:38:13][Europe/Paris][SUCCESS] Model 'User' created.
[2024-10-25 14:21:36][Europe/Paris][ERROR] Failed to run migration.
```

## 🌐 Configuration du Routing

### Routes Web
```php
Routes::get('/users', [UserController::class => 'index']);
Routes::post('/users', [UserController::class => 'store']);
Routes::put('/users/{id}', [UserController::class => 'update']);
Routes::delete('/users/{id}', [UserController::class => 'destroy']);
```

### Routes avec paramètres
```php
Routes::get('/posts/{id}', [PostController::class => 'show']);
Routes::get('/users/{id}/posts', [PostController::class => 'userPosts']);
```

## 📝 TODO / Roadmap

- [ ] Support des middlewares
- [ ] Authentification intégrée
- [ ] Validation des formulaires
- [ ] Relations entre models (ORM)
- [ ] Cache intégré
- [ ] Support des queues
- [ ] Tests unitaires
- [ ] Documentation interactive

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commit vos changements
4. Push vers la branche
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👤 Auteur

**Saskoue**
