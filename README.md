# Sahabat Peduli - overlay Laravel

composer create-project laravel/laravel sahabatpeduli
# salin isi folder ini ke dalam proyek (timpa routes/web.php dan DatabaseSeeder.php)
cd sahabatpeduli
php artisan migrate --seed
php artisan serve

## Midtrans (.env)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false
# Dashboard Midtrans > Settings > Payment > Notification URL: https://domainmu/midtrans/notification
