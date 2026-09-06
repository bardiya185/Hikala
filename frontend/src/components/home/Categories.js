"use client";

import Image from "next/image";
import Link from "next/link";

import { useGetCategoriesHomePage,  } from "@/core/services/queries";

export default function Categories() {
  const {
    data,
    isLoading,
    isError,
    error,
  } = useGetCategoriesHomePage();

  // API ممکن است مستقیماً آرایه بدهد
  // یا داخل data قرار گرفته باشد.
  const categories = Array.isArray(data)
    ? data
    : Array.isArray(data?.data)
      ? data.data
      : [];

  // فقط دسته‌بندی‌های اصلی
  // یعنی دسته‌هایی که parent_id آنها null است.
  const mainCategories = categories.filter(
    (category) => category?.parent_id === null
  );

  /*
   * -------------------------------------------------------
   * Loading
   * -------------------------------------------------------
   */

  if (isLoading) {
    return (
      <section className="w-full py-8 sm:py-10 lg:py-12">
        <div className="mx-auto w-full max-w-[1280px] px-4 sm:px-6 lg:px-8">
          <h2 className="text-center text-xl font-semibold text-neutral-900 sm:text-2xl">
            Categories
          </h2>

          <div className="mt-8 grid grid-cols-3 gap-x-4 gap-y-7 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 md:grid-cols-6 lg:grid-cols-9 lg:gap-x-7 lg:gap-y-9">
            {Array.from({ length: 18 }).map((_, index) => (
              <div
                key={index}
                className="flex flex-col items-center"
              >
                <div className="h-[72px] w-[72px] animate-pulse rounded-full bg-neutral-100 sm:h-[82px] sm:w-[82px] lg:h-[92px] lg:w-[92px]" />

                <div className="mt-3 h-4 w-16 animate-pulse rounded bg-neutral-100" />
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  /*
   * -------------------------------------------------------
   * Error
   * -------------------------------------------------------
   */

  if (isError) {
    console.error(
      "MAIN CATEGORIES ERROR:",
      error
    );

    return (
      <section className="w-full py-8 sm:py-10 lg:py-12">
        <div className="mx-auto w-full max-w-[1280px] px-4 text-center sm:px-6 lg:px-8">
          <h2 className="text-xl font-semibold text-neutral-900 sm:text-2xl">
            Categories
          </h2>

          <p className="mt-6 text-sm text-neutral-500">
            Unable to load categories.
          </p>
        </div>
      </section>
    );
  }

  /*
   * -------------------------------------------------------
   * Main
   * -------------------------------------------------------
   */

  return (
    <section className="w-full py-8 sm:py-10 lg:py-12">
      <div className="mx-auto w-full max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {/* Title */}

        <h2 className="text-center text-xl font-semibold text-neutral-900 sm:text-2xl">
          Categories
        </h2>

        {/* Categories */}

        {mainCategories.length > 0 ? (
          <div className="mt-8 grid grid-cols-3 gap-x-4 gap-y-7 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 md:grid-cols-6 lg:grid-cols-9 lg:gap-x-7 lg:gap-y-9">

            {mainCategories.map((category) => {

              /*
               * API:
               *
               * id
               * name
               * slug
               * icon_key
               * banner
               * parent_id
               */

              const image = category?.banner
  ? category.banner.startsWith("http")
    ? category.banner
    : `${process.env.NEXT_PUBLIC_API_URL}/${category.banner}`
  : "/icons/ip17.jpg";

              return (
                <Link
                  key={category.id}
                  href={`/search/${category.slug}?category_id=${category.id}`}
                  className="
                    group
                    flex
                    flex-col
                    items-center
                    text-center
                    outline-none
                  "
                >

                  {/* Image */}

                  <div
                    className="
                      relative
                      h-[72px]
                      w-[72px]
                      overflow-hidden
                      rounded-full
                      bg-neutral-100
                      transition-all
                      duration-300
                      group-hover:scale-105
                      group-hover:shadow-md
                      group-focus-visible:ring-2
                      group-focus-visible:ring-red-500
                      group-focus-visible:ring-offset-2
                      sm:h-[82px]
                      sm:w-[82px]
                      lg:h-[92px]
                      lg:w-[92px]
                    "
                  >
                    <Image
                      src={image}
                      alt={category?.name || "Category"}
                      fill
                      sizes="
                        (max-width: 640px) 72px,
                        (max-width: 1024px) 82px,
                        92px
                      "
                      className="object-cover"
                    />
                  </div>

                  {/* Title */}

                  <span
                    className="
                      mt-3
                      line-clamp-2
                      text-xs
                      font-medium
                      text-neutral-700
                      transition-colors
                      duration-200
                      group-hover:text-red-500
                      sm:text-sm
                    "
                  >
                    {category?.name}
                  </span>

                </Link>
              );
            })}

          </div>
        ) : (
          <div className="mt-8 text-center text-sm text-neutral-500">
            No categories available.
          </div>
        )}

      </div>
    </section>
  );
}