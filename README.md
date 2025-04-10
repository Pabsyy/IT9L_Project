<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About the Project

This is an Admin Panel built using the Laravel framework. It provides a robust and user-friendly interface for managing inventory, orders, suppliers, and analytics. The project is designed to streamline administrative tasks and improve operational efficiency.

### Features

- **Inventory Management**: Add, edit, view, and delete products with details like name, SKU, category, quantity, price, and description.
- **Order Management**: Manage customer orders and track their statuses.
- **Supplier Management**: Add and contact suppliers directly from the admin panel.
- **Analytics Dashboard**: View key metrics and insights about the business.
- **Dark Mode Support**: Toggle between light and dark themes for better usability.
- **Responsive Design**: Optimized for both desktop and mobile devices.

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- A database (e.g., MySQL)

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd Admin Panel
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Set up the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure the `.env` file with your database and other settings.

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server:
   ```bash
   php artisan serve
   npm run dev
   ```

### Running Tests

To run the test suite:
```bash
php artisan test
```

## Project Structure

The project follows the standard Laravel structure with some additional customizations:

- **`app/`**: Contains the core application logic, including models, controllers, and middleware.
- **`resources/views/`**: Contains Blade templates for the frontend, including the inventory management UI.
- **`resources/js/`**: Contains JavaScript files for interactivity, such as dark mode toggling and modals.
- **`resources/css/`**: Contains Tailwind CSS configurations and custom styles.
- **`routes/web.php`**: Defines the web routes for the application, including inventory, orders, and suppliers.
- **`database/migrations/`**: Contains migration files for setting up the database schema.
- **`config/`**: Contains configuration files for caching, database, and other services.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Contributing

We welcome contributions to this project! Here's how you can contribute:

1. Fork the repository.
2. Create a new branch for your feature or bug fix:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes and commit them:
   ```bash
   git commit -m "Add your commit message here"
   ```
4. Push your changes to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```
5. Open a pull request on the main repository.

Please ensure your code adheres to the project's coding standards and includes tests where applicable.

## Support

If you encounter any issues or have questions, feel free to open an issue in the repository or contact the maintainers.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
