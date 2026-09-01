"use client";

import React from "react";
import { useToggleWishlist } from "@/core/services/queries";

function Wishlist() {
  const {
    data,
    isLoading,
    isFetching,
    isError,
    error,
    status,
  } = useToggleWishlist();

  const wishlist = data?.data;
  console.log("WISHLIST DATA:", data);
  console.log("WISHLIST ITEMS:", wishlist);

  // نمایش درحال بارگذاری
  if (isLoading) {
    return (
      <div className="p-10 text-center">
        Loading wishlist...
      </div>
    );
  }

  // نمایش خطا
  if (isError) {
    return (
      <div className="p-10 text-center">
        <p className="text-red-500">
          Failed to load wishlist
        </p>
        <pre
          dir="ltr"
          className="mt-5 overflow-auto rounded-xl bg-neutral-100 p-5 text-left text-sm"
        >
          {JSON.stringify(
            error?.response?.data ||
              error?.message ||
              error,
            null,
            2
          )}
        </pre>
      </div>
    );
  }

  // بررسی وجود آیتم‌ها
  if (!wishlist || wishlist.length === 0) {
    return (
      <main
        dir="ltr"
        className="mx-auto w-full max-w-[1440px] px-4 py-8"
      >
        <h1 className="text-2xl font-bold">
          Wishlist
        </h1>
        <div className="mt-5 rounded-xl bg-neutral-100 p-10 text-center">
          <p>Your wishlist is empty</p>
        </div>
      </main>
    );
  }

  // نمایش لیست
  return (
    <main
      dir="ltr"
      className="mx-auto w-full max-w-[1440px] px-4 py-8"
    >
      <h1 className="text-2xl font-bold">
        Wishlist ({wishlist.length})
      </h1>

      <div className="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        {wishlist.map((item, index) => (
          <div
            key={item.id || index} // ✅ استفاده از id آیتم یا index
            className="rounded-xl bg-neutral-100 p-5 shadow-sm transition hover:shadow-md"
          >
            <p className="font-medium">
              {item.title || item.name || `Item ${index + 1}`}
            </p>

            {item.price && (
              <p className="mt-2 text-lg font-bold text-blue-600">
                ${item.price}
              </p>
            )}
          </div>
        ))}
      </div>
    </main>
  );
}

export default Wishlist;