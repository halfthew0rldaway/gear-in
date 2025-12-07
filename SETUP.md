# Quick Setup Guide

## For Lecturers / Quick Testing

### Option 1: Automated Setup (Recommended)

If you're on Linux or Mac, use the automated setup script:

```bash
./setup.sh
```

This script will:
- ✅ Check prerequisites
- ✅ Install all dependencies
- ✅ Setup environment
- ✅ Create database
- ✅ Run migrations
- ✅ Seed database
- ✅ Build assets

After the script completes, just run:
```bash
php artisan serve
```

Then open: `http://127.0.0.1:8000`

### Option 2: Manual Setup

Follow the detailed instructions in [README.md](README.md)

### Quick Manual Steps

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Create database
touch database/database.sqlite  # Linux/Mac
# OR
type nul > database\database.sqlite  # Windows

# 4. Run migrations and seed
php artisan migrate
php artisan db:seed

# 5. Build assets
npm run build

# 6. Start server
php artisan serve
```

## Default Login Credentials

**Admin Account:**
- Email: `admin@gear-in.dev`
- Password: `password`

**Customer Account:**
- Email: `customer@gear-in.dev`
- Password: `password`

## Testing Checklist

- [ ] Homepage loads correctly
- [ ] Can browse products
- [ ] Can login as customer
- [ ] Can add products to cart
- [ ] Can complete checkout
- [ ] Can view orders
- [ ] Can login as admin
- [ ] Admin dashboard shows statistics
- [ ] Can manage products
- [ ] Can update order status

## Common Issues

**Issue:** "Database file does not exist"
- Solution: Make sure `database/database.sqlite` exists and has proper permissions

**Issue:** "Assets not loading"
- Solution: Run `npm run build`

**Issue:** "Session driver error"
- Solution: Check `.env` has `SESSION_DRIVER=file`

For more troubleshooting, see [README.md](README.md#troubleshooting)

