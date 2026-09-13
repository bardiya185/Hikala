"use client";

import {
  FaStar,
  FaStarHalfAlt,
  FaRegStar,
  FaFire,
} from "react-icons/fa";
import { useState, useEffect } from "react";
import Image from "next/image";
import { BsSortDownAlt } from "react-icons/bs";
import {
  useGetCommentProduct,
  useGetUserData,
} from "@/core/services/queries";
import {
  AiOutlineLike,
  AiTwotoneDislike,
} from "react-icons/ai";
import { Trash2, Minus, Plus } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";
import { VscCopilotSuccess } from "react-icons/vsc";
import ProductReviewModal from "../ProductReviewModal";
import { useRouter } from "next/navigation";
import { TbBrandSpeedtest } from "react-icons/tb";
import { IoWarningOutline } from "react-icons/io5";

function ProductOverview({ data }) {
  return (
    <div
      id="description-section"
      className="w-full scroll-mt-20"
    >
      <div className="flex flex-col px-4 sm:px-5">
        <p className="mt-8 text-base font-semibold text-neutral-900 sm:mt-12 dark:text-neutral-100">
          Introduction
        </p>

        <p className="mt-3 w-full text-sm leading-7 text-neutral-700 sm:text-base lg:w-9/12 dark:text-neutral-300">
          {data?.description || "No description available."}
        </p>
      </div>
    </div>
  );
}

function ProductSpecifications({ data }) {
  const attr = data?.variants?.[0]?.attributes || [];

  return (
    <div
      id="specifications-section"
      className="w-full scroll-mt-20 px-4 sm:px-5 lg:w-9/12"
    >
      <div className="flex flex-col">
        <p className="mt-8 text-base font-semibold text-neutral-900 sm:mt-5 dark:text-neutral-100">
          Specifications
        </p>

        <div className="mt-2 w-[70px] border-2 border-red-500" />

        <h2 className="mt-5 text-sm font-semibold text-neutral-900 sm:text-base dark:text-neutral-100">
          General Specifications
        </h2>
      </div>

      {attr.length > 0 ? (
        <div className="mt-4 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-800">
          <div className="grid grid-cols-1 sm:grid-cols-2">
            <div className="flex flex-col">
              {attr.map((item, index) => (
                <div
                  key={`name-${item?.id ?? index}`}
                  className="
                    flex
                    min-h-[50px]
                    items-center
                    border-b
                    border-neutral-200
                    bg-neutral-50
                    px-3
                    text-xs
                    text-neutral-500
                    last:border-b-0
                    sm:px-5
                    lg:px-8
                    sm:text-sm

                    dark:border-neutral-800
                    dark:bg-neutral-900
                    dark:text-neutral-400
                  "
                >
                  {item?.attribute_name}
                </div>
              ))}
            </div>

            <div className="flex flex-col">
              {attr.map((item, index) => (
                <div
                  key={`value-${item?.id ?? index}`}
                  className="
                    flex
                    min-h-[50px]
                    items-center
                    border-b
                    border-neutral-200
                    px-3
                    text-xs
                    text-neutral-800
                    last:border-b-0
                    sm:px-5
                    sm:text-sm

                    dark:border-neutral-800
                    dark:text-neutral-200
                  "
                >
                  {item?.value}
                </div>
              ))}
            </div>
          </div>
        </div>
      ) : (
        <div className="mt-4 rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-5 text-sm text-neutral-500 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400">
          No specifications available.
        </div>
      )}
    </div>
  );
}

function InDepthReview() {
  return (
    <div
      id="In-depth-Review-section"
      className="mt-10 w-full scroll-mt-20"
    >
      <h2 className="px-4 text-base font-semibold text-neutral-900 sm:px-5 sm:text-lg dark:text-neutral-100">
        In-depth Review
      </h2>

      <div className="ml-4 mt-2 w-[70px] border-2 border-red-500 sm:ml-5" />

      <div className="mt-6 px-4 sm:mt-8 sm:px-5 lg:pl-10 xl:pl-20">
        <div className="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-800">
          <Image
            src="/icons/b.webp"
            width={1200}
            height={1300}
            alt="Product review"
            className="
              h-auto
              max-h-[700px]
              w-full
              max-w-[850px]
              object-cover
            "
          />
        </div>
      </div>
    </div>
  );
}

