"use client";

import { useState, useRef, useEffect } from "react";
import { useRouter } from "next/navigation";
import {
  Search,
  Loader2,
  X,
  Smartphone,
  ArrowRight,
} from "lucide-react";

import { useDebounce } from "@/core/hooks/useDebounce";
import { useSearchProducts } from "@/core/services/queries";

import {
  MIN_SEARCH_LENGTH,
  getDisplayPrice,
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
      if (
        wrapperRef.current &&
        !wrapperRef.current.contains(event.target)
      ) {
        setIsOpen(false);
      }
    }

    document.addEventListener("mousedown", handleClickOutside);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  useEffect(() => {
    function handleEscape(event) {
      if (event.key === "Escape") {
        setIsOpen(false);
      }
    }

    document.addEventListener("keydown", handleEscape);

    return () => {
      document.removeEventListener("keydown", handleEscape);
    };
  }, []);

  const handleSelectProduct = (product) => {
    setIsOpen(false);
    router.push(`/product/${product.id}`);
  };

  const handleSelectCategory = (category) => {
    setIsOpen(false);

    router.push(
      `/search/${category?.slug}?category_id=${category.id}`
    );
  };

  const handleSearch = () => {
    const trimmedQuery = query.trim();

    if (trimmedQuery.length < MIN_SEARCH_LENGTH) {
      return;
    }

    setIsOpen(false);

    router.push(`/search/${encodeURIComponent(trimmedQuery)}`);
  };

  const handleKeyDown = (e) => {
    if (e.key === "Enter") {
      handleSearch();
    }
  };

  const handleClear = () => {
    setQuery("");
    setIsOpen(false);
  };

  const showDropdown =
    isOpen &&
    debouncedQuery.trim().length >= MIN_SEARCH_LENGTH;

  return (
    <div
      ref={wrapperRef}
      className="relative w-full min-w-0"
    >
      {/* Search Input */}
      <div className="relative w-full">
        <Search
          size={18}
          strokeWidth={2}
          className="
            pointer-events-none
            absolute
            left-3.5
            top-1/2
            z-10
            -translate-y-1/2
            text-neutral-400
            dark:text-neutral-500
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
          placeholder="Search products, brands..."
          aria-label="Search products"
          aria-expanded={showDropdown}
          autoComplete="off"
          className="
            h-10
            w-full
            rounded-xl
            border
            border-neutral-200
            bg-neutral-50
            pl-10
            pr-24
            text-xs
            text-neutral-900
            outline-none
            placeholder:text-neutral-400
            transition-all
            duration-200
            hover:border-neutral-300
            hover:bg-white
            focus:border-red-500
            focus:bg-white
            focus:ring-4
            focus:ring-red-500/10

            sm:h-11
            sm:pl-10
            sm:pr-28
            sm:text-sm

            dark:border-neutral-800
            dark:bg-neutral-900
            dark:text-neutral-100
            dark:placeholder:text-neutral-500
            dark:hover:border-neutral-700
            dark:hover:bg-neutral-900
            dark:focus:border-red-500
            dark:focus:bg-neutral-900
            dark:focus:ring-red-500/10

            lg:pr-10
          "
        />

        {/* Mobile Logo */}
        {!query && (
          <Link
            href="/"
            aria-label="Go to homepage"
            className="
              absolute
              right-3
              top-1/2
              z-10
              flex
              -translate-y-1/2
              items-center
              lg:hidden
            "
          >
            <Image
              src="/icons/en-logo.svg"
              width={100}
              height={30}
              alt="Digikala"
              className="
                h-auto
                w-[76px]
                sm:w-[86px]
              "
            />
          </Link>
        )}

        {/* Clear */}
        {query && (
          <button
            type="button"
            onClick={handleClear}
            aria-label="Clear search"
            title="Clear search"
            className="
              absolute
              right-2.5
              top-1/2
              z-10
              flex
              h-7
              w-7
              -translate-y-1/2
              items-center
              justify-center
              rounded-full
              text-neutral-400
              transition
              hover:bg-neutral-200
              hover:text-neutral-700
              focus:outline-none
              focus:ring-2
              focus:ring-red-500/20
              dark:text-neutral-500
              dark:hover:bg-neutral-800
              dark:hover:text-neutral-200
            "
          >
            <X size={16} strokeWidth={2} />
          </button>
        )}
      </div>

      {/* Search Dropdown */}
      {showDropdown && (
        <div
          className="
            absolute
            left-0
            right-0
            top-full
            z-[100]
            mt-2.5
            w-full
            max-w-[calc(100vw-24px)]
            overflow-hidden
            rounded-2xl
            border
            border-neutral-200
            bg-white
            shadow-[0_20px_60px_rgba(0,0,0,0.14)]
            dark:border-neutral-800
            dark:bg-neutral-950
            dark:shadow-[0_20px_70px_rgba(0,0,0,0.5)]
            sm:w-[540px]
            md:w-[640px]
            lg:w-[740px]
            xl:w-[800px]
          "
        >
          {/* Top Accent */}
          <div
            className="
              h-px
              w-full
              bg-gradient-to-r
              from-transparent
              via-red-500
              to-transparent
            "
          />

          {/* Loading */}
          {isFetching ? (
            <div className="p-4">
              <div className="mb-4 flex items-center justify-between">
                <div className="space-y-2">
                  <div
                    className="
                      h-2.5
                      w-24
                      animate-pulse
                      rounded-full
                      bg-neutral-200
                      dark:bg-neutral-800
                    "
                  />

                  <div
                    className="
                      h-2
                      w-36
                      animate-pulse
                      rounded-full
                      bg-neutral-100
                      dark:bg-neutral-900
                    "
                  />
                </div>

                <Loader2
                  size={17}
                  className="animate-spin text-red-500"
                />
              </div>

              <div className="space-y-1">
                {[1, 2, 3, 4].map((item) => (
                  <div
                    key={item}
                    className="
                      flex
                      items-center
                      gap-3
                      rounded-xl
                      p-2.5
                    "
                  >
                    <div
                      className="
                        h-11
                        w-11
                        shrink-0
                        animate-pulse
                        rounded-xl
                        bg-neutral-100
                        dark:bg-neutral-900
                      "
                    />

                    <div className="flex-1 space-y-2">
                      <div
                        className="
                          h-3
                          w-3/5
                          animate-pulse
                          rounded-full
                          bg-neutral-100
                          dark:bg-neutral-900
                        "
                      />

                      <div
                        className="
                          h-2.5
                          w-1/3
                          animate-pulse
                          rounded-full
                          bg-neutral-100
                          dark:bg-neutral-900
                        "
                      />
                    </div>

                    <div
                      className="
                        h-3
                        w-12
                        animate-pulse
                        rounded-full
                        bg-neutral-100
                        dark:bg-neutral-900
                      "
                    />
                  </div>
                ))}
              </div>
            </div>
          ) : results.length > 0 ? (
            <>
              {/* Search Header */}
              <div
                className="
                  flex
                  items-center
                  justify-between
                  border-b
                  border-neutral-100
                  bg-neutral-50
                  px-4
                  py-3
                  dark:border-neutral-800
                  dark:bg-neutral-900/60
                "
              >
                <div className="min-w-0">
                  <p
                    className="
                      text-[10px]
                      font-bold
                      uppercase
                      tracking-[0.14em]
                      text-neutral-400
                      dark:text-neutral-500
                    "
                  >
                    Search results
                  </p>

                  <p
                    className="
                      mt-1
                      truncate
                      text-xs
                      font-medium
                      text-neutral-700
                      dark:text-neutral-300
                    "
                  >
                    Results for{" "}
                    <span className="font-bold text-neutral-950 dark:text-white">
                      "{query.trim()}"
                    </span>
                  </p>
                </div>

                <span
                  className="
                    hidden
                    shrink-0
                    items-center
                    gap-1.5
                    rounded-full
                    border
                    border-neutral-200
                    bg-white
                    px-2.5
                    py-1
                    text-[10px]
                    font-medium
                    text-neutral-400
                    sm:flex
                    dark:border-neutral-800
                    dark:bg-neutral-950
                    dark:text-neutral-500
                  "
                >
                  <span className="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                  Live
                </span>
              </div>

              {/* Categories */}
              {categories.length > 0 && (
                <div
                  className="
                    border-b
                    border-neutral-100
                    px-4
                    py-3.5
                    dark:border-neutral-800
                  "
                >
                  <div className="mb-2.5 flex items-center justify-between">
                    <p
                      className="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
                        text-neutral-400
                        dark:text-neutral-500
                      "
                    >
                      Categories
                    </p>

                    <span
                      className="
                        text-[10px]
                        text-neutral-400
                        dark:text-neutral-600
                      "
                    >
                      Explore
                    </span>
                  </div>

                  <div className="flex flex-wrap gap-2">
                    {categories.map((cat) => {
                      const isMatch = isCategoryMatch(cat.name);

                      return (
                        <button
                          type="button"
                          key={cat.id}
                          onClick={() =>
                            handleSelectCategory(cat)
                          }
                          className={`
                            group
                            inline-flex
                            items-center
                            gap-1.5
                            rounded-xl
                            border
                            px-3
                            py-2
                            text-xs
                            font-medium
                            shadow-sm
                            transition-all
                            duration-200
                            hover:-translate-y-0.5
                            hover:shadow-md
                            focus:outline-none
                            focus:ring-2
                            focus:ring-red-500/20

                            ${
                              isMatch
                                ? `
                                  border-red-200
                                  bg-red-50
                                  text-red-700
                                  hover:border-red-300
                                  hover:bg-red-100
                                  dark:border-red-900/60
                                  dark:bg-red-950/40
                                  dark:text-red-400
                                `
                                : `
                                  border-neutral-200
                                  bg-white
                                  text-neutral-600
                                  hover:border-neutral-300
                                  hover:bg-neutral-50
                                  dark:border-neutral-800
                                  dark:bg-neutral-900
                                  dark:text-neutral-300
                                  dark:hover:border-neutral-700
                                  dark:hover:bg-neutral-800
                                `
                            }
                          `}
                        >
                          <span
                            className={`
                              flex
                              h-4
                              w-4
                              items-center
                              justify-center
                              rounded-md
                              ${
                                isMatch
                                  ? "bg-red-500 text-white"
                                  : "bg-neutral-100 text-neutral-400 dark:bg-neutral-800"
                              }
                            `}
                          >
                            <Search
                              size={9}
                              strokeWidth={2.5}
                            />
                          </span>

                          <span>{cat.name}</span>
                        </button>
                      );
                    })}
                  </div>
                </div>
              )}

              {/* Products Header */}
              <div
                className="
                  flex
                  items-center
                  justify-between
                  px-4
                  pb-2
                  pt-3.5
                "
              >
                <div className="flex items-center gap-2">
                  <span
                    className="
                      h-1.5
                      w-1.5
                      rounded-full
                      bg-red-500
                      shadow-[0_0_0_3px_rgba(239,68,68,0.1)]
                    "
                  />

                  <p
                    className="
                      text-[10px]
                      font-bold
                      uppercase
                      tracking-[0.14em]
                      text-neutral-500
                    "
                  >
                    Products
                  </p>
                </div>

                <span
                  className="
                    rounded-full
                    bg-neutral-100
                    px-2
                    py-1
                    text-[10px]
                    font-semibold
                    text-neutral-500
                    dark:bg-neutral-900
                    dark:text-neutral-500
                  "
                >
                  {results.length}{" "}
                  {results.length === 1 ? "result" : "results"}
                </span>
              </div>

              {/* Products */}
              <div
                className="
                  max-h-[390px]
                  overflow-y-auto
                  overscroll-contain
                  px-2
                  pb-2
                  scrollbar-thin
                "
              >
                {results.map((product) => {
                  const variant = getDisplayPrice(product);

                  return (
                    <button
                      type="button"
                      key={product.id}
                      onClick={() =>
                        handleSelectProduct(product)
                      }
                      className="
                        group
                        relative
                        flex
                        min-h-[68px]
                        w-full
                        items-center
                        gap-3
                        rounded-2xl
                        px-2.5
                        py-2.5
                        text-left
                        transition-all
                        duration-200
                        hover:bg-neutral-50
                        hover:shadow-sm
                        focus:bg-neutral-50
                        focus:outline-none
                        dark:hover:bg-neutral-900
                        dark:focus:bg-neutral-900
                      "
                    >
                      {/* Red Hover Line */}
                      <span
                        className="
                          absolute
                          bottom-2
                          left-0
                          top-2
                          w-0.5
                          rounded-full
                          bg-red-500
                          opacity-0
                          transition-opacity
                          duration-200
                          group-hover:opacity-100
                        "
                      />

                      {/* Product Icon */}
                      <div
                        className="
                          relative
                          flex
                          h-11
                          w-11
                          shrink-0
                          items-center
                          justify-center
                          overflow-hidden
                          rounded-xl
                          border
                          border-neutral-200
                          bg-gradient-to-br
                          from-neutral-50
                          via-white
                          to-neutral-100
                          shadow-sm
                          transition-all
                          duration-200
                          group-hover:scale-105
                          group-hover:border-neutral-300
                          group-hover:shadow
                          dark:border-neutral-800
                          dark:from-neutral-900
                          dark:via-neutral-950
                          dark:to-neutral-900
                          dark:group-hover:border-neutral-700
                        "
                      >
                        <div
                          className="
                            absolute
                            inset-0
                            bg-red-500/[0.04]
                          "
                        />

                        <Smartphone
                          size={19}
                          strokeWidth={1.7}
                          className="
                            relative
                            text-neutral-400
                            transition-colors
                            group-hover:text-red-500
                            dark:text-neutral-500
                            dark:group-hover:text-red-400
                          "
                        />
                      </div>

                      {/* Product Info */}
                      <div className="min-w-0 flex-1">
                        <p
                          className="
                            truncate
                            text-[13px]
                            font-semibold
                            leading-5
                            text-neutral-800
                            transition-colors
                            group-hover:text-neutral-950
                            dark:text-neutral-200
                            dark:group-hover:text-white
                          "
                        >
                          {product.title}
                        </p>

                        <div className="mt-1 flex items-center gap-1.5">
                          <span
                            className="
                              rounded-md
                              bg-neutral-100
                              px-1.5
                              py-0.5
                              text-[9px]
                              font-medium
                              text-neutral-400
                              dark:bg-neutral-900
                              dark:text-neutral-500
                            "
                          >
                            Product
                          </span>

                          <span
                            className="
                              h-1
                              w-1
                              rounded-full
                              bg-neutral-300
                              dark:bg-neutral-700
                            "
                          />

                          <span
                            className="
                              truncate
                              text-[10px]
                              text-neutral-400
                              dark:text-neutral-600
                            "
                          >
                            View details
                          </span>
                        </div>
                      </div>

                      {/* Price */}
                      {variant && (
                        <div className="flex shrink-0 items-center gap-2">
                          <div className="text-right">
                            <p
                              className="
                                text-[12px]
                                font-bold
                                text-neutral-800
                                dark:text-neutral-200
                              "
                            >
                              ${variant.price}
                            </p>

                            <p
                              className="
                                mt-0.5
                                text-[9px]
                                text-neutral-400
                                dark:text-neutral-600
                              "
                            >
                              From
                            </p>
                          </div>

                          <div
                            className="
                              flex
                              h-7
                              w-7
                              items-center
                              justify-center
                              rounded-full
                              border
                              border-neutral-200
                              bg-white
                              text-neutral-400
                              opacity-0
                              transition-all
                              duration-200
                              group-hover:translate-x-0.5
                              group-hover:opacity-100
                              dark:border-neutral-800
                              dark:bg-neutral-950
                            "
                          >
                            <ArrowRight size={13} />
                          </div>
                        </div>
                      )}
                    </button>
                  );
                })}
              </div>

              {/* Search All */}
              <div
                className="
                  border-t
                  border-neutral-100
                  bg-neutral-50/80
                  p-2
                  dark:border-neutral-800
                  dark:bg-neutral-900/70
                "
              >
                <button
                  type="button"
                  onClick={handleSearch}
                  className="
                    group
                    flex
                    w-full
                    items-center
                    justify-between
                    rounded-xl
                    border
                    border-transparent
                    bg-white
                    px-3.5
                    py-3
                    text-left
                    shadow-sm
                    transition-all
                    duration-200
                    hover:border-red-100
                    hover:bg-red-50
                    dark:bg-neutral-950
                    dark:hover:border-red-900/50
                    dark:hover:bg-red-950/30
                  "
                >
                  <div className="flex min-w-0 items-center gap-2.5">
                    <div
                      className="
                        flex
                        h-8
                        w-8
                        shrink-0
                        items-center
                        justify-center
                        rounded-lg
                        bg-neutral-100
                        text-neutral-400
                        transition-colors
                        group-hover:bg-red-500
                        group-hover:text-white
                        dark:bg-neutral-900
                        dark:text-neutral-500
                        dark:group-hover:bg-red-500
                        dark:group-hover:text-white
                      "
                    >
                      <Search size={14} />
                    </div>

                    <div className="min-w-0">
                      <p
                        className="
                          truncate
                          text-xs
                          font-semibold
                          text-neutral-700
                          group-hover:text-red-600
                          dark:text-neutral-300
                          dark:group-hover:text-red-400
                        "
                      >
                        Search for "{query.trim()}"
                      </p>

                      <p
                        className="
                          mt-0.5
                          text-[9px]
                          text-neutral-400
                          dark:text-neutral-600
                        "
                      >
                        View all matching products
                      </p>
                    </div>
                  </div>

                  <div
                    className="
                      flex
                      h-7
                      w-7
                      shrink-0
                      items-center
                      justify-center
                      rounded-full
                      bg-neutral-100
                      text-neutral-400
                      transition-all
                      duration-200
                      group-hover:translate-x-0.5
                      group-hover:bg-red-500
                      group-hover:text-white
                      dark:bg-neutral-900
                      dark:text-neutral-500
                      dark:group-hover:bg-red-500
                      dark:group-hover:text-white
                    "
                  >
                    <ArrowRight size={13} />
                  </div>
                </button>
              </div>
            </>
          ) : (
            /* Empty State */
            <div className="px-5 py-10 text-center">
              <div
                className="
                  relative
                  mx-auto
                  mb-4
                  flex
                  h-14
                  w-14
                  items-center
                  justify-center
                  rounded-2xl
                  border
                  border-neutral-200
                  bg-gradient-to-br
                  from-neutral-50
                  to-neutral-100
                  shadow-sm
                  dark:border-neutral-800
                  dark:from-neutral-900
                  dark:to-neutral-950
                "
              >
                <Search
                  size={21}
                  strokeWidth={1.7}
                  className="
                    text-neutral-400
                    dark:text-neutral-500
                  "
                />
              </div>

              <p
                className="
                  text-sm
                  font-semibold
                  text-neutral-800
                  dark:text-neutral-200
                "
              >
                No products found
              </p>

              <p
                className="
                  mx-auto
                  mt-1.5
                  max-w-[300px]
                  text-xs
                  leading-5
                  text-neutral-400
                  dark:text-neutral-500
                "
              >
                We couldn't find anything matching{" "}
                <span className="font-medium text-neutral-600 dark:text-neutral-400">
                  "{query.trim()}"
                </span>
                . Try another keyword.
              </p>

              <button
                type="button"
                onClick={handleClear}
                className="
                  mt-5
                  inline-flex
                  items-center
                  gap-1.5
                  rounded-xl
                  border
                  border-neutral-200
                  bg-white
                  px-3.5
                  py-2
                  text-xs
                  font-semibold
                  text-neutral-600
                  shadow-sm
                  transition-all
                  hover:border-neutral-300
                  hover:bg-neutral-50
                  dark:border-neutral-800
                  dark:bg-neutral-900
                  dark:text-neutral-300
                  dark:hover:border-neutral-700
                  dark:hover:bg-neutral-800
                "
              >
                <X size={13} />
                Clear search
              </button>
            </div>
          )}
        </div>
      )}
    </div>
  );
}