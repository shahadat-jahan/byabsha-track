# Byabsha Track — Multi-User Multi-Shop Business & Inventory Tracking System (SaaS Ready)

Welcome to **Byabsha Track**, a premium, production-ready, SaaS-enabled Business Tracking and Inventory Management System. Built on the modern **Laravel 12** framework with a modular, highly scalable architecture, this system is designed for developers, agencies, and business owners looking to run a multi-user business management platform or deploy it for their own multi-location ventures.

---

## 🚀 Why Choose Byabsha Track? (Value Proposition)

Whether you are looking to launch a profitable SaaS platform or manage your own multi-branch retail network, **Byabsha Track** is the ultimate solution.

*   **Multi-User SaaS Ready**: Each registered business owner gets their own fully isolated account. Owners manage multiple shops independently under a single platform — one owner cannot see another owner's data.
*   **Highly Modular Architecture**: Built using a domain-driven modular approach (`nwidart/laravel-modules`). Every business feature is self-contained under its own module, making the system easy to extend, customize, or integrate.
*   **Automated Financial Calculations**: The system automatically computes capital valuation (`Σ stock × purchase_price`), sales margins, restock values, and real-time profits/losses.
*   **Rich Reporting & PDF Export**: Detailed analytics and comparative metrics between shops, with print-ready PDF export options for all vital transactions and profit/loss statements.
*   **Bilingual & Global**: Seamless English and Bengali support with 600+ translation keys, perfect for local and international markets.

---

## 🎯 The Problem & The Solution

### ❌ The Problem
Small-to-medium business owners face severe challenges when managing multiple shops or branches:
1.  **Fragmented Inventory**: Hard to track real-time stock levels, purchase prices, and sale prices across locations.
2.  **Inaccurate Profit Calculations**: Calculating real margins is labor-intensive when products are restocked at varying purchase costs.
3.  **Lack of Professional Reporting**: Hand-written ledgers or spreadsheets fail to provide daily, monthly, or branch-to-branch P&L insights.

### ✅ The Solution: Byabsha Track
1.  **Centralized Multi-Shop Control**: Manage multiple shops under a single owner account.
2.  **Automated Real-Time Capital & Margin Computation**: A powerful services layer automatically calculates profit margins on every sale and updates total shop capital whenever stock changes.
3.  **User-Scoped Data Isolation**: Every database query is scoped by `user_id` and `shop_id`. One owner cannot access another owner's records.
4.  **One-Click PDF Reports**: Generates professional reports for sales, products, and daily/monthly P&L.

---

## ⭐ Key Features

*   **Multi-User Owner Management**: Register multiple business owners on the platform. Each owner manages their own shops and staff independently.
*   **Shop CRUD Management**: Create, view, update, and delete multiple shops per owner.
*   **Smart Product Inventory**: Products with categories, brands, purchase/selling price tracking, and real-time stock status.
*   **FIFO Sales with Stock Sync**: Automatically deducts stock using FIFO batch logic and computes exact net profit on each sale. All writes wrapped in DB transactions to prevent data corruption.
*   **Restock Tracking**: Add new inventory with unit purchase price. Automatically increments stock and updates capital.
*   **Real-time Business Dashboard**: Today's sales, daily and monthly profits, total capital, and low-stock alerts at a single glance.
*   **Comprehensive Reports & Analytics**: Sales list with filters, stock levels, daily/monthly P&L, branch comparisons, and one-click PDF download.
*   **Bilingual UI**: Toggle between English and Bengali (বাংলা).

---

## 🛠️ Technology Stack & Architecture

```
Superadmin (platform operator)
└── Owner (your client / business owner)
      ├── Shop A
      ├── Shop B
      └── Shop C
            ├── Products & Batches (FIFO)
            ├── Sales → auto profit calculation
            ├── Restocks → batch tracking
            ├── Damages → batch stock deduction
            └── Capital → auto inventory valuation
```

Data isolation is **user-scoped**: every query filters by `user_id` / `shop_id`.

| Layer | Choice |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Modules | `nwidart/laravel-modules` v12 |
| Database | MySQL 8+ |
| Frontend | Bootstrap 5.3 + Tailwind CSS 4, Vite 7 |
| PDF | `barryvdh/laravel-dompdf` v3.1 |
| Auth | Laravel Sanctum (API routes) |
| Testing | Pest v4 |
| Code Style | Laravel Pint |

