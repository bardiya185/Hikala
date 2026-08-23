"use client";

import { useState, useRef, useEffect } from "react";
import { useRouter } from "next/navigation";
import { Search, Loader2, X, Smartphone } from "lucide-react";

import { useDebounce } from "@/core/hooks/useDebounce";
import { useSearchProducts } from "@/core/services/queries";

import {
  MIN_SEARCH_LENGTH,
  getDisplayPrice,
  getSearchKeyword,
  isCategoryMatch,
  getUniqueCategories,
} from "@/core/utils/searchHelper";
import Link from "next/link";
import Image from "next/image";

export default function SearchBar() {
  const [query, setQuery] = useState("");
  const [isOpen, setIsOpen] = useState(false);

  const wrapperRef = useRef(null);

  const router = useRouter();

  const debouncedQuery = useDebounce(query, 100);

  const { data, isFetching } = useSearchProducts(debouncedQuery);

  const results = data?.data || [];

  const categories = getUniqueCategories(results);
  useEffect(() => {
    function handleClickOutside(event) {
      if (wrapperRef.current && !wrapperRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    }

    document.addEventListener("mousedown", handleClickOutside);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);
  const handleSelectProduct = (title) => {
  if (!title) return;

  setIsOpen(false);

  router.push(`/search/${encodeURIComponent(title.trim())}`);
};
  const handleSelectCategory = (category) => {

     console.log("SELECTED CATEGORY:", category);
  console.log("CATEGORY SLUG:", category?.slug);
  console.log("CATEGORY ID:", category?.id);
    setIsOpen(false);

    router.push(`/search/${category?.slug}?category_id=${category.id}`);
  };
  const handleKeyDown = (e) => {
    if (e.key === "Enter" && query.trim().length >= MIN_SEARCH_LENGTH) {
      handleSelectProduct(query.trim());
    }
  };

  const showDropdown =
    isOpen && debouncedQuery.trim().length >= MIN_SEARCH_LENGTH;

  return (
    <div
      ref={wrapperRef}
      className="
        relative
        w-full
        min-w-0
      "
    >
      {}
      <div className="relative w-full">
        <Search
          size={18}
          className="
            absolute
            left-3
            top-1/2
            -translate-y-1/2
            text-neutral-400
            pointer-events-none
          "
        />

        <input
          type="text"
          suppressHydrationWarning
          value={query}
          onChange={(e) => {
            setQuery(e.target.value);
            setIsOpen(true);
          }}
          onFocus={() => setIsOpen(true)}
          onKeyDown={handleKeyDown}
          placeholder="Search for products..."
          className="
            w-full
            h-10
            sm:h-11

            pl-10
            pr-9

            rounded-lg
            sm:rounded-xl

            border
            border-neutral-200

            bg-white

            text-xs
            sm:text-sm

            text-neutral-800

            outline-none

            focus:border-red-500
            focus:ring-2
            focus:ring-red-500/10

            transition-all
          "
        />
        <Link
    href="/"
    className="
      absolute

      right-3
      top-1/2
      -translate-y-1/2

      lg:hidden

      flex
      items-center

      z-10
    "
  >
    <Image
      src="/icons/en-logo.svg"
      width={100}
      height={30}
      alt="Digikala"
      className="
        w-[80px]
        sm:w-[90px]
        h-auto
      "
    />
  </Link>

        {}
        {query && (
          <button
            type="button"
            onClick={() => {
              setQuery("");
              setIsOpen(false);
            }}
            className="
              absolute
              right-3
              top-1/2
              -translate-y-1/2
              text-neutral-400
              hover:text-neutral-600
              p-1
              cursor-pointer
            "
            aria-label="Clear search"
          >
            <X size={16} />
          </button>
        )}
      </div>

      {/* =========================
          Dropdown
      ========================= */}
      {showDropdown && (
        <div
          className="
            absolute
            top-full
            left-0
            right-0
            mt-2

            w-full
            sm:w-[500px]
            md:w-[600px]
            lg:w-[700px]

            max-w-[calc(100vw-24px)]

            bg-white

            border
            border-neutral-200

            rounded-xl

            overflow-hidden

            z-[100]

            max-h-[450px]

            overflow-y-auto

            shadow-xl
          "
        >
          {}
          {isFetching ? (
            <div
              className="
                p-4
                flex
                items-center
                justify-center
                gap-2
                text-neutral-400
                text-sm
              "
            >
              <Loader2 size={16} className="animate-spin" />
              Searching...
            </div>
          ) : results.length > 0 ? (
            <>
              {/* =========================
                  Categories
              ========================= */}
              {categories.length > 0 && (
                <div className="px-3.5 py-3 border-b border-neutral-100">
                  <p
                    className="
                      text-[11px]
                      font-medium
                      text-neutral-400
                      uppercase
                      tracking-wide
                      mb-2
                    "
                  >
                    Categories
                  </p>

                  <div className="flex flex-wrap gap-1.5">
                    {categories.map((cat) => {
                      const isMatch = isCategoryMatch(cat.name);

                      return (
                        <button
                          type="button"
                          key={cat.id}
                          onClick={() => handleSelectCategory(cat)}
                          className={`
                            text-xs
                            font-medium
                            px-2.5
                            py-1.5
                            rounded-lg
                            transition-colors

                            ${
                              isMatch
                                ? "bg-red-50 text-red-900 hover:bg-red-100"
                                : "bg-neutral-100 text-neutral-600 hover:bg-neutral-200"
                            }
                          `}
                        >
                          {cat.name}
                        </button>
                      );
                    })}
                  </div>
                </div>
              )}

              {/* =========================
                  Products
              ========================= */}
              <div className="py-1">
                <p
                  className="
                    text-[11px]
                    font-medium
                    text-neutral-400
                    uppercase
                    tracking-wide
                    px-3.5
                    pt-2
                    pb-1
                  "
                >
                  Products
                </p>

                {results.map((product) => {
                  const variant = getDisplayPrice(product);

                  return (
                    <button
                      type="button"
                      key={product.id}
                      onClick={() => handleSelectProduct(product?.title)}
                      className="
                        w-full
                        flex
                        items-center
                        gap-3

                        px-3.5
                        py-2

                        hover:bg-neutral-50

                        transition-colors

                        text-left
                      "
                    >
                      {}
                      <div
                        className="
                          w-9
                          h-9
                          rounded-lg
                          bg-neutral-100
                          flex
                          items-center
                          justify-center
                          shrink-0
                        "
                      >
                        <Smartphone size={18} className="text-neutral-400" />
                      </div>

                      {}
                      <div className="flex-1 min-w-0">
                        <p
                          className="
                            text-[13px]
                            text-neutral-800
                            truncate
                          "
                        >
                          {product.title}
                        </p>
                      </div>

                      {}
                      {variant && (
                        <span
                          className="
                            text-[13px]
                            text-neutral-500
                            shrink-0
                          "
                        >
                          ${variant.price}
                        </span>
                      )}
                    </button>
                  );
                })}
              </div>
            </>
          ) : (
            <div
              className="
                p-4
                text-center
                text-sm
                text-neutral-400
              "
            >
              No products found for "{debouncedQuery}"
            </div>
          )}
        </div>
      )}
    </div>
  );
}
