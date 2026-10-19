// src/app/(main)/order-tracking/TrackOrderClient.jsx

"use client";

import React, { useEffect, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import {
  Package,
  ArrowLeft,
  Search,
  LogIn,
  CheckCircle,
  CircleDot,
  Clock,
  Truck,
  ShieldCheck,
  AlertCircle,
  XCircle,
  RefreshCw,
  FileText,
  ShoppingBag,
} from "lucide-react";

import { useGetOrder } from "@/core/services/queries";
import { getCookie } from "@/core/utils/cookie";
import { formatPrice } from "@/core/utils/formatPrice";
import { formatDate } from "@/core/utils/formatDate";

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

// Normal 4-step journey
const STEPS = [
  { value: "pending", label: "Order placed", icon: ShoppingBag },
  { value: "processing", label: "Processing", icon: CircleDot },
  { value: "shipped", label: "Shipped", icon: Truck },
  { value: "delivered", label: "Delivered", icon: CheckCircle },
];

// Where each raw status sits on the journey ("paid" is between placed and processing)
const STATUS_INDEX = {
  pending: 0,
  paid: 1,
  processing: 1,
  shipped: 2,
  delivered: 3,
};

const TERMINAL_STATUSES = {
  canceled: {
    label: "Order cancelled",
    message:
      "This order was cancelled. If you already paid, the refund is issued through your original payment method.",
  },
  cancelled: {
    label: "Order cancelled",
    message:
      "This order was cancelled. If you already paid, the refund is issued through your original payment method.",
  },
  refunded: {
    label: "Order refunded",
    message:
      "This order was refunded. The amount returns to your original payment method or wallet within the refund window.",
  },
};

function normalizeStatus(status) {
  const raw = status?.value || status;
  return typeof raw === "string" ? raw.toLowerCase() : null;
}

function StatusTimeline({ currentIndex }) {
  return (
    <ol className="mt-5 space-y-0">
      {STEPS.map((step, index) => {
        const Icon = step.icon;
        const isDone = index < currentIndex;
        const isActive = index === currentIndex;

        return (
          <li key={step.value} className="relative flex gap-4 pb-8 last:pb-0">
            {index < STEPS.length - 1 && (
              <span
                aria-hidden="true"
                className="absolute left-5 top-10 h-[calc(100%-24px)] w-0.5 bg-neutral-200 dark:bg-neutral-800"
              />
            )}

            <span
              aria-hidden="true"
              className={`relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full ${
                isDone
                  ? "bg-red-500 text-white"
                  : isActive
                    ? "bg-blue-50 text-blue-500 ring-2 ring-blue-500/40 dark:bg-blue-950/40 dark:text-blue-300"
                    : "bg-neutral-100 text-neutral-400 dark:bg-neutral-800 dark:text-neutral-500"
              }`}
            >
              {isDone ? (
                <CheckCircle className="h-5 w-5" />
              ) : (
                <Icon className="h-5 w-5" />
              )}
            </span>

            <div className="min-w-0 pt-1">
              <p
                className={`text-sm font-bold ${
                  isDone || isActive
                    ? "text-neutral-900 dark:text-neutral-50"
                    : "text-neutral-400 dark:text-neutral-500"
                }`}
              >
                {step.label}
              </p>
              {isActive && (
                <span className="mt-1 inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
                  <Clock aria-hidden="true" className="h-3 w-3" />
                  Current step
                </span>
              )}
            </div>
          </li>
        );
      })}
    </ol>
  );
}

export default function TrackOrderClient() {
  const [isLoggedIn, setIsLoggedIn] = useState(null);
  const [inputValue, setInputValue] = useState("");
  const [orderId, setOrderId] = useState(null);
  const [hint, setHint] = useState(null);

  useEffect(() => {
    setIsLoggedIn(!!getCookie("access_token"));
  }, []);

  const {
    data: orderData,
    isLoading,
    isError,
    refetch,
  } = useGetOrder(orderId);
  const order = orderData?.data;

  const status = normalizeStatus(order?.status);
  const terminal = status ? TERMINAL_STATUSES[status] : null;
  const currentIndex =
    status && STATUS_INDEX[status] !== undefined ? STATUS_INDEX[status] : 0;

  const handleSubmit = (event) => {
    event.preventDefault();
    const trimmed = inputValue.trim();

    if (!trimmed) {
      setHint("Please enter your order ID first.");
      setOrderId(null);
      return;
    }

    setHint(null);
    setOrderId(trimmed);
  };

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
                  <Package aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h1 className="text-xl font-extrabold text-neutral-900 dark:text-neutral-50 sm:text-2xl">
                    Track your order
                  </h1>
                  <p className="mt-1.5 max-w-2xl text-xs leading-6 text-neutral-600 dark:text-neutral-300 sm:text-sm">
                    Enter the order ID you received after checkout and follow
                    your parcel step by step, from the warehouse to your door.
                  </p>
                </div>
              </div>

              <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[11px] font-bold text-neutral-600 shadow-sm dark:bg-neutral-900 dark:text-neutral-300">
                <FileText aria-hidden="true" className="h-3.5 w-3.5" />
                <span>Live order status</span>
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3 border-t border-neutral-200 px-4 py-3 dark:border-neutral-800 sm:px-6">
            <span className="inline-flex items-center gap-1.5 text-[11px] font-medium text-neutral-500 dark:text-neutral-400">
              <ShieldCheck aria-hidden="true" className="h-4 w-4 text-blue-500" />
              <span>Tracking is available for orders placed from your account.</span>
            </span>

            <Link
              href="/profile/orders"
              className="mr-auto text-xs font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              Browse all my orders
            </Link>
          </div>
        </div>

        {/* ---------- Not signed in ---------- */}
        {isLoggedIn === false && (
          <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Sign in required">
            <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
              <div className="flex min-w-0 items-start gap-3">
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300">
                  <LogIn aria-hidden="true" className="h-5 w-5" />
                </span>

                <div className="min-w-0">
                  <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                    Please sign in to track your order
                  </h2>
                  <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                    Order tracking uses your account, so we ask you to verify
                    your mobile number first. It only takes a minute.
                  </p>
                </div>
              </div>

              <Link
                href="/auth/login/sendOtp?redirect=/order-tracking"
                className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
              >
                <LogIn aria-hidden="true" className="h-4 w-4" />
                <span>Sign in</span>
              </Link>
            </div>
          </section>
        )}

        {/* ---------- Signed in: search + result ---------- */}
        {isLoggedIn === true && (
          <>
            {/* Search form */}
            <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Order lookup">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Find your order
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                You can find the order ID in your confirmation message and in
                your orders list.
              </p>

              <form
                onSubmit={handleSubmit}
                className="mt-4 flex flex-col gap-3 sm:flex-row"
              >
                <div className="relative min-w-0 flex-1">
                  <Search
                    aria-hidden="true"
                    className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400"
                  />
                  <input
                    type="text"
                    value={inputValue}
                    onChange={(event) => setInputValue(event.target.value)}
                    placeholder="Order ID — e.g. 123"
                    aria-label="Order ID"
                    className="h-11 w-full rounded-lg border border-neutral-200 bg-neutral-50 pl-9 pr-4 text-sm text-neutral-800 placeholder:text-neutral-400 transition-colors focus:border-red-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-red-500 dark:border-neutral-800 dark:bg-neutral-950/40 dark:text-neutral-100 dark:focus:bg-neutral-900"
                  />
                </div>

                <button
                  type="submit"
                  className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
                >
                  <Search aria-hidden="true" className="h-4 w-4" />
                  <span>Track</span>
                </button>
              </form>

              {hint && (
                <p className="mt-3 flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                  <AlertCircle aria-hidden="true" className="h-4 w-4" />
                  {hint}
                </p>
              )}
            </section>

            {/* Loading */}
            {isLoading && (
              <section
                className={`${CARD_CLASS} flex items-center justify-center gap-3 p-8`}
                aria-label="Loading order"
              >
                <span className="h-8 w-8 animate-spin rounded-full border-4 border-red-500 border-t-transparent" />
                <p className="text-sm font-medium text-neutral-500 dark:text-neutral-400">
                  Looking up your order…
                </p>
              </section>
            )}

            {/* Error / not found */}
            {!isLoading && isError && (
              <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Order not found">
                <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                  <div className="flex min-w-0 items-start gap-3">
                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-400">
                      <AlertCircle aria-hidden="true" className="h-5 w-5" />
                    </span>

                    <div className="min-w-0">
                      <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                        We could not find this order
                      </h2>
                      <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                        Double-check the order ID, or open your orders list to
                        pick the right one. If the problem continues, contact
                        support.
                      </p>
                    </div>
                  </div>

                  <div className="flex shrink-0 items-center gap-2">
                    <button
                      type="button"
                      onClick={() => refetch()}
                      className="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 text-xs font-bold text-neutral-700 shadow-sm transition-colors hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-950/40"
                    >
                      <RefreshCw aria-hidden="true" className="h-4 w-4" />
                      <span>Try again</span>
                    </button>

                    <Link
                      href="/profile/orders"
                      className="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-red-500 px-4 text-xs font-bold text-white transition-colors hover:bg-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
                    >
                      <ArrowLeft aria-hidden="true" className="h-4 w-4" />
                      <span>My orders</span>
                    </Link>
                  </div>
                </div>
              </section>
            )}

            {/* Result */}
            {!isLoading && !isError && order && (
              <>
                {/* Summary card */}
                <section className={`${CARD_CLASS} overflow-hidden`} aria-label="Order summary">
                  <div className="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-200 px-4 py-4 dark:border-neutral-800 sm:px-6">
                    <div className="min-w-0">
                      <h2 className="text-sm font-extrabold text-neutral-900 dark:text-neutral-50">
                        Order {order.order_number ? `#${order.order_number}` : `#${order.id}`}
                      </h2>
                      <p className="mt-1 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                        Placed on {formatDate(order.created_at)}
                      </p>
                    </div>

                    <span
                      className={`inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 text-[11px] font-bold ${
                        terminal
                          ? "bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400"
                          : status === "delivered"
                            ? "bg-blue-50 text-blue-500 dark:bg-blue-950/40 dark:text-blue-300"
                            : "bg-neutral-50 text-neutral-600 dark:bg-neutral-950/40 dark:text-neutral-300"
                      }`}
                    >
                      {terminal ? (
                        <XCircle aria-hidden="true" className="h-3.5 w-3.5" />
                      ) : (
                        <Package aria-hidden="true" className="h-3.5 w-3.5" />
                      )}
                      {terminal ? terminal.label : order.status?.label || status}
                    </span>
                  </div>

                  <div className="p-4 sm:p-6">
                    {terminal ? (
                      <div className="flex items-start gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-4 dark:border-neutral-800 dark:bg-neutral-950/30">
                        <XCircle
                          aria-hidden="true"
                          className="mt-0.5 h-5 w-5 shrink-0 text-red-500 dark:text-red-400"
                        />
                        <p className="min-w-0 text-sm leading-7 text-neutral-700 dark:text-neutral-200">
                          {terminal.message}
                        </p>
                      </div>
                    ) : (
                      <StatusTimeline currentIndex={currentIndex} />
                    )}
                  </div>
                </section>

                {/* Items card */}
                {order.items?.length > 0 && (
                  <section className={`${CARD_CLASS} p-4 sm:p-6`} aria-label="Order items">
                    <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                      Items in this order ({order.items.length})
                    </h2>

                    <ul className="mt-4 space-y-3">
                      {order.items.map((item) => (
                        <li
                          key={item.id}
                          className="flex items-center gap-3 rounded-lg border border-neutral-100 bg-neutral-50/60 p-3 dark:border-neutral-800 dark:bg-neutral-950/30"
                        >
                          <div className="relative h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-neutral-100 dark:bg-neutral-800">
                            {item.product_image ? (
                              <Image
                                src={item.product_image}
                                alt={item.product_title}
                                fill
                                className="object-cover"
                              />
                            ) : (
                              <span className="flex h-full items-center justify-center text-neutral-400 dark:text-neutral-500">
                                <Package aria-hidden="true" className="h-5 w-5" />
                              </span>
                            )}
                          </div>

                          <div className="min-w-0 flex-1">
                            <p className="truncate text-sm font-bold text-neutral-800 dark:text-neutral-100">
                              {item.product_title}
                            </p>
                            <p className="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                              Quantity: {item.quantity}
                            </p>
                          </div>

                          {item.total != null && (
                            <p className="shrink-0 text-sm font-bold text-neutral-900 dark:text-neutral-50">
                              ${formatPrice(item.total)}
                            </p>
                          )}
                        </li>
                      ))}
                    </ul>

                    {order.total_amount != null && (
                      <div className="mt-4 flex items-center justify-between border-t border-neutral-200 pt-4 dark:border-neutral-800">
                        <span className="text-xs font-bold text-neutral-500 dark:text-neutral-400">
                          Order total
                        </span>
                        <span className="text-sm font-extrabold text-neutral-900 dark:text-neutral-50">
                          ${formatPrice(order.total_amount)}
                        </span>
                      </div>
                    )}
                  </section>
                )}
              </>
            )}
          </>
        )}

        {/* ---------- Back to shop ---------- */}
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <div className="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div className="min-w-0">
              <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                Something wrong with a delivery?
              </h2>
              <p className="mt-1.5 text-xs leading-6 text-neutral-500 dark:text-neutral-400">
                Open a support request or check the returns page and we will
                sort it out with you.
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
