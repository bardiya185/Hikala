import React from "react";
import Link from "next/link";
import {
  Truck,
  ArrowLeft,
  ShoppingBag,
  PackageCheck,
  Send,
  Home,
  Clock,
  MapPin,
  ShieldCheck,
  FileText,
} from "lucide-react";

export const metadata = {
  title: "Shipping | Hikala",
  description:
    "Hikala shipping options: standard and express delivery times and costs, how your order moves from checkout to your door, and where we deliver.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const OPTIONS = [
  {
    icon: Truck,
    name: "Standard Delivery",
    eta: "3–5 business days",
    cost: "Free over $50",
    note: "Flat $2.50 for orders under $50. The default method for every order.",
    highlight: false,
  },
  {
    icon: Send,
    name: "Express Delivery",
    eta: "1–2 business days",
    cost: "$4.90",
    note: "Priority handling and faster courier for when you need it soon.",
    highlight: true,
  },
];

const STEPS = [
  {
    icon: ShoppingBag,
    title: "Order placed",
    description:
      "You confirm the cart and pay at checkout. An order number is issued and shown in your account immediately.",
  },
  {
    icon: PackageCheck,
    title: "Packed",
    description:
      "Our warehouse team picks your items, checks them against your order and seals the parcel for handover.",
  },
  {
    icon: Truck,
    title: "In transit",
    description:
      "The courier collects the parcel and moves it through the delivery network. You can follow each step on the order tracking page.",
  },
  {
    icon: Home,
    title: "Delivered",
    description:
      "The parcel is handed over at your registered address to a recipient present there, and the order is marked complete.",
  },
];

