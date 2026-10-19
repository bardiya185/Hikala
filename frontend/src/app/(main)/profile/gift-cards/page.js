"use client";

import React, { useState } from "react";
import Link from "next/link";
import {
  Gift,
  Loader2,
  Sparkles,
  CreditCard,
  MessageSquare,
  ShoppingCart,
  Tag,
} from "lucide-react";
import toast from "react-hot-toast";

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const STEPS = [
  {
    icon: ShoppingCart,
    title: "Choose the amount and quantity",
    text: "In the gift card section, pick the amount you want (from 50,000 to 5,000,000 Toman) and how many cards you need.",
  },
  {
    icon: CreditCard,
    title: "Pay online",
    text: "Once the payment succeeds, your gift card code is texted and emailed to you right away.",
  },
  {
    icon: Gift,
    title: "Give the gift",
    text: "Send the code to a friend so they can shop without needing a bank card.",
  },
  {
    icon: Tag,
    title: "Redeem the code",
    text: "The recipient only has to enter the code on this page; the amount is added to their wallet.",
  },
];

export default function GiftCardsPage() {
  const [code, setCode] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = (event) => {
    event.preventDefault();

    const trimmed = code.trim();

    if (!trimmed) {
      toast.error("Please enter a gift card code");
      return;
    }

    setIsSubmitting(true);

    // No gift-card redemption endpoint exists yet — we never fake a success.
    window.setTimeout(() => {
      setIsSubmitting(false);
      toast.error("This gift card code is not valid");
    }, 450);
  };

  return (
    <div dir="ltr" className="w-full space-y-4 sm:space-y-5">
      {/* ---------- Header ---------- */}
      <div className={`${CARD_CLASS} p-4 sm:p-6`}>
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="min-w-0">
            <h1 className="text-xl font-bold text-neutral-800 dark:text-neutral-100 sm:text-2xl">
              Gift Card
            </h1>
            <p className="mt-1 text-xs text-neutral-500 dark:text-neutral-400 sm:text-sm">
              Buy, gift and redeem gift card codes
            </p>
          </div>

          <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-500 dark:bg-red-950/30 dark:text-red-400">
            <Sparkles aria-hidden="true" className="h-3.5 w-3.5" />
            <span>Gift credit</span>
          </span>
        </div>
      </div>

      {/* ---------- Balance + register ---------- */}
      <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div className={`${CARD_CLASS} overflow-hidden p-4 sm:p-6`}>
          <p className="text-xs font-semibold text-neutral-500 dark:text-neutral-400">
            Gift card balance
          </p>

          <div className="mt-3 flex items-end gap-2">
            <span className="text-3xl font-extrabold tracking-tight text-neutral-800 dark:text-neutral-50 sm:text-4xl">
              0
            </span>
            <span className="pb-1.5 text-sm font-bold text-neutral-500 dark:text-neutral-400">
              Toman
            </span>
          </div>

          <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

          <div className="mt-4 flex items-start gap-3 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-950/40">
            <span className="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-red-500 shadow-sm dark:bg-neutral-900">
              <Gift aria-hidden="true" className="h-4 w-4" />
            </span>
            <p className="text-xs leading-5 text-neutral-500 dark:text-neutral-400">
              No gift card is linked to your account yet. If you received a gift
              code, enter it in the field opposite.
            </p>
          </div>
        </div>

        <form
          onSubmit={handleSubmit}
          dir="ltr"
          className={`${CARD_CLASS} flex flex-col p-4 sm:p-6`}
        >
          <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
            Redeem a gift card code
          </h2>
          <p className="mt-1 text-xs leading-5 text-neutral-500 dark:text-neutral-400">
            Enter the 16-digit code printed on the gift card and move its balance
            into your wallet.
          </p>

          <label
            htmlFor="gift-code"
            className="mb-1.5 mt-5 block text-xs font-semibold text-neutral-600 dark:text-neutral-300"
          >
            Gift card code
          </label>

          <div className="flex flex-col gap-2 sm:flex-row">
            <input
              id="gift-code"
              dir="ltr"
              value={code}
              onChange={(event) =>
                setCode(event.target.value.toUpperCase())
              }
              inputMode="text"
              autoComplete="off"
              placeholder="1234-5678-9012-3456"
              className="h-11 min-w-0 flex-1 rounded-lg border border-neutral-200 bg-white px-3 text-left text-sm tracking-wider text-neutral-800 outline-none transition-colors placeholder:text-neutral-400 focus:border-red-400 focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-700 dark:bg-neutral-950/40 dark:text-neutral-100 dark:placeholder:text-neutral-500"
            />

            <button
              type="submit"
              disabled={isSubmitting}
              className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-5 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus-visible:ring-offset-neutral-900"
            >
              {isSubmitting ? (
                <>
                  <Loader2
                    aria-hidden="true"
                    className="h-4 w-4 animate-spin"
                  />
                  <span>Checking...</span>
                </>
              ) : (
                <span>Add code</span>
              )}
            </button>
          </div>

          <p className="mt-3 text-[11px] leading-5 text-neutral-400 dark:text-neutral-500">
            Gift codes are single-use; once redeemed they cannot be refunded or
            transferred.
          </p>
        </form>
      </div>

      {/* ---------- How to buy ---------- */}
      <div className={`${CARD_CLASS} p-4 sm:p-6`}>
        <div className="flex items-center gap-2">
          <span className="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
            <MessageSquare aria-hidden="true" className="h-5 w-5" />
          </span>

          <h2 className="text-base font-bold text-neutral-800 dark:text-neutral-100">
            How do I buy a gift card?
          </h2>
        </div>

        <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

        <ol className="mt-5 grid grid-cols-1 gap-3 sm:gap-4 lg:grid-cols-2">
          {STEPS.map((step, index) => {
            const Icon = step.icon;

            return (
              <li
                key={step.title}
                className="flex items-start gap-3 rounded-xl border border-neutral-100 bg-neutral-50/60 p-4 dark:border-neutral-800 dark:bg-neutral-950/30"
              >
                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-red-500 shadow-sm dark:bg-neutral-900">
                  <Icon aria-hidden="true" className="h-4 w-4" />
                </span>

                <div className="min-w-0">
                  <p className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    <span className="ml-1 text-neutral-400 dark:text-neutral-500">
                      {index + 1}.
                    </span>
                    {step.title}
                  </p>
                  <p className="mt-1 text-xs leading-5 text-neutral-500 dark:text-neutral-400">
                    {step.text}
                  </p>
                </div>
              </li>
            );
          })}
        </ol>

        <div className="mt-5 flex flex-col items-start justify-between gap-3 rounded-xl bg-neutral-50 p-4 dark:bg-neutral-950/40 sm:flex-row sm:items-center">
          <p className="text-xs leading-5 text-neutral-500 dark:text-neutral-400">
            Buying gift cards for a team or an organization? Talk to the Hikala
            support specialists.
          </p>

          <Link
            href="/"
            className="inline-flex h-10 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white px-4 text-xs font-bold text-neutral-700 transition-colors hover:bg-neutral-100 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800"
          >
            <span>Buy a gift card</span>
          </Link>
        </div>
      </div>
    </div>
  );
}
