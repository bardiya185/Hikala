"use client";

import React, { useRef } from "react";
import Image from "next/image";
import CountdownTimer from "../atom/CountDownTimer";
import Link from "next/link";

function SpecialOffers({ campaign, products }) {
  const sliderRef = useRef(null);
  console.log(campaign)

  const handleScroll = (direction) => {
    if (sliderRef.current) {
      const scrollAmount = direction === "next" ? 200 : -200;
      sliderRef.current.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  };

  if (!products || products.length === 0) return null;

  const bgColor =  campaign.color;

  return (
    <div
      className="relative rounded-lg sm:rounded-xl lg:rounded-2xl p-2 sm:p-3 lg:p-4 my-3 sm:my-6 lg:my-8 select-none ltr overflow-hidden"
      style={{ backgroundColor: bgColor }}
    >
      <div className="flex flex-col lg:flex-row items-stretch lg:items-center gap-2 sm:gap-3 lg:gap-4">
        {}
        {}
        {}
        <div className="flex flex-row lg:flex-col items-center justify-between lg:justify-center w-full lg:w-auto text-white px-2 py-2 lg:p-4 lg:min-w-[180px] shrink-0">
          {}
          <div className="flex items-center gap-1.5 sm:gap-2 lg:mb-3">
            {campaign?.icon && (
              <span className="text-xl sm:text-2xl lg:text-3xl">
                {campaign.icon}
              </span>
            )}
            <h2 className="text-xs sm:text-sm md:text-base lg:text-xl font-extrabold uppercase tracking-wide leading-tight">
              {campaign?.name || "Amazing"}
            </h2>
          </div>

          {}
          {campaign?.ends_at && (
            <div className="scale-75 sm:scale-90 lg:scale-100 origin-center">
              <CountdownTimer targetDate={campaign.ends_at} />
            </div>
          )}

          {}
          {campaign?.description && (
            <p className="hidden lg:block text-white/80 text-xs text-center mt-3 line-clamp-2">
              {campaign.description}
            </p>
          )}
        </div>

        {}
        {}
        {}
        <div className="relative w-full overflow-hidden min-w-0">
          {}
          <button
            onClick={() => handleScroll("prev")}
            className="hidden md:flex absolute left-1 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-8 h-8 lg:w-10 lg:h-10 rounded-full shadow-md items-center justify-center transition-all"
            aria-label="Previous"
          >
            ❮
          </button>

          {}
          <div

            ref={sliderRef}
            className="flex items-stretch gap-2 sm:gap-3 lg:gap-4 overflow-x-auto scrollbar-hide scroll-smooth py-1 px-1"
            style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
          >
            {products.map((product) => (
            <Link href={`/product/${product.id}`}>
              <ProductCard key={product.id} product={product} />
            </Link>
            ))}
          </div>

          {}
          <button
            onClick={() => handleScroll("next")}
            className="hidden md:flex absolute right-1 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-8 h-8 lg:w-10 lg:h-10 rounded-full shadow-md items-center justify-center transition-all"
            aria-label="Next"
          >
            ❯
          </button>
        </div>
      </div>
    </div>
  );
}
function ProductCard({ product }) {
  const productImage = product.images?.[0]?.image_url || "/icons/product1.webp";

  const pricing = product.pricing || {};
  const basePrice = pricing.base_price || 0;
  const finalPrice = pricing.final_price || 0;
  const discountPercent = pricing.discount_percent || 0;
  const hasDiscount = pricing.has_discount || false;

  return (
    <div className="w-[180px] xs:w-[180px] sm:w-[180px] md:w-[200px] lg:w-[260px] bg-white rounded-lg sm:rounded-xl lg:rounded-2xl p-1.5 sm:p-2 lg:p-4 border border-neutral-300 shadow-sm shrink-0 flex flex-col hover:shadow-md transition-shadow">
      {}
      <div className="w-full h-[90px] xs:h-[100px] sm:h-[130px] md:h-[150px] lg:h-[180px] relative flex items-center justify-center bg-gray-50 rounded-md sm:rounded-lg lg:rounded-xl overflow-hidden mb-2">
        <Image
          src={productImage}
          alt={product.title || "product"}
          width={180}
          height={180}
          className="object-contain max-h-full p-1"
          unoptimized={productImage.startsWith("http")}
        />
      </div>

      {}
      <div className="flex flex-col gap-0.5 sm:gap-1 flex-1 min-h-0">
        <h3 className="font-semibold text-gray-800 text-[11px] xs:text-xs sm:text-sm line-clamp-2 leading-tight">
          {product.title}
        </h3>
        <p className="hidden md:block text-xs text-gray-400 line-clamp-1 mt-1">
          {product.short_description}
        </p>
      </div>

      {}
      <div className="mt-1.5 sm:mt-2">
        {hasDiscount ? (
          <>
            <div className="flex items-center gap-1 flex-wrap">
              <del className="text-neutral-400 text-[10px] xs:text-xs sm:text-sm">
                ${basePrice.toLocaleString()}
              </del>
              <span className="text-center text-white text-[9px] xs:text-[10px] sm:text-xs bg-red-600 rounded px-1 sm:px-1.5 py-0.5">
                {discountPercent}%
              </span>
            </div>
            <p className="text-green-600 text-xs xs:text-sm sm:text-base lg:text-lg font-bold">
              ${finalPrice.toLocaleString()}
            </p>
          </>
        ) : (
          <p className="text-gray-800 text-xs xs:text-sm sm:text-base lg:text-lg font-bold">
            ${basePrice.toLocaleString()}
          </p>
        )}
      </div>
    </div>
  );
}

export default SpecialOffers
