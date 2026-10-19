import React from "react";
import Link from "next/link";
import {
  PhoneCall,
  ArrowLeft,
  Phone,
  Mail,
  MapPin,
  ShieldCheck,
  FileText,
  Clock,
  MessageSquare,
} from "lucide-react";
import ContactForm from "./ContactForm";

export const metadata = {
  title: "Contact Us | Hikala",
  description:
    "Reach the Hikala team by phone, email or in person, or send us a message and we will reply within 24 business hours.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  { id: "channels", anchorLabel: "Contact channels" },
  { id: "form", anchorLabel: "Send a message" },
  { id: "hours", anchorLabel: "Support hours" },
];

const CHANNELS = [
  {
    icon: Phone,
    title: "Phone Support",
    description:
      "Talk to a real person about orders, delivery and after-sales questions.",
    actionLabel: "+1 800 123 456",
    href: "tel:+1800123456",
    actionIcon: PhoneCall,
  },
  {
    icon: Mail,
    title: "Email",
    description:
      "Send documents, order IDs or detailed requests and keep a written trail.",
    actionLabel: "support@hikala.example",
    href: "mailto:support@hikala.example",
    actionIcon: Mail,
  },
  {
    icon: MapPin,
    title: "Head Office",
    description:
      "Visit our office for business orders and partnership meetings, by appointment.",
    actionLabel: "228 Market Street, Suite 400, San Francisco, CA 94103",
    href: null,
    actionIcon: MapPin,
  },
];

export default function ContactPage() {
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
                  <PhoneCall aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Contact Us
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Questions about an order, a return or a business
                    partnership? Choose the channel that suits you and our team
                    will take it from there.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>We reply within 24 business hours</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Never share your bank card password with anyone.</span>
            </span>

            <Link
              href="/support"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Browse self-service support
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          {/* Section nav */}
          <nav
            aria-label="Contact sections"
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
              <MessageSquare
                aria-hidden="true"
                className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
              />
              <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                Have your order number ready when you contact us — it helps the
                team resolve things faster.
              </p>
            </div>
          </nav>

          {/* Article */}
          <div className="min-w-0 space-y-4 sm:space-y-5">
            {/* Channels */}
            <section
              id="channels"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <Phone aria-hidden="true" className="h-5 w-5" />
                </span>

                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  Ways to Reach Us
                </h2>
              </div>

              <div className="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                {CHANNELS.map((channel) => {
                  const Icon = channel.icon;

                  return (
                    <div
                      key={channel.title}
                      className="flex flex-col rounded-lg border border-neutral-100 bg-neutral-50/60 p-4 dark:border-neutral-800 dark:bg-neutral-950/30"
                    >
                      <div className="flex items-center gap-2.5">
                        <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                          <Icon aria-hidden="true" className="h-4 w-4" />
                        </span>

                        <h3 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                          {channel.title}
                        </h3>
                      </div>

                      <p className="mt-2.5 flex-1 text-xs leading-6 text-neutral-600 dark:text-neutral-300">
                        {channel.description}
                      </p>

                      {channel.href ? (
                        <a
                          href={channel.href}
                          className="mt-3 inline-flex items-center gap-1.5 break-all text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
                        >
                          {channel.actionLabel}
                        </a>
                      ) : (
                        <p className="mt-3 text-xs font-bold leading-6 text-neutral-700 dark:text-neutral-200">
                          {channel.actionLabel}
                        </p>
                      )}
                    </div>
                  );
                })}
              </div>
            </section>

            {/* Form */}
            <section
              id="form"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <Mail aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                    Send Us a Message
                  </h2>
                  <p className="mt-1 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Fill in the form below. On submit it opens your own email
                    app with the message ready — nothing is sent until you press
                    send there.
                  </p>
                </div>
              </div>

              <ContactForm />
            </section>

            {/* Hours */}
            <section
              id="hours"
              className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
            >
              <div className="flex items-start gap-3">
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-300">
                  <Clock aria-hidden="true" className="h-5 w-5" />
                </span>

                <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                  Support Hours
                </h2>
              </div>

              <ul className="mt-4 space-y-3">
                <li className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30">
                  <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                    1
                  </span>
                  <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                    Phone and email support: Monday to Saturday, 9:00 AM to 6:00
                    PM.
                  </p>
                </li>
                <li className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30">
                  <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                    2
                  </span>
                  <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                    Order tracking questions are answered fastest through your
                    account, because the team sees the full order history.
                  </p>
                </li>
                <li className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30">
                  <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-red-500 shadow-sm dark:bg-neutral-900 dark:text-red-400">
                    3
                  </span>
                  <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                    Urgent delivery issues outside support hours are checked on
                    the next business morning, first thing.
                  </p>
                </li>
              </ul>
            </section>

            {/* ---------- Back to shop ---------- */}
            <div className={`${CARD_CLASS} p-4 sm:p-6`}>
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    Not in the mood to write an email?
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Most answers are one click away in the support center — or
                    simply keep browsing.
                  </p>
                </div>

                <div className="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                  <Link
                    href="/support"
                    className="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-5 text-sm font-bold text-neutral-700 transition-colors hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800"
                  >
                    <span>Support center</span>
                  </Link>

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
    </div>
  );
}
