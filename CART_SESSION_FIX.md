# حل مشكلة Cart على السيرفر

## المشكلة
المنتجات تظهر في الـ cart على اللوكال لكن لا تظهر على السيرفر.

## الأسباب المحتملة

### 1. Session Driver
- **اللوكال**: قد يستخدم `file` أو `database`
- **السيرفر**: قد يستخدم `database` لكن الجدول غير موجود أو الـ permissions غير صحيحة

**الحل**: تأكد من:
```bash
# إنشاء جدول sessions إذا كان driver = database
php artisan session:table
php artisan migrate
```

### 2. Session Permissions
إذا كان driver = `file`:
```bash
# تأكد من permissions
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions
```

### 3. Session Domain/Cookie Settings
في `.env` على السيرفر:
```env
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true  # إذا كان HTTPS
SESSION_SAME_SITE=lax
```

### 4. Cache Issues
قد يكون هناك cache قديم:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### 5. Session Lifetime
تأكد من `SESSION_LIFETIME` في `.env`:
```env
SESSION_LIFETIME=120
```

## الحلول المضافة

### 1. Cache Controller
تم إضافة `CacheController` مع routes:
- `GET /admin/cache/clear` - مسح جميع الـ cache (من المتصفح)
- `POST /admin/cache/clear` - مسح جميع الـ cache (API)
- `GET /admin/cache/optimize` - تحسين التطبيق (من المتصفح)
- `POST /admin/cache/optimize` - تحسين التطبيق (API)

**الاستخدام**:
- افتح المتصفح واذهب إلى: `https://yourdomain.com/admin/cache/clear`
- أو استخدم curl: `curl -X POST https://yourdomain.com/admin/cache/clear`

### 2. تحسينات CartController
- إضافة `session()->save()` بعد كل تعديل على الـ cart
- إضافة logging في debug mode
- التحقق من بنية البيانات قبل المعالجة

## خطوات التشخيص

1. **تحقق من Session Driver**:
```bash
php artisan tinker
>>> config('session.driver')
```

2. **تحقق من Session Data**:
```bash
# في CartController تم إضافة logging
# تحقق من storage/logs/laravel.log
```

3. **تحقق من Permissions**:
```bash
ls -la storage/framework/sessions
```

4. **Clear Cache**:
```bash
# استخدام الـ route الجديد
POST /admin/cache/clear
# أو من terminal
php artisan optimize:clear
```

## إعدادات .env الموصى بها للسيرفر

```env
APP_ENV=production
APP_DEBUG=false

SESSION_DRIVER=database  # أو file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true  # إذا كان HTTPS
SESSION_SAME_SITE=lax

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## ملاحظات مهمة

1. **Session Driver = Database**: 
   - تأكد من وجود جدول `sessions` في قاعدة البيانات
   - تأكد من أن الـ migrations تمت بنجاح

2. **Session Driver = File**:
   - تأكد من permissions على `storage/framework/sessions`
   - تأكد من وجود مساحة كافية على القرص

3. **HTTPS**:
   - إذا كان السيرفر يستخدم HTTPS، تأكد من `SESSION_SECURE_COOKIE=true`

4. **Domain**:
   - تأكد من أن `SESSION_DOMAIN` مطابق لـ domain السيرفر

