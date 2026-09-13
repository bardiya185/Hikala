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
      <div className="overflow-y-auto">

      {items?.map((item)=>(
        <>
      <div key={item.id} className="flex items-center">
        <Image
          src="/icons/miniimg.jpg"
          width={100}
          height={100}
          alt="minicart"
        />
        <p className="w-fit text-wrap text-sm">
         {product?.title}
        </p>
      </div>
        <div className="flex items-center justify-between ">
          <div className="w-[100px] flex items-center justify-center gap-3 h-[40px] border border-solid border-neutral-400 rounded-2xl">
            <button disabled={item.quantity === 1} >
              <FaPlus className="text-red-500" />
            </button>
            <span className=" text-red-500 text-center flex items-center">
              {item?.quantity}
            </span>
            <button onClick={()=>handleDecrease(item)}>
              <FaRegTrashAlt className="text-red-500" />
            </button>
          </div>
          <span className="text-green-500 text-lg">${item?.final_price}</span>
        </div>
        </>
      ))}
      
        <div className="flex items-center justify-between mt-7">
            <button className="w-[210px] h-[41px] bg-red-500 rounded-lg text-white text-center">
                Place an Order
            </button>
            <span className="text-green-500 text-lg">{finalPrice?.final_total}</span>
        </div>
      
    </div>
    </div>
  );
}

export default MiniCart;