---
description: How to setup the application after fresh clone or migration
---

# Application Setup Workflow

## After Fresh Clone
// turbo-all

1. Install dependencies
```bash
composer install
npm install
```

2. Copy environment file
```bash
cp .env.example .env
```

3. Generate application key
```bash
php artisan key:generate
```

4. Configure database in `.env` file (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

5. Run migrations and seeders
```bash
php artisan migrate --seed
```

6. Create storage symbolic link
```bash
php artisan storage:link
```

7. Build frontend assets
```bash
npm run build
```

8. Start development server
```bash
php artisan serve
```

---

## After `migrate:fresh --seed`

The storage symbolic link may be broken after fresh migration. Run:
```bash
# If public/storage is a regular directory, remove it first
Remove-Item -Path "public\storage" -Recurse -Force
php artisan storage:link
```

---

## Troubleshooting

### 403 Forbidden on uploaded files (avatars, images)
**Cause**: `public/storage` is a regular directory instead of symbolic link

**Solution**:
```bash
Remove-Item -Path "public\storage" -Recurse -Force
php artisan storage:link
```

### Avatar not updating after upload
**Cause**: Browser cache or session not refreshed

**Solution**: 
- Hard refresh with Ctrl+Shift+R
- Clear cache: `php artisan cache:clear`
