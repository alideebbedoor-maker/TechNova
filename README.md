# NewsRoom - Internal News Platform

هذا المشروع هو منصة أخبار داخلية متكاملة لشركة **TechNova**، تهدف إلى إدارة المحتوى الإخباري للموظفين بفعالية. تم بناء النظام ليكون قابلاً للتوسع (Scalable) ومبنياً وفق أفضل الممارسات البرمجية في Laravel.

## 🏗 البنية المعمارية (Architecture)
تم اعتماد مبادئ **Clean Architecture** لضمان استقلالية الكود وقابليته للاختبار:
- **Service Container:** ربط منطق العمل (Business Logic) بعيداً عن الـ Controllers لضمان مرونة استبدال مصادر البيانات.
- **Polymorphic Relations:** هيكلة مرنة تدعم التعليقات، المرفقات، والوسوم (Tags) عبر علاقات متعددة الأطراف، مما يسهل إضافة أنواع محتوى جديدة مستقبلاً.
- **Contextual Binding:** توجيه الإشعارات بذكاء (Database للـ Admin، و Email للـ Writers) بناءً على سياق المستخدم.

## 🚀 المميزات التقنية
- **Caching & Performance:** استخدام Redis لتخزين الإحصائيات وتقليل الضغط على قاعدة البيانات في أوقات الذروة.
- **Background Jobs:** إدارة المهام الثقيلة (مثل الإشعارات والتقارير الأسبوعية) عبر Queues و Horizon لضمان عدم تأثر سرعة استجابة النظام.
- **API Versioning:** دعم إصدارين (V1, V2) لضمان توافق النظام مع تطبيقات الموبايل والويب دون التأثير على التوافقية العكسية.
- **API Security:** تطبيق Middleware للحماية، Rate Limiting، وتسجيل الطلبات (Request Logging) بعد اكتمال التنفيذ.

## 🗄 الـ Entities والعلاقات
- **User:** نظام أدوار (Admin, Writer, Reader).
- **Article:** وحدة المحتوى الأساسية (مرتبطة بكاتب).
- **Comment, Attachment, Tag:** كيانات مرتبطة بمحتوى النظام عبر علاقات Polymorphic.

## ⚙️ خطوات التشغيل (Setup Steps)

## ⚙️ خطوات التشغيل (Setup Steps)

1. **استنساخ المشروع:**
   ```bash
   git clone [https://github.com/alideebbedoor-maker/TechNova.git](https://github.com/alideebbedoor-maker/TechNova.git)
   cd TechNova
ضبط البيئة:
نسخ ملف .env.example إلى .env.

ضبط إعدادات DB_CONNECTION و REDIS_HOST.

تجهيز قاعدة البيانات:


php artisan migrate --seed
تشغيل الخدمات:

php artisan serve
php artisan queue:work
🧪 الاختبارات (Testing)
يحتوي المشروع على تغطية اختبارية كاملة تشمل:

Feature Tests: تغطي صلاحيات الوصول، عمليات الـ CRUD، والـ API Structure.

Unit Tests: للتحقق من منطق الإشعارات، الـ Jobs، والـ Emails.

النتيجة: تمر جميع الاختبارات (31 اختباراً) بنجاح لضمان استقرار النظام.

💡 لماذا هذا التوجه؟
القرار المعماري: اخترت فصل الـ Logic في الخدمات (Services) لتسهيل الصيانة والتحكم.

الأداء: الاعتماد على Redis و Queues هو الحل الأمثل لمواجهة التحديات أثناء فترات الذروة (300 مستخدم في آن واحد).

## 🧪 الاختبارات (Testing):

"اعتمدت استراتيجية اختبار شاملة (Testing Strategy) تضمن استقرار النظام عند كل إضافة برمجية (Regression Testing)، مع التركيز على تغطية حالات الاستخدام الحقيقية للمستخدمين والسيناريوهات الأمنية."


