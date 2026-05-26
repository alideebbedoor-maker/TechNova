TechNova NewsRoom

TechNova NewsRoom هو نظام داخلي لإدارة ونشر الأخبار داخل الشركة، مبني باستخدام Laravel، ويهدف لتوفير بيئة مرنة وقابلة للتوسع لإدارة المحتوى، مع أداء عالي وتنظيم واضح للكود.

Setup (تشغيل المشروع)

- git clone repo
- composer install
- cp .env.example .env
- php artisan key:generate
- ضبط إعدادات قاعدة البيانات
- php artisan migrate --seed
- php artisan serve

لتشغيل العمليات الخلفية:

- php artisan queue:work
- php artisan horizon (اختياري)

ما الذي يقدمه النظام

- إنشاء وإدارة الأخبار من قبل الكتّاب
- صلاحيات واضحة (Admin / Writer)
- نظام إشعارات مختلف حسب نوع المستخدم
- حماية API + تتبع الطلبات
- أداء عالي باستخدام Cache

ماذا استخدمنا (Architecture & Tools)

- Service Layer: لفصل الـ business logic عن Controllers
- Repository Pattern: لعزل مصدر البيانات
- Service Container: لحقن الاعتمادات (Dependency Injection)
- Eloquent Relationships:
  - One-to-One (User - Profile)
  - One-to-Many (User - Articles)
  - Polymorphic (Comments, Attachments)
  - Many-to-Many (Tags)
- Form Requests: للتحقق من البيانات + الصلاحيات
- Middleware: للحماية و logging
- Events & Listeners: لتنفيذ العمليات التلقائية
- Queues : للعمليات الخلفية (مثل الإشعارات)
-  Caching: لتحسين الأداء وتقليل الضغط على قاعدة البيانات

الفكرة المعمارية

النظام مبني بحيث أي تغيير مستقبلي (مثل تغيير قاعدة البيانات أو إضافة Features جديدة) لا يؤثر على باقي أجزاء النظام، وذلك بفضل فصل الطبقات واستخدام Design Patterns.