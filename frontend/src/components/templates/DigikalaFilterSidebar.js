"use client";

import React, { useEffect, useMemo, useState } from "react";

import * as Accordion from "@radix-ui/react-accordion";
import * as Switch from "@radix-ui/react-switch";
import * as Checkbox from "@radix-ui/react-checkbox";

import {
  ChevronDown,
  ChevronLeft,
  Search,
  Check,
  RotateCcw,
} from "lucide-react";

import { LuMessageSquareWarning } from "react-icons/lu";

import { useGetBrandsFilter } from "@/core/services/queries";

import {
  useRouter,
  usePathname,
  useSearchParams,
} from "next/navigation";

import { useBrandFilter } from "@/core/hooks/useBrandFilter";

import Slider from "rc-slider";
import "rc-slider/assets/index.css";

/* =========================================================
   FILTER SECTION
========================================================= */

const FilterSection = ({ value, title, children }) => (
  <Accordion.Item
    value={value}
    className="px-4 py-1 sm:px-5"
  >
    <Accordion.Header className="flex">
      <Accordion.Trigger
        className="
          group flex w-full items-center justify-between
          py-4 text-sm font-semibold
          text-neutral-700 transition-colors
          outline-none hover:text-neutral-900
          focus-visible:text-neutral-900
          dark:text-neutral-200 dark:hover:text-white
          dark:focus-visible:text-white
        "
      >
        <span>{title}</span>

        <div className="text-neutral-400 dark:text-neutral-500">
          <span className="lg:hidden">
            <ChevronLeft size={20} />
          </span>

          <span
            className="
              hidden transition-transform duration-300
              lg:inline-block
              group-data-[state=open]:rotate-180
            "
          >
            <ChevronDown size={20} />
          </span>
        </div>
      </Accordion.Trigger>
    </Accordion.Header>

    <Accordion.Content
      className="
        overflow-hidden text-sm text-neutral-600
        data-[state=open]:animate-slideDown
        data-[state=closed]:animate-slideUp
        dark:text-neutral-300
      "
    >
      {children}
    </Accordion.Content>
  </Accordion.Item>
);

/* =========================================================
   MAIN COMPONENT
========================================================= */

