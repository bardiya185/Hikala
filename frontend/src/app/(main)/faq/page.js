import React from "react";
import Link from "next/link";
import {
  HelpCircle,
  ArrowLeft,
  ChevronDown,
  ShoppingBag,
  Truck,
  RotateCcw,
  CreditCard,
  UserCheck,
  Phone,
  FileText,
} from "lucide-react";

export const metadata = {
  title: "FAQ | Hikala",
  description:
    "Frequently asked questions about ordering, shipping, returns, payments and your Hikala account.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  {
    id: "orders",
    title: "Orders",
    icon: ShoppingBag,
    questions: [
      {
        q: "How do I place an order on Hikala?",
        a: "Browse the store, add the products you want to your cart, then go to checkout. Choose your delivery method, confirm your address and pay — you will get an order number on the confirmation page and in your account right away.",
      },
      {
        q: "Can I change or cancel an order after paying?",
        a: "While the order is still pending or processing you can cancel it from your orders list and the payment is refunded automatically. Once the parcel is shipped, cancellation is no longer possible, but you can use the 7-day return window after delivery.",
      },
      {
        q: "How do I find my order ID?",
        a: "Your order ID and order number appear on the checkout confirmation page, in your account under 'My orders', and in the tracking message we send when the parcel is handed to the courier. Enter it on the track order page to see the live status.",
      },
    ],
  },
  {
    id: "shipping",
    title: "Shipping",
    icon: Truck,
    questions: [
      {
        q: "How long does delivery take?",
        a: "Standard delivery arrives in 3–5 business days and Express in 1–2 business days. The clock starts when the parcel leaves our warehouse, not when you place the order, and the estimated window is shown in your cart.",
      },
      {
        q: "Is shipping free?",
        a: "Standard shipping is free for orders over $50; below that it costs a flat $2.50. Express delivery is $4.90 and is always shown at checkout before you pay.",
      },
      {
        q: "Do you deliver to my city?",
        a: "Hikala ships nationwide. In major cities both Standard and Express are available; in other regions delivery can take one extra business day because of courier schedules. Your saved address is used to calculate the exact window at checkout.",
      },
    ],
  },
  {
    id: "returns",
    title: "Returns",
    icon: RotateCcw,
    questions: [
      {
        q: "Can I return an item after receiving it?",
        a: "Yes. Non-consumable products can be returned within 7 days of delivery if they are undamaged, complete with all accessories and still in their original packaging. See the returns page for the full rules and exclusions.",
      },
      {
        q: "When will I receive my refund?",
        a: "Refunds are issued within 7 business days after the returned item passes inspection. Card payments return through the gateway in 3–7 business days, wallet credit appears within 24 hours, and cash-on-delivery refunds are bank-transferred in 5–7 business days.",
      },
    ],
  },
  {
    id: "payments",
    title: "Payments",
    icon: CreditCard,
    questions: [
      {
        q: "Which payment methods can I use at checkout?",
        a: "You can pay online through a secure bank gateway, use your wallet balance, or choose cash on delivery where it is offered. Every fee, including shipping, is shown before you confirm the payment.",
      },
      {
        q: "My payment failed but the money left my account — what now?",
        a: "Do not worry and do not pay again right away. Failed gateway transactions are reversed automatically by your bank within 24 to 72 business hours. If the amount has not returned by then, contact support with the transaction reference.",
      },
    ],
  },
  {
    id: "account",
    title: "Account",
    icon: UserCheck,
    questions: [
      {
        q: "How do I update my address or password?",
        a: "Sign in and open the account area: addresses are managed under 'Addresses' and your password can be changed from 'Account information'. Keeping your postal code current helps courier scheduling.",
      },
      {
        q: "How do I delete my account?",
        a: "Contact Hikala support at any time to request deletion of your account and personal data. Order records that the law requires us to keep are the only exception.",
      },
    ],
  },
];

export default function FaqPage() {
  return (
    <div
      dir="ltr"
      className="mx-auto w-full max-w-[1270px] px-3 py-4 sm:px-4 sm:py-6 lg:px-6 lg:py-8"
    >
      <div dir="ltr" className="w-full space-y-4 sm:space-y-5">
        {/* ---------- Page header ---------- */}
        <div className={`${CARD_CLASS} overflow-hidden`}>
          <div className="bg-red-50/70 px-4 py-5 dark:bg-red-950/20 sm:px-6 sm:py-6">
            <div className="flex flex-wrap items-start justify-between gap-4">
              <div className="flex min-w-0 items-start gap-3">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-red-500 shadow-sm dark:bg-neutral-900">
                  <HelpCircle aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Frequently Asked Questions
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Straight answers about ordering, shipping, returns,
                    payments and your account. Open a question to read the
                    answer.
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
              <HelpCircle aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>More guides are available in the help center.</span>
            </span>

            <Link
              href="/help-center"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Open the help center
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          {/* Section nav */}
          <nav
            aria-label="FAQ sections"
            className={`${CARD_CLASS} h-fit p-4 lg:sticky lg:top-6 lg:p-5`}
          >
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              Contents
            </h2>

            <ul className="mt-3 space-y-1">
              {SECTIONS.map((section) => (
                <li key={section.id}>
                  <a
                    href={`#${section.id}`}
                    className="block rounded-lg px-3 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-50 hover:text-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-neutral-300 dark:hover:bg-neutral-800"
                  >
                    {section.title}
                  </a>
                </li>
              ))}
            </ul>

            <div className="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

            <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
              <Phone aria-hidden="true" className="mt-0.5 h-4 w-4 shrink-0 text-blue-500" />
              <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                Still stuck? Reach Hikala support through the contact page and
                we reply within 24 business hours.
              </p>
            </div>
          </nav>

          {/* Question groups */}
          <div className="min-w-0 space-y-4 sm:space-y-5">
            {SECTIONS.map((section) => {
              const Icon = section.icon;

              return (
                <section
                  key={section.id}
                  id={section.id}
                  className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
                >
                  <div className="flex items-center gap-3">
                    <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-400">
                      <Icon aria-hidden="true" className="h-5 w-5" />
                    </span>

                    <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                      {section.title}
                    </h2>
                  </div>

                  <div className="mt-4 space-y-3">
                    {section.questions.map((qa, index) => (
                      <details
                        key={`${section.id}-${index}`}
                        className="group rounded-xl border border-neutral-100 bg-neutral-50/60 transition-colors open:bg-white dark:border-neutral-800 dark:bg-neutral-950/30 dark:open:bg-neutral-900"
                      >
                        <summary className="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl p-4 [&::-webkit-details-marker]:hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                          <span className="min-w-0 text-sm font-bold text-neutral-800 dark:text-neutral-100 group-hover:text-red-500 dark:group-hover:text-red-400">
                            {qa.q}
                          </span>

                          <ChevronDown
                            aria-hidden="true"
                            className="h-4 w-4 shrink-0 text-neutral-400 transition-transform duration-200 group-open:rotate-180 group-open:text-red-500"
                          />
                        </summary>

                        <div className="border-t border-neutral-100 px-4 py-4 dark:border-neutral-800">
                          <p className="text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                            {qa.a}
                          </p>
                        </div>
                      </details>
                    ))}
                  </div>
                </section>
              );
            })}

            {/* ---------- Back to shop ---------- */}
            <div className={`${CARD_CLASS} p-4 sm:p-6`}>
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    Did that answer your question?
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    If you are ready to shop, your cart is only a click away —
                    otherwise, the help center has more guides.
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
      </div>
    </div>
  );
}