function RatingStars({ rating = 0, maxStars = 5 }) {
  return (
    <div
      className="flex items-center gap-1"
      aria-label={`${rating} out of ${maxStars} stars`}
    >
      {Array.from({ length: maxStars }, (_, index) => {
        const starValue = index + 1;

        if (rating >= starValue) {
          return (
            <FaStar
              key={index}
              className="h-4 w-4 text-amber-400"
            />
          );
        }

        if (rating >= starValue - 0.5) {
          return (
            <FaStarHalfAlt
              key={index}
              className="h-4 w-4 text-amber-400"
            />
          );
        }

        return (
          <FaRegStar
            key={index}
            className="h-4 w-4 text-neutral-300 dark:text-neutral-700"
          />
        );
      })}

      <span className="mr-1 text-xs font-bold text-neutral-600 dark:text-neutral-300">
        {rating}
      </span>
    </div>
  );
}

function SubmitComment({ id, data }) {
  const [isReviewModalOpen, setIsReviewModalOpen] =
    useState(false);

  const {
    data: userData,
    isLoading: isUserLoading,
  } = useGetUserData();

  const router = useRouter();

  const handleSubmitComment = () => {
    if (isUserLoading) return;

    if (!userData) {
      router.push(
        `/auth/login/sendOtp?redirect=${encodeURIComponent(
          window.location.pathname
        )}`
      );

      return;
    }

    setIsReviewModalOpen(true);
  };

  return (
    <div
      id="reviews-section"
      className="max-w-[1270px] scroll-mt-20 px-4 sm:px-5"
    >
      <div className="flex flex-col gap-8 lg:flex-row lg:items-start lg:gap-12">
        {/* Rating */}
        <div className="w-full lg:sticky lg:top-24 lg:w-[240px] lg:shrink-0">
          <div className="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-800 dark:bg-neutral-900">
            <p className="text-sm font-medium text-neutral-800 sm:text-base dark:text-neutral-100">
              5 out of 5
            </p>

            <div className="mt-2 flex items-center gap-1">
              <FaStar className="h-5 w-5 text-amber-400" />
              <FaStar className="h-5 w-5 text-amber-400" />
              <FaStar className="h-5 w-5 text-amber-400" />
              <FaStar className="h-5 w-5 text-amber-400" />
              <FaStar className="h-5 w-5 text-amber-400" />
            </div>

            <button
              type="button"
              onClick={handleSubmitComment}
              disabled={isUserLoading}
              className="
                mt-5
                flex
                h-[42px]
                w-full
                items-center
                justify-center
                rounded-lg
                border
                border-red-500
                text-sm
                font-medium
                text-red-600
                transition-all
                duration-200
                hover:bg-red-50
                hover:border-red-600
                active:scale-[0.99]
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                disabled:cursor-not-allowed
                disabled:opacity-50

                dark:border-red-500
                dark:text-red-400
                dark:hover:bg-red-950/40
              "
            >
              {isUserLoading
                ? "Checking..."
                : "Submit a comment"}
            </button>
          </div>
        </div>

        {/* Reviews */}
        <div className="w-full min-w-0">
          <Reviews id={id} />
        </div>
      </div>

      <ProductReviewModal
        isOpen={isReviewModalOpen}
        onClose={() => setIsReviewModalOpen(false)}
        product={data}
      />
    </div>
  );
}

