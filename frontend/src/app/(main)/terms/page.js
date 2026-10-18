import React from "react";
import Link from "next/link";
import { Scale, ArrowLeft, ShieldCheck, FileText, Phone } from "lucide-react";

export const metadata = {
  title: "قوانین و مقررات | هیکالا",
  description:
    "قوانین و مقررات استفاده از فروشگاه اینترنتی هیکالا: ثبت سفارش، پرداخت، ارسال، بازگشت کالا و مسئولیت‌ها.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  {
    id: "intro",
    title: "۱. مقدمه و چارچوب پذیرش",
    anchorLabel: "مقدمه",
    intro:
      "استفاده از خدمات فروشگاه اینترنتی هیکالا به معنای پذیرش کامل این قوانین است. هر خریدی که در این سایت ثبت می‌شود، بر اساس همین مقررات تنظیم و اجرای آن معنا می‌یابد.",
    clauses: [
      "هیکالا یک فروشگاه اینترنتی است و حق هیچ‌گونه تغییری در مفاد این قوانین را برای خود محفوظ می‌دارد؛ نسخه معتبر، نسخه‌ای است که در همین صفحه منتشر شده باشد.",
      "تمام قیمت‌های اعلام‌شده به تومان و شامل مالیات بر ارزش افزوده است، مگر آنکه خلاف آن صریحاً ذکر شده باشد.",
      "این قوانین میان کاربر و شرکت هیکالا لازم‌الاجراست و در موارد سکوت، مقررات قانون تجارت الکترونیکی و قوانین موضوعه کشور حاکم خواهد بود.",
    ],
  },
  {
    id: "account",
    title: "۲. حساب کاربری و مسئولیت‌های کاربر",
    anchorLabel: "حساب کاربری",
    intro:
      "ثبت‌نام در هیکالا رایگان است و با ارائه شماره موبایل معتبر انجام می‌شود.",
    clauses: [
      "کاربر متعهد است اطلاعات صحیح (نام، شماره تماس، نشانی و کد پستی) را وارد کند؛ مسئولیت عواقب اطلاعات نادرست بر عهده کاربر است.",
      "حفظ رمز عبور و اطلاعات ورود بر عهده کاربر است و هرگونه فعالیت انجام‌شده با حساب شما، منتسب به خودتان شناخته می‌شود.",
      "ایجاد بیش از یک حساب با هدف سوءاستفاده از پیشنهادهای ویژه، ممنوع است و هیکالا می‌تواند سفارش‌های مربوطه را لغو کند.",
      "کاربر می‌تواند در هر زمان با پشتیبانی تماس گرفته و درخواست حذف حساب و داده‌های خود را داشته باشد.",
    ],
  },
  {
    id: "order",
    title: "۳. ثبت سفارش و قیمت کالاها",
    anchorLabel: "ثبت سفارش",
    intro:
      "سفارش پس از انتخاب کالا، افزودن به سبد خرید و تکمیل فرآیند پرداخت نهایی می‌شود.",
    clauses: [
      "ثبت سفارش به معنای عقد قطعی نیست؛ تا زمان تأمین کالا و آماده‌سازی بسته، امکان تغییر قیمت یا لغو سفارش به اطلاع کاربر خواهد رسید.",
      "در صورت در دسترس نبودن ناگهانی کالا (اتمام انبار یا خطای درج قیمت)، مبلغ پرداختی کامل به حساب کاربر بازمی‌گردد.",
      "تلاش می‌کنیم تصاویر و مشخصات فنی کالاها را دقیق منتشر کنیم؛ در صورت مغایرت، توضیحات درج‌شده در سایت ملاک است و کاربر حق انصراف از خرید را دارد.",
      "حداقل مبلغ سفارش، سقف سبد خرید و تعداد مجاز هر کالا ممکن است در جشنواره‌های فروش محدود شود.",
    ],
  },
  {
    id: "payment",
    title: "۴. پرداخت و امنیت تراکنش",
    anchorLabel: "پرداخت",
    intro:
      "پرداخت‌ها از طریق درگاه‌های بانکی معتبر و با پروتکل امن انجام می‌شود.",
    clauses: [
      "هیکالا هیچ‌گاه اطلاعات کارت بانکی یا رمز دوم کاربر را ذخیره نمی‌کند؛ تمام تراکنش‌ها میان کاربر و بانک/درگاه پرداخت انجام می‌شود.",
      "در صورت بروز خطای درگاه، مبلغ ظرف ۲۴ تا ۷۲ ساعت کاری به صورت خودکار به حساب شما بازمی‌گردد.",
      "استفاده از کارت هدیه و اعتبار کیف پول، مشروط به فعال نبودن حساب و رعایت شرایط مندرج در همان بخش است.",
      "هزینه‌های پرداخت یا کارمزد اضافه، در همان مرحله تسویه و پیش از پرداخت نهایی به کاربر نمایش داده می‌شود.",
    ],
  },
  {
    id: "shipping",
    title: "۵. ارسال و تحویل",
    anchorLabel: "ارسال",
    intro:
      "زمان و هزینه ارسال به انتخاب روش حمل، مبدأ انبار و مقصد سفارش بستگی دارد.",
    clauses: [
      "بازه زمانی ارسال روی صفحه هر کالا یا در مرحله تسویه نمایش داده می‌شود و مبدأ آن از زمان آماده‌سازی بسته است، نه زمان ثبت سفارش.",
      "تحویل سفارش تنها در نشانی ثبت‌شده و به گیرنده‌ای که در همان نشانی حضور دارد انجام می‌شود.",
      "در سفارش‌های بالای مبلغ تعیین‌شده، ارسال برای برخی شهرها رایگان است؛ مشمول یا غیرمشمول بودن در سبد خرید مشخص می‌شود.",
      "تأخیر ناشی از عوامل خارجی (شرایط جوی، اختلال پست، محدودیت‌های شهری) به حساب هیکالا گذاشته نمی‌شود، اما پیگیری و اطلاع‌رسانی انجام می‌شود.",
    ],
  },
  {
    id: "returns",
    title: "۶. بازگشت کالا و انصراف از خرید",
    anchorLabel: "بازگشت کالا",
    intro:
      "رضایت شما اولویت ماست؛ بر همین اساس فرآیند مرجوعی شفاف تعریف شده است.",
    clauses: [
      "کالاهای غیرمصرفی تا ۷ روز پس از تحویل، در صورت نداشتن آسیب، فقدان اقلام همراه و حفظ بسته‌بندی اصلی، قابل مرجوع کردن هستند.",
      "کالاهای مصرفی، بهداشتی، لباس زیر و نرم‌افزارهای بسته‌بندی‌شده به دلیل ماهیتشان مشمول بازگشت نیستند، مگر در صورت مغایرت یا نقص فنی.",
      "کالاهای بزرگ (یخچال، ماشین لباسشویی و مانند آن) باید پیش از نصب و راه‌اندازی بررسی شوند؛ پس از نصب، مرجوعی تنها با تأیید کارشناس مجاز امکان‌پذیر است.",
      "هزینه بازگشت کالا در صورت انصراف بدون نقص فنی بر عهده کاربر و در صورت ایراد کالا بر عهده هیکالا است.",
      "تسویه مبلغ مرجوعی حداکثر تا ۷ روز کاری پس از تأیید سلامت کالا انجام می‌شود.",
    ],
  },
  {
    id: "content",
    title: "۷. محتوا و مالکیت فکری",
    anchorLabel: "محتوا",
    intro:
      "تمام محتوای منتشرشده در هیکالا متعلق به این فروشگاه یا تأمین‌کنندگان آن است.",
    clauses: [
      "بازنشر متن، تصاویر و ساختار صفحات بدون ذکر منبع و اخذ اجازه کتبی مجاز نیست.",
      "نام تجاری، لوگو و نشان هیکالا تحت حمایت قانون است و استفاده مشابه از آن‌ها ممنوع می‌باشد.",
      "دیدگاه‌ها و پرسش‌های ثبت‌شده توسط کاربران، پس از حذف اطلاعات شخصی، ممکن است برای راهنمایی سایر خریداران در صفحه کالا نمایش داده شود.",
      "هرگونه محتوا یا نظری که حاوی توهین، ماهیت تبلیغ‌آمیز، مغایر با قوانین یا ناقض حقوق اشخاص ثالث باشد، حذف می‌شود.",
    ],
  },
  {
    id: "liability",
    title: "۸. محدودیت مسئولیت و فورس‌ماژور",
    anchorLabel: "مسئولیت",
    intro:
      "هیکالا خدمات خود را با رعایت استانداردها ارائه می‌کند، اما مسئولیت برخی موارد خارج از اختیار آن است.",
    clauses: [
      "کاربری نادرست، نصب غیرمجاز یا عدم رعایت دستورالعمل‌های تولیدکننده، خارج از تعهدات هیکالا است.",
      "در شرایط فورس‌ماژور (قطعی سراسری اینترنت، بلایای طبیعی، تحریم و ...) اجرای سفارش ممکن است متوقف شود و اطلاع‌رسانی انجام می‌گیرد.",
      "مسئولیت محتوای تبلیغی و مشخصات اعلام‌شده توسط فروشندگان ثالث، بر عهده همان فروشنده است.",
      "در هیچ حالتی خسارت غیرمستقیم (از دست رفتن سود یا فرصت) قابل مطالبه از هیکالا نیست.",
    ],
  },
  {
    id: "contact",
    title: "۹. حل اختلاف و ارتباط با ما",
    anchorLabel: "ارتباط با ما",
    intro:
      "تلاش نخست ما حل دوستانه موارد در داخل مجموعه است.",
    clauses: [
      "در صورت بروز هرگونه مشکل، از طریق فرم «ثبت درخواست» در حساب کاربری یا شماره پشتیبانی اقدام کنید؛ پاسخ‌گویی تا ۲۴ ساعت کاری انجام می‌شود.",
      "در صورت عدم حصول نتیجه، موضوع از طریق مراجع ذی‌صلاح و در چارچوب قوانین کشور پیگیری می‌شود.",
      "ساعت کاری پشتیبانی: شنبه تا پنج‌شنبه، ۹ صبح تا ۶ عصر.",
    ],
  },
];

