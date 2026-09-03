"use client";

import Image from "next/image";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { TfiAlignLeft } from "react-icons/tfi";
import ReactStars from "react-stars";
import { motion } from "framer-motion";
import { formatPrice } from "@/core/utils/formatPrice";
import { FreeShippingBadge } from "@/components/atom/FreeShippingBadge.";
import WishlistButton from "@/components/WishlistButton";
import { useWishlistIds } from "@/core/services/queries";
import { useMemo, useEffect } from "react";

const SORT_OPTIONS = [
  {
    label: "Newest",
    sortBy: "created_at",
    sortOrder: "asc",
  },
  {
    label: "The cheapest",
    sortBy: "base_price",
    sortOrder: "asc",
  },
  {
    label: "The most expensive",
    sortBy: "base_price",
    sortOrder: "desc",
  },
];

// ============================================================
// SORT BUTTON
// ============================================================

function SortButton({ active, onClick, children }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`
        shrink-0
        rounded-lg
        px-2.5
        py-1.5
        text-[11px]
        font-semibold
        transition-colors
        sm:px-3
        sm:text-xs
        ${
          active
            ? "bg-red-50 text-red-500"
            : "text-neutral-400 hover:text-neutral-600"
        }
      `}
    >
      {children}
    </button>
  );
}

// ============================================================
// SORT BAR
// ============================================================

function SortBar({ currentSort, currentSortOrder, onSortChange }) {
  return (
    <div
      dir="ltr"
      className="mb-3 flex w-full items-center gap-2 overflow-x-auto px-1 pb-1 sm:mb-4 sm:gap-3 sm:px-0"
    >
      <div className="flex shrink-0 items-center gap-1.5 text-neutral-700 sm:gap-2">
        <TfiAlignLeft size={16} className="sm:h-[18px] sm:w-[18px]" />
        <span className="text-xs font-semibold sm:text-sm">Sort:</span>
      </div>

      {SORT_OPTIONS.map(({ label, sortBy, sortOrder }) => {
        const isActive =
          currentSort === sortBy && currentSortOrder === sortOrder;

        return (
          <SortButton
            key={`${sortBy}-${sortOrder}`}
            active={isActive}
            onClick={() => onSortChange(sortBy, sortOrder)}
          >
            {label}
          </SortButton>
        );
      })}
    </div>
  );
}

// ============================================================
// HELPERS
// ============================================================

function getProductVariant(product) {
  const variants = product?.variants;
  if (!Array.isArray(variants) || variants.length === 0) return null;

  return (
    variants.find((variant) => variant?.is_default && variant?.is_active) ??
    variants.find((variant) => variant?.is_active) ??
    variants[0]
  );
}

export function getProductPricing(product) {
  const variant = getProductVariant(product);
  const basePrice = variant?.base_price ?? 0;
  const finalPrice = variant?.final_price ?? 0;
  const discountPercent = variant?.discount_percent ?? 0;

  return {
    basePrice,
    finalPrice,
    discountPercent,
    hasDiscount: finalPrice < basePrice && basePrice > 0,
  };
}

export function hasFreeShipping(product) {
  const variant = getProductVariant(product);
  return variant?.shipping_features?.some(
    (feature) => feature?.type === "free" && feature?.is_active === true
  );
}

// ============================================================
// DISCOUNT BADGE
// ============================================================

function DiscountBadge({ discountPercent }) {
  if (!discountPercent || discountPercent <= 0) return null;

  return (
    <div className="flex shrink-0 items-center rounded-md bg-red-50 px-1.5 py-1 text-[9px] font-bold text-red-600 sm:px-2 sm:text-[10px]">
      <svg
        className="mr-0.5 h-2.5 w-2.5 fill-current sm:h-3 sm:w-3"
        viewBox="0 0 24 24"
        aria-hidden="true"
      >
        <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l-7-7c-.37-.36-.59-.86-.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" />
      </svg>
      <span>{discountPercent}% Off</span>
    </div>
  );
}

// ============================================================
// PRODUCT RATING
// ============================================================

export function ProductRating({ rating }) {
  const productRating = rating || 0;

  return (
    <div className="flex shrink-0 items-center gap-0.5 text-[10px] text-neutral-600 sm:gap-1 sm:text-xs">
      <ReactStars
        count={1}
        value={productRating}
        size={15}
        color2="#fbbf24"
        edit={false}
        half
      />
      <span>{productRating}</span>
    </div>
  );
}

// ============================================================
// PRODUCT IMAGE
// ============================================================

