"use client";

import Link from "next/link";
import { AlertTriangle, RotateCcw, Home } from "lucide-react";

export default function Error({ error, reset }) {
  return (
    <main
      dir="ltr"
      className="
        min-h-[80vh] w-full
        flex items-center justify-center
        bg-neutral-50 dark:bg-neutral-950
        px-4 py-16
      "
    >
      <div className="w-full max-w-xl text-center">
        <div
          className="
            mx-auto mb-8 flex h-24 w-24
            items-center justify-center
            rounded-3xl
            bg-amber-50 text-amber-500
            ring-1 ring-amber-100
            dark:bg-amber-950/30 dark:text-amber-400
            dark:ring-amber-900/50
          "
        >
          <AlertTriangle className="h-12 w-12" strokeWidth={1.6} />
        </div>

        <h1
          className="
            text-2xl font-extrabold
            text-neutral-800 dark:text-neutral-100
            sm:text-3xl
          "
        >
          خطایی رخ داد
        </h1>

        <p
          className="
            mx-auto mt-3 max-w-md text-sm leading-7
            text-neutral-500 dark:text-neutral-400
            sm:text-base
          "
        >
          در بارگذاری این صفحه مشکلی پیش آمد. لطفاً دوباره تلاش کنید یا به
          صفحه اصلی بازگردید.
        </p>

        {process.env.NODE_ENV !== "production" && error?.message && (
          <pre
            className="
              mx-auto mt-5 max-w-md overflow-auto
              rounded-lg bg-neutral-100 p-3 text-left
              text-xs text-neutral-600
              dark:bg-neutral-900 dark:text-neutral-400
            "
          >
            {error.message}
          </pre>
        )}

        <div className="mt-8 flex flex-wrap items-center justify-center gap-3">
          <button
            type="button"
            onClick={reset}
            className="
              inline-flex items-center gap-2
              rounded-xl bg-red-500 px-5 py-3
              text-sm font-bold text-white
              shadow-sm transition-all
              hover:bg-red-600 active:scale-[0.98]
              focus:outline-none focus-visible:ring-2
              focus-visible:ring-red-500 focus-visible:ring-offset-2
              dark:focus-visible:ring-offset-neutral-950
            "
          >
            <RotateCcw className="h-4 w-4" />
            تلاش مجدد
          </button>

          <Link
            href="/"
            className="
              inline-flex items-center gap-2
              rounded-xl border border-neutral-200
              bg-white px-5 py-3
              text-sm font-bold text-neutral-700
              transition-all hover:bg-neutral-50
              active:scale-[0.98]
              focus:outline-none focus-visible:ring-2
              focus-visible:ring-neutral-400
              dark:border-neutral-800 dark:bg-neutral-900
              dark:text-neutral-200 dark:hover:bg-neutral-800
            "
          >
            <Home className="h-4 w-4" />
            بازگشت به خانه
          </Link>
        </div>
      </div>
    </main>
  );
}