function Reviews({ id }) {
  const { data: comments } = useGetCommentProduct(id);

  return (
    <div
      className="w-full"
      id="viewpoint-section"
    >
      {/* Sort */}
      <div
        className="
          mt-5
          flex
          items-center
          gap-3
          overflow-x-auto
          whitespace-nowrap
          pb-2
          scrollbar-hide
          sm:gap-5
        "
      >
        <BsSortDownAlt className="h-4 w-4 shrink-0 text-neutral-500 dark:text-neutral-400" />

        {["Latest", "Customer Reviews", "Most useful"].map(
          (label) => (
            <button
              key={label}
              type="button"
              className="
                shrink-0
                text-xs
                text-neutral-600
                transition-colors
                hover:text-neutral-900
                focus:outline-none
                focus-visible:text-red-600
                sm:text-sm

                dark:text-neutral-400
                dark:hover:text-neutral-100
              "
            >
              {label}
            </button>
          )
        )}
      </div>

      {/* User Info */}
      <div
        className="
          mt-5
          flex
          w-full
          max-w-[750px]
          flex-col
          gap-2
          sm:flex-row
          sm:items-center
          sm:justify-between
        "
      >
        <div className="flex items-center gap-2">
          <p className="text-sm font-medium text-neutral-800 dark:text-neutral-200">
            Digikala user
          </p>

          <span
            className="
              rounded-md
              bg-green-500/10
              px-2
              py-1
              text-center
              text-[10px]
              font-medium
              text-green-600

              dark:bg-green-500/10
              dark:text-green-400
            "
          >
            Buyer
          </span>
        </div>

        <div className="text-xs text-neutral-400 dark:text-neutral-500">
          14 مرداد
        </div>
      </div>

      {/* Comment */}
      <div className="w-full max-w-[750px]">
        <p
          className="
            mt-4
            text-xs
            leading-6
            text-neutral-700
            sm:text-[13px]

            dark:text-neutral-300
          "
        >
          {comments?.data?.body || (
            <>
              My phone arrived promptly on the scheduled date. I
              was quite worried it might have issues—I&apos;d never
              bought a phone online before—but before opening the
              box, I checked the serial number on Apple&apos;s
              website. Once I was reassured that it hadn&apos;t been
              previously opened or activated, I unboxed and turned
              it on. Since it&apos;s a dual-SIM model, I verified
              both the serial number and IMEI against the details
              shown on the phone itself.
            </>
          )}
        </p>
      </div>

      {/* Like / Dislike */}
      <div className="mt-4 flex items-center gap-2">
        <button
          type="button"
          aria-label="Like review"
          className="
            flex
            h-9
            w-9
            items-center
            justify-center
            rounded-full
            text-neutral-400
            transition-colors
            hover:bg-neutral-100
            hover:text-neutral-700
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500

            dark:hover:bg-neutral-800
            dark:hover:text-neutral-200
          "
        >
          <AiOutlineLike className="h-5 w-5" />
        </button>

        <button
          type="button"
          aria-label="Dislike review"
          className="
            flex
            h-9
            w-9
            items-center
            justify-center
            rounded-full
            text-neutral-400
            transition-colors
            hover:bg-neutral-100
            hover:text-neutral-700
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500

            dark:hover:bg-neutral-800
            dark:hover:text-neutral-200
          "
        >
          <AiTwotoneDislike className="h-5 w-5" />
        </button>
      </div>
    </div>
  );
}

