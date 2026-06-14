# Gestion Media

Application Laravel 9 de gestion et diffusion de médias (audio, vidéo, documents).

## Prérequis

- PHP 8.0+
- Composer
- MySQL (ou SQLite pour les tests)
- Node.js (optionnel, assets statiques dans `public/`)

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Comptes par défaut (seed)

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Super Administrateur | admin@gmail.com | 12345678 |

Les clients s'inscrivent via `/register` (role Client, accès catalogue).

## Rôles

| ID | Rôle | Accès |
|----|------|-------|
| 1 | Super Administrateur | Back-office complet + gestion des admins |
| 2 | Administrateur | Back-office (CRUD médias, sources, thématiques) |
| 3 | Client | Catalogue `/catalogue` (contenus publiés uniquement) |

## Variables d'environnement

Minimum requis dans `.env` :

```env
APP_KEY=
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_media
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Gestion Media"
```

## Structure principale

- **Admin** : `/dashboard`, CRUD sources/thématiques/audios/videos/documents
- **Client** : `/catalogue`, recherche et filtres par source/thématique
- **Uploads** : `storage/app/public/document/` et `storage/app/public/picture/`

## Tests

```bash
php artisan test
```

Les tests utilisent SQLite en mémoire (configuré dans `phpunit.xml`).

## Développement

```bash
php artisan migrate:fresh --seed   # Réinitialiser avec données demo
```
