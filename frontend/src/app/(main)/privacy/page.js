import React from "react";
import Link from "next/link";
import {
  ShieldCheck,
  ArrowLeft,
  Lock,
  Cookie,
  UserCheck,
  FileText,
} from "lucide-react";

export const metadata = {
  title: "Privacy Policy | Hikala",
  description:
    "Privacy policy for the Hikala online store: what data we collect, why it is used and your rights over your information.",
};

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const SECTIONS = [
  {
    id: "scope",
    title: "1. Scope of This Policy",
    anchorLabel: "Scope and definitions",
    icon: FileText,
    intro:
      "Your privacy matters to us. This policy explains what information Hikala collects while you shop, sign up or contact support, and how that information is stored.",
    clauses: [
      "This policy applies to the Hikala website, app and support services, and serving a 'user' means anyone who uses these channels.",
      "By signing up or continuing to shop on Hikala, you agree to the collection and processing of your information as described here.",
      "Where the law clearly requires something else, the law takes priority over this policy.",
    ],
  },
  {
    id: "collected",
    title: "2. Information We Collect",
    anchorLabel: "Collected information",
    icon: UserCheck,
    intro:
      "We collect only the data we need to run your order and improve our service.",
    clauses: [
      "Identity information: first and last name, mobile number, postal address, postal code and email.",
      "Order information: cart, items, shipping method, delivery address and payment status (never your bank card details).",
      "Technical information: IP address, browser and device type, referring page and technical error reports.",
      "Content information: your reviews, questions, attached files and conversations with support.",
    ],
  },
  {
    id: "usage",
    title: "3. How Your Information Is Used",
    anchorLabel: "Data use",
    icon: Lock,
    intro:
      "Your data is processed only within the purposes stated here.",
    clauses: [
      "Recording, processing and delivering orders, and updating you on each shipping step.",
      "Authentication, support, answering questions and handling return requests.",
      "Preventing violations, abuse and fraud, and keeping accounts secure.",
      "Improving the shopping experience and personalizing offers based on your interests; this part can be turned off.",
      "Marketing messages are sent only if you chose to subscribe, and every message has an unsubscribe link.",
    ],
  },
  {
    id: "cookies",
    title: "4. Cookies and Tracking Technologies",
    anchorLabel: "Cookies",
    icon: Cookie,
    intro:
      "Hikala uses cookies and analytics tools to sign you in, keep your cart and measure how the site performs.",
    clauses: [
      "Essential cookies: needed for signing in, the cart and checkout, and they cannot be removed.",
      "Analytics cookies: they help us see which pages are most read and which buying steps are difficult.",
      "You can limit or delete cookies in your browser settings; in that case some features may stop working.",
      "Location data (province, city and coordinates) is collected only to calculate delivery cost and time, and only with your permission.",
    ],
  },
  {
    id: "sharing",
    title: "5. Sharing With Third Parties",
    anchorLabel: "Sharing",
    icon: ShieldCheck,
    intro:
      "We do not sell your information. Data is shared with technical partners only as far as needed.",
    clauses: [
      "Postal and courier companies: to deliver orders (name, address and contact number).",
      "Bank payment gateways: to complete the transaction; Hikala does not store your card details.",
      "Cloud and technical support providers: under confidentiality agreements and with limited access.",
      "Legal authorities: only where the law requires it or on a valid request from a competent body.",
    ],
  },
  {
    id: "security",
    title: "6. Security and Retention",
    anchorLabel: "Data security",
    icon: Lock,
    intro:
      "We use suitable technical and organizational measures to protect your information.",
    clauses: [
      "The connection between your browser and Hikala's servers is encrypted (HTTPS).",
      "Staff access to user information is limited, role-based and traceable.",
      "Account data is kept while the account is active, and order records are kept for as long as tax law requires.",
      "If information is ever leaked, you will be notified as the law requires.",
    ],
  },
  {
    id: "rights",
    title: "7. Your Rights",
    anchorLabel: "User rights",
    icon: UserCheck,
    intro:
      "You are in control of your information and can submit your request at any time.",
    clauses: [
      "Get a copy of your personal information and review your order history in your account.",
      "Correct or update information that is wrong or incomplete.",
      "Request deletion of your account and data; information we must keep by law is exempt.",
      "Object to data processing and stop promotional messages using the link inside the message.",
      "To use these rights, go to the 'Account information' section or contact Hikala support.",
    ],
  },
  {
    id: "children",
    title: "8. Teen Privacy",
    anchorLabel: "Teens",
    icon: ShieldCheck,
    intro:
      "Shopping on Hikala requires an active account with a mobile number.",
    clauses: [
      "Anyone under 18 should register only with a parent's or guardian's permission and supervision.",
      "If a teenager's information was collected without guardian consent, we delete it as soon as you contact us.",
    ],
  },
  {
    id: "changes",
    title: "9. Changes to This Policy",
    anchorLabel: "Changes",
    icon: FileText,
    intro:
      "This text may be updated as our services grow or the rules change.",
    clauses: [
      "The new version is published on this page and the update date at the top of the page is changed.",
      "Important changes are announced to users by email or SMS.",
      "Keeping on using the services after publication means you accept the updated version.",
    ],
  },
  {
    id: "contact",
    title: "10. Contacting Us",
    anchorLabel: "Contact us",
    icon: FileText,
    intro:
      "Tell us any question, suggestion or complaint you have about privacy.",
    clauses: [
      "Support phone: every day from 9:00 AM to 6:00 PM.",
      "The 'Submit a request' form in your account is the fastest way to follow up on anything about your data.",
    ],
  },
];

