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

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
