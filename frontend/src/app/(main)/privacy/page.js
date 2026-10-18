import React from "react";
import Link from "next/link";
import {
  ShieldCheck,
  ArrowLeft,
  Lock,
  Cookie,
  UserCheck,
  FileText,
} from "lucide-react";

export const metadata = {
  title: "حریم خصوصی | هیکالا",
  description:
    "سیاست حریم خصوصی فروشگاه اینترنتی هیکالا: چه داده‌هایی جمع‌آوری می‌کنیم، چرا استفاده می‌شود و حقوق شما در قبال اطلاعاتتان.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  {
    id: "scope",
    title: "۱. دامنه این سیاست",
    anchorLabel: "دامنه و تعاریف",
    icon: FileText,
    intro:
      "حریم خصوصی شما برای ما ارزشمند است. این سیاست توضیح می‌دهد که هیکالا در مسیر خرید، ثبت‌نام و پشتیبانی، چه اطلاعاتی را دریافت و چگونه از آن‌ها نگهداری می‌کند.",
    clauses: [
      "این سیاست برای وب‌سایت، اپلیکیشن و خدمات پشتیبانی هیکالا اعمال می‌شود و خدمت به «کاربر» یعنی هر شخصی که از این مسیرها استفاده می‌کند.",
      "با ثبت‌نام یا ادامه خرید در هیکالا، شما با جمع‌آوری و پردازش اطلاعات خود بر اساس همین متن موافقت می‌کنید.",
      "در مواردی که قانون صریحاً الزام دیگری داشته باشد، حکم قانون بر این سیاست مقدم است.",
    ],
  },
  {
    id: "collected",
    title: "۲. اطلاعاتی که دریافت می‌کنیم",
    anchorLabel: "اطلاعات جمع‌آوری‌شده",
    icon: UserCheck,
    intro:
      "تنها داده‌هایی را دریافت می‌کنیم که برای اجرای سفارش و بهبود خدمات به آن‌ها نیاز داریم.",
    clauses: [
      "اطلاعات شناسایی: نام و نام خانوادگی، شماره موبایل، نشانی پستی، کد پستی و ایمیل.",
      "اطلاعات سفارش: سبد خرید، کالاها، روش ارسال، آدرس تحویل و وضعیت پرداخت (بدون ذخیره اطلاعات کارت بانکی).",
      "اطلاعات فنی: آدرس IP، نوع مرورگر و دستگاه، صفحه ارجاع‌دهنده و گزارش خطاهای فنی.",
      "اطلاعات محتوایی: دیدگاه‌ها، پرسش‌ها، فایل‌های پیوست‌شده و گفت‌وگوهای شما با پشتیبانی.",
    ],
  },
  {
    id: "usage",
    title: "۳. چگونگی استفاده از اطلاعات",
    anchorLabel: "کاربرد داده‌ها",
    icon: Lock,
    intro:
      "داده‌های شما فقط در چارچوب اهداف اعلام‌شده پردازش می‌شوند.",
    clauses: [
      "ثبت، پردازش و تحویل سفارش و اطلاع‌رسانی مراحل ارسال به شما.",
      "احراز هویت، پشتیبانی، پاسخ به پرسش‌ها و رسیدگی به درخواست‌های بازگشت کالا.",
      "پیشگیری از تخلف، سوءاستفاده و کلاهبرداری و حفظ امنیت حساب‌های کاربری.",
      "بهبود تجربه خرید و شخصی‌سازی پیشنهادها بر اساس علاقه‌مندی‌های شما؛ این بخش قابل غیرفعال‌سازی است.",
      "ارسال اطلاع‌رسانی بازاریابی تنها در صورتی که خودتان عضویت را پذیرفته باشید انجام می‌شود و در هر پیام امکان لغو عضویت وجود دارد.",
    ],
  },
  {
    id: "cookies",
    title: "۴. کوکی‌ها و فناوری‌های ردیابی",
    anchorLabel: "کوکی‌ها",
    icon: Cookie,
    intro:
      "هیکالا برای ورود به حساب، حفظ سبد خرید و سنجش عملکرد سایت از کوکی‌ها و ابزارهای تحلیل استفاده می‌کند.",
    clauses: [
      "کوکی‌های ضروری: برای ورود به حساب، سبد خرید و پرداخت غیرقابل حذف هستند.",
      "کوکی‌های تحلیلی: به ما کمک می‌کنند صفحات پرمخاطب و مراحل دشوار خرید را بشناسیم.",
      "با تنظیمات مرورگر خود می‌توانید کوکی‌ها را محدود یا حذف کنید؛ در این حالت ممکن است برخی قابلیت‌ها از کار بیفتد.",
      "اطلاعات موقعیت مکانی (استان، شهر و مختصات) تنها برای محاسبه هزینه و زمان ارسال و با اجازه شما دریافت می‌شود.",
    ],
  },
  {
    id: "sharing",
    title: "۵. اشتراک‌گذاری با اشخاص ثالث",
    anchorLabel: "اشتراک‌گذاری",
    icon: ShieldCheck,
    intro:
      "ما اطلاعات شما را نمی‌فروشیم. داده‌ها فقط در حد لازم با شرکت‌های همکار فنی به اشتراک گذاشته می‌شود.",
    clauses: [
      "شرکت‌های پستی و شرکت‌های حمل‌ونقل: برای تحویل سفارش (نام، نشانی، شماره تماس).",
      "درگاه‌های پرداخت بانکی: برای تکمیل تراکنش مالی؛ هیکالا اطلاعات کارت شما را ذخیره نمی‌کند.",
      "ارائه‌دهندگان خدمات ابری و پشتیبانی فنی: در چارچوب قرارداد محرمانگی و با دسترسی محدود.",
      "نهادهای قانونی: تنها در صورت الزام قانونی یا درخواست معتبر مراجع ذی‌صلاح.",
    ],
  },
  {
    id: "security",
    title: "۶. امنیت و مدت نگهداری",
    anchorLabel: "امنیت داده‌ها",
    icon: Lock,
    intro:
      "از تمهیدات فنی و سازمانی مناسب برای حفاظت از اطلاعات استفاده می‌کنیم.",
    clauses: [
      "ارتباط میان مرورگر شما و سرورهای هیکالا رمزنگاری‌شده (HTTPS) برقرار می‌شود.",
      "دسترسی کارکنان به اطلاعات کاربران، محدود، نقش‌محور و قابل ثبت است.",
      "اطلاعات حساب تا زمان فعال بودن آن و سوابق سفارش به اندازه الزام‌های قانونی مالیاتی نگهداری می‌شود.",
      "در صورت بروز نشت اطلاعات، طبق قانون با شما اطلاع‌رسانی خواهد شد.",
    ],
  },
  {
    id: "rights",
    title: "۷. حقوق شما",
    anchorLabel: "حقوق کاربر",
    icon: UserCheck,
    intro:
      "شما بر اطلاعات خود کنترل دارید و می‌توانید هر زمان درخواست خود را ثبت کنید.",
    clauses: [
      "دریافت نسخه‌ای از اطلاعات شخصی خود و مشاهده سوابق سفارش‌ها در حساب کاربری.",
      "اصلاح یا به‌روزرسانی اطلاعات نادرست یا ناقص.",
      "درخواست حذف حساب و داده‌ها؛ اطلاعاتی که قانوناً باید نگهداری شود مستثنی است.",
      "اعتراض به پردازش داده‌ها و لغو دریافت پیام‌های تبلیغاتی از همان لینک داخل پیام.",
      "برای اجرای این حقوق به بخش «اطلاعات حساب» یا پشتیبانی هیکالا مراجعه کنید.",
    ],
  },
  {
    id: "children",
    title: "۸. حریم خصوصی نوجوانان",
    anchorLabel: "نوجوانان",
    icon: ShieldCheck,
    intro:
      "خرید در هیکالا نیازمند حساب کاربری معتبر با شماره موبایل است.",
    clauses: [
      "ثبت‌نام برای افراد زیر ۱۸ سال تنها با اجازه و نظارت والدین یا قیم انجام شود.",
      "در صورت دریافت اطلاعات از نوجوانی بدون اجازه سرپرست، با تماس شما این اطلاعات حذف می‌شود.",
    ],
  },
  {
    id: "changes",
    title: "۹. تغییرات این سیاست",
    anchorLabel: "تغییرات",
    icon: FileText,
    intro:
      "این متن ممکن است با رشد خدمات یا تغییر قوانین به‌روزرسانی شود.",
    clauses: [
      "نسخه جدید در همین صفحه منتشر و تاریخ به‌روزرسانی در بالای صفحه درج می‌شود.",
      "تغییرات مهم از طریق ایمیل یا پیامک به اطلاع کاربران می‌رسد.",
      "ادامه استفاده از خدمات پس از انتشار، به معنای پذیرش نسخه به‌روزشده است.",
    ],
  },
  {
    id: "contact",
    title: "۱۰. ارتباط با ما",
    anchorLabel: "تماس با ما",
    icon: FileText,
    intro:
      "پرسش، پیشنهاد یا شکایت خود درباره حریم خصوصی را با ما در میان بگذارید.",
    clauses: [
      "تلفن پشتیبانی: همه روزه از ساعت ۹ صبح تا ۶ عصر.",
      "فرم «ثبت درخواست» در حساب کاربری، سریع‌ترین راه پیگیری موارد مربوط به داده‌های شماست.",
    ],
  },
];