export default function PrivacyPage() {
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
                  <ShieldCheck aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Hikala Privacy Policy
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    What information we collect about you, why we need it, where it
                    is stored and how much control you have over it.
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
              <Lock aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Bank card details are never stored on Hikala.</span>
            </span>

            <Link
              href="/terms"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              View terms &amp; conditions
            </Link>
          </div>
        </div>

        {/* ---------- Nav + article ---------- */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-5">
          <nav
            aria-label="Privacy sections"
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
                    className="block rounded-lg px-3 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-50 hover:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-neutral-300 dark:hover:bg-neutral-800"
                  >
                    {section.anchorLabel}
                  </a>
                </li>
              ))}
            </ul>

            <div className="mt-4 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

            <div className="mt-4 flex items-start gap-2 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
              <UserCheck
                aria-hidden="true"
                className="mt-0.5 h-4 w-4 shrink-0 text-blue-500"
              />
              <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
                To access, correct or delete your information, go to the 'Account
                information' section.
              </p>
            </div>
          </nav>

          <div className="min-w-0 space-y-4 sm:space-y-5">
            {SECTIONS.map((section) => {
              const Icon = section.icon;

              return (
                <section
                  key={section.id}
                  id={section.id}
                  className={`${CARD_CLASS} scroll-mt-24 p-4 sm:p-6`}
                >
                  <div className="flex items-start gap-3">
                    <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
                      <Icon aria-hidden="true" className="h-5 w-5" />
                    </span>

                    <h2 className="text-base font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-lg">
                      {section.title}
                    </h2>
                  </div>

                  <p className="mt-3 text-sm leading-7 text-neutral-600 dark:text-neutral-300">
                    {section.intro}
                  </p>

                  <ol className="mt-4 space-y-3">
                    {section.clauses.map((clause, index) => (
                      <li
                        key={`${section.id}-${index}`}
                        className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                      >
                        <span className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[11px] font-bold text-blue-500 shadow-sm dark:bg-neutral-900 dark:text-blue-300">
                          {index + 1}
                        </span>

                        <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                          {clause}
                        </p>
                      </li>
                    ))}
                  </ol>
                </section>
              );
            })}

            {/* ---------- Back to shop ---------- */}
            <div className={`${CARD_CLASS} p-4 sm:p-6`}>
              <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    Shop with peace of mind
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Your data is used only to run your order and is never given to
                    any third party.
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
