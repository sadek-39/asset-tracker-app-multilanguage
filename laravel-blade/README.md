# Asset Tracker - Laravel Implementation

This implementation focuses on the traditional MVC pattern using Laravel Blade and session-based authentication.

## Features
- **MVC Architecture**: Robust separation of concerns.
- **Session-based Auth**: Using Laravel's built-in session authentication guards.
- **Blade Templating**: Fast, server-side rendered templates with Alpine.js for interactivity.
- **Tailwind CSS**: Utility-first styling for a premium feel.
- **DB Transactions**: Safe updates for balance and transaction records.

## Tech Stack
- **Framework**: Laravel 10.x
- **Frontend**: Blade + Tailwind CSS + Alpine.js
- **Database**: Eloquent ORM + MySQL
- **Auth**: Laravel Sessions

## Setup Instructions
1. Install PHP 8.1+ and Composer.
2. Run `composer install` to download dependencies.
3. Configure your `.env` file with MySQL credentials.
4. Run `php artisan migrate` to create database tables.
5. Run `php artisan serve` to start the local server.

## Project Structure
- `app/Http/Controllers/`: Request handling and business logic coordination.
- `app/Models/`: Eloquent entities and relationships.
- `resources/views/`: Blade templates for the UI.
- `database/migrations/`: Schema definition for the shared database.
- `routes/web.php`: Application routing for the MVC flow.