export default function DigikalaFilterSidebar({
  products = [],
}) {
  /* =======================================================
     SAFE PRODUCTS
  ======================================================= */

  const safeProducts = useMemo(
    () => (Array.isArray(products) ? products : []),
    [products]
  );

  /* =======================================================
     BRANDS
  ======================================================= */

  const {
    data: allBrandsFromApi,
    isLoading,
  } = useGetBrandsFilter();

  const [searchTerm, setSearchTerm] = useState("");

  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const {
    selectedBrands,
    filteredBrands,
    handleCheckboxChange,
  } = useBrandFilter({
    products: safeProducts,
    allBrandsFromApi,
    searchTerm,
  });

  /* =======================================================
     PRICE LIST
  ======================================================= */

  const prices = useMemo(() => {
    return safeProducts
      .flatMap((product) =>
        Array.isArray(product?.variants)
          ? product.variants.map((variant) =>
              Number(variant?.final_price)
            )
          : []
      )
      .filter(
        (price) =>
          Number.isFinite(price) && price >= 0
      );
  }, [safeProducts]);

  /* =======================================================
     MIN / MAX PRICE
  ======================================================= */

  const minProductPrice = useMemo(() => {
    if (!prices.length) return 0;
    return Math.min(...prices);
  }, [prices]);

  const maxProductPrice = useMemo(() => {
    if (!prices.length) return 0;
    return Math.max(...prices);
  }, [prices]);

  /* =======================================================
     URL PRICE
  ======================================================= */

  const urlMinPrice = searchParams.get("min_price");
  const urlMaxPrice = searchParams.get("max_price");

  const parsedUrlMin = Number(urlMinPrice);
  const parsedUrlMax = Number(urlMaxPrice);

  /* =======================================================
     INITIAL SELECTED PRICE
  ======================================================= */

  const getInitialMin = () => {
    if (
      urlMinPrice !== null &&
      Number.isFinite(parsedUrlMin)
    ) {
      return Math.max(
        minProductPrice,
        Math.min(parsedUrlMin, maxProductPrice)
      );
    }

    return minProductPrice;
  };

  const getInitialMax = () => {
    if (
      urlMaxPrice !== null &&
      Number.isFinite(parsedUrlMax)
    ) {
      return Math.min(
        maxProductPrice,
        Math.max(parsedUrlMax, minProductPrice)
      );
    }

    return maxProductPrice;
  };

  /* =======================================================
     LOCAL SLIDER STATE
  ======================================================= */

  const [priceRange, setPriceRange] = useState(() => [
    getInitialMin(),
    getInitialMax(),
  ]);

  /* =======================================================
     SYNC URL -> SLIDER
  ======================================================= */

  useEffect(() => {
    const min = getInitialMin();
    const max = getInitialMax();

    setPriceRange([min, max]);
  }, [
    urlMinPrice,
    urlMaxPrice,
    minProductPrice,
    maxProductPrice,
  ]);

  /* =======================================================
     PRICE CHANGE - LOCAL ONLY
  ======================================================= */

  const handlePriceChange = (value) => {
    if (!Array.isArray(value) || value.length !== 2) {
      return;
    }

    const min = Number(value[0]);
    const max = Number(value[1]);

    if (!Number.isFinite(min) || !Number.isFinite(max)) {
      return;
    }

    setPriceRange([min, max]);
  };

  /* =======================================================
     PRICE CHANGE COMPLETE
  ======================================================= */

  const handlePriceChangeComplete = (value) => {
    if (!Array.isArray(value) || value.length !== 2) {
      return;
    }

    let min = Number(value[0]);
    let max = Number(value[1]);

    if (!Number.isFinite(min) || !Number.isFinite(max)) {
      return;
    }

    min = Math.max(
      minProductPrice,
      Math.min(min, maxProductPrice)
    );

    max = Math.min(
      maxProductPrice,
      Math.max(max, minProductPrice)
    );

    if (min > max) {
      [min, max] = [max, min];
    }

    const params = new URLSearchParams(
      searchParams.toString()
    );

    if (min <= minProductPrice) {
      params.delete("min_price");
    } else {
      params.set("min_price", String(min));
    }

    if (max >= maxProductPrice) {
      params.delete("max_price");
    } else {
      params.set("max_price", String(max));
    }

    const queryString = params.toString();

    router.replace(
      queryString
        ? `${pathname}?${queryString}`
        : pathname,
      { scroll: false }
    );
  };

  /* =======================================================
     AVAILABLE PRODUCTS
  ======================================================= */

  const available =
    searchParams.get("available") === "1";

  const handleAvailableChange = (checked) => {
    const params = new URLSearchParams(
      searchParams.toString()
    );

    if (checked) {
      params.set("available", "1");
    } else {
      params.delete("available");
    }

    const queryString = params.toString();

    router.replace(
      queryString
        ? `${pathname}?${queryString}`
        : pathname,
      { scroll: false }
    );
  };

  /* =======================================================
     RESET PRICE FILTER
  ======================================================= */

  const handleResetPrice = () => {
    const params = new URLSearchParams(
      searchParams.toString()
    );

    params.delete("min_price");
    params.delete("max_price");

    setPriceRange([
      minProductPrice,
      maxProductPrice,
    ]);

    const queryString = params.toString();

    router.replace(
      queryString
        ? `${pathname}?${queryString}`
        : pathname,
      { scroll: false }
    );
  };

  /* =======================================================
     FORMAT PRICE
  ======================================================= */

  const formatPrice = (price) => {
    return Number(price).toLocaleString("en-US", {
      maximumFractionDigits: 2,
    });
  };

  /* =======================================================
     RENDER
  ======================================================= */

  return (
    <aside
      dir="ltr"
      className="
        w-full overflow-hidden
        bg-white text-left font-sans
        dark:bg-neutral-900 dark:text-neutral-100

        border-0 rounded-none
        sm:rounded-xl sm:border sm:border-neutral-200
        dark:sm:border-neutral-800

        lg:sticky lg:top-5 lg:self-start

        shadow-none
        sm:shadow-sm
        dark:sm:shadow-black/20
      "
    >
      {/* ===================================================
          HEADER
      =================================================== */}

      <div
        className="
          flex items-center justify-between
          border-b border-neutral-100
          px-4 py-4 text-base font-bold
          text-neutral-800
          dark:border-neutral-800
          dark:text-neutral-100
          sm:px-5
        "
      >
        <span>Filters</span>

        {(urlMinPrice !== null ||
          urlMaxPrice !== null ||
          available ||
          selectedBrands?.length > 0) && (
          <span className="rounded-full bg-red-50 px-2 py-1 text-[10px] font-semibold text-red-600 dark:bg-red-950/30 dark:text-red-400">
            Active
          </span>
        )}
      </div>

      {/* ===================================================
          ACCORDION
      =================================================== */}

      <Accordion.Root
        type="multiple"
        className="
          w-full divide-y
          divide-neutral-100
          dark:divide-neutral-800
        "
      >
        {/* =================================================
            ADDRESS
        ================================================= */}

        <FilterSection
          value="address"
          title="Express Delivery"
        >
          <div className="space-y-3 pb-4">
            <div
              className="
                flex w-full items-center gap-3
                rounded-lg border
                border-amber-100 bg-amber-50 p-3
                dark:border-amber-900/50
                dark:bg-amber-950/20
              "
            >
              <LuMessageSquareWarning
                className="
                  h-6 w-6 shrink-0
                  text-amber-500
                  dark:text-amber-400
                "
              />

              <p
                className="
                  text-xs leading-relaxed
                  text-neutral-600
                  dark:text-neutral-300
                "
              >
                To view available products in
                a nearby warehouse, please specify
                your address.
              </p>
            </div>

            <button
              type="button"
              className="
                w-full rounded-lg border
                border-red-500 py-2
                text-xs font-semibold
                text-red-500 transition-colors
                hover:bg-red-50
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                dark:border-red-500
                dark:text-red-400
                dark:hover:bg-red-950/30
              "
            >
              Select Address
            </button>
          </div>
        </FilterSection>

        {/* =================================================
            BRAND
        ================================================= */}

        <FilterSection
          value="brand"
          title="Brand"
        >
          <div className="pb-4 pt-1">
            <div
              className="
                mb-3 flex items-center
                rounded-lg border
                border-transparent
                bg-neutral-100 px-3 py-2
                transition-colors
                focus-within:border-red-200
                focus-within:bg-white
                dark:bg-neutral-800
                dark:focus-within:border-red-900
                dark:focus-within:bg-neutral-850
              "
            >
              <Search
                size={16}
                className="
                  ml-2 shrink-0
                  text-neutral-400
                  dark:text-neutral-500
                "
              />

              <input
                type="text"
                placeholder="Search brands..."
                value={searchTerm}
                onChange={(event) =>
                  setSearchTerm(event.target.value)
                }
                className="
                  w-full min-w-0
                  bg-transparent
                  font-sans text-xs
                  text-neutral-700 outline-none
                  placeholder:text-neutral-400
                  dark:text-neutral-100
                  dark:placeholder:text-neutral-500
                "
                aria-label="Search brands"
              />
            </div>

            <div
              className="
                max-h-[220px] space-y-1
                overflow-y-auto
                scrollbar-thin
                scrollbar-thumb-neutral-200
                dark:scrollbar-thumb-neutral-700
              "
            >
              {isLoading ? (
                <div className="space-y-2 py-2">
                  {[1, 2, 3, 4].map((item) => (
                    <div
                      key={item}
                      className="
                        h-8 w-full animate-pulse
                        rounded-md bg-neutral-100
                        dark:bg-neutral-800
                      "
                    />
                  ))}
                </div>
              ) : filteredBrands?.length ? (
                filteredBrands.map((brand) => {
                  const brandId = String(brand.id);
                  const checkboxId = `brand-${brandId}`;
                  const isChecked =
                    selectedBrands.includes(brandId);

                  return (
                    <div
                      key={brandId}
                      className="
                        flex w-full items-center
                        justify-start rounded-lg
                        px-2 transition-colors
                        hover:bg-neutral-50
                        dark:hover:bg-neutral-800
                      "
                    >
                      <div className="ml-3 shrink-0 py-2">
                        <Checkbox.Root
                          id={checkboxId}
                          checked={isChecked}
                          onCheckedChange={() =>
                            handleCheckboxChange(brandId)
                          }
                          className="
                            flex h-4 w-4 shrink-0
                            items-center justify-center
                            rounded border
                            border-neutral-300
                            bg-white transition-colors
                            outline-none
                            focus-visible:ring-2
                            focus-visible:ring-red-500
                            data-[state=checked]:border-red-500
                            data-[state=checked]:bg-red-500
                            dark:border-neutral-600
                            dark:bg-neutral-900
                            dark:data-[state=checked]:border-red-500
                            dark:data-[state=checked]:bg-red-500
                          "
                        >
                          <Checkbox.Indicator className="text-white">
                            <Check
                              size={12}
                              strokeWidth={3}
                            />
                          </Checkbox.Indicator>
                        </Checkbox.Root>
                      </div>

                      <label
                        htmlFor={checkboxId}
                        className="
                          flex min-w-0 grow
                          cursor-pointer select-none
                          items-center justify-between
                          gap-2 border-b
                          border-neutral-100 py-2
                          text-xs font-semibold
                          text-neutral-700
                          dark:border-neutral-800
                          dark:text-neutral-200
                        "
                      >
                        <span className="truncate">
                          {brand.name}
                        </span>

                        <span
                          className="
                            hidden max-w-[40%]
                            truncate text-left
                            text-[10px] font-normal
                            text-neutral-400
                            sm:block
                            dark:text-neutral-500
                          "
                        >
                          {brand.slug}
                        </span>
                      </label>
                    </div>
                  );
                })
              ) : (
                <p
                  className="
                    py-3 text-center text-xs
                    text-neutral-400
                    dark:text-neutral-500
                  "
                >
                  No brands found.
                </p>
              )}
            </div>
          </div>
        </FilterSection>

        {/* =================================================
            PRICE
        ================================================= */}

        <FilterSection
          value="price"
          title="Price Range"
        >
          <div className="overflow-hidden px-1 pb-6 pt-2">
            {prices.length > 0 &&
            maxProductPrice > minProductPrice ? (
              <>
                <div className="px-3 py-5 sm:px-4">
                  <Slider
                    range
                    min={minProductPrice}
                    max={maxProductPrice}
                    value={priceRange}
                    onChange={handlePriceChange}
                    onChangeComplete={
                      handlePriceChangeComplete
                    }
                    allowCross={false}
                    step={1}
                    reverse
                    pushable={false}
                  />
                </div>

                <div className="mt-2 grid grid-cols-2 gap-2">
                  <div className="min-w-0">
                    <span
                      className="
                        mb-1 block text-[10px]
                        text-neutral-400
                        dark:text-neutral-500
                      "
                    >
                      Minimum Price
                    </span>

                    <div
                      className="
                        overflow-hidden whitespace-nowrap
                        rounded-lg border
                        border-neutral-200
                        bg-white px-2 py-2
                        text-center text-[11px]
                        text-neutral-700
                        dark:border-neutral-700
                        dark:bg-neutral-800
                        dark:text-neutral-200
                        sm:px-3 sm:text-xs
                      "
                    >
                      ${formatPrice(priceRange[0])}
                    </div>
                  </div>

                  <div className="min-w-0">
                    <span
                      className="
                        mb-1 block text-[10px]
                        text-neutral-400
                        dark:text-neutral-500
                      "
                    >
                      Maximum Price
                    </span>

                    <div
                      className="
                        overflow-hidden whitespace-nowrap
                        rounded-lg border
                        border-neutral-200
                        bg-white px-2 py-2
                        text-center text-[11px]
                        text-neutral-700
                        dark:border-neutral-700
                        dark:bg-neutral-800
                        dark:text-neutral-200
                        sm:px-3 sm:text-xs
                      "
                    >
                      ${formatPrice(priceRange[1])}
                    </div>
                  </div>
                </div>

                <div
                  className="
                    mt-3 flex items-center
                    justify-between gap-3 px-1
                    text-[10px] text-neutral-400
                    dark:text-neutral-500
                  "
                >
                  <span>
                    ${formatPrice(minProductPrice)}
                  </span>

                  <span>
                    ${formatPrice(maxProductPrice)}
                  </span>
                </div>

                {(urlMinPrice !== null ||
                  urlMaxPrice !== null) && (
                  <button
                    type="button"
                    onClick={handleResetPrice}
                    className="
                      mt-4 flex w-full
                      items-center justify-center
                      gap-1.5 rounded-lg
                      border border-neutral-200
                      py-2 text-[11px]
                      font-semibold
                      text-neutral-500
                      transition-colors
                      hover:border-red-200
                      hover:bg-red-50
                      hover:text-red-500
                      focus:outline-none
                      focus-visible:ring-2
                      focus-visible:ring-red-500
                      dark:border-neutral-700
                      dark:text-neutral-400
                      dark:hover:border-red-900
                      dark:hover:bg-red-950/30
                      dark:hover:text-red-400
                    "
                  >
                    <RotateCcw className="h-3.5 w-3.5" />
                    Reset Price
                  </button>
                )}
              </>
            ) : prices.length === 1 ||
              minProductPrice === maxProductPrice ? (
              <div className="py-4 text-center">
                <p
                  className="
                    text-xs text-neutral-500
                    dark:text-neutral-400
                  "
                >
                  Product Price
                </p>

                <p
                  className="
                    mt-2 text-sm font-semibold
                    text-neutral-700
                    dark:text-neutral-200
                  "
                  dir="ltr"
                >
                  ${formatPrice(minProductPrice)}
                </p>
              </div>
            ) : (
              <p
                className="
                  py-4 text-center text-xs
                  text-neutral-400
                  dark:text-neutral-500
                "
              >
                No product price available.
              </p>
            )}
          </div>
        </FilterSection>
      </Accordion.Root>

      {/* ===================================================
          AVAILABLE PRODUCTS
      =================================================== */}

      <div
        className="
          border-t border-neutral-100
          dark:border-neutral-800
        "
      >
        <div
          className="
            flex items-center
            justify-between gap-4
            px-4 py-4 sm:px-5
          "
        >
          <label
            htmlFor="available-stock"
            className="
              cursor-pointer text-sm
              font-semibold text-neutral-700
              dark:text-neutral-200
            "
          >
            In-stock products only
          </label>

          <Switch.Root
            id="available-stock"
            checked={available}
            onCheckedChange={handleAvailableChange}
            aria-label="Show in-stock products only"
            className="
              relative h-6 w-11 shrink-0
              cursor-pointer rounded-full
              bg-neutral-200
              transition-colors duration-200
              outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              data-[state=checked]:bg-red-500
              dark:bg-neutral-700
              dark:data-[state=checked]:bg-red-500
            "
          >
            <Switch.Thumb
              className="
                block h-4 w-4
                translate-x-[4px]
                rounded-full bg-white
                shadow-sm
                transition-transform duration-200
                data-[state=checked]:translate-x-[24px]
              "
            />
          </Switch.Root>
        </div>
      </div>
    </aside>
  );
}