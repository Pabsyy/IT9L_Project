<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🚀 About the Project

A full-featured e-commerce and inventory management system built using Laravel. Designed for seamless experiences across both customer and admin interfaces, the platform features modern UI, robust functionality, and dark mode support.

🖱️ Access Admin Panel:
Double-click the Laravel logo in the top header to quickly access the admin login page.

## 🛒 Customer Features

### Shopping Experience
- 🛍️ Product catalog with category browsing and search
- 🛒 Shopping cart and wishlist
- ⭐ Product ratings & reviews
- 🎯 Featured product showcase
- 🔐 Social authentication (Google, Facebook)

### Account Management
- ✍️ User registration & login
- 👤 Profile and password management
- 📦 Order history & real-time tracking
- 💳 Multiple payment methods
- 🏠 Address book functionality

## 🧑‍💼 Admin Features

### Inventory Management
- 📦 Full CRUD for products
- 🔄 Stock tracking and adjustments
- 🏷️ Category and brand management
- 🤝 Supplier management
- 📑 Purchase order creation and management

### Order Management
- 📬 Order processing and shipment tracking
- 🔄 Status updates and history
- 📁 View and manage customer orders

### Analytics & Reports
- 📊 Sales reports and revenue stats
- 📈 Inventory and business metrics

### User Management
- 👥 Admin & customer account control
- 🔐 Role-based access control

## ⚙️ Technical Features
- Laravel 10+ framework
- Tailwind CSS for a clean, responsive UI
- Mobile-first and fully responsive design
- 🌙 Built-in dark mode
- 🔐 Secure authentication with social logins
- RESTful API endpoints
- 📁 File uploads (product images, documents, etc.)
- ⚡ Caching and job queue system

## 🛠 Getting Started

### ✅ Prerequisites
- PHP 8.2+
- Composer
- Node.js and npm
- MySQL or compatible database
- Web server (Apache/Nginx)

### 📦 Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd ecommerce-system
   ```

2. Install backend dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   ```

4. Set up your environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your .env with:
   - Database credentials
   - Mail settings
   - Social login credentials
   - Other environment-specific settings

6. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Start local servers:
   ```bash
   php artisan serve
   npm run dev
   ```

## 📄 Running Tests

Run all tests using:
```bash
php artisan test
```

## 📁 Project Structure

- **app/** — Business logic
  - Http/Controllers/ — Application controllers
  - Models/ — Eloquent models
  - Services/ — Custom business logic
  - Middleware/ — HTTP middleware

- **resources/** — Frontend layer
  - views/ — Blade templates
  - js/, css/, lang/ — Assets and localization

- **routes/**
  - web.php — Web routes
  - api.php — API endpoints

- **database/**
  - migrations/ — Schema definitions
  - seeders/ — Sample data
  - factories/ — Test data generation

- **config/, public/, storage/, tests/** — Laravel default directories

## 🔐 Security Features
- Role-based access control
- Hashed password storage
- CSRF protection
- XSS and SQL injection prevention
- API token-based authentication

## 🤝 Contributing

We welcome contributions from the community!

1. Fork the repository
2. Create your branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Implement your changes
4. Run tests:
   ```bash
   php artisan test
   ```
5. Commit and push:
   ```bash
   git commit -m "Add: [short description]"
   git push origin feature/your-feature-name
   ```
6. Open a pull request on GitHub

## 🧩 Support

For help or questions:
- Submit an issue
- Contact project maintainers
- Refer to the official Laravel documentation

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
