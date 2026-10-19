import React from "react";
import Link from "next/link";
import {
  HelpCircle,
  ArrowLeft,
  Search,
  BookOpen,
  Truck,
  RotateCcw,
  Package,
  LifeBuoy,
  Mail,
  FileText,
} from "lucide-react";

export const metadata = {
  title: "Help Center | Hikala",
  description:
    "The Hikala help center: find answers about orders, shipping, returns, payments and your account, or contact our support team.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const TOPICS = [
  {
    href: "/faq",
    icon: HelpCircle,
    title: "FAQ",
    description:
      "Quick answers to the questions we hear most often about shopping on Hikala.",
  },
  {
    href: "/shipping",
    icon: Truck,
    title: "Shipping",
    description:
      "Delivery options, estimated times and how your parcel moves from our warehouse to your door.",
  },
  {
    href: "/returns",
    icon: RotateCcw,
    title: "Returns & Refunds",
    description:
      "The 7-day return window, which items qualify and when your refund reaches you.",
  },
  {
    href: "/order-tracking",
    icon: Package,
    title: "Track Your Order",
    description:
      "Enter your order ID to see the live status of your parcel, step by step.",
  },
  {
    href: "/support",
    icon: LifeBuoy,
    title: "Support Requests",
    description:
      "Open a ticket for a specific order or product and follow it until it is resolved.",
  },
  {
    href: "/contact",
    icon: Mail,
    title: "Contact Us",
    description:
      "Talk to the Hikala team directly by phone or email during business hours.",
  },
];

const POPULAR_ARTICLES = [
  {
    href: "/faq#orders",
    title: "How do I place an order on Hikala?",
    topic: "Orders",
  },
  {
    href: "/faq#orders",
    title: "My order was cancelled — what happens to my payment?",
    topic: "Orders",
  },
  {
    href: "/faq#shipping",
    title: "How long does delivery take and is it free?",
    topic: "Shipping",
  },
  {
    href: "/faq#returns",
    title: "Can I return an item after receiving it?",
    topic: "Returns",
  },
  {
    href: "/faq#payments",
    title: "Which payment methods can I use at checkout?",
    topic: "Payments",
  },
  {
    href: "/faq#account",
    title: "How do I update my address or password?",
    topic: "Account",
  },
];

export default function HelpCenterPage() {
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
                    Hikala Help Center
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Everything you need to know about ordering, shipping,
                    returns and your account — pick a topic below and get your
                    answer in minutes.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>Updated: October 2026</span>
              </span>
            </div>
          </div>

          {/* Search-looking bar */}
          <div className="border-t border-neutral-200 px-4 py-4 dark:border-neutral-800 sm:px-6">
            <div
              role="presentation"
              className="flex h-12 w-full items-center gap-3 rounded-lg border border-neutral-200 bg-neutral-50 px-4 dark:border-neutral-800 dark:bg-neutral-950/40"
            >
              <Search
                aria-hidden="true"
                className="h-4 w-4 shrink-0 text-neutral-400"
              />
              <span className="min-w-0 flex-1 truncate text-sm text-neutral-400 dark:text-neutral-500">
                Search help articles — e.g. “return window”, “shipping cost”…
              </span>
              <Link
                href="/faq"
                className="shrink-0 text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
              >
                Browse FAQ
              </Link>
            </div>
          </div>
        </div>

        {/* ---------- Topic grid ---------- */}
        <section aria-label="Help topics">
          <div className="mb-3 flex items-center gap-2">
            <BookOpen
              aria-hidden="true"
              className="h-4 w-4 text-neutral-500 dark:text-neutral-400"
            />
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              Browse by topic
            </h2>
          </div>

          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 sm:gap-4">
            {TOPICS.map((topic) => {
              const Icon = topic.icon;

              return (
                <Link
                  key={topic.href}
                  href={topic.href}
                  className={`${CARD_CLASS} group flex items-start gap-3 p-4 transition-colors hover:border-neutral-300 hover:bg-neutral-50/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:hover:border-neutral-700 dark:hover:bg-neutral-950/30 sm:p-5`}
                >
                  <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors group-hover:bg-white dark:bg-red-950/40 dark:text-red-400 dark:group-hover:bg-neutral-900">
                    <Icon aria-hidden="true" className="h-5 w-5" />
                  </span>

                  <span className="min-w-0 flex-1">
                    <span className="flex items-center gap-1.5 text-sm font-bold text-neutral-900 dark:text-neutral-50">
                      {topic.title}
                      <ArrowLeft
                        aria-hidden="true"
                        className="h-4 w-4 text-neutral-400 transition-transform group-hover:-translate-x-0.5 group-hover:text-red-500"
                      />
                    </span>
                    <span className="mt-1 block text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                      {topic.description}
                    </span>
                  </span>
                </Link>
              );
            })}
          </div>
        </section>

        {/* ---------- Popular articles ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Popular articles">
          <div className="flex items-center gap-2">
            <Package
              aria-hidden="true"
              className="h-4 w-4 text-blue-500"
            />
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              Popular articles
            </h2>
          </div>

          <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
            The questions other Hikala shoppers read the most this month.
          </p>

          <ul className="mt-4 divide-y divide-neutral-100 dark:divide-neutral-800">
            {POPULAR_ARTICLES.map((article) => (
              <li key={article.title}>
                <a
                  href={article.href}
                  className="group flex items-center justify-between gap-3 py-3 rounded-lg px-2 transition-colors hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:hover:bg-neutral-800"
                >
                  <span className="min-w-0 text-sm font-medium text-neutral-700 transition-colors group-hover:text-red-500 dark:text-neutral-200">
                    {article.title}
                  </span>

                  <span className="shrink-0 rounded-full bg-neutral-50 px-2.5 py-1 text-[11px] font-bold text-neutral-500 dark:bg-neutral-950/40 dark:text-neutral-400">
                    {article.topic}
                  </span>
                </a>
              </li>
            ))}
          </ul>

          <div className="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

          <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
            <LifeBuoy
              aria-hidden="true"
              className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
            />
            <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
              Could not find what you were looking for?{" "}
              <Link
                href="/contact"
                className="font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
              >
                Contact Hikala support
              </Link>{" "}
              and we reply within 24 business hours.
            </p>
          </div>
        </section>

        {/* ---------- Back to shop ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Still need a hand?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Our support team is available Saturday to Thursday, 9:00 AM to
                6:00 PM.
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
