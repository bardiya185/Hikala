"use client";

import React, { useRef } from "react";
import Image from "next/image";
import CountdownTimer from "../atom/CountDownTimer";
import Link from "next/link";

function AmazingSliders({ campaign, products }) {
  const sliderRef = useRef(null);

  const handleScroll = (direction) => {
    if (sliderRef.current) {
      const scrollAmount = direction === "next" ? 280 : -280;

      sliderRef.current.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  };

  if (!products || products.length === 0) return null;

  const bgColor = campaign?.color || "#DC2626";

  return (
    <section
      className="
        relative
        my-4
        overflow-hidden
        rounded-xl
        p-2.5
        select-none
        sm:my-6
        sm:rounded-2xl
        sm:p-3
        lg:my-8
        lg:p-4
      "
      style={{ backgroundColor: bgColor }}
      aria-label={campaign?.name || "Amazing offers"}
    >
      <div className="flex flex-col items-stretch gap-2 sm:gap-3 lg:flex-row lg:items-center lg:gap-4">
        {/* Campaign Info */}
        <div
          className="
            flex
            w-full
            shrink-0
            flex-row
            items-center
            justify-between
            px-2
            py-2
            text-white

            lg:w-auto
            lg:min-w-[180px]
            lg:flex-col
            lg:justify-center
            lg:p-4
          "
        >
          <div className="flex items-center gap-1.5 sm:gap-2 lg:mb-3">
            {campaign?.icon && (
              <span
                className="text-xl sm:text-2xl lg:text-3xl"
                aria-hidden="true"
              >
                {campaign.icon}
              </span>
            )}

            <h2
              className="
                text-xs
                font-extrabold
                uppercase
                leading-tight
                tracking-wide
                sm:text-sm
                md:text-base
                lg:text-xl
              "
            >
              {campaign?.name || "Amazing"}
            </h2>
          </div>

          {campaign?.ends_at && (
            <div className="origin-center scale-75 sm:scale-90 lg:scale-100">
              <CountdownTimer targetDate={campaign.ends_at} />
            </div>
          )}

          {campaign?.description && (
            <p className="mt-3 hidden max-w-[180px] text-center text-xs leading-5 text-white/80 lg:line-clamp-2 lg:block">
              {campaign.description}
            </p>
          )}
        </div>

        {/* Products Slider */}
        <div className="relative min-w-0 flex-1 overflow-hidden">
          {/* Previous */}
          <button
            type="button"
            onClick={() => handleScroll("prev")}
            className="
              absolute
              left-1
              top-1/2
              z-10
              hidden
              h-9
              w-9
              -translate-y-1/2
              items-center
              justify-center
              rounded-full
              border
              border-neutral-200
              bg-white/95
              text-lg
              text-neutral-700
              shadow-lg
              transition-all
              hover:scale-105
              hover:bg-white
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-white
              md:flex
              lg:h-10
              lg:w-10
            "
            aria-label="Previous products"
          >
            ‹
          </button>

          <div
            ref={sliderRef}
            dir="ltr"
            className="
              flex
              items-stretch
              gap-2
              overflow-x-auto
              scroll-smooth
              px-1
              py-1
              sm:gap-3
              lg:gap-4
              scrollbar-hide
            "
            style={{
              scrollbarWidth: "none",
              msOverflowStyle: "none",
            }}
          >
            {products.map((product) => (
              <ProductCard
                key={product.id}
                product={product}
              />
            ))}
          </div>

          {/* Next */}
          <button
            type="button"
            onClick={() => handleScroll("next")}
            className="
              absolute
              right-1
              top-1/2
              z-10
              hidden
              h-9
              w-9
              -translate-y-1/2
              items-center
              justify-center
              rounded-full
              border
              border-neutral-200
              bg-white/95
              text-lg
              text-neutral-700
              shadow-lg
              transition-all
              hover:scale-105
              hover:bg-white
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-white
              md:flex
              lg:h-10
              lg:w-10
            "
            aria-label="Next products"
          >
            ›
          </button>
        </div>
      </div>
    </section>
  );
}

function ProductCard({ product }) {
  const productImage = product?.images?.[0]?.image_url;

  const pricing = product?.pricing || {};

  const basePrice = pricing.base_price || 0;
  const finalPrice = pricing.final_price || 0;
  const discountPercent = pricing.discount_percent || 0;
  const hasDiscount = pricing.has_discount || false;

  return (
    <article
      className="
        group
        flex
        w-[170px]
        shrink-0
        flex-col
        rounded-xl
        border
        border-neutral-200
        bg-white
        p-1.5
        shadow-sm
        transition-all
        duration-300

        hover:-translate-y-0.5
        hover:shadow-lg

        sm:w-[180px]
        sm:rounded-xl
        sm:p-2

        md:w-[200px]

        lg:w-[260px]
        lg:rounded-2xl
        lg:p-3

        dark:border-neutral-700
        dark:bg-neutral-900
        dark:shadow-black/10
        dark:hover:border-neutral-600
        dark:hover:shadow-black/30
      "
    >
      <Link
        href={`/product/${product.id}`}
        className="
          flex
          h-full
          flex-col
          rounded-lg
          outline-none

          focus-visible:ring-2
          focus-visible:ring-red-500
          focus-visible:ring-offset-2
          dark:focus-visible:ring-offset-neutral-900
        "
      >
        {/* Product Image */}
        <div
          className="
            relative
            mb-2
            flex
            h-[90px]
            w-full
            items-center
            justify-center
            overflow-hidden
            rounded-lg
            bg-neutral-50

            sm:h-[120px]

            md:h-[145px]

            lg:h-[175px]
            lg:rounded-xl

            dark:bg-neutral-950
          "
        >
          {productImage ? (
            <Image
              src={productImage}
              alt={product?.title || "Product"}
              width={220}
              height={220}
              sizes="
                (max-width: 640px) 170px,
                (max-width: 768px) 180px,
                (max-width: 1024px) 200px,
                260px
              "
              className="
                h-full
                w-full
                object-contain
                p-2
                transition-transform
                duration-500
                ease-out
                group-hover:scale-105
              "
            />
          ) : (
            <div
              className="
                flex
                h-full
                w-full
                items-center
                justify-center
                text-xs
                text-neutral-400
                dark:text-neutral-600
              "
            >
              No image
            </div>
          )}
        </div>

        {/* Product Info */}
        <div className="flex min-h-0 flex-1 flex-col">
          <h3
            className="
              line-clamp-2
              text-[11px]
              font-semibold
              leading-5
              text-neutral-800
              transition-colors
              group-hover:text-red-600

              sm:text-xs

              md:text-sm

              dark:text-neutral-200
              dark:group-hover:text-red-400
            "
          >
            {product?.title}
          </h3>

          {product?.short_description && (
            <p
              className="
                mt-1
                hidden
                line-clamp-1
                text-xs
                leading-5
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
        <div className="mt-2">
          {hasDiscount ? (
            <>
              <div className="flex flex-wrap items-center gap-1.5">
                <del
                  className="
                    text-[10px]
                    text-neutral-400
                    sm:text-xs
                    md:text-sm
                    dark:text-neutral-500
                  "
                >
                  ${Number(basePrice).toLocaleString()}
                </del>

                <span
                  className="
                    rounded-md
                    bg-red-600
                    px-1.5
                    py-0.5
                    text-[9px]
                    font-bold
                    text-white
                    sm:text-[10px]
                    md:text-xs
                  "
                >
                  {discountPercent}%
                </span>
              </div>

              <p
                className="
                  mt-0.5
                  text-xs
                  font-bold
                  text-green-600
                  sm:text-sm
                  md:text-base
                  lg:text-lg
                  dark:text-green-400
                "
              >
                ${Number(finalPrice).toLocaleString()}
              </p>
            </>
          ) : (
            <p
              className="
                text-xs
                font-bold
                text-neutral-800
                sm:text-sm
                md:text-base
                lg:text-lg
                dark:text-neutral-100
              "
            >
              ${Number(basePrice).toLocaleString()}
            </p>
          )}
        </div>
      </Link>
    </article>
  );
}

export default AmazingSliders;