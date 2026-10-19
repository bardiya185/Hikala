import React from "react";
import Link from "next/link";
import {
  Layers,
  ArrowLeft,
  Search,
  Truck,
  Wrench,
  Briefcase,
  ShieldCheck,
  Handshake,
  FileText,
  Phone,
} from "lucide-react";

export const metadata = {
  title: "Our Services | Hikala",
  description:
    "The services behind every Hikala order: product sourcing, express shipping, installation support, business orders, warranty care and seller partnerships.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SERVICES = [
  {
    icon: Search,
    title: "Product Sourcing",
    description:
      "Tell us what you need and our team finds genuine products from verified suppliers, with transparent pricing before you commit.",
  },
  {
    icon: Truck,
    title: "Express Shipping",
    description:
      "Same-day dispatch on in-stock items and tracked delivery through trusted courier partners, with live status updates at every step.",
  },
  {
    icon: Wrench,
    title: "Installation Support",
    description:
      "For appliances and equipment that need setup, we coordinate authorized installers and stay available until everything works.",
  },
  {
    icon: Briefcase,
    title: "Business Orders",
    description:
      "Volume pricing, consolidated invoicing and scheduled deliveries for offices, shops and projects that order beyond the cart.",
  },
  {
    icon: ShieldCheck,
    title: "Warranty & After-sales",
    description:
      "We manage warranty claims, replacements and repairs on your behalf, so you deal with one team instead of chasing suppliers.",
  },
  {
    icon: Handshake,
    title: "Seller Partnerships",
    description:
      "Brands and suppliers can list on Hikala with full onboarding support, catalog tooling and performance reporting.",
  },
];

export default function ServicesPage() {
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
                  <Layers aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Our Services
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Shopping is only the beginning. These are the services the
                    Hikala team runs before, during and after every order.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>Six core services</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Most services are included with your order at no extra cost.</span>
            </span>

            <Link
              href="/support"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Visit the support center
            </Link>
          </div>
        </div>

        {/* ---------- Service cards ---------- */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {SERVICES.map((service) => {
            const Icon = service.icon;

            return (
              <section
                key={service.title}
                className={`${CARD_CLASS} flex flex-col p-4 sm:p-6`}
              >
                <div className="flex items-start gap-3">
                  <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                    <Icon aria-hidden="true" className="h-5 w-5" />
                  </span>

                  <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                    {service.title}
                  </h2>
                </div>

                <p className="mt-3 flex-1 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                  {service.description}
                </p>
              </section>
            );
          })}
        </div>

        {/* ---------- CTA to contact ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Need sourcing, business orders or installation?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Describe your case to our team and we will set the right service
                up for you, usually within one business day.
              </p>
            </div>

            <Link
              href="/contact"
              className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <Phone aria-hidden="true" className="h-4 w-4" />
              <span>Talk to our team</span>
            </Link>
          </div>
        </div>

        {/* ---------- Back to store ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                See the services in action
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Every product page shows the shipping, warranty and support
                options that apply to that order.
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
