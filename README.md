# Laravel Dummy Ecommerce Project

This is a comprehensive Laravel-based ecommerce application featuring user authentication, role-based access control (RBAC), and product management.

## Features
- **Authentication**: Secure login and registration.
- **RBAC**: Super Admin, Admin, and Regular User roles using Spatie Laravel-Permission.
- **Product Management**: Categories and Products CRUD.
- **Order System**: Order placement and status tracking.
- **Responsive UI**: Modern interface designed for all devices.

---

## Prerequisites
Ensure you have the following installed on your system:
- **PHP**: ^8.2
- **Composer**: ^2.0
- **Node.js & NPM**: Latest stable versions
- **MySQL**: (Laragon is highly recommended for Windows users)

---

## Installation & Setup

Follow these steps to get the project running locally:

### 1. Clone the Repository
```bash
git clone <repository-url>
cd laravelDummyEcom
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the example environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
1. Create a new database named `laravel_dummy_ecom` in your MySQL server (e.g., via phpMyAdmin or Laragon).
2. Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_dummy_ecom
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 5. Run Migrations & Seeders
This will set up the table structure and create the default roles and administrative users:
```bash
php artisan migrate --seed
```

### 6. Storage Link
This will set up storage link and cache config
```bash
php artisan storage:link
php artisan config:cache
```

### 7. Compile Assets
```bash
npm run dev
```

### 8. Start the Server
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`.

---

## Development Credentials

You can use the following accounts to test different permission levels:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@example.com` | `password` |
| **Admin** | `admin@example.com` | `password` |
| **Regular User** | `user@example.com` | `password` |

---

## Project Structure (Key Areas)
- **Controllers**: `app/Http/Controllers`
- **Models**: `app/Models`
- **Migrations**: `database/migrations`
- **Seeders**: `database/seeders`
- **Views**: `resources/views`
- **Routes**: `routes/web.php`

---

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
