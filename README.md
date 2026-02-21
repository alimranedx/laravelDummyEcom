# Laravel Dummy Ecommerce Project

This is a comprehensive Laravel-based ecommerce application featuring user authentication, a hierarchical Role-Based Access Control (RBAC) system, and standard e-commerce features.

## Features
- **Authentication**: Secure login and registration.
- **Dynamic RBAC**: Hierarchical permission system (Module > Sub-module > Page) with a custom middleware.
- **User Types**: Managed through Enums (Super Admin, Admin, and Regular User).
- **Product Management**: Brands, Categories, and Products CRUD.
- **Sales System**: Order placement and status tracking.
- **Admin Dashboard**: Real-time overview of the system status.
- **Responsive UI**: Modern, premium admin interface using Bootstrap Icons and glassmorphism-inspired design.

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
1. Create a new database named `laravel_dummy_ecom` in your MySQL server.
2. Update your `.env` file with your database credentials.

### 5. Run Migrations & Seeders
This will set up the table structure, create the default roles, and seed the hierarchical permission system:
```bash
php artisan migrate --seed
```

### 6. Storage Link & Asset Compilation
```bash
php artisan storage:link
npm run dev
```

### 7. Start the Server
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`.

---

## Developer Documentation: Implementing New Features

This project uses a custom, database-driven hierarchical permission system. Follow these steps when adding a new module or feature to ensure it integrates with the RBAC and Dynamic Sidebar.

### 1. The Permission Hierarchy
Permissions are structured as:
`Module` > `SubModule` > `Page` (represents a Controller method)

### 2. Adding a New Feature Step-by-Step

#### Step A: Create the Controller and Routes
1. Create your controller: `php artisan make:controller Admin/YourFeatureController -r`
2. Define your routes in `routes/admin.php` within the `check_page_permission` middleware group.

#### Step B: Register in `AdminModuleSeeder`
You must register your new feature in `database/seeders/AdminModuleSeeder.php` so the system knows it exists.
1. Add your module/sub-module definition to the `$modules` array.
2. Specify the `controller_name` (fully qualified with namespace) for the sub-module.
3. Define the `pages` (methods like `index`, `create`, `store`).
4. Re-run seed: `php artisan db:seed --class=AdminModuleSeeder`

#### Step C: Sidebar Integration
The sidebar in `resources/views/admin/layout.blade.php` automatically renders modules and sub-modules that the logged-in user has permission to see.
- If your route name follows the standard `admin.feature-name.index` pattern, it will work automatically.
- If you use a custom route name, update the mapping logic in the `layout.blade.php` sidebar loop.

### 3. Permission Enforcement
All admin routes are protected by the `CheckPagePermission` middleware. It extracts the controller and method from the current request and checks if the user's role is associated with that specific `Page` in the `role_pages` table.

> **IMPORTANT**: Standard Laravel/Spatie `can` middleware (e.g., `->middleware('can:manage brands')`) is **NOT** used for admin routes. All authorization must be configured via the **RBAC** module in the admin panel. Adding legacy `can` middleware to routes will cause `403 Unauthorized` errors.

- **Super Admin**: Bypasses all checks and sees everything.
- **Admin**: Permissions are managed via the **RBAC > Role Permission Association** interface.
- **RBAC Module**: This specific module is hard-coded to be restricted to **Super Admins only**.

---

## Development Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@example.com` | `password` |
| **Admin** | `admin@example.com` | `password` |
| **Regular User** | `user@example.com` | `password` |

---

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