export default function PrivacyPage() {
  return (
    <div
      dir="ltr"
      className="mx-auto w-full max-w-[1270px] px-3 py-4 sm:px-4 sm:py-6 lg:px-6 lg:py-8"
    >
      <div dir="rtl" className="w-full space-y-4 sm:space-y-5">
        {/* ---------- Page header ---------- */}
        <div className={`${CARD_CLASS} overflow-hidden`}>
          <div className="bg-blue-50/70 px-4 py-5 dark:bg-blue-950/20 sm:px-6 sm:py-6">
            <div className="flex flex-wrap items-start justify-between gap-4">
              <div className="flex min-w-0 items-start gap-3">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-blue-500 shadow-sm dark:bg-neutral-900">
                  <ShieldCheck aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    سیاست حریم خصوصی هیکالا
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    چه اطلاعاتی درباره شما جمع‌آوری می‌شود، چرا به آن‌ها نیاز داریم،
                    کجا نگهداری می‌شوند و شما چه کنترلی بر آن‌ها دارید.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>آخرین به‌روزرسانی: مهر ۱۴۰۵</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <Lock aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>اطلاعات کارت بانکی در هیکالا ذخیره نمی‌شود.</span>
            </span>

            <Link
              href="/terms"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              مشاهده قوانین و مقررات
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          <nav
            aria-label="فهرست بخش‌های حریم خصوصی"
            className={`${CARD_CLASS} h-fit p-4 lg:sticky lg:top-6 lg:p-5`}
          >
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              فهرست مطالب
            </h2>

            <ul className="mt-3 space-y-1">
              {SECTIONS.map((section) => (
                <li key={section.id}>
                  <a
                    href={`#${section.id}`}
                    className="block rounded-lg px-3 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-50 hover:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-neutral-300 dark:hover:bg-neutral-800"
                  >
                    {section.anchorLabel}
                  </a>
                </li>
              ))}
            </ul>

            <div className="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

            <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
              <UserCheck
                aria-hidden="true"
                className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
              />
              <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                برای دسترسی، اصلاح یا حذف اطلاعات خود به بخش «اطلاعات حساب» بروید.
              </p>
            </div>
          </nav>

          <div className="min-w-0 space-y-4 sm:space-y-5">
            {SECTIONS.map((section) => {
              const Icon = section.icon;

              return (
                <section
                  key={section.id}
                  id={section.id}
                  className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
                >
                  <div className="flex items-start gap-3">
                    <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
                      <Icon aria-hidden="true" className="h-5 w-5" />
                    </span>

                    <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                      {section.title}
                    </h2>
                  </div>

                  <p className="mt-3 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                    {section.intro}
                  </p>

                  <ol className="mt-4 space-y-3">
                    {section.clauses.map((clause, index) => (
                      <li
                        key={`${section.id}-${index}`}
                        className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                      >
                        <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-blue-500 shadow-sm dark:bg-neutral-900 dark:text-blue-300">
                          {index + 1}
                        </span>

                        <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                          {clause}
                        </p>
                      </li>
                    ))}
                  </ol>
                </section>
              );
            })}

            {/* ---------- Back to shop ---------- */}
            <div className={`${CARD_CLASS} p-4 sm:p-6`}>
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    خرید با خیال راحت
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    داده‌های شما فقط برای اجرای سفارش استفاده می‌شود و در اختیار
                    هیچ شخص ثالثی قرار نمی‌گیرد.
                  </p>
                </div>

                <Link
                  href="/"
                  className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
                >
                  <ArrowLeft aria-hidden="true" className="h-4 w-4" />
                  <span>بازگشت به فروشگاه</span>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
