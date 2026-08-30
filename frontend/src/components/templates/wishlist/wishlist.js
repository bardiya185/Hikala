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

  console.log(
    "WISHLIST COMPONENT DATA:",
    data
  );

  console.log(
    "WISHLIST STATUS:",
    status
  );

  console.log(
    "WISHLIST LOADING:",
    isLoading
  );

  console.log(
    "WISHLIST FETCHING:",
    isFetching
  );

  console.log(
    "WISHLIST ERROR:",
    error
  );

  if (isLoading) {
    return (
      <div className="p-10 text-center">
        Loading wishlist...
      </div>
    );
  }

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

  return (
    <main
      dir="ltr"
      className="mx-auto w-full max-w-[1440px] px-4 py-8"
    >
      <h1 className="text-2xl font-bold">
        Wishlist
      </h1>

      <div className="mt-5 rounded-xl bg-neutral-100 p-5">
        <pre
          dir="ltr"
          className="overflow-auto text-sm"
        >
          {JSON.stringify(
            data,
            null,
            2
          )}
        </pre>
      </div>
    </main>
  );
}

export default Wishlist;