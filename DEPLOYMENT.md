# دليل رفع المشروع على Hostinger

## 1. شراء الاستضافة

1. ادخل [hostinger.com](https://hostinger.com)
2. اختر **Business Web Hosting** - 48 شهر
3. سجل الدومين المجاني
4. أكمل الدفع

---

## 2. تجهيز قاعدة البيانات

1. من لوحة Hostinger، اذهب إلى **Databases** > **MySQL Databases**
2. أنشئ قاعدة بيانات جديدة:
   - Database name: `qr_menu`
   - Username: (سيُنشأ تلقائياً)
   - Password: (اختر كلمة مرور قوية)
3. **احفظ البيانات** - ستحتاجها للـ .env

---

## 3. رفع الملفات

### الطريقة 1: عبر File Manager (أسهل)

1. على جهازك، احذف المجلدات التالية من المشروع:
   - `vendor/`
   - `node_modules/`
   - `.git/`

2. اضغط المشروع كـ ZIP

3. من Hostinger:
   - اذهب إلى **File Manager**
   - ادخل مجلد `public_html`
   - **احذف كل الملفات الموجودة**
   - ارفع ملف ZIP
   - فك الضغط

### الطريقة 2: عبر SSH (أسرع للتحديثات)

```bash
ssh -p 65002 u123456789@your-server.hostinger.com
cd public_html
git clone https://github.com/username/restaurant-menu.git .
```

---

## 4. إعداد الـ Environment

1. من File Manager، أعد تسمية `.env.production` إلى `.env`

2. عدّل الملف وضع البيانات الصحيحة:

```env
APP_URL=https://yourdomain.com

DB_DATABASE=u123456789_qr_menu
DB_USERNAME=u123456789_qruser
DB_PASSWORD=YourStrongPassword123!
```

---

## 5. تشغيل أوامر Laravel

### عبر SSH:

```bash
cd public_html

# تثبيت المكتبات
php composer.phar install --optimize-autoloader --no-dev

# توليد مفتاح التطبيق
php artisan key:generate

# تشغيل migrations
php artisan migrate --force

# إنشاء رابط التخزين
php artisan storage:link

# تنظيف وتحسين
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### عبر Hostinger Terminal (إذا لم يكن SSH متاحاً):

من لوحة Hostinger > **Advanced** > **SSH Access** > **Browser terminal**

---

## 6. إنشاء حساب المدير

عبر SSH أو Terminal:

```bash
php artisan tinker
```

ثم أدخل:

```php
use App\Models\User;
use App\Models\Role;

$admin = User::create([
    'name' => 'Admin',
    'email' => 'admin@yourdomain.com',
    'password' => bcrypt('YourSecurePassword123!'),
    'is_active' => true,
]);

$adminRole = Role::where('name', 'admin')->first();
$admin->roles()->attach($adminRole);

exit
```

---

## 7. ضبط الصلاحيات

تأكد من صلاحيات المجلدات:

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env
```

---

## 8. التحقق

1. افتح `https://yourdomain.com`
2. جرب تسجيل الدخول: `https://yourdomain.com/login`
3. تأكد من رفع الصور يعمل

---

## 9. إعداد SSL (تلقائي)

Hostinger يفعّل SSL تلقائياً، لكن تأكد من:
1. **SSL/TLS** > **Force HTTPS** مفعّل

---

## 10. إعداد البريد (اختياري)

من Hostinger:
1. **Emails** > **Email Accounts**
2. أنشئ `info@yourdomain.com`
3. حدّث بيانات MAIL في `.env`

---

## حل المشاكل الشائعة

### خطأ 500:
```bash
php artisan config:clear
php artisan cache:clear
chmod -R 755 storage
```

### الصور لا تظهر:
```bash
php artisan storage:link
```

### صفحة بيضاء:
- تأكد من `APP_DEBUG=false` في الإنتاج
- راجع `storage/logs/laravel.log`

---

## للتحديثات المستقبلية

```bash
# ارفع الملفات الجديدة ثم:
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
