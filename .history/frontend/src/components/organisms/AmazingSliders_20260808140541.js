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
      className="relative rounded-2xl p-4 my-8 select-none ltr"
      style={{ backgroundColor: bgColor }}
    >
      <div className="flex flex-col lg:flex-row items-center gap-4">
        
        {/* ================================================== */}
        {/* 🎯 Campaign Header (چپ) */}
        {/* ================================================== */}
        <div className="flex flex-col items-center justify-center text-white p-4 min-w-[200px] shrink-0">
          {/* Icon + Title */}
          <div className="flex items-center gap-2 mb-3">
            {campaign?.icon && (
              <span className="text-3xl">{campaign.icon}</span>
            )}
            <h2 className="text-xl font-extrabold text-center uppercase tracking-wide">
              {campaign?.name || "Amazing Offers"}
            </h2>
          </div>

          {/* ⏰ Countdown Timer */}
          {campaign?.ends_at && (
            <CountdownTimer targetDate={campaign.ends_at} />
          )}

          {/* 📝 Description (اختیاری) */}
          {campaign?.description && (
            <p className="text-white/80 text-xs text-center mt-3 line-clamp-2">
              {campaign.description}
            </p>
          )}
        </div>

        {/* ================================================== */}
        {/* 📦 Products Slider */}
        {/* ================================================== */}
        <div className="relative w-full overflow-hidden">
          
          {/* ⬅️ Previous Button */}
          <button
            onClick={() => handleScroll("prev")}
            className="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition-all"
            aria-label="Previous"
          >
            ❮
          </button>

          {/* 📜 Scrollable Products */}
          <div
            ref={sliderRef}
            className="flex items-center gap-4 overflow-x-auto scrollbar-hide scroll-smooth py-2 px-1"
            style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
          >
            {products.map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>

          {/* ➡️ Next Button */}
          <button
            onClick={() => handleScroll("next")}
            className="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition-all"
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
// 🎴 Product Card (کامپوننت جدا برای تمیزی)
// ================================================================
function ProductCard({ product }) {
  // 🖼️ عکس محصول (اگه داره، از images استفاده کن)
  const productImage = 
    product.images?.[0]?.url || 
    product.images?.[0]?.image_path || 
    "/icons/product1.webp";

  // 💰 اطلاعات قیمت
  const pricing = product.pricing || {};
  const basePrice = pricing.base_price || 0;
  const finalPrice = pricing.final_price || 0;
  const discountPercent = pricing.discount_percent || 0;
  const hasDiscount = pricing.has_discount || false;

  return (
    <div className="w-[260px] h-[350px] bg-white rounded-2xl p-4 border border-neutral-400 shadow-sm shrink-0 flex flex-col justify-between hover:shadow-md transition-shadow">
      <div className="w-full h-full bg-white border border-solid rounded-[20px] px-3">
        
        {/* 🖼️ Product Image */}
        <div className="w-full h-[180px] relative flex items-center justify-center bg-gray-50 rounded-xl overflow-hidden mb-3">
          <Image
            src={productImage}
            alt={product.title || "product"}
            width={180}
            height={180}
            className="object-contain max-h-full"
            unoptimized={productImage.startsWith('http')} // اگه از URL خارجی هست
          />
        </div>

        {/* 📝 Product Info */}
        <div className="flex flex-col gap-1">
          <h3 className="font-semibold text-gray-800 text-sm line-clamp-2">
            {product.title}
          </h3>
          <p className="text-xs text-gray-400 line-clamp-1 mt-3">
            {product.short_description}
          </p>
        </div>

        {/* 💰 Pricing */}
        {hasDiscount ? (
          <>
            <div className="flex items-center gap-2 mt-2">
              <del className="text-neutral-400 text-sm">
                ${basePrice.toLocaleString()}
              </del>
              <span className="text-center text-white text-[13px] bg-red-600 rounded-[5px] px-2 py-0.5">
                {discountPercent}%
              </span>
            </div>
            <p className="text-green-600 text-[18px] font-bold">
              ${finalPrice.toLocaleString()}
            </p>
          </>
        ) : (
          <p className="text-gray-800 text-[18px] font-bold mt-2">
            ${basePrice.toLocaleString()}
          </p>
        )}
      </div>
    </div>
  );
}

export default AmazingSliders;