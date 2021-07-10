echo "======= Database Migrations"
php artisan migrate

echo "======= Import CWE's"
php artisan klustair:importcwe 4.3