import React from "react";
import Link from "next/link";
import {
  LifeBuoy,
  ArrowLeft,
  Truck,
  RotateCcw,
  MessageCircle,
  Package,
  ChevronRight,
  FileText,
  ShieldCheck,
  Clock,
  Timer,
  CircleAlert,
  Phone,
  Mail,
} from "lucide-react";

export const metadata = {
  title: "Support | Hikala",
  description:
    "Hikala support center: help topics, shipping, returns, FAQs and order tracking, with response times and emergency contacts.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const TOPICS = [
  {
    href: "/help-center",
    icon: LifeBuoy,
    title: "Help Center",
    description:
      "Guides and answers for accounts, orders, payments and the most common questions.",
  },
  {
    href: "/shipping",
    icon: Truck,
    title: "Shipping & Delivery",
    description:
      "Delivery methods, costs, free-shipping thresholds and how dispatch windows work.",
  },
  {
    href: "/returns",
    icon: RotateCcw,
    title: "Returns & Refunds",
    description:
      "Return windows, condition requirements and how quickly refunds are issued.",
  },
  {
    href: "/faq",
    icon: MessageCircle,
    title: "FAQ",
    description:
      "Quick answers on coupons, invoices, account settings and product listings.",
  },
  {
    href: "/order-tracking",
    icon: Package,
    title: "Order Tracking",
    description:
      "Follow your parcel from preparation to handover with live tracking status.",
  },
];

const RESPONSE_TIMES = [
  {
    icon: Timer,
    label: "Average first response",
    value: "Under 2 hours during support hours",
  },
  {
    icon: Mail,
    label: "Email requests",
    value: "Answered within 24 business hours",
  },
  {
    icon: Clock,
    label: "Phone support",
    value: "Monday to Saturday, 9:00 AM to 6:00 PM",
  },
];

export default function SupportPage() {
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
                  <LifeBuoy aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Support
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Find your answer in a topic below, check how fast we reply,
                    or reach a human directly when it is urgent.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>Average first response: under 2 hours</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Support never asks for your password or card details.</span>
            </span>

            <Link
              href="/contact"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Contact support directly
            </Link>
          </div>
        </div>

        {/* ---------- Topic grid ---------- */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {TOPICS.map((topic) => {
            const Icon = topic.icon;

            return (
              <Link
                key={topic.href}
                href={topic.href}
                className={`${CARD_CLASS} group flex flex-col p-4 transition-colors hover:border-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 sm:p-5 dark:hover:border-red-900`}
              >
                <div className="flex items-start gap-3">
                  <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                    <Icon aria-hidden="true" className="h-5 w-5" />
                  </span>

                  <h2 className="text-sm font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-base">
                    {topic.title}
                  </h2>
                </div>

                <p className="mt-3 flex-1 text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm sm:leading-7">
                  {topic.description}
                </p>

                <span className="mt-3 inline-flex items-center gap-1 text-xs font-bold text-blue-500 transition-colors group-hover:text-blue-600 dark:text-blue-400">
                  <span>Open topic</span>
                  <ChevronRight
                    aria-hidden="true"
                    className="h-3.5 w-3.5"
                  />
                </span>
              </Link>
            );
          })}
        </div>

        {/* ---------- Response times + emergency ---------- */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          {/* Response times */}
          <section className={`${CARD_CLASS} p-4 sm:p-6`}>
            <div className="flex items-start gap-3">
              <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                <Clock aria-hidden="true" className="h-5 w-5" />
              </span>

              <div className="min-w-0">
                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  How Fast We Reply
                </h2>
                <p className="mt-1 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                  Every channel is monitored during support hours so nothing
                  sits unread.
                </p>
              </div>
            </div>

            <ul className="mt-4 space-y-3">
              {RESPONSE_TIMES.map((item) => {
                const Icon = item.icon;

                return (
                  <li
                    key={item.label}
                    className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                  >
                    <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                      <Icon aria-hidden="true" className="h-3.5 w-3.5" />
                    </span>

                    <div className="min-w-0">
                      <p className="text-xs font-bold text-neutral-800 dark:text-neutral-100">
                        {item.label}
                      </p>
                      <p className="mt-0.5 text-sm leading-6 text-neutral-600 dark:text-neutral-300">
                        {item.value}
                      </p>
                    </div>
                  </li>
                );
              })}
            </ul>
          </section>

          {/* Emergency contact */}
          <section
            className={`${CARD_CLASS} flex flex-col p-4 sm:p-6`}
          >
            <div className="flex items-start gap-3">
              <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                <CircleAlert aria-hidden="true" className="h-5 w-5" />
              </span>

              <div className="min-w-0">
                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  Urgent Issue?
                </h2>
                <p className="mt-1 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                  Payment taken but order not created, wrong or damaged item
                  delivered, or a missing parcel: use these channels first and
                  include your order number.
                </p>
              </div>
            </div>

            <div className="mt-4 flex-1 space-y-3">
              <a
                href="tel:+1800123456"
                className="flex items-center gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 transition-colors hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-800 dark:bg-neutral-950/30 dark:hover:bg-neutral-800"
              >
                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                  <Phone aria-hidden="true" className="h-4 w-4" />
                </span>

                <span className="min-w-0">
                  <span className="block text-xs font-bold text-neutral-800 dark:text-neutral-100">
                    Emergency phone line
                  </span>
                  <span className="block text-sm font-bold text-blue-500 dark:text-blue-400">
                    +1 800 123 456
                  </span>
                </span>
              </a>

              <a
                href="mailto:support@hikala.example"
                className="flex items-center gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 transition-colors hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-800 dark:bg-neutral-950/30 dark:hover:bg-neutral-800"
              >
                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                  <Mail aria-hidden="true" className="h-4 w-4" />
                </span>

                <span className="min-w-0">
                  <span className="block text-xs font-bold text-neutral-800 dark:text-neutral-100">
                    Priority email queue
                  </span>
                  <span className="block break-all text-sm font-bold text-blue-500 dark:text-blue-400">
                    support@hikala.example
                  </span>
                </span>
              </a>
            </div>
          </section>
        </div>

        {/* ---------- CTA to contact ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Still stuck?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Send the team a message with your order details and we will pick
                it up from here.
              </p>
            </div>

            <Link
              href="/contact"
              className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <MessageCircle aria-hidden="true" className="h-4 w-4" />
              <span>Contact support</span>
            </Link>
          </div>
        </div>

        {/* ---------- Back to store ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Problem solved?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Head back to the store and keep browsing the curated catalog.
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
