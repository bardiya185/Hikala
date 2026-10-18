# راهنمای دیپلوی روی Railway

این ریپو یک مونورپو با دو سرویس است: `backend/` (Laravel 12 + PHP 8.3 + nginx) و `frontend/` (Next.js 15 standalone). هر دو داکر‌فایل و `railway.json` آماده دارند.

> **حالت فعال فعلی (پلن رایگان Railway): تک‌سرویس.**
> چون پلن Free فقط دو resource اجازه می‌دهد (MySQL + volume + سرویس برنامه)، یک `Dockerfile` در **ریشه ریپو** هر دو اپ را در یک کانتینر می‌سازد: nginx روی `$PORT`، مسیرهای `/api|/sanctum|/storage|/up|/docs|/oauth` → Laravel (php-fpm) و بقیه → Next.js standalone روی پورت داخلی 3000 (supervisord). سرویس `backend` با Root Directory خالی از همان داکرفایل ریشه بیلد می‌شود و دامنه‌اش هم API و هم سایت است.
> متغیرهای بیلد: `NEXT_PUBLIC_BASE_URL=<دامنه سرویس>` و `NEXT_PUBLIC_NESHAN_API_KEY` (Railway این‌ها را به‌عنوان ARG به داکر می‌دهد). برای fetchهای سمت‌سرور، entrypoint به‌صورت خودکار `SERVER_BASE_URL=http://127.0.0.1:$PORT` ست می‌کند (قابل override).
> اگر بعداً پلن Hobby گرفتید، می‌توانید با همان مراحل بخش‌های ۲ و ۳ هر اپ را سرویس جدا کرد.

## ۱) ساخت پروژه و دیتابیس
1. در Railway یک Project بسازید.
2. داخل پروژه: **New → Database → MySQL** (پلاگین رسمی MySQL).
   - نام دیتابیس/یوزر/پسورد به‌صورت خودکار به‌عنوان متغیرهای `MYSQL_DATABASE`، `MYSQL_USER`، `MYSQL_PASSWORD`، `MYSQL_HOST`، `MYSQL_PORT` در دسترس‌اند.

## ۲) سرویس Backend
1. **New → Service → Deploy from GitHub repo** (یا `railway up` با CLI).
2. در تنظیمات سرویس: **Root Directory = `backend`**. بیلد به‌صورت خودکار از `backend/Dockerfile` استفاده می‌کند.
3. متغیرها (Service → Variables):

```
APP_NAME=Hikala
APP_ENV=production
APP_DEBUG=false
APP_URL=<domain-backend>      # بعد از Generate Domain پر کنید
APP_KEY=                      # با artisan key:generate بسازید: php artisan key:generate --show
DB_CONNECTION=mysql
DB_HOST=${{mysql.INTERNAL_HOSTNAME}}   # یا آدرس private شبکه Railway
DB_PORT=${{mysql.PORT}}
DB_DATABASE=${{mysql.DATABASE}}
DB_USERNAME=${{mysql.USER}}
DB_PASSWORD=${{mysql.PASSWORD}}
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
RUN_MIGRATIONS=true           # اختیاری، پیش‌فرض true
SEED_DATABASE=true            # برای دمو دیتای کامل (محصولات/دسته/عکس‌ها)
```

> Railway به‌طور خودکار متغیرهای `MYSQL_*` را به سرویس‌های هم‌شبکه تزریق می‌کند؛ می‌توانید مستقیم از همان‌ها استفاده کنید.

4. **Networking → Generate Domain** → آدرس `https://...up.railway.app` بگیرید و در `APP_URL` بگذارید.
5. Health Check خودکار: مسیر `/up` (در `railway.json` تنظیم شده).
6. کانتینر هنگام بوت: منتور DB → `migrate --force` → (اختیاری) `db:seed` → `storage:link` → کش کانفیگ/روت.

### نکته Storage (تصاویر آپلودی)
فایل‌های آپلودی ادمین در `storage/app/public` داخل کانتینر است و با هر ری‌دیپلوی پاک می‌شود. برای ماندگاری، یک **Volume** به مسیر `/var/www/html/storage/app/public` وصل کنید. (تصاویر دمو از seed می‌آیند و مشکلی ندارند.)

## ۳) سرویس Frontend
1. **New → Service → همان ریپو**، با **Root Directory = `frontend`**.
2. Build Args (Service → Settings → Docker Build Arguments):

```
NEXT_PUBLIC_BASE_URL = <domain-backend از مرحله قبل>
NEXT_PUBLIC_NESHAN_API_KEY = <کلید نشون>
```

> این مقادیر زمان بیلد در باندل کلاینت bake می‌شوند؛ اگر دامنه بک‌اند عوض شد، حتماً frontend را **Redeploy** کنید.

3. متغیرها: فقط `PORT=3000` (پیش‌فرض image است).
4. **Generate Domain** → این آدرس، سایت نهایی شماست.

## ۴) ترتیب درست دیپلوی
1. MySQL را بسازید و بالا بیاید.
2. Backend را دیپلوی + دامنه بگیرید + `APP_URL` را ست و Redeploy کنید.
3. با `SEED_DATABASE=true` یک‌بار seed را اجرا کنید (لاگ `Imported product_images dump` را ببینید).
4. Frontend را با Build Arg دامنه بک‌اند دیپلوی کنید.

## ۵) نکات امنیتی / رفع‌شده
- کد OTP دیگر در پاسخ HTTP برنمی‌گردد مگر `APP_DEBUG=true`؛ در پروداکشن کد را از **Railway → Backend → Deployments → View Logs** بخوانید (برای لاگین دموی ادمین). برای محیط واقعی باید سرویس پیامک وصل شود.
- `backend/.env` در گیت ترک شده است — قبل از پوش روی گیت‌هاب، با `git rm --cached backend/.env` از ترک خارجش کنید (فایل روی دیسک می‌ماند). مقدار `APP_KEY` فعلی را هم در Railway ست کنید.
- `trustProxies` روی `*` تنظیم شده تا IP/URL پشت پروکسی Railway درست باشد.

## ۶) دیتابیس لوکال
- `.env` بک‌اند الان روی `DB_DATABASE=hikala_fresh` (دیتابیس تازه‌مایگریت‌شده با seed کامل) است. دیتابیس قدیمی `hikala` دست‌نخورده باقی مانده.
- اجرای لوکال: `php artisan serve` داخل `backend/` و `pnpm dev` یا `pnpm build && node .next/standalone/server.js` داخل `frontend/`.
