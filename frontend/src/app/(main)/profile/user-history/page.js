"use client";

import React, { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { History, ImageOff, Trash2 } from "lucide-react";
import toast from "react-hot-toast";

import { formatPrice } from "@/core/utils/formatPrice";
import { timeAgo } from "@/core/utils/formatDate";
import { RECENTLY_VIEWED_STORAGE_KEY } from "@/components/atom/RecentlyViewedTracker";

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const PLACEHOLDER_IMAGE = "/icons/test.webp";

// ------------------------------------------------------------
// Helpers
// ------------------------------------------------------------

function resolveImageSrc(image) {
  const raw = typeof image === "string" ? image.trim() : "";

  if (!raw) return null;

  if (/^(https?:)?\/\//i.test(raw)) return raw;

  if (raw.startsWith("/storage/")) {
    const base = process.env.NEXT_PUBLIC_BASE_URL || "";

    return base ? `${base.replace(/\/+$/, "")}${raw}` : raw;
  }

  return raw;
}

function normalizeEntry(entry) {
  if (!entry || entry.id === undefined || entry.id === null) return null;

  return {
    id: entry.id,
    title: typeof entry.title === "string" ? entry.title : "Product",
    price: Number(entry.price || 0),
    image: resolveImageSrc(entry.image),
    ts: Number(entry.ts || 0),
  };
}

function readRecentlyViewed() {
  try {
    const raw = window.localStorage.getItem(RECENTLY_VIEWED_STORAGE_KEY);
    if (!raw) return [];

    const parsed = JSON.parse(raw);
    if (!Array.isArray(parsed)) return [];

    return parsed
      .map(normalizeEntry)
      .filter(Boolean)
      .sort((a, b) => b.ts - a.ts);
  } catch (error) {
    console.error("Recently viewed read failed:", error);
    return [];
  }
}

// ------------------------------------------------------------
// Product card
// ------------------------------------------------------------

function ViewedProductCard({ item }) {
  return (
    <Link
      href={`/product/${item.id}`}
      dir="ltr"
      className="group flex min-w-0 flex-col rounded-xl border border-neutral-200 bg-white p-3 transition-all hover:shadow-md active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-800 dark:bg-neutral-900"
    >
      <div className="relative aspect-square w-full overflow-hidden rounded-lg bg-neutral-50 dark:bg-neutral-950/40">
        {item.image ? (
          <Image
            src={item.image}
            alt={item.title}
            fill
            unoptimized={/^https?:\/\//i.test(item.image)}
            sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 220px"
            className="object-contain p-2 transition-transform duration-500 group-hover:scale-105"
          />
        ) : (
          <div className="flex h-full w-full flex-col items-center justify-center gap-2 text-neutral-400 dark:text-neutral-600">
            <Image
              src={PLACEHOLDER_IMAGE}
              alt=""
              width={80}
              height={80}
              className="h-16 w-16 object-contain opacity-70"
            />
            <span className="text-[10px]">No image</span>
          </div>
        )}
      </div>

      <h3 className="mt-3 min-h-[40px] overflow-hidden text-xs font-bold leading-5 text-neutral-800 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:2] dark:text-neutral-100 sm:text-sm sm:leading-6">
        {item.title}
      </h3>

      <div className="mt-2 flex items-end justify-between gap-2">
        <p dir="ltr" className="min-w-0 text-sm font-bold text-green-600 sm:text-base">
          <span className="text-neutral-800 dark:text-neutral-100">
            {formatPrice(item.price)}
          </span>{" "}
          <span className="text-[10px] font-medium text-neutral-500 dark:text-neutral-400">
            Toman
          </span>
        </p>

        {item.ts > 0 && (
          <span className="shrink-0 text-[10px] text-neutral-400 dark:text-neutral-500">
            {timeAgo(new Date(item.ts))}
          </span>
        )}
      </div>
    </Link>
  );
}

// ------------------------------------------------------------
// Page
// ------------------------------------------------------------

export default function UserHistoryPage() {
  const [items, setItems] = useState([]);
  const [isReady, setIsReady] = useState(false);

  useEffect(() => {
    setItems(readRecentlyViewed());
    setIsReady(true);
  }, []);

  const isEmpty = useMemo(() => items.length === 0, [items]);

  const handleClear = () => {
    try {
      window.localStorage.removeItem(RECENTLY_VIEWED_STORAGE_KEY);
      setItems([]);
      toast.success("Viewing history cleared");
    } catch {
      toast.error("The history could not be cleared");
    }
  };

  return (
    <div dir="ltr" className="w-full">
      <div className={`${CARD_CLASS} p-4 sm:p-6`}>
        {/* Header */}
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="flex min-w-0 items-center gap-2">
            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
              <History aria-hidden="true" className="h-5 w-5" />
            </span>

            <div className="min-w-0">
              <h1 className="text-xl font-bold text-neutral-800 dark:text-neutral-100 sm:text-2xl">
                Recently Viewed
              </h1>
              <p className="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400 sm:text-sm">
                {isReady && !isEmpty
                  ? `${items.length} products in your last 20 views`
                  : "Products you browse recently are stored here"}
              </p>
            </div>
          </div>

          {isReady && !isEmpty && (
            <button
              type="button"
              onClick={handleClear}
              className="inline-flex h-9 items-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-3 text-xs font-bold text-neutral-600 transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-red-950/30"
            >
              <Trash2 aria-hidden="true" className="h-3.5 w-3.5" />
              <span>Clear history</span>
            </button>
          )}
        </div>

        <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

        {/* Loading skeleton */}
        {!isReady && (
          <div className="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4">
            {[1, 2, 3, 4, 5, 6].map((skeleton) => (
              <div
                key={skeleton}
                className="h-[230px] animate-pulse rounded-xl bg-neutral-100 dark:bg-neutral-800"
              />
            ))}
          </div>
        )}

        {/* Empty state */}
        {isReady && isEmpty && (
          <div className="flex min-h-[300px] flex-col items-center justify-center px-2 py-6 text-center">
            <span className="flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100 text-neutral-400 dark:bg-neutral-800 dark:text-neutral-500">
              <ImageOff aria-hidden="true" className="h-8 w-8" />
            </span>

            <h2 className="mt-4 text-base font-bold text-neutral-800 dark:text-neutral-100">
              You have not viewed any products yet
            </h2>

            <p className="mt-2 max-w-md text-sm leading-6 text-neutral-500 dark:text-neutral-400">
              As you browse categories and product pages, your latest views are
              recorded in this section so you can get back to them faster.
            </p>

            <Link
              href="/"
              className="mt-5 inline-flex h-11 items-center justify-center rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <span>Go to the home page</span>
            </Link>
          </div>
        )}

        {/* Grid */}
        {isReady && !isEmpty && (
          <div className="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4">
            {items.map((item) => (
              <ViewedProductCard key={item.id} item={item} />
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