export function ProductImage({ product, priority }) {
  return (
    <div className="relative flex aspect-[4/5] w-full items-center justify-center overflow-hidden rounded-xl bg-white sm:rounded-2xl">
      <Image
        src="/icons/images.jfif"
        width={200}
        height={250}
        alt={product?.title || "product"}
        priority={priority}
        className="h-full w-full object-contain transition-transform duration-500 group-hover:scale-105"
      />
    </div>
  );
}

// ============================================================
// PRODUCT SKELETON (LOADING)
// ============================================================

function ProductSkeleton() {
  return (
    <div className="group relative min-w-0 rounded-2xl border border-neutral-100 bg-white p-1.5 sm:rounded-[20px] sm:p-2 lg:p-3">
      <div className="relative flex h-full flex-col justify-between rounded-xl border border-neutral-100 p-2 sm:rounded-[10px] sm:p-3 lg:p-4">
        {/* Image Skeleton */}
        <div className="aspect-[4/5] w-full animate-pulse rounded-xl bg-neutral-200 sm:rounded-2xl" />

        {/* Title Skeleton */}
        <div className="mt-3 space-y-2">
          <div className="h-4 w-3/4 animate-pulse rounded bg-neutral-200" />
          <div className="h-4 w-1/2 animate-pulse rounded bg-neutral-200" />
        </div>

        {/* Price Skeleton */}
        <div className="mt-3 flex items-center justify-between">
          <div className="h-5 w-20 animate-pulse rounded bg-neutral-200" />
          <div className="h-5 w-16 animate-pulse rounded bg-neutral-200" />
        </div>

        {/* Bottom Skeleton */}
        <div className="mt-3 flex items-center justify-between">
          <div className="h-4 w-16 animate-pulse rounded bg-neutral-200" />
          <div className="h-6 w-6 animate-pulse rounded-full bg-neutral-200" />
        </div>
      </div>
    </div>
  );
}

// ============================================================
// PRODUCT CARD
// ============================================================