---

## 📁 Clean Modular Structure

```
app/
  Console/Commands/CreateTenant.php   ← owner + shop + subscription setup
  Models/
    TenantModel.php                   ← base Eloquent model (extended by all modules)
    User.php                          ← roles: superadmin / owner / manager
  Services/
    PlanService.php                   ← subscription feature gating

Modules/
  Auth/           login, logout, registration
  Landing/        public marketing page
  Dashboard/      KPI cards, graphs, low-stock alerts
  Shop/           multi-shop CRUD
  Product/        inventory, batch tracking, dynamic attributes
  Sale/           checkout, FIFO cost deduction, profit calc
  Restock/        purchase batches, stock increment
  Damage/         damaged stock write-off
  Capital/        automated inventory valuation
  Report/         P&L reports, PDF export
  Settings/       app-wide config (low stock threshold, currency, branding)
  Subscription/   plan management and feature gating
  User/           manager accounts, module access control
```

---

## ⚡ Easy Installation & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL 8+

### Quick Installation

```bash
git clone https://github.com/shahadat-jahan/byabsha-track.git
cd byabsha-track

composer install
cp .env.example .env
php artisan key:generate
npm install
```

Configure `.env`:
```env
DB_DATABASE=byabshatrack
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

```bash
# Run migrations and seed the superadmin
php artisan migrate --seed

# Start dev server (artisan serve + npm run dev)
composer dev
```

Visit `http://localhost:8000`

Default superadmin credentials are printed to terminal during seeding. To set your own:
```env
SUPERADMIN_EMAIL=admin@example.com
SUPERADMIN_PASSWORD=yourpassword
```

---

## 🏢 Creating a New Owner Account

### Via CLI (recommended)

```bash
php artisan tenant:create "Acme Corporation" --email=admin@acme.com
```

This creates an owner user, a shop, and a 30-day trial subscription — all inside a single DB transaction.

```
--email=      Owner email (prompted if omitted)
--password=   Password (auto-generated if omitted, printed once)
--shop=       Shop name (defaults to the tenant name)
--plan=       Subscription plan slug (default: trial)
--days=       Trial length in days (default: 30)
```

### Via Tinker

```bash
php artisan tinker
```

```php
$owner = \App\Models\User::create([
    'name'     => 'John Doe',
    'email'    => 'john@acme.com',
    'password' => bcrypt('password123'),
    'role'     => 'owner',
]);

$shop = \Modules\Shop\Models\Shop::create([
    'name'    => 'Acme Main Branch',
    'user_id' => $owner->id,
]);
```

---

## 🔒 Security & Data Integrity

*   **DB Transactions**: All sales, restocks, and damage operations are wrapped in transactions. If any step fails, changes roll back fully.
*   **User-Scoped Queries**: Every query is filtered by `user_id` / `shop_id` — one owner cannot access another owner's data.
*   **FIFO with Lock**: Batch stock deductions use `lockForUpdate()` to prevent race conditions under concurrent load.
*   **API Security**: Routes under `/api/v1` are guarded with Laravel Sanctum token-based authentication.

---

## 📋 Technical Evaluation & Production Report

### 1. Setup Process Reflection

Successfully cloned and initialized the ecosystem using the automated script.

Evaluated database consistency by running full seed operations (`php artisan migrate --seed`).

Verified tenant isolation by manually bootstrapping organizations and logging out/in via custom user sessions.

### 2. Project Structure Summary

The platform employs a robust Domain-Driven Modular Design governed by `nwidart/laravel-modules` split across 9 micro-domains. While marketing elements label the database scoping as traditional multi-tenancy, code evaluation shows it operates via a **Single-Database User-Scoped Context**. High-level owners manage multiple shops, and all core entities (Product, Sale, Restock) are safely scoped down to the owner's operational tree.

### 3. Issues & Production Risks Found

- **Silent Capital Desynchronization (High Severity):** Inside `SaleController`, the `updateShopCapital()` logic was executing outside the core atomic `DB::transaction()` lifecycle block. A drop in connection or sudden memory failure right before capital update would deduct product stocks but leave the overall capital table bloated with outdated values.

