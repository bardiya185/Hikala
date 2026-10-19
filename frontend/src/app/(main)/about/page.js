import React from "react";
import Link from "next/link";
import {
  Store,
  ArrowLeft,
  BadgeCheck,
  Truck,
  CreditCard,
  Headphones,
  ShieldCheck,
  FileText,
  Phone,
  Sparkles,
  Target,
  Rocket,
} from "lucide-react";

export const metadata = {
  title: "About Us | Hikala",
  description:
    "Learn about Hikala: our mission, our story, the values behind the store and the numbers that keep us moving.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  { id: "mission", anchorLabel: "Our mission" },
  { id: "story", anchorLabel: "Our story" },
  { id: "values", anchorLabel: "What we value" },
  { id: "numbers", anchorLabel: "By the numbers" },
  { id: "visit", anchorLabel: "Start shopping" },
];

const VALUES = [
  {
    title: "Quality Products",
    description:
      "Every item is curated and checked before it reaches a product page, so what you receive matches what you read.",
    icon: BadgeCheck,
  },
  {
    title: "Fast Delivery",
    description:
      "Parcels are prepared the same day they are sourced and handed to trusted courier partners without delay.",
    icon: Truck,
  },
  {
    title: "Secure Payment",
    description:
      "Checkout runs over encrypted connections through trusted banking gateways. Card details never touch our servers.",
    icon: CreditCard,
  },
  {
    title: "Customer Support",
    description:
      "Real people answer phones, chats and emails, from the first question to after-sales care.",
    icon: Headphones,
  },
];

const STORY = [
  {
    icon: Sparkles,
    text: "Hikala started as a small online shelf of hard-to-find home and tech goods, built on one simple idea: shopping online should feel as safe and personal as walking into a trusted neighborhood store.",
  },
  {
    icon: Target,
    text: "As orders grew, we doubled down on curation instead of volume. We work closely with verified suppliers and test categories one by one, so the catalog stays small enough to trust and wide enough to be useful.",
  },
  {
    icon: Rocket,
    text: "Today Hikala serves thousands of customers across the country with fast fulfilment, transparent pricing and support that answers quickly. The catalog is bigger, but the original promise has not changed.",
  },
];

const STATS = [
  { value: "119+", label: "Curated products" },
  { value: "12", label: "Product categories" },
  { value: "24/7", label: "Customer support" },
  { value: "10k+", label: "Orders delivered" },
];

export default function AboutPage() {
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
                  <Store aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    About Hikala
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Who we are, why we started and what we care about when you
                    place an order on this store.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>Since 2019</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Every listing is curated and quality-checked.</span>
            </span>

            <Link
              href="/services"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Explore our services
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          {/* Section nav */}
          <nav
            aria-label="About sections"
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
                Want to know more about the team behind the store? Our support
                line is happy to help.
              </p>
            </div>
          </nav>

          {/* Article */}
          <div className="min-w-0 space-y-4 sm:space-y-5">
            {/* Mission */}
            <section
              id="mission"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <Target aria-hidden="true" className="h-5 w-5" />
                </span>

                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  Our Mission
                </h2>
              </div>

              <p className="mt-3 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                Hikala exists to make online shopping simple and trustworthy. We
                hand-pick useful products at fair prices, show honest
                descriptions, and stand behind every order with fast delivery
                and real human support. No endless catalogs, no guesswork — just
                a curated store that respects your time and your money.
              </p>
            </section>

            {/* Story */}
            <section
              id="story"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <Sparkles aria-hidden="true" className="h-5 w-5" />
                </span>

                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  Our Story
                </h2>
              </div>

              <ol className="mt-4 space-y-3">
                {STORY.map((item, index) => {
                  const Icon = item.icon;

                  return (
                    <li
                      key={`story-${index}`}
                      className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                    >
                      <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                        {index + 1}
                      </span>

                      <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                        {item.text}
                      </p>
                    </li>
                  );
                })}
              </ol>
            </section>

            {/* Values */}
            <section
              id="values"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <BadgeCheck aria-hidden="true" className="h-5 w-5" />
                </span>

                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  What We Value
                </h2>
              </div>

              <p className="mt-3 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                Four commitments shape every part of the store, from sourcing to
                after-sales.
              </p>

              <div className="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                {VALUES.map((value) => {
                  const Icon = value.icon;

                  return (
                    <div
                      key={value.title}
                      className="rounded-lg border border-neutral-100 bg-neutral-50/60 p-4 dark:border-neutral-800 dark:bg-neutral-950/30"
                    >
                      <div className="flex items-center gap-2.5">
                        <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                          <Icon aria-hidden="true" className="h-4 w-4" />
                        </span>

                        <h3 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                          {value.title}
                        </h3>
                      </div>

                      <p className="mt-2.5 text-xs leading-6 text-neutral-600 dark:text-neutral-300">
                        {value.description}
                      </p>
                    </div>
                  );
                })}
              </div>
            </section>

            {/* Stats */}
            <section
              id="numbers"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <Rocket aria-hidden="true" className="h-5 w-5" />
                </span>

                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  Hikala by the Numbers
                </h2>
              </div>

              <div className="mt-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
                {STATS.map((stat) => (
                  <div
                    key={stat.label}
                    className="rounded-lg border border-neutral-100 bg-neutral-50/60 p-4 text-center dark:border-neutral-800 dark:bg-neutral-950/30"
                  >
                    <p className="text-xl font-extrabold text-red-500 dark:text-red-400 sm:text-2xl">
                      {stat.value}
                    </p>
                    <p className="mt-1 text-[11px] font-medium leading-5 text-neutral-500 dark:text-neutral-400">
                      {stat.label}
                    </p>
                  </div>
                ))}
              </div>
            </section>

            {/* ---------- Continue shopping ---------- */}
            <section
              id="visit"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    Ready to see it for yourself?
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Browse the curated catalog and place your order in a few
                    minutes.
                  </p>
                </div>

                <Link
                  href="/"
                  className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
                >
                  <ArrowLeft aria-hidden="true" className="h-4 w-4" />
                  <span>Continue shopping</span>
                </Link>
              </div>
            </section>

            {/* ---------- Back to store ---------- */}
            <div className={`${CARD_CLASS} p-4 sm:p-6`}>
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    Prefer to look around first?
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Head back to the homepage to explore categories, offers and
                    new arrivals.
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
