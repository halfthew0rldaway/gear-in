# ⚡ Gear-In Commerce

> *"This project represents the currently best and most complex application that I have ever made yet."*

![Banner](https://img.shields.io/badge/Status-Production%20Ready-success?style=for-the-badge) ![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white) ![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**Gear-In** is a sophisticated, full-featured e-commerce platform engineered to deliver a seamless shopping experience. Built with performance and scalability in mind, it implements advanced architectural patterns, robust transaction management, and a highly responsive user interface.

This repository hosts the source code for the "Tugas Besar Mata Kuliah Pemrograman Web Lanjut" final project, pushing the boundaries of academic expectations to professional-grade software development.

---

## ✨ Key Features

### 🛍️ Client Experience
*   **Dynamic Catalog System**: Advanced filtering, sorting, and search capabilities with instant suggestions.
*   **Optimized Checkout Flow**: Multi-step checkout with integrated shipping calculation, diverse payment gateways simulation (QRIS, VA, E-Wallet), and real-time stock validation.
*   **Smart Cart & Logic**: Handles product variants, stock locking, and automated price adjustments based on active discounts.
*   **Voucher & Promo Engine**: Complex rule-based engine for fixed/percentage discounts, minimum spend thresholds, and usage limits.
*   **Real-time Interaction**: Built-in chat system connecting customers directly with administrators for support.
*   **Customer Dashboard**: Order history tracking with detailed timeline status, wishlist management, and spending insights.

### 🛡️ Administrative Control
*   **Analytics Dashboard**: Comprehensive charts and metrics tracking revenue, order volume, and top-performing products.
*   **Inventory Management**: Detailed product CRUD with variant support, image galleries, and precise stock adjustments.
*   **Order Operations**: Full workflow control—from payment verification to shipping updates and cancellation handling.
*   **Content Moderation**: Review approval system to ensure quality user-generated content.

### 🔧 Technical Highlights
*   **Robust Architecture**: Implements **Service Layer** pattern to separate business logic from controllers, ensuring maintainability.
*   **Data Integrity**: Uses **Pessimistic Locking** (`lockForUpdate`) during checkout to prevent race conditions and stock overselling in high-concurrency scenarios.
*   **Performance First**: Heavy utilization of eager loading (`N+1` prevention), GPU-accelerated CSS animations, and optimized database queries.
*   **Responsive Design**: Mobile-first approach ensuring flawless rendering across all device viewports.

---

## 🚀 Getting Started

Follow these instructions to set up the project on your local machine.

### Prerequisites
*   **PHP** >= 8.2
*   **Composer**
*   **Node.js** & **NPM**
*   **MySQL** or **SQLite**

### Installation

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/halfthew0rldaway/gear-in.git
    cd gear-in
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configure your database settings in `.env` (DB_DATABASE, DB_USERNAME, etc.). For SQLite, simply create a `database/database.sqlite` file and use `DB_CONNECTION=sqlite`.*

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate --seed
    ```
    *This will set up the schema and populate the database with comprehensive dummy data.*

5.  **Build Assets**
    ```bash
    npm run build
    ```

6.  **Run Application**
    ```bash
    php artisan serve
    ```

---

## 🔐 Default Credentials

| Role | Email | Password | Access |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@gear-in.dev` | `password` | Full System Access |
| **Admin** | `admin2@gear-in.dev` | `password` | Order & Product Mgmt |
| **Customer** | `customer@gear-in.dev` | `password` | Storefront Experience |

---

## 💎 Project Structure

The project strictly follows modern Laravel best practices:

```
app/
├── Http/
│   ├── Controllers/    # Request handling
│   ├── Requests/       # Form Request Validation
│   └── Middleware/     # Route protection
├── Models/             # Eloquent ORM
└── Services/           # Business Logic Layer (Cart, Voucher, etc.)
database/
├── migrations/         # Schema definitions
└── seeders/            # Data population
resources/
├── views/              # Blade templates
└── css/                # Tailwind configuration
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Copyright © 2026 Gear-In. All rights reserved.
