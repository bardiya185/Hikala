"use client";

import { useEffect } from "react";

export const RECENTLY_VIEWED_STORAGE_KEY = "hikala_recently_viewed";

export const RECENTLY_VIEWED_LIMIT = 20;

/**
 * Accepts a raw product image value which may be a string URL,
 * an image object ({ image_url | url }) or nothing at all.
 */
function resolveImageUrl(image) {
  if (typeof image === "string") return image.trim();

  if (image && typeof image === "object") {
    return String(image.image_url || image.url || "").trim();
  }

  return "";
}

function readRecentlyViewed() {
  if (typeof window === "undefined") return [];

  try {
    const raw = window.localStorage.getItem(RECENTLY_VIEWED_STORAGE_KEY);
    if (!raw) return [];

    const parsed = JSON.parse(raw);
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
}

/**
 * Records the currently viewed product in localStorage so the
 * "recently viewed" profile page can list it later.
 *
 * Renders nothing.
 */
export default function RecentlyViewedTracker({ product }) {
  const id = product?.id ?? product?._id ?? null;
  const title = product?.title || product?.name || "";
  const price = Number(product?.price || 0) || 0;
  const image = resolveImageUrl(product?.image);

  useEffect(() => {
    if (typeof window === "undefined" || !id) return;

    try {
      const list = readRecentlyViewed();

      const entry = {
        id,
        title,
        price,
        image,
        ts: Date.now(),
      };

      const next = [
        entry,
        ...list.filter(
          (item) => String(item?.id) !== String(id)
        ),
      ].slice(0, RECENTLY_VIEWED_LIMIT);

      window.localStorage.setItem(
        RECENTLY_VIEWED_STORAGE_KEY,
        JSON.stringify(next)
      );
    } catch (error) {
      console.error("Recently viewed tracking failed:", error);
    }
  }, [id, title, price, image]);

  return null;
}
