# Setting up Symfony PHP Project (macOS)

This guide will help you set up and run an existing Symfony PHP project on your macOS machine using MAMP for local development.

## Prerequisites

Before you begin, make sure you have the following installed:

- PHP (recommended version: 8.2)
- Composer
- MAMP

## Steps

1. **Install MAMP**:
   - Download and install MAMP from [mamp.info](https://www.mamp.info/en/). Ensure Apache and MySQL servers are running after installation.

2. **Install PHP**:
   - Ensure PHP is installed and added to your system's PATH environment variable. You can check this by running `php -v` in your terminal.

3. **Install Composer**:
   - Composer is a dependency manager for PHP. Follow the installation instructions at [getcomposer.org](https://getcomposer.org/) to install Composer globally on your system.

4. **Clone the Symfony project from Git**:
   - Clone the Symfony project repository from Git using the command `git clone <repository_url>`. Replace `<repository_url>` with the actual URL of your Git repository.

5. **Set up Symfony project**:
   - Navigate to the cloned project directory.

6. **Install dependencies**:
   - Install Composer dependencies by running `composer install`.

7. **Configure MySQL database**:
   - Start MAMP MySQL server and access phpMyAdmin at `http://localhost/phpmyadmin`.
   - Create a new database (e.g., `symfony_db`) with `utf8mb4_unicode_ci` collation.

8. **Configure Symfony `.env` file**:
   - In your Symfony project directory, locate the `.env` file.
   - Update the `DATABASE_URL` to match your MySQL database credentials:
     ```
     DATABASE_URL=mysql://db_user:db_password@localhost:3306/app?serverVersion=8.0.3-MariaDB&charset=utf8mb4
     ```
     Replace `db_user`, `db_password`, and `app` with your MySQL username, password, and database name respectively.

9. **Run Symfony database migrations**:
   - Run migrations with `php bin/console doctrine:migrations:migrate`.

10. **Start the Symfony server**:
    - Start the Symfony development server with `symfony server:start`.
    - Open the provided local development server URL (usually `http://localhost:8000`) in your web browser to see your Symfony application running.

## Development

You can now start developing your Symfony application. Refer to the [Symfony documentation](https://symfony.com/doc/current/index.html) for more details on Symfony development.

## Additional Resources

- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [Composer Documentation](https://getcomposer.org/doc/)
- [MAMP Documentation](https://documentation.mamp.info/)
