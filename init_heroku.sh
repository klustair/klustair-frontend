echo "======= Generate Laravel App Key"
php artisan key:generate

echo "======= Database Migrations"
php artisan migrate

echo "======= Create Admin User"
php artisan klustair:init createAdmin

echo "======= Import CWE's"
php artisan klustair:importcwe 4.3