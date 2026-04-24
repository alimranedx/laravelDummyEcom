# 🛍️ Laravel Dummy Ecommerce Project

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql)](https://www.mysql.com/)

A comprehensive, production-ready Laravel ecommerce application featuring a robust hierarchical Role-Based Access Control (RBAC) system, real-time notifications via WebSockets, and a fully-featured REST API.

---

## ✨ Key Features

- **🔐 Secure Authentication**: Multi-guard authentication for Users and Admins.
- **🛡️ Dynamic RBAC**: Sophisticated hierarchical permission system (Module > Sub-module > Page) with custom enforcement middleware.
- **🔔 Live Notifications**: Real-time admin alerts for sales and registrations powered by **Laravel Reverb WebSockets** with persistent database tracking.
- **🚀 RESTful API**: JWT-secured endpoints for seamless integration with mobile apps and modern frontend frameworks (React, Vue, etc.).
- **📦 Inventory Management**: Complete CRUD for Brands, Categories, and Products.
- **💳 Sales System**: Order processing, status management, and dashboard analytics.
- **🎨 Premium UI**: Modern, responsive admin interface featuring glassmorphism elements and Bootstrap 5.

---

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade, Bootstrap 5, Alpine.js, Vite
- **WebSockets**: Laravel Reverb
- **API Auth**: JWT (`tymon/jwt-auth`)
- **Database**: MySQL 8.0+

---

## 🚀 Getting Started

### Prerequisites
- **PHP**: ^8.2
- **Composer**: ^2.0
- **Node.js & NPM**: Latest LTS
- **Database**: MySQL (Laragon recommended for Windows)

### Installation Steps

1. **Clone & Install**
   ```bash
   git clone <repository-url>
   cd laravelDummyEcom
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```

3. **Broadcasting & Live Notifications (Reverb)**
   If you are setting this up on a new machine, you need to generate fresh WebSocket keys.
   
   **Option A: Generate New Keys (Fresh Installation)**
   If you want to generate brand new keys for your machine, run:
   ```bash
   php artisan reverb:install
   ```
   **What this command does:**
   - Detects your broadcasting driver.
   - Generates a random **App ID**, **Public Key**, and **Secret**.
   - Automatically appends these values to your `.env` file.
   - Configures the `VITE_` versions of the keys for your frontend assets.

   
   **Option B: Manual Sync (Recommended for Multi-Device)**
   If you already have keys on another machine, copy these values into your `.env`:
   ```env
   REVERB_APP_ID=784099
   REVERB_APP_KEY=mcbjissv0g2lw9dnvfxp
   REVERB_APP_SECRET=ghg1cbanebvagc2kz5df
   REVERB_HOST="127.0.0.1"
   REVERB_PORT=8081
   REVERB_SCHEME=http
   ```
   > [!TIP]
   > **REVERB_APP_KEY** is used by the Frontend. **REVERB_APP_SECRET** is used by the Backend to sign messages. If these mismatch between your Frontend and Backend, you will get a "Pusher error: Not found".

4. **Database Configuration**
   - Create a database named `laravel_dummy_ecom`.
   - Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in your `.env`.

5. **Migrate & Seed**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

6. **Compile Assets & Run**
   Open three terminals:
   - **Terminal 1 (Backend)**: `php artisan serve`
   - **Terminal 2 (Vite/Assets)**: `npm run dev`
   - **Terminal 3 (WebSockets)**: `php artisan reverb:start --port=8081`


---

## 🔄 Maintenance & Updates

### Pulling Latest Changes
When pulling updates from Git, always run this sequence to ensure your environment stays in sync:
```bash
git pull
composer install
npm install
php artisan migrate
php artisan db:seed
php artisan config:clear
php artisan optimize:clear
```
### Troubleshooting Reverb (WebSockets)
If you see `WebSocket connection failed` or `Pusher error: Not found`:
1. **Restart the Server**: Always run `php artisan reverb:start` in a dedicated terminal.
2. **Match Keys**: Ensure the `VITE_REVERB_*` keys in your **Frontend** `.env` exactly match your **Backend** `.env`.
3. **Clear Cache**: Run `php artisan config:clear` and `php artisan optimize:clear`.
4. **Check Protocol**: If your app is not using SSL locally, ensure `REVERB_SCHEME=http` and `VITE_REVERB_SCHEME=http`.
5. **WSS Error**: If the browser tries to connect via `wss://` but you are on `http`, it's because the frontend `.env` is missing and defaulting to secure mode.


---

## 🛠️ Developer Guide: RBAC System

This project uses a database-driven hierarchical permission system.

### Permission Hierarchy
`Module` > `Sub-module` (Controller) > `Page` (Method)

### Adding a New Feature
1. **Controller**: Create with `php artisan make:controller Admin/NameController -r`.
2. **Routes**: Define in `routes/admin.php` inside the `check_page_permission` group.
   - **Naming Rule**: Use kebab-case of the sub-module name (e.g., "Sale Report" -> `admin.sale-report.index`).
3. **Seeder**: Register in `database/seeders/AdminModuleSeeder.php`.
4. **Permissions**: Re-run `php artisan db:seed --class=AdminModuleSeeder`.

---

## 🔌 API Documentation

### Postman Integration
A complete collection is included: `dummy-ecom-api.postman_collection.json`.
- **Import**: File > Import in Postman.
- **Auth**: The `Login` request automatically updates the environment variable for subsequent secure requests.

### Core Endpoints
- **Public**: `POST /api/auth/login`, `GET /api/products`
- **Protected**: `GET /api/auth/me`, `GET /api/user/orders`, `GET /api/user/dashboard`

---

## 🔑 Development Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@example.com` | `password` |
| **Admin** | `admin@example.com` | `password` |
| **Regular User** | `user@example.com` | `password` |

---

## 📄 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
