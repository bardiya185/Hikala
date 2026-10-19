import React from "react";
import Link from "next/link";
import { Scale, ArrowLeft, ShieldCheck, FileText, Phone } from "lucide-react";

export const metadata = {
  title: "Terms & Conditions | Hikala",
  description:
    "Terms and conditions for using the Hikala online store: placing orders, payment, delivery, returns and responsibilities.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  {
    id: "intro",
    title: "1. Introduction and Acceptance",
    anchorLabel: "Introduction",
    intro:
      "Using the Hikala online store means you fully accept these terms. Every order placed on this site is arranged and carried out under these rules.",
    clauses: [
      "Hikala is an online store and reserves the right to change any part of these terms; the valid version is the one published on this page.",
      "All listed prices are in Toman and include value-added tax, unless stated otherwise.",
      "These terms are binding between you and Hikala. Where they are silent, the Electronic Commerce Law and other applicable law govern.",
    ],
  },
  {
    id: "account",
    title: "2. Your Account and Responsibilities",
    anchorLabel: "Account",
    intro:
      "Signing up on Hikala is free and only requires a valid mobile number.",
    clauses: [
      "You agree to enter correct information (name, contact number, address and postal code); you are responsible for the consequences of wrong details.",
      "Keeping your password and login details safe is up to you, and any activity carried out through your account is treated as your own.",
      "Creating more than one account to abuse special offers is not allowed, and Hikala may cancel the related orders.",
      "You can contact support at any time to request deletion of your account and your data.",
    ],
  },
  {
    id: "order",
    title: "3. Placing Orders and Product Prices",
    anchorLabel: "Placing an Order",
    intro:
      "An order is finalized after you choose a product, add it to the cart and complete the payment.",
    clauses: [
      "Placing an order is not a confirmed contract; until the product is sourced and the parcel is prepared, the price may change or the order may be cancelled, and we will let you know.",
      "If a product suddenly becomes unavailable (out of stock or a pricing error), the full amount is refunded to your account.",
      "We try to publish accurate images and technical specifications. If there is a mismatch, the description on the site is the reference and you may cancel the purchase.",
      "Minimum order amount, cart limits and the allowed quantity per product may be restricted during sales events.",
    ],
  },
  {
    id: "payment",
    title: "4. Payment and Transaction Security",
    anchorLabel: "Payment",
    intro:
      "Payments are made through trusted banking gateways over a secure protocol.",
    clauses: [
      "Hikala never stores your bank card details or second password; all transactions take place between you and the bank or payment gateway.",
      "If a gateway error happens, the amount is refunded to your account automatically within 24 to 72 business hours.",
      "Using a gift card and wallet credit requires an active account and the conditions listed in that same section.",
      "Payment fees or any extra charge are shown to you at the checkout step, before the final payment.",
    ],
  },
  {
    id: "shipping",
    title: "5. Delivery and Handover",
    anchorLabel: "Delivery",
    intro:
      "Delivery time and cost depend on the shipping method you choose, the origin warehouse and the destination.",
    clauses: [
      "The delivery window is shown on each product page or at checkout, and it counts from the moment the parcel is prepared, not from the moment you order.",
      "Orders are handed over only at the registered address, to a recipient who is present there.",
      "For orders above the set amount, delivery is free to some cities; whether your order qualifies is shown in the cart.",
      "Delays caused by external factors (weather, postal disruptions, city restrictions) are not charged to Hikala, but we keep tracking the parcel and informing you.",
    ],
  },
  {
    id: "returns",
    title: "6. Returns and Changing Your Mind",
    anchorLabel: "Returns",
    intro:
      "Your satisfaction comes first, which is why the returns process is defined clearly.",
    clauses: [
      "Non-consumable products can be returned within 7 days of delivery if they are undamaged, complete with all accessories, and still in their original packaging.",
      "Consumable, hygiene and underwear items and sealed software are not returnable because of what they are, unless they differ from the description or have a technical fault.",
      "Large appliances (refrigerators, washing machines and the like) must be checked before installation; once installed, a return is only possible with an authorized expert's approval.",
      "Return shipping is on you if you simply change your mind, and on Hikala if the product is faulty.",
      "Refunds for returned items are issued within 7 business days after the product is confirmed to be in good condition.",
    ],
  },
  {
    id: "content",
    title: "7. Content and Intellectual Property",
    anchorLabel: "Content",
    intro:
      "All content published on Hikala belongs to this store or to its suppliers.",
    clauses: [
      "Republishing text, images or page structure without crediting the source and getting written permission is not allowed.",
      "The Hikala trade name, logo and mark are protected by law, and similar use of them is prohibited.",
      "Reviews and questions submitted by users may be shown on the product page to guide other buyers, once personal details are removed.",
      "Any content or comment that is offensive, promotional, unlawful or violates the rights of third parties is removed.",
    ],
  },
  {
    id: "liability",
    title: "8. Limits of Responsibility and Force Majeure",
    anchorLabel: "Responsibility",
    intro:
      "Hikala delivers its services according to standards, but some things are outside its control.",
    clauses: [
      "Misuse, unauthorized installation or ignoring the manufacturer's instructions is outside Hikala's obligations.",
      "In force majeure situations (nationwide internet outages, natural disasters, sanctions and the like), order fulfilment may be paused and we will keep you informed.",
      "The seller concerned is responsible for promotional content and specifications provided by third-party sellers.",
      "Under no circumstances can indirect losses (lost profit or opportunity) be claimed from Hikala.",
    ],
  },
  {
    id: "contact",
    title: "9. Resolving Disputes and Contacting Us",
    anchorLabel: "Contact Us",
    intro:
      "Our first aim is to settle matters amicably within the company.",
    clauses: [
      "If any problem comes up, use the 'Submit a request' form in your account or call support; we reply within 24 business hours.",
      "If no solution is reached, the matter is followed up through the competent authorities within the framework of the law.",
      "Support hours: Saturday to Thursday, 9:00 AM to 6:00 PM.",
    ],
  },
];

export default function TermsPage() {
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
                  <Scale aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Hikala Terms &amp; Conditions
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    This page sets out the framework for using the Hikala online
                    store, from signing up and buying to delivery, returns and
                    buyer protection.
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
              <span>This text is the store&apos;s general terms.</span>
            </span>

            <Link
              href="/privacy"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              View privacy policy
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          {/* Section nav */}
          <nav
            aria-label="Terms sections"
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
                    {section.anchorLabel}
                  </a>
                </li>
              ))}
            </ul>

            <div className="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

            <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
              <Phone aria-hidden="true" className="mt-0.5 h-4 w-4 shrink-0 text-blue-500" />
              <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                Question or objection about these terms? Contact Hikala support.
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
                    Ready to start shopping?
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Once you accept these terms you can place your order in a few
                    minutes.
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
