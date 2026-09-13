"use client";

import { useRemoveCartItem, useUpdateCartItem } from "@/core/services/mutations";
import { useCart } from "@/core/services/queries";
import Image from "next/image";
import React from "react";
import toast from "react-hot-toast";
import { FaMinus, FaPlus, FaRegTrashAlt } from "react-icons/fa";

function MiniCart() {
  const { mutate: updateCartItem, isPending: isUpdating } =
    useUpdateCartItem();

  const { mutate: removeCartItem, isPending: isRemoving } =
    useRemoveCartItem();

  const { data: cart, isLoading } = useCart();

  const items = cart?.data?.items || [];
  const summary = cart?.data?.summary;

  const isPending = isUpdating || isRemoving;

  const handleIncrease = (item) => {
    if (isPending) return;

    const stock =
      item?.product_variant?.stock ?? item?.variant?.stock ?? Infinity;

    if (item?.quantity >= stock) return;

    updateCartItem({
      itemId: item.id,
      quantity: item.quantity + 1,
    });
  };

  const handleDecrease = (item) => {
    if (isPending) return;

    if (item?.quantity <= 1) {
      removeCartItem(item.id, {
        onSuccess: () => toast.success("Removed from cart"),
      });
      return;
    }

    updateCartItem({
      itemId: item.id,
      quantity: item.quantity - 1,
    });
  };

  const handleRemove = (item) => {
    if (isPending) return;

    removeCartItem(item.id, {
      onSuccess: () => toast.success("Removed from cart"),
    });
  };

  if (isLoading) {
    return (
      <div
        className="
          absolute right-4 top-full z-50 mt-2
          w-[calc(100vw-2rem)] max-w-[550px]
          overflow-hidden rounded-2xl
          border border-neutral-200
          bg-white
          p-6
          text-center
          shadow-2xl
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/40
        "
      >
        <div className="flex items-center justify-center gap-3">
          <div
            className="
              h-5 w-5 animate-spin rounded-full
              border-2 border-neutral-200
              border-t-red-500
              dark:border-neutral-700
              dark:border-t-red-400
            "
          />
          <p className="text-sm text-neutral-500 dark:text-neutral-400">
            در حال دریافت سبد خرید ...
          </p>
        </div>
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div
        className="
          absolute right-4 top-full z-50 mt-2
          w-[calc(100vw-2rem)] max-w-[400px]
          overflow-hidden rounded-2xl
          border border-neutral-200
          bg-white
          p-8
          text-center
          shadow-2xl
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/40
        "
      >
        <div className="flex flex-col items-center gap-3">
          <div
            className="
              flex h-12 w-12 items-center justify-center
              rounded-full
              bg-neutral-100
              text-neutral-500
              dark:bg-neutral-800
              dark:text-neutral-400
            "
          >
            <FaRegTrashAlt className="h-5 w-5" />
          </div>

          <p className="text-sm font-medium text-neutral-700 dark:text-neutral-200">
            سبد خرید شما خالی است
          </p>
        </div>
      </div>
    );
  }

  return (
    <div
      className="
        absolute right-4 top-full z-50 mt-2
        w-[calc(100vw-2rem)] max-w-[550px]
        overflow-hidden rounded-2xl
        border border-neutral-200
        bg-white
        shadow-2xl
        dark:border-neutral-800
        dark:bg-neutral-900
        dark:shadow-black/40
      "
    >
      {/* Header */}
      <div
        className="
          flex items-center justify-between
          border-b border-neutral-200
          px-5 py-4
          dark:border-neutral-800
        "
      >
        <p className="text-sm font-bold text-neutral-900 dark:text-neutral-100">
          Your shopping cart summary
        </p>

        <span
          className="
            rounded-full
            bg-neutral-100
            px-2.5 py-1
            text-xs font-medium
            text-neutral-600
            dark:bg-neutral-800
            dark:text-neutral-300
          "
        >
          {items.length} {items.length === 1 ? "item" : "items"}
        </span>
      </div>

      {/* Items */}
      <div className="max-h-[420px] overflow-y-auto px-5">
        {items.map((item) => {
          const product = item?.variant?.product || item?.product_variant?.product;

          const image =
            item?.variant?.product?.main_image ||
            item?.product_variant?.product?.main_image ||
            product?.main_image ||
            "/icons/product1.webp";

          const title =
            product?.title ||
            item?.variant?.product?.title ||
            item?.product_variant?.product?.title ||
            "Product";

          const price =
            item?.final_price ??
            item?.product_variant?.final_price ??
            item?.product_variant?.price ??
            0;

          return (
            <div
              key={item.id}
              className="
                border-b border-neutral-200
                py-4
                last:border-b-0
                dark:border-neutral-800
              "
            >
              <div className="flex items-start gap-3">
                {/* Product Image */}
                <div
                  className="
                    relative h-16 w-16 shrink-0
                    overflow-hidden rounded-xl
                    border border-neutral-200
                    bg-neutral-50
                    dark:border-neutral-800
                    dark:bg-neutral-950
                  "
                >
                  <Image
                    src={image}
                    width={64}
                    height={64}
                    alt={title}
                    className="h-full w-full object-contain p-1"
                    unoptimized={image.startsWith("http")}
                  />
                </div>

                {/* Product Details */}
                <div className="min-w-0 flex-1">
                  <p
                    className="
                      line-clamp-2
                      text-sm font-medium leading-6
                      text-neutral-800
                      dark:text-neutral-100
                    "
                  >
                    {title}
                  </p>

                  <p className="mt-1 text-sm font-bold text-green-600 dark:text-green-400">
                    ${Number(price).toLocaleString()}
                  </p>
                </div>

                {/* Remove */}
                <button
                  type="button"
                  onClick={() => handleRemove(item)}
                  disabled={isPending}
                  aria-label={`Remove ${title}`}
                  className="
                    flex h-8 w-8 shrink-0
                    items-center justify-center
                    rounded-lg
                    text-neutral-400
                    transition-colors
                    hover:bg-red-50
                    hover:text-red-500
                    disabled:cursor-not-allowed
                    disabled:opacity-50
                    focus:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-red-500
                    dark:text-neutral-500
                    dark:hover:bg-red-950/30
                    dark:hover:text-red-400
                  "
                >
                  <FaRegTrashAlt className="h-4 w-4" />
                </button>
              </div>

              {/* Quantity */}
              <div className="mt-3 flex items-center justify-between gap-3">
                <div
                  className="
                    flex h-9 items-center
                    overflow-hidden rounded-xl
                    border border-neutral-200
                    bg-neutral-50
                    dark:border-neutral-700
                    dark:bg-neutral-950
                  "
                >
                  <button
                    type="button"
                    onClick={() => handleIncrease(item)}
                    disabled={isPending}
                    aria-label={`Increase ${title}`}
                    className="
                      flex h-9 w-9 items-center justify-center
                      text-neutral-500
                      transition-colors
                      hover:bg-neutral-200
                      hover:text-neutral-900
                      disabled:cursor-not-allowed
                      disabled:opacity-40
                      dark:text-neutral-400
                      dark:hover:bg-neutral-800
                      dark:hover:text-white
                    "
                  >
                    <FaPlus className="h-3 w-3" />
                  </button>

                  <span
                    className="
                      flex h-full min-w-8
                      items-center justify-center
                      border-x border-neutral-200
                      px-2
                      text-sm font-semibold
                      text-neutral-800
                      dark:border-neutral-700
                      dark:text-neutral-100
                    "
                  >
                    {item?.quantity}
                  </span>

                  <button
                    type="button"
                    onClick={() => handleDecrease(item)}
                    disabled={isPending}
                    aria-label={
                      item?.quantity === 1
                        ? `Remove ${title}`
                        : `Decrease ${title}`
                    }
                    className="
                      flex h-9 w-9 items-center justify-center
                      text-neutral-500
                      transition-colors
                      hover:bg-red-50
                      hover:text-red-500
                      disabled:cursor-not-allowed
                      disabled:opacity-40
                      dark:text-neutral-400
                      dark:hover:bg-red-950/30
                      dark:hover:text-red-400
                    "
                  >
                    {item?.quantity === 1 ? (
                      <FaRegTrashAlt className="h-3.5 w-3.5" />
                    ) : (
                      <FaMinus className="h-3 w-3" />
                    )}
                  </button>
                </div>

                <span className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                  ${(Number(price) * Number(item?.quantity || 0)).toLocaleString()}
                </span>
              </div>
            </div>
          );
        })}
      </div>

      {/* Footer */}
      <div
        className="
          border-t border-neutral-200
          bg-neutral-50
          px-5 py-4
          dark:border-neutral-800
          dark:bg-neutral-950
        "
      >
        <div className="mb-4 flex items-center justify-between gap-4">
          <span className="text-sm text-neutral-500 dark:text-neutral-400">
            Total
          </span>

          <span className="text-lg font-bold text-green-600 dark:text-green-400">
            $
            {Number(
              summary?.final_total ??
                items.reduce((total, item) => {
                  const price =
                    item?.final_price ??
                    item?.product_variant?.final_price ??
                    item?.product_variant?.price ??
                    0;

                  return total + Number(price) * Number(item?.quantity || 0);
                }, 0)
            ).toLocaleString()}
          </span>
        </div>

        <button
          type="button"
          className="
            flex h-11 w-full
            items-center justify-center
            rounded-xl
            bg-red-500
            px-5
            text-sm font-bold text-white
            shadow-sm
            transition-all duration-200
            hover:bg-red-600
            active:scale-[0.99]
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500
            focus-visible:ring-offset-2
            dark:bg-red-500
            dark:hover:bg-red-400
            dark:focus-visible:ring-offset-neutral-950
          "
        >
          Place an Order
        </button>
      </div>
    </div>
  );
}

export default MiniCart;