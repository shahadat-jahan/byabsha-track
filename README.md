# Byabsha Track — Multi-Tenant Multi-Shop Business & Inventory Tracking System (SaaS Ready)

Welcome to **Byabsha Track**, a premium, production-ready, SaaS-enabled Business Tracking and Inventory Management System. Built on the modern **Laravel 12** framework with a modular, highly scalable architecture, this system is designed for developers, agencies, and business owners looking to run a multi-tenant business management platform or deploy it for their own multi-location ventures.

---

## 🚀 Why Choose Byabsha Track? (Value Proposition)

Whether you are looking to launch a profitable SaaS platform or manage your own multi-branch retail network, **Byabsha Track** is the ultimate solution.

*   **SaaS Ready (Multi-Tenancy)**: Out-of-the-box support for multiple independent organizations (tenants). Each tenant has isolated database records, users, and configurations. You can monetize this by selling subscriptions to business owners!
*   **Highly Modular Architecture**: Built using a domain-driven modular approach (`nwidart/laravel-modules`). Every business feature is self-contained under its own module. This makes the system incredibly easy to extend, customize, or integrate with other systems.
*   **Automated Financial Calculations**: No manual tracking needed. The system automatically computes capital valuation (`Σ stock × purchase_price`), sales margins, restock values, and real-time profits/losses.
*   **Rich Reporting & PDF Export**: Detailed analytics and comparative metrics between shops, with print-ready PDF export options for all vital transactions and profit/loss statements.
*   **Bilingual & Global**: Seamless English and Bengali support with over 600+ translation keys, perfect for local and international markets.

---

## 🎯 The Problem & The Solution

### ❌ The Problem
Small-to-medium business owners face severe challenges when managing multiple shops or branches:
1.  **Fragmented Inventory**: It is hard to keep track of real-time stock levels, purchase prices, and sale prices across different physical locations.
2.  **Inaccurate Profit Calculations**: Calculating real margins is labor-intensive, especially when products are restocked at varying purchase costs.
3.  **Data Segregation Issues**: Running software for different companies typically requires multiple servers or databases, which multiplies hosting costs and maintenance complexity.
4.  **Lack of Professional Reporting**: Hand-written ledger books or generic spreadsheets fail to provide daily, monthly, or branch-to-branch P&L insights.

###  The Solution: Byabsha Track
Byabsha Track directly addresses these pain points by offering:
1.  **Centralized Multi-Shop Control**: Manage multiple independent shops under a single unified login context.
2.  **Automated Real-Time Capital & Margin Computation**: A powerful services layer automatically calculates profit margins on every sale and updates total shop capital whenever stock changes.
3.  **Robust Multi-Tenant Isolation**: Uses Laravel's Global Query Scopes to automatically isolate data. Tenants share a single database securely without any risk of data leaks.
4.  **One-Click PDF Reports**: Generates professional reports for sales, products, daily/monthly P&L, ready to be printed or emailed.

---

## ⭐ Key Features

*   **Tenant & User Management**: Admin and staff can register under a tenant account. Command-line and Tinker interfaces make tenant setup trivial.
*   **Shop CRUD Management**: Create, view, update, and delete multiple shops.
*   **Smart Product Inventory**: Maintain products with categories, brands, purchase and selling price tracking, and current stock status.
*   **Sales Transactions with Stock Sync**: Automatically deducts stock and computes exact net profit on each sale. Includes complete database transaction safety to prevent data corruption.
*   **Restock Tracking**: Add new inventory with unit purchase price. Automatically increments stock levels and updates capital.
*   **Real-time Business Dashboard**: Track today's sales, daily and monthly profits, total capital, and low-stock alerts at a single glance.
*   **Comprehensive Reports & Analytics**:
    *   Sales list report with advanced filters
    *   Stock levels & low stock alerts
    *   Daily & Monthly P&L breakdowns
    *   Branch-by-branch comparisons
    *   One-click print-ready PDF download for all reports
*   **Bilingual User Interface**: Easily toggle between English and Bengali (বাংলা).

---

## 🛠️ Technology Stack & Architecture

We leverage a cutting-edge PHP stack designed for security, performance, and scaling:

