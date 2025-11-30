<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# DigiCRM

A Laravel-based CRM application.

## Technology Stack

- **Backend:** [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Frontend:** [Livewire 3](https://livewire.laravel.com), [Alpine.js](https://alpinejs.dev), [Tailwind CSS](https://tailwindcss.com)
- **Database:** MySQL
- **Build Tools:** [Vite](https://vitejs.dev)

## Prerequisites

Ensure you have the following installed on your local machine:

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM

## Installation & Setup

Follow these steps to set up the project locally.

### 1. Install Backend Dependencies

Navigate to the project directory and install PHP dependencies:

```bash
composer install
```

### 2. Configure Environment

Copy the example environment file to create your local configuration:

```bash
cp .env.example .env
```

Open the `.env` file and configure your database settings (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.).

### 3. Generate Application Key

Generate the Laravel application key:

```bash
php artisan key:generate
```

### 4. Run Database Migrations

Set up your database tables:

```bash
php artisan migrate --seed
```

### Default Login Account

- **Email:** test@example.com
- **Password:** password

### 5. Install Frontend Dependencies

Install the Node.js dependencies:

```bash
npm install
```

### 6. Build/Run Frontend

To start the development server with hot module replacement (HMR):

```bash
npm run dev
```

Or to build for production:

```bash
npm run build
```

### 7. Serve the Application

If you are not using a tool like Valet or Laragon, you can serve the application using Artisan:

```bash
php artisan serve
```

The application should now be accessible at `http://localhost:8000`.

## Production Deployment Guide

For a perfect production setup, ensure you run the following commands after deploying your code:

### 1. Link Storage
Create the symbolic link for the storage directory to make public files accessible:
```bash
php artisan storage:link
```

### 2. Set Permissions
Ensure the storage directory is writable by the web server:
```bash
sudo chmod -R ugo+rw storage
```

### 3. Ensure Log File Exists
Create the log file if it doesn't exist (adjust the path `/home/domain.com/laravel-app` to your actual project root):
```bash
touch /home/domain.com/laravel-app/storage/logs/laravel.log
```

### 4. Re-apply Permissions
Apply permissions again to ensure the new log file and any created directories are writable:
```bash
sudo chmod -R ugo+rw storage
```

## Useful Artisan Commands

Here are some common commands you might need during development and maintenance:

### Clear Caches
Clear various caches to ensure changes take effect:

```bash
# Clear compiled view files (useful when blade changes aren't reflecting)
php artisan view:clear

# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear all caches at once (optimize:clear)
php artisan optimize:clear
```

### Database
Manage your database state:

```bash
# Run migrations
php artisan migrate

# Run migrations and seed database
php artisan migrate --seed

# Rollback the last migration operation
php artisan migrate:rollback

# Seed the database
php artisan db:seed
```

### Maintenance
```bash
# Put the application into maintenance mode
php artisan down

# Bring the application out of maintenance mode
php artisan up
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