function SellerCard({
  selectedVariant,
  cartItem,
  handleAddToCarts,
  handleDeacrease,
  handleIncrease,
  up,
  isPending,
}) {
  const stock = Number(selectedVariant?.stock || 0);

  const maxOrderQuantity = Number(
    selectedVariant?.max_order_quantity || 0
  );

  const finalPrice = Number(
    selectedVariant?.final_price || 0
  );

  const basePrice = Number(
    selectedVariant?.base_price || 0
  );

  const discountPercent = Number(
    selectedVariant?.discount_percent || 0
  );

  const cartQuantity = Number(cartItem?.quantity || 0);

  const hasDiscount =
    discountPercent > 0 &&
    basePrice > 0 &&
    finalPrice > 0 &&
    finalPrice < basePrice;

  const isOutOfStock =
    !selectedVariant || stock <= 0;

  const reachedStockLimit =
    cartQuantity >= stock;

  const reachedOrderLimit =
    maxOrderQuantity > 0 &&
    cartQuantity >= maxOrderQuantity;

  const disableIncrease =
    up ||
    !selectedVariant ||
    isOutOfStock ||
    reachedStockLimit ||
    reachedOrderLimit;

  return (
    <aside
      className="
        w-full
        rounded-2xl
        border
        border-neutral-200
        bg-white
        p-4
        shadow-sm
        sm:p-5

        lg:sticky
        lg:top-24

        dark:border-neutral-800
        dark:bg-neutral-900
        dark:shadow-black/20
      "
    >
      {/* Seller Header */}
      <div className="flex items-center justify-between gap-3">
        <div>
          <h3 className="text-sm font-semibold text-neutral-900 sm:text-base dark:text-neutral-100">
            Seller
          </h3>

          <p className="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
            Official seller
          </p>
        </div>

        <div className="flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400">
          <VscCopilotSuccess className="h-4 w-4" />
          <span>Trusted</span>
        </div>
      </div>

      {/* Features */}
      <div className="mt-4 grid grid-cols-1 gap-3 border-y border-neutral-100 py-4 sm:grid-cols-2 dark:border-neutral-800">
        <div className="flex items-center gap-2">
          <TbBrandSpeedtest className="h-5 w-5 shrink-0 text-neutral-600 dark:text-neutral-400" />

          <span className="text-xs text-neutral-600 sm:text-sm dark:text-neutral-300">
            Fast delivery
          </span>
        </div>

        <div className="flex items-center gap-2">
          <VscCopilotSuccess className="h-5 w-5 shrink-0 text-green-600 dark:text-green-400" />

          <span className="text-xs text-neutral-600 sm:text-sm dark:text-neutral-300">
            Product warranty
          </span>
        </div>
      </div>

      {/* Price */}
      <div className="mt-5">
        {hasDiscount && (
          <div className="flex items-center gap-2">
            <span className="min-w-[38px] rounded-md bg-red-600 px-2 py-1 text-center text-xs font-bold text-white">
              {discountPercent}%
            </span>

            <del className="text-xs text-neutral-400 sm:text-sm dark:text-neutral-500">
              {basePrice.toLocaleString()} $
            </del>
          </div>
        )}

        <div className="mt-2 flex items-center gap-2">
          <span className="text-xl font-bold text-neutral-900 sm:text-2xl dark:text-neutral-100">
            {finalPrice > 0
              ? finalPrice.toLocaleString()
              : "0"}{" "}
            $
          </span>
        </div>
      </div>

      {/* Stock */}
      {stock > 0 ? (
        <div className="mt-3 flex items-center gap-2">
          <FaFire className="h-5 w-5 shrink-0 text-orange-500 dark:text-orange-400" />

          <span className="text-xs font-medium text-orange-500 sm:text-sm dark:text-orange-400">
            {stock} items left in stock.
          </span>
        </div>
      ) : (
        <div className="mt-3 flex items-center gap-2">
          <IoWarningOutline className="h-5 w-5 shrink-0 text-red-500" />

          <span className="text-xs font-medium text-red-500 sm:text-sm dark:text-red-400">
            Out of stock
          </span>
        </div>
      )}

      {/* Cart Controls */}
      {cartItem ? (
        <div className="mt-5">
          <div className="flex h-12 w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-2 dark:border-neutral-700 dark:bg-neutral-950">
            <button
              type="button"
              onClick={handleDeacrease}
              disabled={up}
              aria-label={
                cartQuantity === 1
                  ? "Remove from cart"
                  : "Decrease quantity"
              }
              className="
                flex
                h-9
                w-9
                items-center
                justify-center
                rounded-lg
                text-neutral-700
                transition-colors
                hover:bg-neutral-100
                disabled:cursor-not-allowed
                disabled:opacity-50
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500

                dark:text-neutral-300
                dark:hover:bg-neutral-800
              "
            >
              {cartQuantity === 1 ? (
                <Trash2 className="h-4 w-4" />
              ) : (
                <Minus className="h-4 w-4" />
              )}
            </button>

            <div className="flex min-w-[50px] items-center justify-center">
              {up ? (
                <RotatingLines
                  visible
                  height="20"
                  width="20"
                  strokeWidth="5"
                  animationDuration="0.75"
                  ariaLabel="Loading"
                />
              ) : (
                <span className="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                  {cartQuantity}
                </span>
              )}
            </div>

            <button
              type="button"
              onClick={handleIncrease}
              disabled={disableIncrease}
              aria-label="Increase quantity"
              className="
                flex
                h-9
                w-9
                items-center
                justify-center
                rounded-lg
                text-neutral-700
                transition-colors
                hover:bg-neutral-100
                disabled:cursor-not-allowed
                disabled:opacity-40
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500

                dark:text-neutral-300
                dark:hover:bg-neutral-800
              "
            >
              <Plus className="h-4 w-4" />
            </button>
          </div>

          {(reachedStockLimit || reachedOrderLimit) && (
            <p className="mt-2 text-center text-xs text-orange-500 dark:text-orange-400">
              {reachedOrderLimit
                ? `Maximum ${maxOrderQuantity} items allowed.`
                : "Maximum available quantity reached."}
            </p>
          )}
        </div>
      ) : (
        <div className="mt-5">
          <button
            type="button"
            onClick={handleAddToCarts}
            disabled={
              isPending ||
              !selectedVariant ||
              stock <= 0
            }
            className="
              flex
              h-12
              w-full
              items-center
              justify-center
              rounded-xl
              bg-red-600
              px-4
              text-sm
              font-semibold
              text-white
              shadow-sm
              shadow-red-600/10
              transition-all
              hover:bg-red-700
              active:scale-[0.99]
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              disabled:cursor-not-allowed
              disabled:bg-neutral-300
              disabled:text-neutral-500

              dark:bg-red-600
              dark:hover:bg-red-500
              dark:focus-visible:ring-offset-neutral-900
              dark:disabled:bg-neutral-800
              dark:disabled:text-neutral-500
            "
          >
            {isPending ? (
              <span className="flex items-center gap-2">
                <RotatingLines
                  visible
                  height="20"
                  width="20"
                  strokeWidth="5"
                  animationDuration="0.75"
                  ariaLabel="Loading"
                />
                <span>Adding...</span>
              </span>
            ) : stock <= 0 ? (
              "Out of Stock"
            ) : (
              "Add to Basket"
            )}
          </button>
        </div>
      )}

      {/* Shipping */}
      <div className="mt-5 space-y-3 border-t border-neutral-100 pt-4 dark:border-neutral-800">
        <div className="flex items-center gap-2">
          <TbBrandSpeedtest className="h-5 w-5 shrink-0 text-neutral-600 dark:text-neutral-400" />

          <span className="text-xs text-neutral-600 sm:text-sm dark:text-neutral-300">
            Fast delivery available
          </span>
        </div>

        <div className="flex items-center gap-2">
          <VscCopilotSuccess className="h-5 w-5 shrink-0 text-green-600 dark:text-green-400" />

          <span className="text-xs text-neutral-600 sm:text-sm dark:text-neutral-300">
            Safe and secure purchase
          </span>
        </div>
      </div>
    </aside>
  );
}

