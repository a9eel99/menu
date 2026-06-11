# 🍽️ QR Menu System - نظام المنيو الرقمي

نظام إدارة قوائم المطاعم الرقمية باستخدام QR Code - يدعم اللغة العربية والإنجليزية

![Laravel](https://img.shields.io/badge/Laravel-11-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 📋 المميزات

### للمطاعم
- ✅ إنشاء منيو رقمي احترافي
- ✅ دعم كامل للغة العربية والإنجليزية
- ✅ إدارة الأقسام والأصناف بسهولة
- ✅ رفع صور للأصناف
- ✅ تحديد الأسعار والخصومات
- ✅ علامات للأصناف (حار، نباتي، جديد، الأكثر مبيعاً)
- ✅ توليد QR Code تلقائي
- ✅ تخصيص الألوان والشعار
- ✅ روابط التواصل الاجتماعي
- ✅ ساعات العمل

### للفروع
- ✅ إنشاء فروع متعددة لكل مطعم
- ✅ كل فرع له منيو مستقل أو موروث
- ✅ تخصيص الأسعار لكل فرع
- ✅ QR Code منفصل لكل فرع

### للإدارة
- ✅ لوحة تحكم شاملة
- ✅ نظام صلاحيات (مدير / موظف)
- ✅ إحصائيات وتقارير
- ✅ نسخ القائمة بين المطاعم
- ✅ ترتيب الأقسام والأصناف بالسحب والإفلات

---

## 🛠️ متطلبات التشغيل

- PHP >= 8.2
- MySQL >= 5.7 أو MariaDB >= 10.3
- Composer
- Node.js >= 18 (اختياري للـ assets)
- إضافات PHP المطلوبة:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD أو Imagick

---

## ⚡ التثبيت السريع

### 1. استنساخ المشروع
```bash
git clone https://github.com/your-username/qr-menu.git
cd qr-menu
```

### 2. تثبيت المتطلبات
```bash
composer install
```

### 3. إعداد ملف البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. إعداد قاعدة البيانات
عدّل ملف `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qr_menu
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. تشغيل الهجرات والبيانات الأولية
```bash
php artisan migrate --seed
```

### 6. ربط التخزين
```bash
php artisan storage:link
```

### 7. تشغيل السيرفر
```bash
php artisan serve
```

---

## 🔑 بيانات الدخول الافتراضية

### مدير النظام
- **البريد:** admin@demo.com
- **كلمة المرور:** password

### موظف (اختياري)
- **البريد:** staff@demo.com
- **كلمة المرور:** password

---

## 📁 هيكل المشروع

```
qr-menu/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # كنترولرات لوحة التحكم
│   │   ├── AuthController  # تسجيل الدخول
│   │   └── MenuController  # عرض المنيو للزوار
│   └── Models/             # موديلات قاعدة البيانات
├── database/
│   ├── migrations/         # هجرات قاعدة البيانات
│   └── seeders/           # بيانات أولية
├── resources/views/
│   ├── admin/             # صفحات لوحة التحكم
│   ├── auth/              # صفحات تسجيل الدخول
│   ├── menu/              # صفحة المنيو للزوار
│   └── layouts/           # القوالب الأساسية
├── public/
│   └── storage/           # الملفات المرفوعة
└── routes/
    └── web.php            # مسارات التطبيق
```

---

## 🗄️ قاعدة البيانات

### الجداول الرئيسية
| الجدول | الوصف |
|--------|-------|
| users | المستخدمين |
| roles | الأدوار (admin, staff) |
| restaurants | المطاعم والفروع |
| categories | أقسام القائمة |
| menu_items | الأصناف |
| tags | العلامات (حار، جديد...) |
| social_links | روابط التواصل |

---

## 🎨 تخصيص المظهر

### تغيير الألوان
من لوحة التحكم > إعدادات المطعم:
- اللون الأساسي (Primary Color)
- اللون الثانوي (Secondary Color)

### تغيير الشعار
ارفع صورة الشعار من إعدادات المطعم (يُفضل PNG شفاف)

---

## 🔧 أوامر مفيدة

```bash
# مسح الكاش
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# أو كلهم مرة واحدة
php artisan optimize:clear

# إعادة توليد الـ autoload
composer dump-autoload
```

---

## 🌐 النشر على السيرفر

### متطلبات الاستضافة
- PHP 8.2+
- MySQL
- SSL Certificate (مُوصى به)
- دعم mod_rewrite

### خطوات النشر
1. ارفع الملفات للسيرفر
2. اضبط Document Root على مجلد `public`
3. عدّل `.env` بإعدادات السيرفر
4. شغّل `php artisan migrate --seed`
5. اضبط صلاحيات مجلد `storage` (755 أو 775)

### إعدادات .htaccess
الملف موجود في `public/.htaccess` جاهز للاستخدام

---

## 📱 روابط المنيو

- **المنيو:** `https://yourdomain.com/menu/{slug}`
- **QR Code:** `https://yourdomain.com/admin/restaurants/{id}/qrcode`

---

## 🔒 الأمان

- ✅ حماية CSRF على جميع الفورمات
- ✅ تشفير كلمات المرور بـ bcrypt
- ✅ التحقق من الصلاحيات
- ✅ حماية من SQL Injection
- ✅ حماية من XSS

---

## 📄 الرخصة

هذا المشروع مرخص تحت رخصة MIT - انظر ملف [LICENSE](LICENSE) للتفاصيل.

---

## 🤝 الدعم الفني

للدعم الفني أو الاستفسارات:
- 📧 البريد: support@example.com
- 💬 واتساب: +966xxxxxxxxx

---

## 📸 صور من النظام

### لوحة التحكم
![Dashboard](screenshots/dashboard.png)

### صفحة المنيو
![Menu](screenshots/menu.png)

### QR Code
![QR Code](screenshots/qrcode.png)

---

**صُنع بـ ❤️ باستخدام Laravel**
