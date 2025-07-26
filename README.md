پروژه لاراول – تمرین 
این پروژه شامل پیاده‌سازی سه تمرین اصلی در بستر فریمورک Laravel 12 است که شامل CRUD، API، تست‌ها، بانک Mock، مدیریت داده‌ها و کار با API خارجی می‌باشد. کدها تمیز، ماژولار و با استفاده از Traits، Factories و Seeders نوشته شده‌اند تا از کد تکراری جلوگیری شود.
ویژگی‌های کلیدی پروژه

پیاده‌سازی کامل CRUD برای جداول Users، Licenses و License Requests.

ساختار دیتابیس (ERD)
جداول:

users: شامل اطلاعات کاربر (کد ملی، نام، نام خانوادگی، تاریخ تولد، جنسیت، موبایل، آدرس و ...).
licenses: شامل اطلاعات مجوز (کد، عنوان، توضیح، سازمان صادرکننده، مدت اعتبار، هزینه صدور و ...).
license_requests: ثبت درخواست مجوز توسط کاربران، همراه با آدرس کسب‌وکار، وضعیت (pending/approved/rejected) و تاریخ‌ها.

ارتباط‌ها:

هر کاربر (users) می‌تواند چندین درخواست مجوز (license_requests) داشته باشد. (One-to-Many)
هر مجوز (licenses) می‌تواند در چندین درخواست (license_requests) استفاده شود. (One-to-Many)

ERD به‌صورت فایل PDF ضمیمه شده است.
تمرین اول (CRUD + API دریافت درخواست‌های تایید شده)
Endpoint: /api/license_requests/approved_requests
ورودی:

national_code و پارامتر page_size برای صفحه‌بندی.

خروجی JSON شامل:

request_id
request_code
license_code
license_title
requested_at
requested_at_jalali
business_postal_code
business_address
expires_at
expires_at_jalali

تاریخ‌ها به دو فرمت میلادی و جلالی (requested_at, requested_at_jalali).
وضعیت‌ها:

200 برای موفقیت.
400 برای خطای ولیدیشن.
404 در صورت نبود کاربر یا داده.

تمرین دوم (فروشندگان کیبورد)
Endpoint: /api/keyboard_sellers?city={CITY}
ویژگی‌ها:

اعتبارسنجی شهر (فقط Zanjan, Tehran, Ardabil, Isfahan, Qazvin مجازند).
هزینه ارسال = صفر اگر city کاربر با city فروشنده یکسان باشد.
محاسبه میانگین قیمت و یافتن فروشنده با کمترین total_cost.
ذخیره گزارش هر فراخوانی در فایل storage/app/keyboard_sellers_log.jsonl (append mode).
کش نتایج API خارجی برای یک ساعت.

تمرین سوم (سیستم بانک Mock)
کلاس Bank به‌صورت abstract ساخته شده و شامل متدهای:

getToken() (قابل override در کلاس فرزند).
getLastThreeTransactions() (final – قابل override نیست).

پیاده‌سازی بانک‌ها:

MellatBank – خواندن توکن و تراکنش‌ها از BankMockController.
SamanBank – مشابه بالا.

Endpoint تست: /api/bank_test (برگشت توکن و تراکنش ۳ رکورد آخر هر بانک).
تست‌ها (Feature Tests)
تست‌ها در مسیر tests/Feature/CompleteProjectTest.php قرار دارند.
تست‌های نوشته‌شده:

دریافت درخواست‌های تایید شده کاربر (موفقیت، خطای کد ملی، کاربر ناموجود).
دریافت فروشندگان کیبورد (شهر معتبر و نامعتبر).
بانک Mock (بررسی دریافت توکن و تراکنش‌ها).

اجرا:
php artisan test

راه‌اندازی پروژه
کلون کردن پروژه:
git clone <repo-url>
cd <project>

نصب وابستگی‌ها:
composer install

نصب پکیج Morilog Jalali:
composer require morilog/jalali

تنظیم فایل محیط:
cp .env.example .env
php artisan key:generate

اجرای مایگریشن و داده تست:
php artisan migrate:fresh --seed

اجرای سرور:
php artisan serve

امتیازهای اضافه

استفاده از Traits برای استانداردسازی پاسخ‌ها و مدیریت عملیات (SoftDelete, restore, destroy).
Custom Cast برای تاریخ جلالی/میلادی.
Factory + Seeder برای تولید داده تست.
Cache و Storage برای بهینه‌سازی پاسخ فروشندگان کیبورد.
Rules اختصاصی برای اعتبار سنجی کد ملی و موبایل.
تست‌های جامع Feature با PHPUnit.