- **Unimplemented Scaffold Command:** The system documentation prominently featured a custom CLI utility (`tenant:create`), but the command file was missing from the console registry, leading to runtime failures if invoked via DevOps pipelines.

- **Restock edit was fully blocked once any batch unit was sold (Low Severity)** `RestockService::updateRestock()` threw a validation error if even one unit from a batch had been sold. Operators routinely need to fix purchase price typos on partially-sold batches. Non-price fields (`note`, `restock_date`, quantity upward adjustment) are now freely editable. Price changes on sold batches trigger a profit recomputation on affected sales.
Affected file: `Modules/Restock/Services/RestockService.php`

- **`tenant:create` artisan command was missing (Low Severity)**

- **Global Cache-Scoping Risks (Medium Severity):** Application settings inside the Settings module utilized raw key lookups (`setting.{$key}`). In a scaled, highly-concurrent production environment, this introduces structural key collisions across multi-tenant contexts.

- **Heavy Memory Footprint during PDF Export (Medium Severity):** `ReportController::exportSalesPdf()` fetches 1,000 rows, hydrates full Eloquent models with relations, and passes them to DomPDF synchronously. DomPDF consumes 5–15× the raw data size in memory. Fix: use a queued job for large exports or enforce a maximum date range.

- **Inconsistent Checkout Request Payload (Low Severity):** The `quickSale` action tracked customer profile metadata (e.g., fields for phone and name), whereas the standard `store` checkout endpoint silently discarded this customer-centric structure.

### 4. What Was Fixed & Patched

- **Encapsulated Capital Triggers:** Relocated `updateShopCapital()` triggers completely inside the native database transaction blocks across all `SaleController` write operations (`store`, `quickSale`, `update`, `destroy`). Writes are now atomic; they either fully persist or fully roll back together.

- **Engineered `tenant:create` CLI Command:** Authored the missing artisan routine (`CreateTenant.php`). Wrapped the Owner setup, multi-shop scaffolding, and subscription configurations safely into a transactional routine to block partial shell generation.

- **Standardized Code Style:** Cleaned up miscellaneous trailing whitespace blocks and structural formatting across the codebase to match rigid PSR-12 styling standards.

### 5. Production Readiness & Deployment Checklist

- **Queue Workers Monitoring:** The application relies on `QUEUE_CONNECTION=database` out-of-the-box. Deploy Supervisor to consistently sustain background daemons running `php artisan queue:work` to prevent deadlocks on automated background processes.

- **Task Scheduling:** Ensure system crontab maps the scheduler precisely every minute:
  ```
  * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
  ```

- **Production Caching Architecture:** The default profile relies on `CACHE_STORE=database`. Prior to migrating Settings to production, scale out to a Redis instance to support advanced cache tag groupings, as standard database engines do not support native cache tagging patterns.

- **System Resource Allocations:** Enforce a minimum configuration of `memory_limit=256M` (512M safer) inside `php.ini` to allow safe rendering of heavy PDF layout buffers before offloading to queue streams.

- **Secure Storage Interlink:** Remember to fire `php artisan storage:link` inside the production system root directory to verify that tenant logos and static uploaded media resolve correctly on web clients.

- **Environment Isolation:** Verify that `.env` explicitly registers `APP_DEBUG=false` and `APP_ENV=production` before exposing web ports to live external traffic.

---

## 🚀 Deployment / Server Notes

- **Queue worker**: `QUEUE_CONNECTION=database` by default. Run `php artisan queue:work` via Supervisor or background jobs will never execute.
- **Scheduler**: add `* * * * * php artisan schedule:run` to cron.
- **Cache driver**: default is `database`. Switch to Redis in production — `Cache::tags()` (needed for scoped settings cache) is not supported by the database driver.
- **Storage link**: run `php artisan storage:link` so uploaded assets (logo, favicon) are served correctly.
- **PHP memory limit**: set `memory_limit=256M` minimum; `512M` recommended until the PDF queued-job fix is applied.
- **`APP_DEBUG=false`** in production — `.env.example` ships with `true`.

---
