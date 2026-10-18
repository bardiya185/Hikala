"use client";

import React from "react";
import Link from "next/link";
import { MessageSquare, Package, ShoppingBag } from "lucide-react";

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

export default function CommentsPage() {
  return (
    <div dir="ltr" className="w-full">
      <div className={`${CARD_CLASS} p-4 sm:p-6`}>
        {/* Header */}
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="min-w-0">
            <h1 className="text-xl font-bold text-neutral-800 dark:text-neutral-100 sm:text-2xl">
              دیدگاه‌ها و پرسش‌های من
            </h1>
            <p className="mt-1 text-xs text-neutral-500 dark:text-neutral-400 sm:text-sm">
              ثبت نظر و پرسش درباره محصولاتی که خریداری کرده‌اید
            </p>
          </div>

          <span className="shrink-0 rounded-full bg-neutral-100 px-3 py-1.5 text-xs font-bold text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
            ۰ مورد
          </span>
        </div>

        <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

        {/* Premium empty state */}
        <div className="flex min-h-[360px] flex-col items-center justify-center px-2 py-6 text-center">
          <div className="relative">
            <span className="flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-red-500 dark:bg-red-950/30 dark:text-red-400">
              <MessageSquare aria-hidden="true" className="h-9 w-9" />
            </span>

            <span className="absolute -bottom-1 -left-1 flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-blue-500 text-white dark:border-neutral-900">
              <Package aria-hidden="true" className="h-4 w-4" />
            </span>
          </div>

          <h2 className="mt-6 text-base font-bold text-neutral-800 dark:text-neutral-100 sm:text-lg">
            هنوز دیدگاه یا پرسشی ثبت نکرده‌اید
          </h2>

          <p className="mt-2 max-w-md text-sm leading-6 text-neutral-500 dark:text-neutral-400">
            نظر شما درباره کیفیت کالا، بسته‌بندی و ارسال به دیگر خریداران کمک می‌کند
            و به ما در بهتر شدن خدمات. پس از تکمیل سفارش‌ها می‌توانید برای هر محصول
            دیدگاه ثبت کنید یا پرسش خود را بپرسید.
          </p>

          <div className="mt-6 flex flex-col items-center gap-2 sm:flex-row sm:justify-center">
            <Link
              href="/profile/orders"
              className="inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900 sm:w-auto"
            >
              <ShoppingBag aria-hidden="true" className="h-4 w-4" />
              <span>مشاهده سفارش‌ها</span>
            </Link>

            <Link
              href="/"
              className="inline-flex h-11 w-full items-center justify-center rounded-lg border border-neutral-200 bg-white px-6 text-sm font-bold text-neutral-600 transition-colors hover:bg-neutral-50 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus-visible:ring-offset-neutral-900 sm:w-auto"
            >
              <span>شروع خرید</span>
            </Link>
          </div>

          <div className="mt-8 grid w-full max-w-lg grid-cols-1 gap-2 sm:grid-cols-3">
            {[
              { title: "کیفیت کالا", note: "رضایت از محصول تحویل‌شده" },
              { title: "بسته‌بندی", note: "سلامت و نحوه ارسال" },
              { title: "سرعت ارسال", note: "تطابق با زمان وعده‌داده‌شده" },
            ].map((item) => (
              <div
                key={item.title}
                className="rounded-lg border border-dashed border-neutral-200 bg-neutral-50/60 p-3 text-center dark:border-neutral-800 dark:bg-neutral-950/30"
              >
                <p className="text-xs font-bold text-neutral-700 dark:text-neutral-200">
                  {item.title}
                </p>
                <p className="mt-1 text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                  {item.note}
                </p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
