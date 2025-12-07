@echo off
REM Gear-In E-Commerce Setup Script for Windows
REM This script automates the setup process for the application

echo.
echo ====================================
echo   Gear-In E-Commerce Setup Script
echo ====================================
echo.

REM Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP is not installed. Please install PHP 8.2 or higher.
    pause
    exit /b 1
)
echo [OK] PHP is installed
php -v
echo.

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed. Please install Composer.
    pause
    exit /b 1
)
echo [OK] Composer is installed
echo.

REM Check if Node.js is installed
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Node.js is not installed. Please install Node.js 18.x or higher.
    pause
    exit /b 1
)
echo [OK] Node.js is installed
node -v
echo.

REM Check if npm is installed
where npm >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] npm is not installed. Please install npm.
    pause
    exit /b 1
)
echo [OK] npm is installed
echo.

REM Step 1: Install PHP dependencies
echo [1/7] Installing PHP dependencies...
call composer install --no-interaction
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install PHP dependencies
    pause
    exit /b 1
)
echo [OK] PHP dependencies installed
echo.

REM Step 2: Install Node.js dependencies
echo [2/7] Installing Node.js dependencies...
call npm install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install Node.js dependencies
    pause
    exit /b 1
)
echo [OK] Node.js dependencies installed
echo.

REM Step 3: Setup environment
if not exist .env (
    echo [3/7] Setting up environment file...
    copy .env.example .env >nul
    call php artisan key:generate
    echo [OK] Environment file created
) else (
    echo [SKIP] .env file already exists
)
echo.

REM Step 4: Create database file
if not exist database\database.sqlite (
    echo [4/7] Creating database file...
    type nul > database\database.sqlite
    echo [OK] Database file created
) else (
    echo [SKIP] Database file already exists
)
echo.

REM Step 5: Run migrations
echo [5/7] Running database migrations...
call php artisan migrate --force
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to run migrations
    pause
    exit /b 1
)
echo [OK] Migrations completed
echo.

REM Step 6: Seed database
echo [6/7] Seeding database...
call php artisan db:seed --force
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to seed database
    pause
    exit /b 1
)
echo [OK] Database seeded
echo.

REM Step 7: Build assets
echo [7/7] Building frontend assets...
call npm run build
if %ERRORLEVEL% NEQ 0 (
    echo [WARNING] Failed to build assets. You may need to run 'npm run build' manually.
) else (
    echo [OK] Assets built
)
echo.

REM Clear caches
echo Clearing caches...
call php artisan optimize:clear
echo [OK] Caches cleared
echo.

echo ====================================
echo   Setup completed successfully!
echo ====================================
echo.
echo Default Login Accounts:
echo   Admin:    admin@gear-in.dev / password
echo   Customer: customer@gear-in.dev / password
echo.
echo To start the server, run:
echo   php artisan serve
echo.
echo Then open: http://127.0.0.1:8000
echo.
pause

