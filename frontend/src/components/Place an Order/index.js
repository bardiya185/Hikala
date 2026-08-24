"use client";

import { useCart } from "@/core/services/queries";
import React from "react";
import { RiErrorWarningLine } from "react-icons/ri";

function PlaceAnOrder() {
  const { data: ll } = useCart();

  const finalPrice = ll?.data?.summary;
  const items = ll?.data?.items;

  if (!items || items.length === 0) {
    return null;
  }

  return (
    <>
      {/* =========================================
          MOBILE / TABLET : 0px تا 1023px
      ========================================= */}
      <div
        className="
       ‫fixed
    bottom-0
    left-0
    right-0

    lg:hidden

    z-[90]

    w-full

    bg-white

    border-t
    border-neutral-200

    shadow-[0_-4px_15px_rgba(0,0,0,0.08)]

    px-3
    sm:px-5

    py-3
  "
        
      >
        <div
          className="
            flex
            items-center
            justify-between
            gap-3

            w-full
          "
        >
          {/* Price */}
          <div
            className="
              flex
              flex-col
              min-w-0
            "
          >
            <span
              className="
                text-[11px]
                sm:text-xs
                text-neutral-400
              "
            >
              Shopping Cart Total
            </span>

            <span
              className="
                text-sm
                sm:text-base

                font-bold
                text-neutral-800

                whitespace-nowrap
              "
            >
              ${finalPrice?.final_total}
            </span>
          </div>

          {/* Order Button */}
          <button
            type="button"
            className="
              flex-1

              max-w-[240px]

              h-11
              sm:h-12

              rounded-lg

              bg-red-500
              hover:bg-red-600
              active:bg-red-700

              text-white

              text-sm
              sm:text-base

              font-medium

              transition-colors
            "
          >
            Place an Order
          </button>
        </div>
      </div>

      {/* =========================================
          DESKTOP : 1024px به بالا
      ========================================= */}
      <div
        className="
          hidden
          lg:block

          w-full
          max-w-[400px]

          border
          border-neutral-200

          bg-white

          rounded-xl

          mx-auto

          shadow-sm

          overflow-hidden
        "
      >
        {/* Payment details */}
        <div className="px-4 sm:px-5 pt-4 sm:pt-5">
          <p className="text-base sm:text-lg font-medium text-neutral-800">
            Payment details
          </p>
        </div>

        {/* Total price */}
        <div
          className="
            flex
            items-center
            justify-between
            gap-4

            px-4
            sm:px-5

            mt-6
            sm:mt-7

            text-sm
            text-neutral-500
          "
        >
          <p>Total price of goods</p>

          <span className="whitespace-nowrap font-medium text-neutral-700">
            ${finalPrice?.final_total}
          </span>
        </div>

        {/* Cart total */}
        <div
          className="
            flex
            items-center
            justify-between
            gap-4

            px-4
            sm:px-5

            mt-4
            sm:mt-5

            text-sm
          "
        >
          <p>Shopping Cart Total</p>

          <span className="whitespace-nowrap font-semibold text-neutral-800">
            ${finalPrice?.final_total}
          </span>
        </div>

        {/* Order button */}
        <div className="px-4 sm:px-5 mt-5">
          <button
            type="button"
            className="
              w-full

              h-11
              sm:h-[43px]

              rounded-lg

              bg-red-500
              hover:bg-red-600
              active:bg-red-700

              text-white

              text-sm
              sm:text-base

              font-medium

              transition-colors
            "
          >
            Place an Order
          </button>
        </div>

        {/* Warning */}
        <div
          className="
            flex
            items-start
            gap-2

            px-4
            sm:px-5

            mt-4
            sm:mt-5

            mb-4
            sm:mb-5

            text-neutral-400

            text-[11px]
            sm:text-xs

            leading-5
          "
        >
          <RiErrorWarningLine
            className="shrink-0 mt-0.5"
            size={16}
          />

          <p>
            The order has not yet been paid for, and if items go out of
            stock, they will be removed from the cart.
          </p>
        </div>
      </div>
    </>
  );
}

export default PlaceAnOrder;
