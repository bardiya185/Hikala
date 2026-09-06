"use client";

import { Trash2, Minus, Plus } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";

export default function CartItem({
  isPending,
  item,
  onIncrease,
  onDecrease,
  onRemove,
}) {
  const product = item?.variant?.product;
  const variant = item?.variant;

  const quantity = item?.quantity ?? 0;

  const price =
    item?.product_variant?.final_price ??
    item?.product_variant?.price ??
    item?.base_price ??
    variant?.final_price ??
    variant?.price ??
    0;

  return (
    <div
      className="
        w-full
        flex
        flex-col
        sm:flex-row
        items-stretch
        gap-4
        sm:gap-5
        p-3
        sm:p-4
        md:p-5
        bg-white
        border
        border-gray-200
        rounded-2xl
        shadow-sm
        hover:shadow-md
        transition-shadow
      "
    >
      {/* =========================
          Product Image
      ========================== */}
      <div
        className="
          w-full
          h-48
          sm:w-32
          sm:h-32
          md:w-40
          md:h-32
          lg:w-48
          flex-shrink-0
          relative
          bg-gray-50
          rounded-xl
          overflow-hidden
          border
          border-gray-100
          flex
          items-center
          justify-center
        "
      >
        <img
          src="/icons/ip17.jpg"
          alt={product?.title || "product image"}
          className="w-full h-full object-contain p-3"
        />
      </div>

      {/* =========================
          Product Information
      ========================== */}
      <div className="flex-1 min-w-0 flex flex-col justify-between gap-4">
        {}
        <div>
          <h3
            className="
              text-sm
              sm:text-base
              font-bold
              text-gray-900
              leading-snug
              line-clamp-2
            "
          >
            {product?.title || "Product"}
          </h3>
        </div>

        {}
        <div className="flex flex-col gap-2 text-xs text-gray-600">
          {}
          <div className="flex items-center gap-2">
            <span
              className="
                w-3.5
                h-3.5
                rounded-full
                border
                border-gray-300
                inline-block
                bg-gray-200
                flex-shrink-0
              "
            />

            <span className="text-gray-600">Product variant</span>
          </div>

          {}
          <div className="flex items-start gap-2">
            <svg
              className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
              />
            </svg>

            <span>18-Month Sadrtel Official Warranty</span>
          </div>
        </div>

        {/* =========================
            Quantity Controls
        ========================== */}
        <div
          className="
            flex
            flex-col
            xs:flex-row
            sm:flex-row
            items-start
            sm:items-center
            justify-between
            gap-3
            mt-1
          "
        >
          {}
          <div
            className="
              flex
              items-center
              border
              border-gray-300
              rounded-lg
              px-2
              py-1
              gap-2
              bg-gray-50/50
            "
          >
            <button
              type="button"
              onClick={onDecrease}
              disabled={isPending}
              aria-label="Decrease quantity"
              className="
                w-7
                h-7
                flex
                items-center
                justify-center
                text-gray-600
                hover:text-red-500
                transition-colors
                disabled:opacity-40
              "
            >
              {quantity === 1 ? (
                <Trash2 size={16} className="text-red-500" />
              ) : (
                <Minus size={16} />
              )}
            </button>

            {!isPending ? (
              <span
                className="
                  text-sm
                  font-bold
                  text-gray-900
                  w-6
                  text-center
                "
              >
                {quantity}
              </span>
            ) : (
              <div className="w-6 h-6 flex items-center justify-center">
                <RotatingLines
                  visible={true}
                  height="22"
                  width="22"
                  strokeWidth="5"
                  animationDuration="0.75"
                  ariaLabel="loading"
                />
              </div>
            )}

            <button
              type="button"
              onClick={onIncrease}
              disabled={isPending}
              aria-label="Increase quantity"
              className="
                w-7
                h-7
                flex
                items-center
                justify-center
                text-gray-600
                hover:text-black
                transition-colors
                disabled:opacity-40
              "
            >
              <Plus size={16} />
            </button>
          </div>

          {}
          <button
            type="button"
            onClick={onRemove}
            disabled={isPending}
            className="
              text-xs
              sm:text-sm
              text-gray-400
              hover:text-red-500
              transition-colors
              disabled:opacity-40
            "
          >
            Remove item
          </button>
        </div>
      </div>

      {/* =========================
          Price
      ========================== */}
      <div
        className="
          w-full
          sm:w-auto
          sm:min-w-[120px]
          flex
          flex-row
          sm:flex-col
          items-center
          sm:items-end
          justify-between
          sm:justify-start
          gap-3
          border-t
          sm:border-t-0
          pt-3
          sm:pt-0
          border-gray-100
        "
      >
        {}
        <div className="text-left sm:text-right">
          <span
            className="
              text-lg
              sm:text-xl
              md:text-2xl
              font-black
              text-green-500
              whitespace-nowrap
            "
          ></span>
        </div>

        {}
        {variant?.stock <= 3 && variant?.stock > 0 && (
          <div
            className="
              flex
              items-center
              gap-1.5
              text-xs
              text-amber-600
              bg-amber-50
              px-2.5
              py-1
              rounded-md
              border
              border-amber-200/60
            "
          >
            <svg
              className="w-3.5 h-3.5 text-amber-500"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fillRule="evenodd"
                clipRule="evenodd"
                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              />
            </svg>

            <span>Only {variant.stock} left</span>
          </div>
        )}
      </div>
    </div>
  );
}