function ProductCard({ product, index, isInWishlist }) {
  const { basePrice, finalPrice, discountPercent, hasDiscount } =
    getProductPricing(product);

  const freeShipping = hasFreeShipping(product);

  return (
    <motion.div
      initial={{ opacity: 0, y: 10 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{
        ease: "easeOut",
        duration: 0.3,
        delay: Math.min(index * 0.03, 0.3),
      }}
      className="group relative min-w-0 rounded-2xl border border-neutral-100 bg-white p-1.5 sm:rounded-[20px] sm:p-2 lg:p-3"
    >
      <div className="relative flex h-full flex-col justify-between rounded-xl border border-neutral-100 p-2 sm:rounded-[10px] sm:p-3 lg:p-4">
        {/* Wishlist Button */}
        <WishlistButton productId={product?.id} isInWishlist={isInWishlist} />

        <Link href={`/product/${product?.id}`} className="block">
          <ProductImage product={product} priority={index < 4} />

          <div className="mt-3 flex items-start justify-between gap-1.5 sm:mt-4 sm:gap-2 lg:mt-5">
            <h3 className="min-w-0 flex-1 overflow-hidden text-xs font-bold leading-5 text-neutral-800 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:2] sm:text-sm sm:leading-6">
              {product?.title}
            </h3>

            <ProductRating rating={product?.rating} />
          </div>
        </Link>

        <div
          dir="ltr"
          className="mt-3 flex flex-wrap items-center justify-between gap-2 sm:mt-4"
        >
          <div className="flex min-w-0 flex-wrap items-center gap-1.5 sm:gap-2">
            <p className="text-sm font-bold text-green-600 sm:text-base">
              ${formatPrice(finalPrice)}
            </p>

            {hasDiscount && (
              <span className="text-[10px] text-neutral-400 line-through sm:text-xs">
                ${formatPrice(basePrice)}
              </span>
            )}
          </div>

          <div className="flex flex-wrap items-center gap-1.5">
            {freeShipping && <FreeShippingBadge />}

            {hasDiscount && discountPercent > 0 && (
              <DiscountBadge discountPercent={discountPercent} />
            )}
          </div>
        </div>
      </div>
    </motion.div>
  );
}

// ============================================================
// MAIN PRODUCTS COMPONENT
// ============================================================

function Products({
  data,
  current_sort,
  current_sortorder,
  isFromBanner,
  bannerId,
  isLoading = false, // ✅ اضافه کردن isLoading
}) {
  const searchParams = useSearchParams();
  const router = useRouter();

  // Set default sort to "newest" (created_at desc)
  // useEffect(() => {
  //   const sortBy = searchParams.get("sort_by");
  //   const sortOrder = searchParams.get("sort_order");

  //   if (!sortBy || !sortOrder) {
  //     const params = new URLSearchParams(searchParams.toString());
  //     params.set("sort_by", "create_at");
  //     params.set("sort_order", "asc");
  //     router.push(`?${params.toString()}`);
  //   }
  // }, []);

  useEffect(() => {
    const sortBy = searchParams.get("sort_by");
    const sortOrder = searchParams.get("sort_order");
  
    // اگر پارامتر سورت وجود نداشت، سورت پیش‌فرض رو اعمال کن
    if (!sortBy || !sortOrder) {
      handleSortChange("created_at", "asc"); // ✅ اینجوری
    }
  }, []);
  
  const { data: wishlistIds = [] } = useWishlistIds();

  const wishlistSet = useMemo(
    () => new Set(wishlistIds.map(String)),
    [wishlistIds]
  );
  
  const urlBannerId =
    searchParams.get("bannerId") || searchParams.get("banner_id");

  const clientBannerId = bannerId || urlBannerId;

  const clientIsFromBanner =
  isFromBanner ||
  searchParams.get("source") === "banner" ||
  Boolean(clientBannerId);
  
  const handleSortChange = (sortBy, sortOrder) => {
    const params = new URLSearchParams(searchParams.toString());
    
    if (sortBy && sortOrder) {
      params.set("sort_by", sortBy);
      params.set("sort_order", sortOrder);
    } else {
      params.delete("sort_by");
      params.delete("sort_order");
    }
    
    const queryString = params.toString();
    router.push(queryString ? `?${queryString}` : "?");
  };

  const products = Array.isArray(data) ? data : [];


  // ============================================================
  // ✅ LOADING STATE
  // ============================================================

  if (isLoading) {
    return (
      <>
        {clientIsFromBanner && (
          <div className="mb-3 px-1 sm:px-0">
            <div className="h-7 w-32 animate-pulse rounded bg-neutral-200" />
          </div>
        )}

        {/* Sort Bar Skeleton */}
        <div className="mb-3 flex w-full items-center gap-2 px-1 pb-1 sm:mb-4 sm:px-0">
          <div className="flex shrink-0 items-center gap-1.5">
            <div className="h-4 w-4 animate-pulse rounded bg-neutral-200" />
            <div className="h-4 w-10 animate-pulse rounded bg-neutral-200" />
          </div>
          <div className="flex gap-2">
            {[1, 2, 3].map((i) => (
              <div
                key={i}
                className="h-8 w-20 animate-pulse rounded-lg bg-neutral-200"
              />
            ))}
          </div>
        </div>

        {/* Products Grid Skeleton */}
        <div
          dir="ltr"
          className="grid w-full grid-cols-2 gap-2 sm:grid-cols-2 sm:gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4 lg:gap-4"
        >
          {Array.from({ length: 8 }).map((_, index) => (
            <ProductSkeleton key={index} />
          ))}
        </div>
      </>
    );
  }

  // ============================================================
  // EMPTY STATE
  // ============================================================

  if (products.length === 0) {
    return (
      <>
        <SortBar
          currentSort={current_sort}
          currentSortOrder={current_sortorder}
          onSortChange={handleSortChange}
        />

        <div className="flex flex-col items-center justify-center py-12 text-center">
          <div className="text-5xl mb-4">🔍</div>
          <h3 className="text-lg font-semibold text-neutral-800">
            No products found
          </h3>
          <p className="mt-1 text-sm text-neutral-500">
            Try adjusting your filters or search terms
          </p>
        </div>
      </>
    );
  }

  // ============================================================
  // RENDER PRODUCTS
  // ============================================================

  return (
    <>
      {clientIsFromBanner && (
        <div className="mb-3 px-1 sm:px-0">
          <h1 className="text-base font-bold text-neutral-800 sm:text-lg">
            Products
          </h1>
        </div>
      )}

      <SortBar
        currentSort={current_sort}
        currentSortOrder={current_sortorder}
        onSortChange={handleSortChange}
      />

      <div
        dir="ltr"
        className="grid w-full grid-cols-2 gap-2 sm:grid-cols-2 sm:gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4 lg:gap-4"
      >
        {products.map((product, index) => (
          <ProductCard
            key={product?.id ?? index}
            product={product}
            index={index}
            isInWishlist={wishlistSet.has(String(product?.id))}
          />
        ))}
      </div>
    </>
  );
}

export default Products;