export default function TermsPage() {
  return (
    <div
      dir="ltr"
      className="mx-auto w-full max-w-[1270px] px-3 py-4 sm:px-4 sm:py-6 lg:px-6 lg:py-8"
    >
      <div dir="rtl" className="w-full space-y-4 sm:space-y-5">
        {/* ---------- Page header ---------- */}
        <div className={`${CARD_CLASS} overflow-hidden`}>
          <div className="bg-red-50/70 px-4 py-5 dark:bg-red-950/20 sm:px-6 sm:py-6">
            <div className="flex flex-wrap items-start justify-between gap-4">
              <div className="flex min-w-0 items-start gap-3">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-red-500 shadow-sm dark:bg-neutral-900">
                  <Scale aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    قوانین و مقررات هیکالا
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    این صفحه چارچوب استفاده از فروشگاه اینترنتی هیکالا را
                    مشخص می‌کند؛ از ثبت‌نام و خرید تا ارسال، بازگشت کالا و حمایت از
                    خریدار.
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
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>این متن نسخه عمومی مقررات فروشگاه است.</span>
            </span>

            <Link
              href="/privacy"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              مشاهده حریم خصوصی
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          {/* Section nav */}
          <nav
            aria-label="فهرست بخش‌های قوانین"
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
                    className="block rounded-lg px-3 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-50 hover:text-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-neutral-300 dark:hover:bg-neutral-800"
                  >
                    {section.anchorLabel}
                  </a>
                </li>
              ))}
            </ul>

            <div className="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

            <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
              <Phone aria-hidden="true" className="mt-0.5 h-4 w-4 shrink-0 text-blue-500" />
              <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                پرسش یا اعتراضی درباره این مقررات دارید؟ با پشتیبانی هیکالا تماس
                بگیرید.
              </p>
            </div>
          </nav>

          {/* Article */}
          <div className="min-w-0 space-y-4 sm:space-y-5">
            {SECTIONS.map((section) => (
              <section
                key={section.id}
                id={section.id}
                className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
              >
                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  {section.title}
                </h2>

                <p className="mt-2 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                  {section.intro}
                </p>

                <ol className="mt-4 space-y-3">
                  {section.clauses.map((clause, index) => (
                    <li
                      key={`${section.id}-${index}`}
                      className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                    >
                      <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                        {index + 1}
                      </span>

                      <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                        {clause}
                      </p>
                    </li>
                  ))}
                </ol>
              </section>
            ))}

            {/* ---------- Back to shop ---------- */}
            <div className={`${CARD_CLASS} p-4 sm:p-6`}>
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    آماده خرید هستید؟
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    با پذیرش این قوانین می‌توانید در چند دقیقه سفارش خود را ثبت کنید.
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
