# PixelCraftsLab — Laravel Locked-Design Build

Laravel owns the application route/controller layer. The exported Claude Design
frontend stays byte-for-byte unchanged so the design and animations do not drift.

## Run

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan optimize:clear
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Verify

```bash
python3 scripts/verify_locked_design.py
php artisan test --filter=PixelCraftsLabSiteTest
```

## Important

Do not rename `public/support.js` or the `public/assets/` folder. The original
design references them with relative URLs.
