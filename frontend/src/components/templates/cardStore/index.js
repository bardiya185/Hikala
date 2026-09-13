"use client";

import Image from "next/image";
import Link from "next/link";
import React from "react";

function CardShop({ data }) {
  const banners = data?.banners || [];

  if (!banners.length) return null;

  return (
    <section className="w-full">
      <div
        className="
          grid grid-cols-2 gap-3
          sm:grid-cols-2 sm:gap-4
          lg:grid-cols-4 lg:gap-5
        "
      >
        {banners.map((banner) => {
          const hasQuery = banner?.url?.includes("?");
          const href = banner?.url
            ? `${banner.url}${hasQuery ? "&" : "?"}source=banner&bannerId=${banner.id}`
            : "#";

          return (
            <Link
              key={banner.id}
              href={href}
              aria-label={`View banner ${banner.id}`}
              className="
                group block overflow-hidden
                rounded-xl
                border border-neutral-200
                bg-white
                shadow-sm
                transition-all duration-300
                hover:-translate-y-0.5
                hover:shadow-lg
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                focus-visible:ring-offset-2
                dark:border-neutral-800
                dark:bg-neutral-900
                dark:shadow-black/20
                dark:focus-visible:ring-offset-neutral-950
              "
            >
              <div className="relative aspect-[4/3] w-full overflow-hidden">
                <Image
                  unoptimized
                  src={banner?.image || "/icons/product1.webp"}
                  alt={banner?.title || "Banner"}
                  fill
                  sizes="
                    (max-width: 640px) 50vw,
                    (max-width: 1024px) 50vw,
                    25vw
                  "
                  className="
                    object-cover
                    transition-transform duration-500
                    group-hover:scale-105
                  "
                />

                <div
                  className="
                    pointer-events-none absolute inset-0
                    bg-gradient-to-t
                    from-black/10 via-transparent to-transparent
                    opacity-0
                    transition-opacity duration-300
                    group-hover:opacity-100
                  "
                />
              </div>
            </Link>
          );
        })}
      </div>
    </section>
  );
}

export default CardShop;