export default function ShippingPage() {
  return (
    <div
      dir="ltr"
      className="mx-auto w-full max-w-[1270px] px-3 py-4 sm:px-4 sm:py-6 lg:px-6 lg:py-8"
    >
      <div dir="ltr" className="w-full space-y-4 sm:space-y-5">
        {/* ---------- Page header ---------- */}
        <div className={`${CARD_CLASS} overflow-hidden`}>
          <div className="bg-blue-50/70 px-4 py-5 dark:bg-blue-950/20 sm:px-6 sm:py-6">
            <div className="flex flex-wrap items-start justify-between gap-4">
              <div className="flex min-w-0 items-start gap-3">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-blue-500 shadow-sm dark:bg-neutral-900">
                  <Truck aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Shipping with Hikala
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Choose the delivery speed that fits your order. Times are
                    counted from the moment the parcel is prepared, and the
                    exact cost is always shown in your cart before you pay.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>Last updated: October 2026</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Every parcel is tracked from our warehouse to your door.</span>
            </span>

            <Link
              href="/order-tracking"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Track an existing order
            </Link>
          </div>
        </div>

        {/* ---------- Delivery options ---------- */}
        <section aria-label="Delivery options">
          <h2 className="mb-3 text-sm font-bold text-neutral-800 dark:text-neutral-100">
            Delivery options
          </h2>

          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
            {OPTIONS.map((option) => {
              const Icon = option.icon;

              return (
                <div
                  key={option.name}
                  className={`${CARD_CLASS} relative flex flex-col gap-3 p-4 sm:p-5 ${
                    option.highlight
                      ? "border-red-500 dark:border-red-500"
                      : ""
                  }`}
                >
                  {option.highlight && (
                    <span className="absolute right-4 top-4 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-600 dark:bg-red-950/40 dark:text-red-400">
                      Fastest
                    </span>
                  )}

                  <div className="flex items-center gap-3">
                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
                      <Icon aria-hidden="true" className="h-5 w-5" />
                    </span>

                    <h3 className="text-sm font-extrabold text-neutral-900 dark:text-neutral-50">
                      {option.name}
                    </h3>
                  </div>

                  <dl className="space-y-2 text-sm">
                    <div className="flex items-center gap-2">
                      <Clock
                        aria-hidden="true"
                        className="h-4 w-4 shrink-0 text-neutral-400"
                      />
                      <dt className="sr-only">Estimated time</dt>
                      <dd className="font-medium text-neutral-700 dark:text-neutral-200">
                        {option.eta}
                      </dd>
                    </div>
                    <div className="flex items-center gap-2">
                      <ShoppingBag
                        aria-hidden="true"
                        className="h-4 w-4 shrink-0 text-neutral-400"
                      />
                      <dt className="sr-only">Cost</dt>
                      <dd
                        className={`font-bold ${
                          option.highlight
                            ? "text-neutral-900 dark:text-neutral-50"
                            : "text-blue-500 dark:text-blue-400"
                        }`}
                      >
                        {option.cost}
                      </dd>
                    </div>
                  </dl>

                  <p className="text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    {option.note}
                  </p>
                </div>
              );
            })}
          </div>

          {/* Compact comparison table */}
          <div className={`${CARD_CLASS} mt-4 overflow-x-auto p-4 sm:p-5`}>
            <table className="w-full min-w-[520px] text-left text-sm">
              <thead>
                <tr className="border-b border-neutral-200 text-xs font-bold text-neutral-500 dark:border-neutral-800 dark:text-neutral-400">
                  <th scope="col" className="py-2 pr-4">
                    Method
                  </th>
                  <th scope="col" className="py-2 pr-4">
                    Delivery time
                  </th>
                  <th scope="col" className="py-2 pr-4">
                    Cost
                  </th>
                  <th scope="col" className="py-2">
                    Free over $50
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-neutral-100 dark:divide-neutral-800">
                <tr>
                  <td className="py-3 pr-4 font-bold text-neutral-800 dark:text-neutral-100">
                    Standard
                  </td>
                  <td className="py-3 pr-4 text-neutral-600 dark:text-neutral-300">
                    3–5 business days
                  </td>
                  <td className="py-3 pr-4 text-neutral-600 dark:text-neutral-300">
                    $2.50
                  </td>
                  <td className="py-3 text-blue-500 dark:text-blue-400">
                    Yes
                  </td>
                </tr>
                <tr>
                  <td className="py-3 pr-4 font-bold text-neutral-800 dark:text-neutral-100">
                    Express
                  </td>
                  <td className="py-3 pr-4 text-neutral-600 dark:text-neutral-300">
                    1–2 business days
                  </td>
                  <td className="py-3 pr-4 text-neutral-600 dark:text-neutral-300">
                    $4.90
                  </td>
                  <td className="py-3 text-neutral-500 dark:text-neutral-400">
                    No
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        {/* ---------- How it works ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="How shipping works">
          <div className="flex items-center gap-2">
            <MapPin aria-hidden="true" className="h-4 w-4 text-red-500" />
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              How it works
            </h2>
          </div>

          <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
            Four simple steps between your checkout and your doorstep.
          </p>

          <ol className="mt-5 space-y-0">
            {STEPS.map((step, index) => {
              const Icon = step.icon;

              return (
                <li key={step.title} className="relative flex gap-4 pb-8 last:pb-0">
                  {index < STEPS.length - 1 && (
                    <span
                      aria-hidden="true"
                      className="absolute left-5 top-10 h-[calc(100%-24px)] w-0.5 bg-neutral-200 dark:bg-neutral-800"
                    />
                  )}

                  <span className="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-400">
                    <Icon aria-hidden="true" className="h-5 w-5" />
                  </span>

                  <div className="min-w-0 pt-0.5">
                    <h3 className="flex items-center gap-2 text-sm font-extrabold text-neutral-900 dark:text-neutral-50">
                      <span className="flex h-6 w-6 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                        {index + 1}
                      </span>
                      {step.title}
                    </h3>
                    <p className="mt-1.5 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                      {step.description}
                    </p>
                  </div>
                </li>
              );
            })}
          </ol>
        </section>

        {/* ---------- Coverage note ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Delivery coverage">
          <div className="flex items-start gap-3">
            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
              <MapPin aria-hidden="true" className="h-5 w-5" />
            </span>

            <div className="min-w-0">
              <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                Where we deliver
              </h2>
              <p className="mt-2 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                Hikala ships to every province in the country. Standard and
                Express are available in major cities; in other regions the
                parcel may take one extra business day because of courier
                schedules. The delivery window shown on each product page and
                at checkout is calculated from your saved address, and delays
                caused by weather or postal disruptions are always communicated
                to you through your order page.
              </p>
            </div>
          </div>
        </section>

        {/* ---------- CTA: to cart ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Ready to get it delivered?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Add items worth $50 or more and standard shipping is on us.
              </p>
            </div>

            <Link
              href="/checkout/cart"
              className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <ShoppingBag aria-hidden="true" className="h-4 w-4" />
              <span>Go to cart</span>
            </Link>
          </div>
        </div>

        {/* ---------- Back to shop ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Questions about a delivery?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                See the shipping answers in our FAQ, or contact support any
                time.
              </p>
            </div>

            <Link
              href="/"
              className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <ArrowLeft aria-hidden="true" className="h-4 w-4" />
              <span>Back to the store</span>
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
