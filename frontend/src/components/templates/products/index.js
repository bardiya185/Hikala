"use client";

import Image from "next/image";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { TfiAlignLeft } from "react-icons/tfi";
import ReactStars from "react-stars";
import { motion, AnimatePresence } from "framer-motion";
import { formatPrice } from "@/core/utils/formatPrice";
import { FreeShippingBadge } from "@/components/atom/FreeShippingBadge.";
import WishlistButton from "@/components/WishlistButton";
import { useWishlistIds } from "@/core/services/queries";
import { useMemo, useEffect, useTransition } from "react";

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

function SortButton({
  active,
  onClick,
  children,
  disabled,
}) {
  return (
    <button
      type="button"
      onClick={onClick}
      disabled={disabled}
      aria-pressed={active}
      className={`
        shrink-0 rounded-lg px-2.5 py-1.5
        text-[11px] font-semibold
        transition-all duration-200
        focus:outline-none
        focus-visible:ring-2
        focus-visible:ring-red-500
        sm:px-3 sm:text-xs
        ${
          active
            ? "bg-red-50 text-red-600 shadow-sm dark:bg-red-950/40 dark:text-red-400"
            : "text-neutral-500 hover:bg-neutral-100 hover:text-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-100"
        }
        ${
          disabled
            ? "cursor-not-allowed opacity-50"
            : "cursor-pointer"
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

function SortBar({
  currentSort,
  currentSortOrder,
  onSortChange,
  isPending,
}) {
  return (
    <div
      dir="ltr"
      className="mb-3 flex w-full items-center gap-2 overflow-x-auto border-b border-neutral-100 px-1 pb-2 sm:mb-4 sm:gap-3 sm:px-0 dark:border-neutral-800"
    >
      <div className="flex shrink-0 items-center gap-1.5 text-neutral-700 sm:gap-2 dark:text-neutral-200">
        <TfiAlignLeft
          size={16}
          className="sm:h-[18px] sm:w-[18px]"
        />

        <span className="text-xs font-semibold sm:text-sm">
          Sort:
        </span>
      </div>

      {SORT_OPTIONS.map(
        ({ label, sortBy, sortOrder }) => {
          const isActive =
            currentSort === sortBy &&
            currentSortOrder === sortOrder;

          return (
            <SortButton
              key={`${sortBy}-${sortOrder}`}
              active={isActive}
              onClick={() =>
                onSortChange(sortBy, sortOrder)
              }
              disabled={isPending}
            >
              {label}
            </SortButton>
          );
        },
      )}

      {isPending && (
        <div className="ml-1 flex shrink-0 items-center gap-1.5">
          <div className="h-3 w-3 animate-spin rounded-full border-2 border-red-500 border-t-transparent dark:border-red-400 dark:border-t-transparent" />

          <span className="text-[10px] text-neutral-400 dark:text-neutral-500">
            Sorting...
          </span>
        </div>
      )}
    </div>
  );
}

// ============================================================
// HELPERS
// ============================================================

function getProductVariant(product) {
  const variants = product?.variants;

  if (
    !Array.isArray(variants) ||
    variants.length === 0
  ) {
    return null;
  }

  return (
    variants.find(
      (variant) =>
        variant?.is_default &&
        variant?.is_active,
    ) ??
    variants.find(
      (variant) => variant?.is_active,
    ) ??
    variants[0]
  );
}

export function getProductPricing(product) {
  const variant = getProductVariant(product);

  const basePrice = Number(
    variant?.base_price ?? 0,
  );

  const finalPrice = Number(
    variant?.final_price ?? 0,
  );

  const discountPercent = Number(
    variant?.discount_percent ?? 0,
  );

  return {
    basePrice,
    finalPrice,
    discountPercent,
    hasDiscount:
      finalPrice < basePrice &&
      basePrice > 0,
  };
}

export function hasFreeShipping(product) {
  const variant = getProductVariant(product);

  return Boolean(
    variant?.shipping_features?.some(
      (feature) =>
        feature?.type === "free" &&
        feature?.is_active === true,
    ),
  );
}

// ============================================================
// DISCOUNT BADGE
// ============================================================

function DiscountBadge({ discountPercent }) {
  if (
    !discountPercent ||
    discountPercent <= 0
  ) {
    return null;
  }

  return (
    <div className="flex shrink-0 items-center rounded-md bg-red-50 px-1.5 py-1 text-[9px] font-bold text-red-600 dark:bg-red-950/40 dark:text-red-400 sm:px-2 sm:text-[10px]">
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
  const productRating = Number(rating || 0);

  return (
    <div className="flex shrink-0 items-center gap-0.5 text-[10px] text-neutral-600 dark:text-neutral-300 sm:gap-1 sm:text-xs">
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

export function ProductImage({
  product,
  priority = false,
}) {
  const image =
    product?.images?.find(
      (item) =>
        item?.is_main === true ||
        item?.is_main === 1,
    ) ?? product?.images?.[0];

  const imageUrl = image?.image_url;

  return (
    <div className="relative flex aspect-[4/5] w-full items-center justify-center overflow-hidden rounded-xl bg-neutral-50 sm:rounded-2xl dark:bg-neutral-950">
      {imageUrl ? (
        <Image
          src={imageUrl}
          alt={
            image?.alt ||
            product?.title ||
            "Product"
          }
          width={200}
          height={250}
          priority={priority}
          sizes="
            (max-width: 640px) 50vw,
            (max-width: 768px) 33vw,
            (max-width: 1024px) 25vw,
            220px
          "
          className="h-full w-full object-contain p-1 transition-transform duration-500 group-hover:scale-105 sm:p-2"
        />
      ) : (
        <div className="flex h-full w-full items-center justify-center text-sm text-neutral-400 dark:text-neutral-600">
          No image
        </div>
      )}
    </div>
  );
}

// ============================================================
// PRODUCT SKELETON
// ============================================================

function ProductSkeleton({ delay = 0 }) {
  return (
    <motion.div
      initial={{
        opacity: 0,
        scale: 0.97,
      }}
      animate={{
        opacity: 1,
        scale: 1,
      }}
      exit={{
        opacity: 0,
        scale: 0.97,
      }}
      transition={{
        duration: 0.3,
        delay: delay * 0.05,
        ease: "easeInOut",
      }}
      className="group relative min-w-0 rounded-2xl border border-neutral-100 bg-white p-1.5 shadow-sm sm:rounded-[20px] sm:p-2 lg:p-3 dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20"
    >
      <div className="relative flex h-full flex-col justify-between rounded-xl border border-neutral-100 p-2 sm:rounded-[10px] sm:p-3 lg:p-4 dark:border-neutral-800">
        <div className="aspect-[4/5] w-full animate-pulse rounded-xl bg-neutral-200 sm:rounded-2xl dark:bg-neutral-800" />

        <div className="mt-3 space-y-2">
          <div className="h-4 w-3/4 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />

          <div className="h-4 w-1/2 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />
        </div>

        <div className="mt-3 flex items-center justify-between">
          <div className="h-5 w-20 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />

          <div className="h-5 w-16 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />
        </div>

        <div className="mt-3 flex items-center justify-between">
          <div className="h-4 w-16 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />

          <div className="h-6 w-6 animate-pulse rounded-full bg-neutral-200 dark:bg-neutral-800" />
        </div>
      </div>
    </motion.div>
  );
}

// ============================================================
// PRODUCT CARD
// ============================================================

function ProductCard({
  product,
  index,
  isInWishlist,
}) {
  const {
    basePrice,
    finalPrice,
    discountPercent,
    hasDiscount,
  } = getProductPricing(product);

  const freeShipping =
    hasFreeShipping(product);

  return (
    <motion.div
      initial={{
        opacity: 0,
        y: 10,
      }}
      animate={{
        opacity: 1,
        y: 0,
      }}
      transition={{
        ease: "easeOut",
        duration: 0.3,
        delay: Math.min(index * 0.03, 0.3),
      }}
      className="group relative min-w-0 rounded-2xl border border-neutral-100 bg-white p-1.5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-neutral-200 hover:shadow-md sm:rounded-[20px] sm:p-2 lg:p-3 dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20 dark:hover:border-neutral-700 dark:hover:shadow-black/30"
    >
      <div className="relative flex h-full flex-col justify-between rounded-xl border border-neutral-100 p-2 sm:rounded-[10px] sm:p-3 lg:p-4 dark:border-neutral-800">
        <WishlistButton
          productId={product?.id}
          isInWishlist={isInWishlist}
        />

        <Link
          href={`/product/${product?.id}`}
          className="block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
        >
          <ProductImage
            product={product}
            priority={index < 4}
          />

          <div className="mt-3 flex items-start justify-between gap-1.5 sm:mt-4 sm:gap-2 lg:mt-5">
            <h3 className="min-w-0 flex-1 overflow-hidden text-xs font-bold leading-5 text-neutral-800 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:2] sm:text-sm sm:leading-6 dark:text-neutral-100">
              {product?.title}
            </h3>

            <ProductRating
              rating={product?.rating}
            />
          </div>
        </Link>

        <div
          dir="ltr"
          className="mt-3 flex flex-wrap items-center justify-between gap-2 sm:mt-4"
        >
          <div className="flex min-w-0 flex-wrap items-center gap-1.5 sm:gap-2">
            <p className="text-sm font-bold text-green-600 sm:text-base dark:text-green-400">
              ${formatPrice(finalPrice)}
            </p>

            {hasDiscount && (
              <span className="text-[10px] text-neutral-400 line-through sm:text-xs dark:text-neutral-500">
                ${formatPrice(basePrice)}
              </span>
            )}
          </div>

          <div className="flex flex-wrap items-center gap-1.5">
            {freeShipping && (
              <FreeShippingBadge />
            )}

            {hasDiscount &&
              discountPercent > 0 && (
                <DiscountBadge
                  discountPercent={
                    discountPercent
                  }
                />
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
  isLoading = false,
}) {
  const searchParams = useSearchParams();
  const router = useRouter();
  const [isPending, startTransition] =
    useTransition();

  const { data: wishlistIds = [] } =
    useWishlistIds();

  const wishlistSet = useMemo(
    () =>
      new Set(
        Array.isArray(wishlistIds)
          ? wishlistIds.map(String)
          : [],
      ),
    [wishlistIds],
  );

  // ============================================================
  // DEFAULT SORT
  // ============================================================

  useEffect(() => {
    const sortBy =
      searchParams.get("sort_by");

    const sortOrder =
      searchParams.get("sort_order");

    if (!sortBy || !sortOrder) {
      const params = new URLSearchParams(
        searchParams.toString(),
      );

      params.set(
        "sort_by",
        "created_at",
      );

      params.set(
        "sort_order",
        "asc",
      );

      router.replace(
        `?${params.toString()}`,
      );
    }
  }, [router, searchParams]);

  // ============================================================
  // BANNER
  // ============================================================

  const urlBannerId =
    searchParams.get("bannerId") ||
    searchParams.get("banner_id");

  const clientBannerId =
    bannerId || urlBannerId;

  const clientIsFromBanner =
    Boolean(isFromBanner) ||
    searchParams.get("source") ===
      "banner" ||
    Boolean(clientBannerId);

  // ============================================================
  // SORT
  // ============================================================

  const handleSortChange = (
    sortBy,
    sortOrder,
  ) => {
    startTransition(() => {
      const params = new URLSearchParams(
        searchParams.toString(),
      );

      if (sortBy && sortOrder) {
        params.set(
          "sort_by",
          sortBy,
        );

        params.set(
          "sort_order",
          sortOrder,
        );
      } else {
        params.delete("sort_by");
        params.delete("sort_order");
      }

      const queryString =
        params.toString();

      router.push(
        queryString
          ? `?${queryString}`
          : "?",
      );
    });
  };

  const products = Array.isArray(data)
    ? data
    : [];

  // ============================================================
  // LOADING STATE
  // ============================================================

  if (isLoading) {
    return (
      <>
        {clientIsFromBanner && (
          <div className="mb-3 px-1 sm:px-0">
            <div className="h-7 w-32 animate-pulse rounded-lg bg-neutral-200 dark:bg-neutral-800" />
          </div>
        )}

        <div className="mb-3 flex w-full items-center gap-2 overflow-hidden px-1 pb-1 sm:mb-4 sm:px-0">
          <div className="flex shrink-0 items-center gap-1.5">
            <div className="h-4 w-4 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />

            <div className="h-4 w-10 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />
          </div>

          <div className="flex gap-2">
            {[1, 2, 3].map((item) => (
              <div
                key={item}
                className="h-8 w-20 animate-pulse rounded-lg bg-neutral-200 dark:bg-neutral-800"
              />
            ))}
          </div>
        </div>

        <div
          dir="ltr"
          className="grid w-full grid-cols-2 gap-2 sm:gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4"
        >
          {Array.from({
            length: 8,
          }).map((_, index) => (
            <ProductSkeleton
              key={index}
              delay={index}
            />
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
          currentSortOrder={
            current_sortorder
          }
          onSortChange={handleSortChange}
          isPending={isPending}
        />

        <div className="flex flex-col items-center justify-center rounded-2xl border border-dashed border-neutral-200 bg-white px-4 py-14 text-center dark:border-neutral-800 dark:bg-neutral-900">
          <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100 text-3xl dark:bg-neutral-800">
            🔍
          </div>

          <h3 className="text-lg font-semibold text-neutral-800 dark:text-neutral-100">
            No products found
          </h3>

          <p className="mt-1 max-w-sm text-sm text-neutral-500 dark:text-neutral-400">
            Try adjusting your filters or
            search terms
          </p>
        </div>
      </>
    );
  }

  // ============================================================
  // PRODUCTS
  // ============================================================

  return (
    <>
      {clientIsFromBanner && (
        <div className="mb-3 px-1 sm:px-0">
          <h1 className="text-base font-bold text-neutral-800 sm:text-lg dark:text-neutral-100">
            Products
          </h1>
        </div>
      )}

      <SortBar
        currentSort={current_sort}
        currentSortOrder={current_sortorder}
        onSortChange={handleSortChange}
        isPending={isPending}
      />

      <AnimatePresence mode="wait">
        {isPending ? (
          <motion.div
            key="skeleton"
            initial={{
              opacity: 0,
            }}
            animate={{
              opacity: 1,
            }}
            exit={{
              opacity: 0,
            }}
            transition={{
              duration: 0.3,
            }}
            dir="ltr"
            className="grid w-full grid-cols-2 gap-2 sm:gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4"
          >
            {Array.from({
              length: 8,
            }).map((_, index) => (
              <ProductSkeleton
                key={index}
                delay={index}
              />
            ))}
          </motion.div>
        ) : (
          <motion.div
            key="products"
            initial={{
              opacity: 0,
            }}
            animate={{
              opacity: 1,
            }}
            transition={{
              duration: 0.4,
              ease: "easeInOut",
            }}
            dir="ltr"
            className="grid w-full grid-cols-2 gap-2 sm:gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-4"
          >
            {products.map(
              (product, index) => (
                <ProductCard
                  key={
                    product?.id ??
                    index
                  }
                  product={product}
                  index={index}
                  isInWishlist={wishlistSet.has(
                    String(product?.id),
                  )}
                />
              ),
            )}
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}

export default Products;