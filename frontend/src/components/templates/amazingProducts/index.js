"use client";

import React from "react";
import { Sparkles } from "lucide-react";

function AmazingProducts() {
  return (
    <section className="w-full px-4 py-6 sm:px-6 lg:px-8">
      <div
        className="
          mx-auto flex w-full max-w-7xl
          items-center justify-between gap-4
          overflow-hidden rounded-2xl
          border border-neutral-200
          bg-white px-5 py-5
          shadow-sm
          sm:px-6
          lg:px-8
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/20
        "
      >
        <div className="flex min-w-0 items-center gap-3">
          <div
            className="
              flex h-10 w-10 shrink-0 items-center justify-center
              rounded-xl
              bg-red-50
              text-red-600
              dark:bg-red-950/40
              dark:text-red-400
            "
          >
            <Sparkles className="h-5 w-5" />
          </div>

          <div className="min-w-0">
            <h2
              className="
                truncate text-base font-bold
                text-neutral-900
                sm:text-lg
                dark:text-neutral-100
              "
            >
              Amazing Products
            </h2>

            <p
              className="
                mt-0.5 hidden text-xs
                text-neutral-500
                sm:block sm:text-sm
                dark:text-neutral-400
              "
            >
              Discover our amazing products
            </p>
          </div>
        </div>

        <div
          className="
            h-1.5 w-12 shrink-0 rounded-full
            bg-red-500
            sm:w-16
            dark:bg-red-400
          "
        />
      </div>
    </section>
  );
 }

export default AmazingProducts;