export default function ProductMoreDetails({
  data,
  selectedVariant,
  maxlength = 150,
  cartItem,
  handleAddToCarts,
  handleDeacrease,
  handleIncrease,
  up,
  isPending,
}) {
  const TABS = [
    {
      id: "Introduction",
      label: "Introduction",
      targetId: "description-section",
    },
    {
      id: "In-depth Review",
      label: "In-depth Review",
      targetId: "In-depth-Review-section",
    },
    {
      id: "specifications",
      label: "Specifications",
      targetId: "specifications-section",
    },
    {
      id: "reviews",
      label: "Reviews",
      targetId: "reviews-section",
    },
    {
      id: "Viewpoint",
      label: "Viewpoint",
      targetId: "viewpoint-section",
    },
  ];

  const [activeTab, setActiveTab] =
    useState("Introduction");

  const [showAllSpecs, setShowAllSpecs] =
    useState(false);

  useEffect(() => {
    const handleScroll = () => {
      const sections = TABS.map((tab) => ({
        id: tab.id,
        element: document.getElementById(tab.targetId),
      })).filter((item) => item.element);

      if (!sections.length) return;

      const currentSection = sections
        .filter(({ element }) => {
          const rect = element.getBoundingClientRect();
          return rect.top <= 140;
        })
        .sort((a, b) => {
          const aTop = Math.abs(
            a.element.getBoundingClientRect().top - 100
          );

          const bTop = Math.abs(
            b.element.getBoundingClientRect().top - 100
          );

          return aTop - bTop;
        })[0];

      if (currentSection) {
        setActiveTab(currentSection.id);
      }
    };

    window.addEventListener("scroll", handleScroll, {
      passive: true,
    });

    handleScroll();

    return () =>
      window.removeEventListener("scroll", handleScroll);
  }, []);

  const handleTabClick = (tab) => {
    const element = document.getElementById(
      tab.targetId
    );

    if (!element) return;

    setActiveTab(tab.id);

    element.scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  };

  const allAttributes =
    selectedVariant?.attributes || [];

  const visibleAttributes = showAllSpecs
    ? allAttributes
    : allAttributes.slice(0, 4);

  const hasMoreSpecs = allAttributes.length > 4;

  return (
    <div className="mx-auto w-full max-w-[1440px]">
      <section
        className="relative"
        id="product-section"
      >
        {/* Tabs */}
        <div
          className="
            sticky
            top-0
            z-40
            flex
            w-full
            gap-1
            overflow-x-auto
            whitespace-nowrap
            border-b
            border-neutral-200
            bg-white/95
            backdrop-blur-md
            scrollbar-hide
            sm:gap-2

            dark:border-neutral-800
            dark:bg-neutral-950/95
          "
        >
          {TABS.map((tab) => (
            <button
              key={tab.id}
              type="button"
              onClick={() => handleTabClick(tab)}
              className={`
                shrink-0
                border-b-2
                px-3
                py-3
                text-xs
                font-medium
                transition-all
                duration-200
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                sm:px-4
                sm:text-sm

                ${
                  activeTab === tab.id
                    ? `
                      border-red-600
                      text-neutral-900
                      dark:text-neutral-100
                    `
                    : `
                      border-transparent
                      text-neutral-500
                      hover:text-neutral-800
                      dark:text-neutral-500
                      dark:hover:text-neutral-200
                    `
                }
              `}
            >
              {tab.label}
            </button>
          ))}
        </div>

        {/* Main Grid */}
        <div
          className="
            mt-5
            grid
            grid-cols-1
            items-start
            gap-6
            sm:mt-6
            lg:grid-cols-[minmax(0,1fr)_360px]
            lg:gap-8
          "
        >
          {/* Content */}
          <div className="min-w-0 w-full">
            <ProductOverview data={data} />

            <InDepthReview />

            <ProductSpecifications data={data} />

            {hasMoreSpecs && (
              <div className="mt-4 px-4 sm:px-5 lg:w-9/12">
                <button
                  type="button"
                  onClick={() =>
                    setShowAllSpecs((prev) => !prev)
                  }
                  className="
                    text-sm
                    font-medium
                    text-red-600
                    transition-colors
                    hover:text-red-700
                    focus:outline-none
                    focus-visible:underline

                    dark:text-red-400
                    dark:hover:text-red-300
                  "
                >
                  {showAllSpecs
                    ? "Show less"
                    : "Show all specifications"}
                </button>
              </div>
            )}

            <div
              className="
                mx-4
                my-8
                border-t
                border-neutral-200
                sm:mx-5

                dark:border-neutral-800
              "
            />

            <SubmitComment
              id={data?.id}
              data={data}
            />
          </div>

          {/* Seller */}
          <SellerCard
            data={data}
            selectedVariant={selectedVariant}
            cartItem={cartItem}
            handleAddToCarts={handleAddToCarts}
            handleDeacrease={handleDeacrease}
            handleIncrease={handleIncrease}
            up={up}
            isPending={isPending}
          />
        </div>
      </section>
    </div>
  );
}