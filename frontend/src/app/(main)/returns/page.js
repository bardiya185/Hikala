import React from "react";
import Link from "next/link";
import {
  RotateCcw,
  ArrowLeft,
  CheckCircle,
  XCircle,
  ClipboardList,
  Hourglass,
  Package,
  FileText,
  ShieldCheck,
  ListChecks,
} from "lucide-react";

export const metadata = {
  title: "Returns | Hikala",
  description:
    "Hikala returns and refunds policy: the 7-day return window, eligible items, how to start a return, and when your refund arrives.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const ELIGIBLE_ITEMS = [
  "Products returned within 7 days of the delivery date",
  "Undamaged, unused items with all accessories and original packaging",
  "Items that differ from the description or have a technical fault, even if opened",
  "Sale and discounted items, under the same 7-day conditions",
];

const RETURN_STEPS = [
  {
    title: "Open a return request",
    description:
      "Go to your orders, choose the delivered order and select 'Return an item'. Add a short reason and a photo if the item is faulty.",
  },
  {
    title: "Wait for approval",
    description:
      "Our team reviews the request within 1–2 business days and sends you a confirmation with the handover method.",
  },
  {
    title: "Hand over the parcel",
    description:
      "Drop the item at a courier office or book a courier pickup. Keep the item complete and safely packed until it is collected.",
  },
  {
    title: "Inspection and refund",
    description:
      "Once the warehouse confirms the item is in good condition, the refund is issued using the timeline table below.",
  },
];

const REFUND_TIMELINE = [
  {
    method: "Online payment (card gateway)",
    window: "3–7 business days after approval",
    note: "Refunded to the original card through the payment gateway.",
  },
  {
    method: "Wallet balance",
    window: "Within 24 hours after approval",
    note: "Credited to your Hikala wallet and usable on your next order.",
  },
  {
    method: "Cash on delivery",
    window: "5–7 business days after approval",
    note: "Transferred to the bank account you register with support.",
  },
];

const EXCLUSIONS = [
  "Consumable items such as food, supplements and opened personal care products",
  "Hygiene items and underwear once the seal is broken",
  "Sealed software, digital codes and gift cards after activation",
  "Items damaged by misuse, neglect or unauthorized repair",
  "Products without their original packaging and complete accessories",
];

export default function ReturnsPage() {
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
                  <RotateCcw aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Returns &amp; Refunds
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Changed your mind or received something wrong? You have 7
                    days from delivery to start a return, and the process is
                    the same for every order.
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
              <span>Refunds are issued within 7 business days of approval.</span>
            </span>

            <Link
              href="/profile/orders"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Go to my orders
            </Link>
          </div>
        </div>

        {/* ---------- Eligible items ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Eligible items">
          <div className="flex items-start gap-3">
            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
              <CheckCircle aria-hidden="true" className="h-5 w-5" />
            </span>

            <div className="min-w-0">
              <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                What can be returned
              </h2>
              <p className="mt-2 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                The 7-day return window applies to most products sold on
                Hikala. A return is accepted when all of the following hold:
              </p>

              <ul className="mt-4 space-y-3">
                {ELIGIBLE_ITEMS.map((item) => (
                  <li
                    key={item}
                    className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                  >
                    <CheckCircle
                      aria-hidden="true"
                      className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
                    />
                    <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                      {item}
                    </p>
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </section>

        {/* ---------- Steps to return ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="How to return an item">
          <div className="flex items-center gap-2">
            <ClipboardList aria-hidden="true" className="h-4 w-4 text-red-500" />
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              Steps to return an item
            </h2>
          </div>

          <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
            Four steps from request to refund.
          </p>

          <ol className="mt-4 space-y-3">
            {RETURN_STEPS.map((step, index) => (
              <li
                key={step.title}
                className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
              >
                <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                  {index + 1}
                </span>

                <div className="min-w-0">
                  <h3 className="text-sm font-bold text-neutral-900 dark:text-neutral-50">
                    {step.title}
                  </h3>
                  <p className="mt-1 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                    {step.description}
                  </p>
                </div>
              </li>
            ))}
          </ol>

          {/* Start a return guidance */}
          <div className="mt-4 flex flex-col items-start justify-between gap-3 rounded-lg bg-neutral-50 p-4 dark:bg-neutral-950/40 sm:flex-row sm:items-center">
            <div className="flex items-start gap-2">
              <Package
                aria-hidden="true"
                className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
              />
              <p className="text-xs leading-6 text-neutral-600 dark:text-neutral-300">
                <span className="font-bold text-neutral-800 dark:text-neutral-100">
                  Start a return:
                </span>{" "}
                sign in, open the order from your orders list and submit the
                request from there.
              </p>
            </div>

            <Link
              href="/profile/orders"
              className="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-5 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <ArrowLeft aria-hidden="true" className="h-4 w-4" />
              <span>My orders</span>
            </Link>
          </div>
        </section>

        {/* ---------- Refund timeline table ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Refund timeline">
          <div className="flex items-center gap-2">
            <Hourglass aria-hidden="true" className="h-4 w-4 text-blue-500" />
            <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
              When will I get my money back?
            </h2>
          </div>

          <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
            The refund clock starts when your returned item passes inspection.
          </p>

          <div className="mt-4 overflow-x-auto rounded-lg border border-neutral-100 dark:border-neutral-800">
            <table className="w-full min-w-[560px] text-left text-sm">
              <thead>
                <tr className="border-b border-neutral-200 bg-neutral-50 text-xs font-bold text-neutral-500 dark:border-neutral-800 dark:bg-neutral-950/40 dark:text-neutral-400">
                  <th scope="col" className="px-4 py-3">
                    Original payment method
                  </th>
                  <th scope="col" className="px-4 py-3">
                    Refund window
                  </th>
                  <th scope="col" className="px-4 py-3">
                    How it is returned
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-neutral-100 dark:divide-neutral-800">
                {REFUND_TIMELINE.map((row) => (
                  <tr key={row.method}>
                    <td className="px-4 py-3 font-bold text-neutral-800 dark:text-neutral-100">
                      {row.method}
                    </td>
                    <td className="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                      {row.window}
                    </td>
                    <td className="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                      {row.note}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>

        {/* ---------- Exclusions ---------- */}
        <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Return exclusions">
          <div className="flex items-start gap-3">
            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-400">
              <XCircle aria-hidden="true" className="h-5 w-5" />
            </span>

            <div className="min-w-0">
              <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                What cannot be returned
              </h2>
              <p className="mt-2 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                For hygiene and safety reasons, the following items are
                excluded from the 7-day window, unless they arrive faulty or
                different from their description:
              </p>

              <ul className="mt-4 space-y-3">
                {EXCLUSIONS.map((item) => (
                  <li
                    key={item}
                    className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                  >
                    <XCircle
                      aria-hidden="true"
                      className="mt-0.5 h-4 w-4 shrink-0 text-red-500 dark:text-red-400"
                    />
                    <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                      {item}
                    </p>
                  </li>
                ))}
              </ul>

              <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
                <ListChecks
                  aria-hidden="true"
                  className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
                />
                <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                  Not sure if your item qualifies? Check the full rules in the{" "}
                  <Link
                    href="/terms"
                    className="font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
                  >
                    terms &amp; conditions
                  </Link>{" "}
                  or ask our support team.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* ---------- Back to shop ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Shopping with confidence
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                If an item is not right for you, sending it back takes only a
                few minutes.
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
