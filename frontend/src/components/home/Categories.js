"use client";

import Image from "next/image";
import Link from "next/link";

import { useGetCategoriesHomePage } from "@/core/services/queries";

const API_URL =
  process.env.NEXT_PUBLIC_API_URL ||
  process.env.NEXT_PUBLIC_BASE_URL ||
  "http://localhost:8000";

const FALLBACK_IMAGE = "/icons/ip17.jpg";

const getCategoryImage = (banner) => {
  if (!banner || typeof banner !== "string") {
    return FALLBACK_IMAGE;
  }

  const trimmedBanner = banner.trim();

  if (!trimmedBanner) {
    return FALLBACK_IMAGE;
  }

  if (
    trimmedBanner.startsWith("http://") ||
    trimmedBanner.startsWith("https://")
  ) {
    return trimmedBanner;
  }

  if (trimmedBanner.startsWith("/storage/")) {
    return `${API_URL}${trimmedBanner}`;
  }

  if (trimmedBanner.startsWith("/")) {
    return `${API_URL}${trimmedBanner}`;
  }

  return `${API_URL}/storage/${trimmedBanner.replace(/^\/+/, "")}`;
};


export default function  categories() {
  const {
    data,
    isLoading,
    isError,
  } = useGetCategoriesHomePage();

  const categories = Array.isArray(data)
    ? data
    : Array.isArray(data?.data)
      ? data.data
      : Array.isArray(data?.data?.data)
        ? data.data.data
        : [];

  const mainCategories = categories.filter(
    (category) =>
      category &&
      (category.parent_id === null ||
        category.parent_id === undefined)
  );

  if (isLoading) {
    return (
      <section
        aria-labelledby="categories-title"
        className="
          w-full
          bg-white
          py-8
          dark:bg-neutral-950
          sm:py-10
          lg:py-12
        "
      >
        <div
          className="
            mx-auto w-full max-w-[1280px]
            px-4 sm:px-6 lg:px-8
          "
        >
          <h2
            id="categories-title"
            className="
              text-center text-xl font-semibold
              text-neutral-900
              dark:text-white
              sm:text-2xl
            "
          >
            Categories
          </h2>

          <div
            className="
              mt-8 grid grid-cols-3
              gap-x-4 gap-y-7
              sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8
              md:grid-cols-6
              lg:grid-cols-9 lg:gap-x-7 lg:gap-y-9
            "
            aria-hidden="true"
          >
            {Array.from({ length: 18 }).map((_, index) => (
              <div
                key={index}
                className="flex flex-col items-center"
              >
                <div
                  className="
                    h-[72px] w-[72px]
                    animate-pulse rounded-full
                    bg-neutral-100
                    dark:bg-neutral-800
                    sm:h-[82px] sm:w-[82px]
                    lg:h-[92px] lg:w-[92px]
                  "
                />

                <div
                  className="
                    mt-3 h-4 w-16
                    animate-pulse rounded
                    bg-neutral-100
                    dark:bg-neutral-800
                  "
                />
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  if (isError) {
    return (
      <section
        aria-labelledby="categories-title"
        className="
          w-full
          bg-white
          py-8
          dark:bg-neutral-950
          sm:py-10
          lg:py-12
        "
      >
        <div
          className="
            mx-auto w-full max-w-[1280px]
            px-4 text-center
            sm:px-6 lg:px-8
          "
        >
          <h2
            id="categories-title"
            className="
              text-xl font-semibold
              text-neutral-900
              dark:text-white
              sm:text-2xl
            "
          >
            Categories
          </h2>

          <p
            className="
              mt-6 text-sm
              text-neutral-500
              dark:text-neutral-400
            "
          >
            Unable to load categories.
          </p>
        </div>
      </section>
    );
  }

  return (
    <section
      aria-labelledby="categories-title"
      className="
        w-full
        bg-white
        py-8
        dark:bg-neutral-950
        sm:py-10
        lg:py-12
      "
    >
      <div
        className="
          mx-auto w-full max-w-[1280px]
          px-4 sm:px-6 lg:px-8
        "
      >
        <h2
          id="categories-title"
          className="
            text-center text-xl font-semibold
            text-neutral-900
            dark:text-white
            sm:text-2xl
          "
        >
          Categories
        </h2>

        {mainCategories.length > 0 ? (
          <div
            className="
              mt-8 grid grid-cols-3
              gap-x-4 gap-y-7
              sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8
              md:grid-cols-6
              lg:grid-cols-9 lg:gap-x-7 lg:gap-y-9
            "
          >
            {mainCategories.map((category) => {
              if (!category?.id) {
                return null;
              }

              const image = getCategoryImage(
                category?.banner
              );

              const categoryName =
                category?.name || "Category";

              const categorySlug =
                category?.slug ||
                String(category.id);

              return (
                <Link
                  key={category.id}
                  href={`/search/${encodeURIComponent(
                    categorySlug
                  )}?category_id=${category.id}`}
                  aria-label={`View ${categoryName}`}
                  className="
                    group flex min-w-0
                    flex-col items-center
                    text-center outline-none
                  "
                >
                  <div
                    className="
                      relative
                      h-[72px] w-[72px]
                      overflow-hidden
                      rounded-full
                      border border-neutral-100
                      bg-neutral-100
                      shadow-sm
                      transition-all duration-300
                      group-hover:scale-105
                      group-hover:border-neutral-200
                      group-hover:shadow-md
                      group-focus-visible:ring-2
                      group-focus-visible:ring-red-500
                      group-focus-visible:ring-offset-2
                      dark:border-neutral-800
                      dark:bg-neutral-900
                      dark:group-hover:border-neutral-700
                      dark:group-focus-visible:ring-red-400
                      dark:group-focus-visible:ring-offset-neutral-950
                      sm:h-[82px] sm:w-[82px]
                      lg:h-[92px] lg:w-[92px]
                    "
                  >
                    <Image
                      src={image}
                      alt={categoryName}
                      fill
                      sizes="
                        (max-width: 640px) 72px,
                        (max-width: 1024px) 82px,
                        92px
                      "
                      className="
                        object-cover
                        transition-transform
                        duration-500
                        group-hover:scale-105
                      "
                    />
                  </div>

                  <span
                    className="
                      mt-3 line-clamp-2
                      min-h-8 max-w-[110px]
                      text-xs font-medium
                      leading-4
                      text-neutral-700
                      transition-colors duration-200
                      group-hover:text-red-500
                      dark:text-neutral-300
                      dark:group-hover:text-red-400
                      sm:text-sm
                    "
                  >
                    {categoryName}
                  </span>
                </Link>
              );
            })}
          </div>
        ) : (
          <div
            className="
              mt-8 rounded-xl
              border border-dashed
              border-neutral-200
              px-4 py-10
              text-center
              dark:border-neutral-800
            "
          >
            <p
              className="
                text-sm
                text-neutral-500
                dark:text-neutral-400
              "
            >
              No categories available.
            </p>
          </div>
        )}
      </div>
    </section>
  );
}