*   **Backend**: Laravel 12 (PHP 8.2+)
*   **Module Management**: `nwidart/laravel-modules` (v12) for self-contained, domain-driven design.
*   **Database**: MySQL (optimized indexes, foreign keys with cascade-delete safety)
*   **Frontend**: Bootstrap 5.3 + Tailwind CSS 4 for a responsive, modern admin dashboard.
*   **Asset Bundler**: Vite 7
*   **PDF Generation**: `barryvdh/laravel-dompdf` (v3.1)
*   **Testing**: Pest v4 (robust unit and feature tests included)

---

## 📁 Clean Modular Structure

The codebase is organized into **9 decoupled modules** under `Modules/`, making it highly maintainable and clean:

1.  **Auth**: Manages secure login/logout authentication with guest/auth middleware.
2.  **Landing**: A clean, public landing page for marketing.
3.  **Dashboard**: Business metrics, monthly graphs, and low-stock indicators.
4.  **Shop**: Management of physical retail outlets or online channels.
5.  **Product**: CRUD for inventory, categories, pricing, and stock monitoring.
6.  **Sale**: Handles checkout, inventory deduction, and profit margin calculation.
7.  **Restock**: Inventory procurement records and purchase cost calculations.
8.  **Capital**: Automated valuation of shop assets and current inventory cost.
9.  **Report**: Advanced search filters and professional PDF export layouts.

---

## ⚡ Easy Installation & Setup

Set up the project locally or on a server in just a few steps:

### Prerequisites
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL

### Quick Installation
1.  **Clone the repository**:
    ```bash
    git clone https://github.com/shahadat-jahan/byabsha-track.git
    cd byabsha-track
    ```
2.  **Run the automatic setup command** (installs vendor packages, creates `.env`, generates app keys, and builds assets):
    ```bash
    composer setup
    ```
    *Alternatively, you can install manual steps:*
    ```bash
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    ```
3.  **Configure Database**:
    Open the `.env` file and input your MySQL database credentials:
    ```env
    DB_DATABASE=byabshatrack
    DB_USERNAME=your_db_username
    DB_PASSWORD=your_db_password
    ```
4.  **Run Migrations & Seed**:
    ```bash
    php artisan migrate --seed
    ```
5.  **Build Frontend Assets & Run Server**:
    *   To run in development mode:
        ```bash
        composer dev
        ```
    *   To build production-ready optimized assets:
        ```bash
        npm run build
        php artisan serve
        ```
    Now, navigate to `http://localhost:8000` to see the application!
    
---

## 🏢 Multi-Tenant Setup Guide

Creating a new tenant (organization) is simple:

### Create Tenant via CLI
Run the following artisan command:
```bash
php artisan tenant:create "Acme Corporation" --domain=acme.yourdomain.com --email=admin@acme.com
```
This automatically:
1.  Creates a new Tenant record with slug `acme-corporation`.
2.  Assigns the domain `acme.yourdomain.com`.
3.  Sets up the tenant administrator.

### Create Tenant Users & Login
To manually create a tenant user inside Tinker:
```bash
php artisan tinker
```
```php
$tenant = Tenant::where('slug', 'acme-corporation')->first();

User::create([
    'name' => 'John Doe',
    'email' => 'john@acme.com',
    'password' => bcrypt('password123'),
    'role' => 'owner',
    'tenant_id' => $tenant->id
]);
```
Once created, log in using the credentials to access the fully isolated tenant dashboard.

---

## 🔒 Security & Data Integrity
*   **Database Transactions**: All sales and restocking operations are wrapped in transactional blocks. If any step fails, changes roll back, ensuring database consistency.
*   **Automatic Scoping**: The Global Scope filters database queries automatically, preventing tenants from seeing other tenants' data.
*   **API Security**: Sanitized API routes under `/api/v1` are guarded with Laravel Sanctum token-based authentication.

---

## 📋 Technical Evaluation & Production Report

### 1. Setup Process Reflection
- Successfully cloned and ran the system locally using `composer setup` (handling automated vendor publishing, app key generation, and asset compilation via Vite 7).
- Verified local environment health via database seeding (`php artisan migrate --seed`) and verified multi-tenant functionality using the `tenant:create` CLI command.

### 2. Project Structure Summary
The application leverages a robust Domain-Driven Modular Design via `nwidart/laravel-modules` partitioned into 9 structural modules under `Modules/`. Core database tables rely heavily on Eloquent Global Scopes to auto-append `tenant_id` constraints on all SELECT queries, isolating multi-tenant datasets within a single-database infrastructure safely.

---

*For inquiries, customization, or technical support, please contact the developer via CodeCanyon profile dashboard.*
