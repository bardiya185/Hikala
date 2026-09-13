"use client";

import React, { useRef } from "react";
import Image from "next/image";
import CountdownTimer from "../atom/CountDownTimer";
import Link from "next/link";

function SpecialOffers({ campaign, products }) {
  const sliderRef = useRef(null);

  const handleScroll = (direction) => {
    if (sliderRef.current) {
      const scrollAmount = direction === "next" ? 240 : -240;

      sliderRef.current.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  };

  if (!products || products.length === 0) return null;

  const bgColor = campaign?.color || "#dc2626";

  return (
    <section
      aria-label={campaign?.name || "Special offers"}
      className="
        relative my-3 overflow-hidden rounded-xl p-2 select-none
        sm:my-6 sm:rounded-2xl sm:p-3
        lg:my-8 lg:rounded-3xl lg:p-4
      "
      style={{ backgroundColor: bgColor }}
    >
      <div className="flex flex-col items-stretch gap-2 sm:gap-3 lg:flex-row lg:items-center lg:gap-4">
        {/* Campaign Info */}
        <div
          className="
            flex w-full shrink-0 flex-row items-center justify-between
            px-2 py-2 text-white
            sm:px-3
            lg:w-auto lg:min-w-[190px] lg:flex-col lg:justify-center lg:p-4
          "
        >
          <div className="flex items-center gap-1.5 sm:gap-2 lg:mb-3">
            {campaign?.icon && (
              <span
                className="text-xl leading-none sm:text-2xl lg:text-3xl"
                aria-hidden="true"
              >
                {campaign.icon}
              </span>
            )}

            <h2 className="text-xs font-extrabold uppercase tracking-wide leading-tight sm:text-sm md:text-base lg:text-xl">
              {campaign?.name || "Amazing"}
            </h2>
          </div>

          {campaign?.ends_at && (
            <div className="origin-center scale-75 sm:scale-90 lg:scale-100">
              <CountdownTimer targetDate={campaign.ends_at} />
            </div>
          )}

          {campaign?.description && (
            <p className="mt-3 hidden max-w-[180px] line-clamp-2 text-center text-xs leading-relaxed text-white/80 lg:block">
              {campaign.description}
            </p>
          )}
        </div>

        {/* Products Slider */}
        <div className="relative min-w-0 w-full overflow-hidden">
          {/* Previous */}
          <button
            type="button"
            onClick={() => handleScroll("prev")}
            className="
              absolute left-1 top-1/2 z-10 hidden
              h-9 w-9 -translate-y-1/2
              items-center justify-center
              rounded-full
              border border-neutral-200
              bg-white/95
              text-lg font-bold text-neutral-700
              shadow-lg
              transition-all duration-200
              hover:scale-105 hover:bg-white hover:text-neutral-950
              focus:outline-none focus-visible:ring-2
              focus-visible:ring-white focus-visible:ring-offset-2
              md:flex
              lg:h-10 lg:w-10
              dark:border-neutral-700
              dark:bg-neutral-900/95
              dark:text-neutral-200
              dark:hover:bg-neutral-800
              dark:hover:text-white
            "
            aria-label="Previous products"
          >
            ❮
          </button>

          <div
            ref={sliderRef}
            className="
              scrollbar-hide
              flex items-stretch gap-2 overflow-x-auto
              scroll-smooth px-1 py-1
              sm:gap-3
              lg:gap-4
            "
            style={{
              scrollbarWidth: "none",
              msOverflowStyle: "none",
            }}
          >
            {products.map((product) => (
              <Link
                key={product.id}
                href={`/product/${product.id}`}
                className="
                  block shrink-0 rounded-xl
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-white
                  focus-visible:ring-offset-2
                "
              >
                <ProductCard product={product} />
              </Link>
            ))}
          </div>

          {/* Next */}
          <button
            type="button"
            onClick={() => handleScroll("next")}
            className="
              absolute right-1 top-1/2 z-10 hidden
              h-9 w-9 -translate-y-1/2
              items-center justify-center
              rounded-full
              border border-neutral-200
              bg-white/95
              text-lg font-bold text-neutral-700
              shadow-lg
              transition-all duration-200
              hover:scale-105 hover:bg-white hover:text-neutral-950
              focus:outline-none focus-visible:ring-2
              focus-visible:ring-white focus-visible:ring-offset-2
              md:flex
              lg:h-10 lg:w-10
              dark:border-neutral-700
              dark:bg-neutral-900/95
              dark:text-neutral-200
              dark:hover:bg-neutral-800
              dark:hover:text-white
            "
            aria-label="Next products"
          >
            ❯
          </button>
        </div>
      </div>
    </section>
  );
}

function ProductCard({ product }) {
  const productImage =
    product?.images?.[0]?.image_url || "/icons/product1.webp";

  const pricing = product?.pricing || {};

  const basePrice = Number(pricing.base_price) || 0;
  const finalPrice = Number(pricing.final_price) || 0;
  const discountPercent = Number(pricing.discount_percent) || 0;
  const hasDiscount = Boolean(pricing.has_discount);

  return (
    <article
      className="
        flex h-full w-[180px] shrink-0 flex-col
        rounded-xl border border-neutral-200
        bg-white p-1.5
        shadow-sm
        transition-all duration-200
        hover:-translate-y-0.5 hover:shadow-lg
        sm:w-[190px] sm:rounded-2xl sm:p-2
        md:w-[210px]
        lg:w-[260px] lg:p-4
        dark:border-neutral-800
        dark:bg-neutral-900
        dark:shadow-black/20
        dark:hover:border-neutral-700
        dark:hover:bg-neutral-900
      "
    >
      {/* Product Image */}
      <div
        className="
          relative mb-2 flex h-[90px] w-full
          items-center justify-center
          overflow-hidden rounded-lg
          bg-neutral-50
          sm:h-[120px]
          md:h-[150px]
          lg:mb-3 lg:h-[180px] lg:rounded-xl
          dark:bg-neutral-950
        "
      >
        <Image
          src={productImage}
          alt={product?.title || "Product"}
          width={180}
          height={180}
          sizes="
            (max-width: 640px) 180px,
            (max-width: 768px) 190px,
            (max-width: 1024px) 210px,
            260px
          "
          className="
            h-full w-full
            object-contain
            p-1.5
            transition-transform duration-300
            group-hover:scale-105
            sm:p-2
          "
          unoptimized={productImage.startsWith("http")}
        />
      </div>

      {/* Product Info */}
      <div className="flex min-h-0 flex-1 flex-col gap-0.5 sm:gap-1">
        <h3
          className="
            line-clamp-2
            text-[11px] font-semibold leading-tight
            text-neutral-800
            sm:text-xs
            md:text-sm
            dark:text-neutral-100
          "
        >
          {product?.title || "Product"}
        </h3>

        {product?.short_description && (
          <p
            className="
              mt-1 hidden line-clamp-1
              text-xs leading-relaxed
              text-neutral-400
              md:block
              dark:text-neutral-500
            "
          >
            {product.short_description}
          </p>
        )}
      </div>

      {/* Pricing */}
      <div className="mt-1.5 sm:mt-2">
        {hasDiscount ? (
          <>
            <div className="flex flex-wrap items-center gap-1.5">
              <del className="text-[10px] text-neutral-400 sm:text-xs md:text-sm dark:text-neutral-500">
                ${basePrice.toLocaleString()}
              </del>

              {discountPercent > 0 && (
                <span
                  className="
                    rounded-md bg-red-600 px-1.5 py-0.5
                    text-[9px] font-bold text-white
                    sm:text-[10px] md:text-xs
                    dark:bg-red-500
                  "
                >
                  {discountPercent}%
                </span>
              )}
            </div>

            <p className="mt-0.5 text-xs font-bold text-green-600 sm:text-sm md:text-base lg:text-lg dark:text-green-400">
              ${finalPrice.toLocaleString()}
            </p>
          </>
        ) : (
          <p className="text-xs font-bold text-neutral-800 sm:text-sm md:text-base lg:text-lg dark:text-neutral-100">
            ${basePrice.toLocaleString()}
          </p>
        )}
      </div>
    </article>
  );
}

export default SpecialOffers;