"use client";

import React, { useRef } from "react";
import Image from "next/image";
import CountdownTimer from "../atom/CountDownTimer";

function AmazingSliders({ campaign, products }) {
  const sliderRef = useRef(null);

  const handleScroll = (direction) => {
    if (sliderRef.current) {
      const scrollAmount = direction === "next" ? 300 : -300;
      sliderRef.current.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  };

  // 🚫 اگه محصولی نبود، هیچی نشون نده
  if (!products || products.length === 0) {
    return null;
  }

  // 🎨 رنگ داینامیک از Campaign
  const bgColor = campaign?.color || "#DC2626";

  return (
    <div
      className="relative rounded-xl sm:rounded-2xl p-3 sm:p-4 my-4 sm:my-8 select-none ltr"
      style={{ backgroundColor: bgColor }}
    >
      <div className="flex flex-col lg:flex-row items-center gap-3 sm:gap-4">
        
        {/* ================================================== */}
        {/* 🎯 Campaign Header */}
        {/* ================================================== */}
        <div className="flex flex-row lg:flex-col items-center justify-between lg:justify-center w-full lg:w-auto text-white p-2 sm:p-4 lg:min-w-[200px] shrink-0">
          
          {/* Icon + Title */}
          <div className="flex items-center gap-2 lg:mb-3">
            {campaign?.icon && (
              <span className="text-2xl sm:text-3xl">{campaign.icon}</span>
            )}
            <h2 className="text-base sm:text-lg lg:text-xl font-extrabold text-center uppercase tracking-wide">
              {campaign?.name || "Amazing Offers"}
            </h2>
          </div>

          {/* ⏰ Countdown Timer */}
          {campaign?.ends_at && (
            <div className="lg:mt-0">
              <CountdownTimer targetDate={campaign.ends_at} />
            </div>
          )}

          {/* 📝 Description (فقط در دسکتاپ) */}
          {campaign?.description && (
            <p className="hidden lg:block text-white/80 text-xs text-center mt-3 line-clamp-2">
              {campaign.description}
            </p>
          )}
        </div>

        {/* ================================================== */}
        {/* 📦 Products Slider */}
        {/* ================================================== */}
        <div className="relative w-full overflow-hidden">
          
          {/* ⬅️ Previous Button (فقط دسکتاپ) */}
          <button
            onClick={() => handleScroll("prev")}
            className="hidden sm:flex absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-9 h-9 lg:w-10 lg:h-10 rounded-full shadow-md items-center justify-center transition-all"
            aria-label="Previous"
          >
            ❮
          </button>

          {/* 📜 Scrollable Products */}
          <div
            ref={sliderRef}
            className="flex items-center gap-3 sm:gap-4 overflow-x-auto scrollbar-hide scroll-smooth py-2 px-1"
            style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
          >
            {products.map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>

          {/* ➡️ Next Button (فقط دسکتاپ) */}
          <button
            onClick={() => handleScroll("next")}
            className="hidden sm:flex absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-9 h-9 lg:w-10 lg:h-10 rounded-full shadow-md items-center justify-center transition-all"
            aria-label="Next"
          >
            ❯
          </button>
        </div>
      </div>
    </div>
  );
}

// ================================================================
// 🎴 Product Card (رسپانسیو)
// ================================================================
function ProductCard({ product }) {
  const productImage =
    product.images?.[0]?.url ||
    product.images?.[0]?.image_path ||
    "/icons/product1.webp";

  const pricing = product.pricing || {};
  const basePrice = pricing.base_price || 0;
  const finalPrice = pricing.final_price || 0;
  const discountPercent = pricing.discount_percent || 0;
  const hasDiscount = pricing.has_discount || false;

  return (
    <div className="w-[160px] sm:w-[200px] lg:w-[260px] h-[280px] sm:h-[320px] lg:h-[350px] bg-white rounded-xl sm:rounded-2xl p-2 sm:p-4 border border-neutral-400 shadow-sm shrink-0 flex flex-col justify-between hover:shadow-md transition-shadow">
      <div className="w-full h-full bg-white border border-solid rounded-[15px] sm:rounded-[20px] px-2 sm:px-3">
        
        {/* 🖼️ Product Image */}
        <div className="w-full h-[110px] sm:h-[140px] lg:h-[180px] relative flex items-center justify-center bg-gray-50 rounded-lg sm:rounded-xl overflow-hidden mb-2 sm:mb-3">
          <Image
            src={productImage}
            alt={product.title || "product"}
            width={180}
            height={180}
            className="object-contain max-h-full"
            unoptimized={productImage.startsWith("http")}
          />
        </div>

        {/* 📝 Product Info */}
        <div className="flex flex-col gap-1">
          <h3 className="font-semibold text-gray-800 text-xs sm:text-sm line-clamp-2">
            {product.title}
          </h3>
          <p className="hidden sm:block text-xs text-gray-400 line-clamp-1 mt-2 sm:mt-3">
            {product.short_description}
          </p>
        </div>

        {/* 💰 Pricing */}
        {hasDiscount ? (
          <div className="mt-2">
            <div className="flex items-center gap-1 sm:gap-2">
              <del className="text-neutral-400 text-xs sm:text-sm">
                ${basePrice.toLocaleString()}
              </del>
              <span className="text-center text-white text-[10px] sm:text-[13px] bg-red-600 rounded-[5px] px-1.5 sm:px-2 py-0.5">
                {discountPercent}%
              </span>
            </div>
            <p className="text-green-600 text-sm sm:text-base lg:text-[18px] font-bold">
              ${finalPrice.toLocaleString()}
            </p>
          </div>
        ) : (
          <p className="text-gray-800 text-sm sm:text-base lg:text-[18px] font-bold mt-2">
            ${basePrice.toLocaleString()}
          </p>
        )}
      </div>
    </div>
  );
}

export default AmazingSliders;