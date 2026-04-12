# Laravel Dummy Ecommerce Project

This is a comprehensive Laravel-based ecommerce application featuring user authentication, a hierarchical Role-Based Access Control (RBAC) system, and standard e-commerce features.

## Features
- **Authentication**: Secure login and registration.
- **Dynamic RBAC**: Hierarchical permission system (Module > Sub-module > Page) with a custom middleware.
- **User Types**: Managed through Enums (Super Admin, Admin, and Regular User).
- **Product Management**: Brands, Categories, and Products CRUD.
- **Sales System**: Order placement and status tracking.
- **Admin Dashboard**: Real-time overview of the system status.
- **REST API**: Comprehensive JWT-secured endpoints for Auth, Products, and User Dashboard to easily integrate with frontend frameworks (e.g., React, Vue).
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
`Module` > `Sub-module` (associated with a Controller) > `Page` (associated with a Controller Method)

- **Modules**: High-level groupings (e.g., "Inventory", "RBAC").
- **Sub-modules**: Specific features (e.g., "Brands", "Role Permission Association").
- **Pages**: Individual actions/methods (e.g., `index`, `create`, `store`).

### 2. Adding a New Feature Step-by-Step

#### Step A: Create the Controller and Routes
1. Create your controller: `php artisan make:controller Admin/YourFeatureController -r`
2. Define your routes in `routes/admin.php` within the `check_page_permission` middleware group.
3. **CRITICAL: Route Naming Convention**
   The sidebar generates links automatically based on the sub-module name. To ensure compatibility:
   - Use kebab-case of the sub-module name as the route prefix.
   - Example: Sub-module "Sale Report" -> Route name should start with `admin.sale-report.`
   - Example Route: `Route::get('sale-report', [SaleReportController::class, 'index'])->name('admin.sale-report.index');`

#### Step B: Register in `AdminModuleSeeder`
You must register your new feature in `database/seeders/AdminModuleSeeder.php` so the system knows it exists.
1. Add your module/sub-module definition to the `$modules` array.
2. Specify the `controller_name` (e.g., `App\Http\Controllers\Admin\SaleReportController`).
3. Define the `pages` array (e.g., `['index' => 2, 'create' => 2, 'store' => 1]`).
   - `method_type` constants: `1=Post`, `2=Get`, `3=Put`, `4=Delete`.
4. Re-run seed: `php artisan db:seed --class=AdminModuleSeeder`

#### Step C: Sidebar Integration
The sidebar in `resources/views/admin/layout.blade.php` automatically renders modules and sub-modules.
- The system searches for a route named: `admin.str_replace(' ','-',{kebab-case-sub-module-name}).{default_method}`.
- If your route doesn't follow this, you can add a manual override in the `@if (!Route::has($routeName))` block in `layout.blade.php`.

### 3. Permission Enforcement
All admin routes are protected by the `CheckPagePermission` middleware.
1. It looks up the `pages` table using the current `Controller@method`.
2. It checks the `role_pages` table to see if any of the user's roles are associated with that page ID.
3. **Super Admin**: Always has access (bypasses check).
4. **RBAC Module**: Hard-coded to be restricted to **Super Admins only** for security.

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

---

## API Documentation & Usage

This project exposes JWT-secured RESTful API endpoints intended for consumption by frontend frameworks such as React or mobile applications.

### API Postman Setup
To quickly test and integrate the API, a complete **Postman Collection** is included natively within the project:
1. Locate `dummy-ecom-api.postman_collection.json` in the root of your project directory.
2. In Postman, go to **File > Import** and select this file.
3. Once imported, you will get pre-configured requests under the **Dummy E-Com API** collection.
4. **Automated Authentication**: The `Login` request natively intercepts your generated `access_token` upon a successful `200 OK` response and automatically updates your Postman environment. You do not need to manually configure the Bearer Token for subsequent secure requests (e.g., calling `/api/auth/me` or `/api/user/orders`).

### Key API Endpoints
- **Public**:
  - `POST /api/auth/login` (Returns API JWT)
  - `POST /api/auth/register`
  - `GET /api/products` (Accepts `?search=` and `?page=` params)
  - `GET /api/products/{id}`
- **Protected (Requires JWT Bearer Token)**:
  - `GET /api/auth/me`
  - `POST /api/auth/logout`
  - `GET /api/user/dashboard`
  - `GET /api/user/orders`
