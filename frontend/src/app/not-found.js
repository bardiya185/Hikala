import Link from "next/link";
import { Search, Home, ShoppingBag, LifeBuoy } from "lucide-react";

export default function NotFound() {
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
            bg-red-50 text-red-500
            ring-1 ring-red-100
            dark:bg-red-950/30 dark:text-red-400
            dark:ring-red-900/50
          "
        >
          <LifeBuoy className="h-12 w-12" strokeWidth={1.6} />
        </div>

        <p
          className="
            select-none text-[120px] font-black leading-none
            text-neutral-200 dark:text-neutral-800
            sm:text-[150px]
          "
        >
          404
        </p>

        <h1
          className="
            -mt-6 text-2xl font-extrabold
            text-neutral-800 dark:text-neutral-100
            sm:text-3xl
          "
        >
          Page not found
        </h1>

        <p
          className="
            mx-auto mt-3 max-w-md text-sm leading-7
            text-neutral-500 dark:text-neutral-400
            sm:text-base
          "
        >
          Unfortunately the address you were looking for no longer exists or has
          moved. You can return to the home page or search our products.
        </p>

        <div className="mt-8 flex flex-wrap items-center justify-center gap-3">
          <Link
            href="/"
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
            <Home className="h-4 w-4" />
            Back to home
          </Link>

          <Link
            href="/search"
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
            <Search className="h-4 w-4" />
            Search products
          </Link>

          <Link
            href="/checkout/cart"
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
            <ShoppingBag className="h-4 w-4" />
            Cart
          </Link>
        </div>
      </div>
    </main>
  );
}
