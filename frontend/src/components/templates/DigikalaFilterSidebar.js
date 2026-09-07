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
    className="px-4 sm:px-5 py-1"
  >
    <Accordion.Header className="flex">
      <Accordion.Trigger
        className="
          flex
          w-full
          items-center
          justify-between
          py-4
          text-sm
          font-semibold
          text-neutral-700
          hover:text-neutral-900
          transition-colors
          group
          outline-none
        "
      >
        <span>{title}</span>

        <div className="text-neutral-400">
          {/* Mobile */}
          <span className="lg:hidden">
            <ChevronLeft size={20} />
          </span>

          {/* Desktop */}
          <span
            className="
              hidden
              lg:inline-block
              transition-transform
              duration-300
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
        data-[state=open]:animate-slideDown
        data-[state=closed]:animate-slideUp
        overflow-hidden
        text-sm
        text-neutral-600
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
    products,
    allBrandsFromApi,
    searchTerm,
  });

  /* =======================================================
     PRICE LIST
  ======================================================= */

  const prices = useMemo(() => {
    return products
      .flatMap((product) =>
        (product.variants || []).map((variant) =>
          Number(variant.final_price)
        )
      )
      .filter(
        (price) =>
          Number.isFinite(price) &&
          price >= 0
      );
  }, [products]);

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
        Math.min(
          parsedUrlMin,
          maxProductPrice
        )
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
        Math.max(
          parsedUrlMax,
          minProductPrice
        )
      );
    }

    return maxProductPrice;
  };

  /* =======================================================
     LOCAL SLIDER STATE

     Important:
     This state prevents the router from
     re-rendering while dragging.
  ======================================================= */

  const [priceRange, setPriceRange] = useState([
    getInitialMin(),
    getInitialMax(),
  ]);

  /* =======================================================
     SYNC URL -> SLIDER

     If the URL changes externally,
     the slider changes as well.
  ======================================================= */

  useEffect(() => {
    const min = getInitialMin();
    const max = getInitialMax();

    setPriceRange([
      min,
      max,
    ]);
  }, [
    urlMinPrice,
    urlMaxPrice,
    minProductPrice,
    maxProductPrice,
  ]);

  /* =======================================================
     PRICE CHANGE - LOCAL ONLY

     No router here.
     Therefore, the slider moves smoothly.
  ======================================================= */

  const handlePriceChange = (value) => {
    if (!Array.isArray(value)) return;

    const [min, max] = value;

    setPriceRange([
      Number(min),
      Number(max),
    ]);
  };

  /* =======================================================
     PRICE CHANGE COMPLETE

     When the user releases the handle,
     the URL changes.
  ======================================================= */

  const handlePriceChangeComplete = (value) => {
    if (!Array.isArray(value)) return;

    const [min, max] = value;

    const params = new URLSearchParams(
      searchParams.toString()
    );

    /* ---------------------------------------------
       MIN PRICE
    --------------------------------------------- */

    if (min <= minProductPrice) {
      params.delete("min_price");
    } else {
      params.set(
        "min_price",
        String(min)
      );
    }

    /* ---------------------------------------------
       MAX PRICE
    --------------------------------------------- */

    if (max >= maxProductPrice) {
      params.delete("max_price");
    } else {
      params.set(
        "max_price",
        String(max)
      );
    }

    const queryString = params.toString();

    router.replace(
      queryString
        ? `${pathname}?${queryString}`
        : pathname,
      {
        scroll: false,
      }
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
      {
        scroll: false,
      }
    );
  };

  /* =======================================================
     FORMAT PRICE
  ======================================================= */

  const formatPrice = (price) => {
    return Number(price).toLocaleString(
      "en-US",
      {
        maximumFractionDigits: 2,
      }
    );
  };

  /* =======================================================
     RENDER
  ======================================================= */

  return (
    <aside
      dir="ltr"
      className="
        w-full
        bg-white
        text-left
        font-sans

        border-0
        rounded-none

        sm:border
        sm:border-neutral-200
        sm:rounded-xl

        lg:sticky
        lg:top-5
        lg:self-start

        overflow-hidden
      "
    >
      {/* ===================================================
          HEADER
      =================================================== */}

      <div
        className="
          px-4
          sm:px-5
          py-4
          text-base
          font-bold
          text-neutral-800
          border-b
          border-neutral-100
        "
      >
        Filters
      </div>

      {/* ===================================================
          ACCORDION
      =================================================== */}

      <Accordion.Root
        type="multiple"
        className="
          w-full
          divide-y
          divide-neutral-100
        "
      >
        {/* =================================================
            ADDRESS
        ================================================= */}

        <FilterSection
          value="address"
          title="Express Delivery"
        >
          <div className="pb-4 space-y-3">
            <div
              className="
                w-full
                flex
                items-center
                gap-3
                bg-amber-50
                border
                border-amber-100
                rounded-lg
                p-3
              "
            >
              <LuMessageSquareWarning
                className="
                  w-6
                  h-6
                  text-amber-500
                  shrink-0
                "
              />

              <p
                className="
                  text-xs
                  text-neutral-600
                  leading-relaxed
                "
              >
                To view available products
                in a nearby warehouse, please
                specify your address.
              </p>
            </div>

            <button
              type="button"
              className="
                w-full
                py-2
                text-xs
                font-semibold
                text-red-500
                border
                border-red-500
                rounded-lg
                hover:bg-red-50
                transition-colors
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
            {/* SEARCH */}

            <div
              className="
                relative
                flex
                items-center
                mb-3
                bg-neutral-100
                rounded-lg
                px-3
                py-2
              "
            >
              <Search
                size={16}
                className="
                  text-neutral-400
                  ml-2
                  shrink-0
                "
              />

              <input
                type="text"
                placeholder="Search brands..."
                value={searchTerm}
                onChange={(e) =>
                  setSearchTerm(
                    e.target.value
                  )
                }
                className="
                  bg-transparent
                  text-xs
                  w-full
                  min-w-0
                  outline-none
                  text-neutral-700
                  font-sans
                "
              />
            </div>

            {/* BRANDS */}

            <div
              className="
                max-h-[220px]
                overflow-y-auto
                space-y-1
                scrollbar-thin
                scrollbar-thumb-neutral-200
              "
            >
              {isLoading ? (
                <p
                  className="
                    text-xs
                    text-neutral-400
                    text-center
                    py-2
                  "
                >
                  Loading...
                </p>
              ) : filteredBrands.length ? (
                filteredBrands.map(
                  (brand) => {
                    const brandId =
                      String(brand.id);

                    const checkboxId =
                      `brand-${brandId}`;

                    const isChecked =
                      selectedBrands.includes(
                        brandId
                      );

                    return (
                      <div
                        key={brandId}
                        onClick={() =>
                          handleCheckboxChange(
                            brandId
                          )
                        }
                        className="
                          w-full
                          flex
                          items-center
                          justify-start
                          cursor-pointer
                          hover:bg-neutral-50
                          rounded
                          px-2
                          transition-colors
                        "
                      >
                        <div
                          className="
                            ml-3
                            py-2
                            shrink-0
                          "
                          onClick={(e) =>
                            e.stopPropagation()
                          }
                        >
                          <Checkbox.Root
                            id={checkboxId}
                            checked={isChecked}
                            onCheckedChange={() =>
                              handleCheckboxChange(
                                brandId
                              )
                            }
                            className="
                              flex
                              h-4
                              w-4
                              shrink-0
                              items-center
                              justify-center
                              rounded
                              border
                              border-neutral-300
                              bg-white
                              data-[state=checked]:bg-red-500
                              data-[state=checked]:border-red-500
                              transition-colors
                              outline-none
                            "
                          >
                            <Checkbox.Indicator
                              className="text-white"
                            >
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
                            grow
                            min-w-0
                            flex
                            items-center
                            justify-between
                            gap-2
                            py-2
                            border-b
                            border-neutral-100
                            text-xs
                            font-semibold
                            text-neutral-700
                            cursor-pointer
                            select-none
                          "
                        >
                          <span className="truncate">
                            {brand.name}
                          </span>

                          <span
                            className="
                              hidden
                              sm:block
                              text-[10px]
                              text-neutral-400
                              font-normal
                              ltr
                              text-left
                              truncate
                              max-w-[40%]
                            "
                          >
                            {brand.slug}
                          </span>
                        </label>
                      </div>
                    );
                  }
                )
              ) : (
                <p
                  className="
                    text-xs
                    text-neutral-400
                    text-center
                    py-2
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
          <div
            className="
              pb-6
              pt-2
              px-1
              overflow-hidden
            "
          >
            {prices.length > 0 &&
            maxProductPrice > minProductPrice ? (
              <>
                {/* -----------------------------------------
                    SLIDER
                ----------------------------------------- */}

                <div
                  className="
                    px-3
                    sm:px-4
                    py-5
                  "
                >
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
                    reverse={true}
                    pushable={false}
                  />
                </div>

                {/* -----------------------------------------
                    SELECTED VALUES
                ----------------------------------------- */}

                <div
                  className="
                    grid
                    grid-cols-2
                    gap-2
                    mt-2
                  "
                >
                  {/* MIN */}

                  <div className="min-w-0">
                    <span
                      className="
                        block
                        text-[10px]
                        text-neutral-400
                        mb-1
                      "
                    >
                      Minimum Price
                    </span>

                    <div
                      className="
                        border
                        border-neutral-200
                        rounded-lg
                        px-2
                        sm:px-3
                        py-2
                        text-[11px]
                        sm:text-xs
                        text-neutral-700
                        text-center
                        bg-white
                        whitespace-nowrap
                        overflow-hidden
                      "
                    >
                      $
                      {formatPrice(
                        priceRange[0]
                      )}
                    </div>
                  </div>

                  {/* MAX */}

                  <div className="min-w-0">
                    <span
                      className="
                        block
                        text-[10px]
                        text-neutral-400
                        mb-1
                      "
                    >
                      Maximum Price
                    </span>

                    <div
                      className="
                        border
                        border-neutral-200
                        rounded-lg
                        px-2
                        sm:px-3
                        py-2
                        text-[11px]
                        sm:text-xs
                        text-neutral-700
                        text-center
                        bg-white
                        whitespace-nowrap
                        overflow-hidden
                      "
                    >
                      $
                      {formatPrice(
                        priceRange[1]
                      )}
                    </div>
                  </div>
                </div>

                {/* -----------------------------------------
                    FULL RANGE
                ----------------------------------------- */}

                <div
                  className="
                    flex
                    justify-between
                    gap-3
                    mt-3
                    px-1
                    text-[10px]
                    text-neutral-400
                    ltr
                  "
                >
                  <span>
                    $
                    {formatPrice(
                      minProductPrice
                    )}
                  </span>

                  <span>
                    $
                    {formatPrice(
                      maxProductPrice
                    )}
                  </span>
                </div>
              </>
            ) : prices.length === 1 ||
              minProductPrice ===
                maxProductPrice ? (
              <div
                className="
                  py-4
                  text-center
                "
              >
                <p
                  className="
                    text-xs
                    text-neutral-500
                  "
                >
                  Product Price
                </p>

                <p
                  className="
                    mt-2
                    text-sm
                    font-semibold
                    text-neutral-700
                    ltr
                  "
                >
                  $
                  {formatPrice(
                    minProductPrice
                  )}
                </p>
              </div>
            ) : (
              <p
                className="
                  text-xs
                  text-neutral-400
                  text-center
                  py-4
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
          border-t
          border-neutral-100
        "
      >
        <div
          className="
            px-4
            sm:px-5
            py-4
            flex
            items-center
            justify-between
            gap-4
          "
        >
          <label
            htmlFor="available-stock"
            className="
              text-sm
              font-semibold
              text-neutral-700
              cursor-pointer
            "
          >
            In-stock products only
          </label>

          <Switch.Root
            id="available-stock"
            checked={available}
            onCheckedChange={
              handleAvailableChange
            }
            className="
              w-11
              h-6
              shrink-0
              bg-neutral-200
              data-[state=checked]:bg-cyan-500
              rounded-full
              relative
              transition-colors
              duration-200
              cursor-pointer
              outline-none
            "
          >
            <Switch.Thumb
              className="
                block
                w-4
                h-4
                bg-white
                rounded-full
                shadow
                transition-transform
                duration-200
                translate-x-[4px]
                data-[state=checked]:-translate-x-[24px]
              "
            />
          </Switch.Root>
        </div>
      </div>
    </aside>
  );
}