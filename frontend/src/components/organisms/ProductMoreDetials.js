"use client";

import { FaStar, FaStarHalfAlt, FaRegStar, FaFire } from "react-icons/fa";
import { useState, useEffect } from "react";
import Image from "next/image";
import { BsSortDownAlt } from "react-icons/bs";
import { useGetCommentProduct, useGetUserData } from "@/core/services/queries";
import { AiOutlineLike, AiTwotoneDislike } from "react-icons/ai";
import { Trash2, Minus, Plus } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";
import { VscCopilotSuccess } from "react-icons/vsc";
import ProductReviewModal from "../ProductReviewModal";
import { useRouter } from "next/navigation";
import { TbBrandSpeedtest } from "react-icons/tb";
import { IoWarningOutline } from "react-icons/io5";

function ProductOverview({ data }) {
  return (
    <div id="description-section" className="w-full scroll-mt-16">
      <div className="flex flex-col px-4 sm:px-5">
        <p className="mt-8 sm:mt-12 text-base font-semibold">Introduction</p>

        <p className="w-full lg:w-9/12 mt-3 text-sm sm:text-base leading-7 text-neutral-700">
          {data?.description}
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
      className="w-full lg:w-9/12 px-4 sm:px-5 scroll-mt-16"
    >
      <div className="flex flex-col">
        <p className="mt-8 sm:mt-5 text-base font-semibold">
          Specifications
        </p>

        <div className="w-[70px] mt-2 border-2 border-red-500" />

        <h1 className="mt-5 text-sm sm:text-base font-semibold">
          General Specifications
        </h1>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 mt-4 overflow-hidden rounded-lg border border-neutral-200">
        {/* Names Column */}
        <div className="flex flex-col">
          {attr.map((item) => (
            <div
              key={`name-${item?.id}`}
              className="
                min-h-[50px]
                flex
                items-center
                px-3
                sm:px-5
                lg:px-8
                text-xs
                sm:text-sm
                text-neutral-400
                bg-neutral-50
                border-b
                border-neutral-200
                last:border-b-0
              "
            >
              {item?.attribute_name}
            </div>
          ))}
        </div>

        {/* Values Column */}
        <div className="flex flex-col">
          {attr.map((item) => (
            <div
              key={`value-${item?.id}`}
              className="
                min-h-[50px]
                flex
                items-center
                px-3
                sm:px-5
                text-xs
                sm:text-sm
                text-neutral-800
                border-b
                border-neutral-200
                last:border-b-0
              "
            >
              {item?.value}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

function InDepthReview() {
  return (
    <div
      id="In-depth-Review-section"
      className="w-full mt-10 scroll-mt-16"
    >
      <h1 className="px-4 sm:px-5 text-base sm:text-lg font-semibold">
        In-depth Review
      </h1>

      <div className="w-[70px] mt-2 ml-4 sm:ml-5 border-2 border-red-500" />

      <div className="mt-6 sm:mt-8 px-4 sm:px-5 lg:pl-10 xl:pl-20">
        <Image
          src="/icons/b.webp"
          width={1200}
          height={1300}
          alt="product review"
          className="
            w-full
            max-w-[850px]
            h-auto
            max-h-[700px]
            object-cover
            rounded-lg
          "
        />
      </div>
    </div>
  );
}

function RatingStars({ rating = 0, maxStars = 5 }) {
  return (
    <div className="flex items-center gap-1 text-amber-400">
      {Array.from({ length: maxStars }, (_, index) => {
        const starValue = index + 1;

        if (rating >= starValue) {
          return <FaStar key={index} className="w-4 h-4" />;
        }

        if (rating >= starValue - 0.5) {
          return <FaStarHalfAlt key={index} className="w-4 h-4" />;
        }

        return (
          <FaRegStar
            key={index}
            className="w-4 h-4 text-gray-300"
          />
        );
      })}

      <span className="text-xs font-bold text-gray-600 mr-1">
        {rating}
      </span>
    </div>
  );
}

function SubmitComment({ id, data }) {
  const [isReviewModalOpen, setIsReviewModalOpen] = useState(false);

  const { data: userData, isLoading: isUserLoading } = useGetUserData();

  const router = useRouter();

  const handleSubmitComment = () => {
    // هنوز وضعیت کاربر مشخص نشده
    if (isUserLoading) return;

    // کاربر لاگین نیست
    if (!userData) {
      router.push(
        `/auth/login/sendOtp?redirect=${encodeURIComponent(
          window.location.pathname
        )}`
      );

      return;
    }

    // کاربر لاگین است
    setIsReviewModalOpen(true);
  };

  return (
    <div
      id="reviews-section"
      className="px-4 sm:px-5 max-w-[1270px] scroll-mt-16"
    >
      <div
        className="
          flex
          flex-col
          lg:flex-row
          lg:items-start
          gap-8
          lg:gap-12
        "
      >
        {/* LEFT SIDE - Rating */}
        <div
          className="
            w-full
            lg:w-[240px]
            lg:shrink-0
            lg:sticky
            lg:top-24
          "
        >
          <div className="flex flex-col items-start">
            <p className="font-medium text-sm sm:text-base text-neutral-800">
              5 out of 5
            </p>

            <div
              className="flex items-center gap-1 mt-2 text-yellow-400"
              aria-label="5 out of 5 stars"
            >
              <FaStar className="w-5 h-5" />
              <FaStar className="w-5 h-5" />
              <FaStar className="w-5 h-5" />
              <FaStar className="w-5 h-5" />
              <FaStar className="w-5 h-5" />
            </div>

            <button
              type="button"
              onClick={handleSubmitComment}
              disabled={isUserLoading}
              className="
                w-full
                max-w-[240px]
                h-[41px]
                mt-5
                border
                border-red-500
                rounded-lg
                text-red-600
                text-sm
                text-center
                transition-all
                duration-200
                hover:bg-red-50
                hover:border-red-600
                active:scale-[0.99]
                disabled:cursor-not-allowed
                disabled:opacity-50
              "
            >
              {isUserLoading ? "Checking..." : "Submit a comment"}
            </button>
          </div>
        </div>

        {/* RIGHT SIDE - Reviews */}
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
    <div className="w-full" id="viewpoint-section">
      <div className="w-full">
        {/* Sort buttons */}
        <div
          className="
            flex
            items-center
            gap-3
            sm:gap-5
            mt-5
            overflow-x-auto
            whitespace-nowrap
            pb-2
            scrollbar-hide
          "
        >
          <BsSortDownAlt className="shrink-0 w-4 h-4 text-neutral-500" />

          <button
            type="button"
            className="
              shrink-0
              text-xs
              sm:text-sm
              text-neutral-600
              hover:text-neutral-900
              transition-colors
            "
          >
            Latest
          </button>

          <button
            type="button"
            className="
              shrink-0
              text-xs
              sm:text-sm
              text-neutral-600
              hover:text-neutral-900
              transition-colors
            "
          >
            Customer Reviews
          </button>

          <button
            type="button"
            className="
              shrink-0
              text-xs
              sm:text-sm
              text-neutral-600
              hover:text-neutral-900
              transition-colors
            "
          >
            Most useful
          </button>
        </div>

        {/* User info */}
        <div
          className="
            w-full
            flex
            flex-col
            sm:flex-row
            sm:items-center
            sm:justify-between
            gap-2
            mt-5
            max-w-[750px]
          "
        >
          <div className="flex items-center gap-2">
            <p className="text-sm font-medium">Digikala user</p>

            <span
              className="
                px-2
                py-1
                text-[10px]
                text-center
                bg-green-400/20
                text-green-600
                rounded-md
              "
            >
              Buyer
            </span>
          </div>

          <div className="text-xs text-neutral-400">
            14 مرداد
          </div>
        </div>
      </div>

      {/* Comment body */}
      <div className="w-full max-w-[750px]">
        <p
          className="
            mt-4
            text-xs
            sm:text-[13px]
            leading-6
            text-neutral-700
          "
        >
          {comments?.data?.body}

          {!comments?.data?.body && (
            <>
              My phone arrived promptly on the scheduled date. I was
              quite worried it might have issues—I&apos;d never bought
              a phone online before—but before opening the box, I
              checked the serial number on Apple&apos;s website. Once I
              was reassured that it hadn&apos;t been previously opened
              or activated, I unboxed and turned it on. Since it&apos;s
              a dual-SIM model, I verified both the serial number and
              IMEI against the details shown on the phone itself.
            </>
          )}
        </p>
      </div>

      {/* Like/Dislike */}
      <div className="flex items-center gap-4 mt-4">
        <button
          type="button"
          aria-label="Like review"
          className="
            flex
            items-center
            justify-center
            w-8
            h-8
            rounded-full
            text-neutral-400
            hover:bg-neutral-100
            hover:text-neutral-700
            transition-colors
          "
        >
          <AiOutlineLike className="w-5 h-5" />
        </button>

        <button
          type="button"
          aria-label="Dislike review"
          className="
            flex
            items-center
            justify-center
            w-8
            h-8
            rounded-full
            text-neutral-400
            hover:bg-neutral-100
            hover:text-neutral-700
            transition-colors
          "
        >
          <AiTwotoneDislike className="w-5 h-5" />
        </button>
      </div>
    </div>
  );
}

function SellerCard({
  data,
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
    selectedVariant?.max_order_quantity || 0,
  );

  const finalPrice = Number(
    selectedVariant?.final_price || 0,
  );

  const basePrice = Number(
    selectedVariant?.base_price || 0,
  );

  const discountPercent = Number(
    selectedVariant?.discount_percent || 0,
  );

  const cartQuantity = Number(cartItem?.quantity || 0);

  const hasDiscount =
    discountPercent > 0 &&
    basePrice > 0 &&
    finalPrice > 0 &&
    finalPrice < basePrice;

  const isOutOfStock = !selectedVariant || stock <= 0;

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
    <div className="w-full rounded-xl border border-neutral-200 bg-white p-4 sm:p-5">
      {/* Seller Header */}
      <div className="flex items-center justify-between gap-3">
        <div>
          <h3 className="text-sm font-semibold text-neutral-900 sm:text-base">
            Seller
          </h3>

          <p className="mt-1 text-xs text-neutral-500">
            Official seller
          </p>
        </div>

        <div className="flex items-center gap-1 text-xs text-green-600">
          <VscCopilotSuccess className="h-4 w-4" />
          <span>Trusted</span>
        </div>
      </div>

      {/* Seller Features */}
      <div className="mt-4 grid grid-cols-1 gap-3 border-y border-neutral-100 py-4 sm:grid-cols-2">
        <div className="flex items-center gap-2">
          <TbBrandSpeedtest className="h-5 w-5 shrink-0 text-neutral-600" />

          <span className="text-xs text-neutral-600 sm:text-sm">
            Fast delivery
          </span>
        </div>

        <div className="flex items-center gap-2">
          <VscCopilotSuccess className="h-5 w-5 shrink-0 text-green-600" />

          <span className="text-xs text-neutral-600 sm:text-sm">
            Product warranty
          </span>
        </div>
      </div>

      {/* Price */}
      <div className="mt-5">
        {hasDiscount && (
          <div className="flex items-center gap-2">
            <span className="min-w-[36px] rounded-md bg-red-600 px-2 py-1 text-center text-xs font-medium text-white">
              {discountPercent}%
            </span>

            <del className="text-xs text-neutral-400 sm:text-sm">
              {basePrice.toLocaleString()} $
            </del>
          </div>
        )}

        <div className="mt-2 flex items-center gap-2">
          <span className="text-xl font-bold text-neutral-900 sm:text-2xl">
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
          <FaFire className="h-5 w-5 shrink-0 text-orange-600" />

          <span className="text-xs font-medium text-orange-500 sm:text-sm">
            {stock} items left in stock.
          </span>
        </div>
      ) : (
        <div className="mt-3 flex items-center gap-2">
          <IoWarningOutline className="h-5 w-5 shrink-0 text-red-500" />

          <span className="text-xs font-medium text-red-500 sm:text-sm">
            Out of stock
          </span>
        </div>
      )}

      {/* Cart Controls */}
      {cartItem ? (
        <div className="mt-5">
          <div className="flex h-12 w-full items-center justify-between rounded-lg border border-neutral-200 bg-white px-2">
            {/* Decrease / Remove */}
            <button
              type="button"
              onClick={handleDeacrease}
              disabled={up}
              aria-label={
                cartQuantity === 1
                  ? "Remove from cart"
                  : "Decrease quantity"
              }
              className="flex h-9 w-9 items-center justify-center rounded-md text-neutral-700 transition-colors hover:bg-neutral-100 disabled:cursor-not-allowed disabled:opacity-50"
            >
              {cartQuantity === 1 ? (
                <Trash2 className="h-4 w-4" />
              ) : (
                <Minus className="h-4 w-4" />
              )}
            </button>

            {/* Quantity */}
            <div className="flex min-w-[50px] items-center justify-center">
              {up ? (
                <RotatingLines
                  visible={true}
                  height="20"
                  width="20"
                  strokeWidth="5"
                  animationDuration="0.75"
                  ariaLabel="Loading"
                />
              ) : (
                <span className="text-sm font-semibold text-neutral-900">
                  {cartQuantity}
                </span>
              )}
            </div>

            {/* Increase */}
            <button
              type="button"
              onClick={handleIncrease}
              disabled={disableIncrease}
              aria-label="Increase quantity"
              className="flex h-9 w-9 items-center justify-center rounded-md text-neutral-700 transition-colors hover:bg-neutral-100 disabled:cursor-not-allowed disabled:opacity-40"
            >
              <Plus className="h-4 w-4" />
            </button>
          </div>

          {/* Quantity limitation */}
          {(reachedStockLimit || reachedOrderLimit) && (
            <p className="mt-2 text-center text-xs text-orange-500">
              {reachedOrderLimit
                ? `Maximum ${maxOrderQuantity} items allowed.`
                : "Maximum available quantity reached."}
            </p>
          )}
        </div>
      ) : (
        /* Add To Basket */
        <div className="mt-5">
          <button
            type="button"
            onClick={handleAddToCarts}
            disabled={
              isPending ||
              !selectedVariant ||
              stock <= 0
            }
            className="flex h-12 w-full items-center justify-center rounded-lg bg-red-600 px-4 text-sm font-semibold text-white transition-colors hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-neutral-300 disabled:text-neutral-500"
          >
            {isPending ? (
              <span className="flex items-center gap-2">
                <RotatingLines
                  visible={true}
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

      {/* Shipping Information */}
      <div className="mt-5 space-y-3 border-t border-neutral-100 pt-4">
        <div className="flex items-center gap-2">
          <TbBrandSpeedtest className="h-5 w-5 shrink-0 text-neutral-600" />

          <span className="text-xs text-neutral-600 sm:text-sm">
            Fast delivery available
          </span>
        </div>

        <div className="flex items-center gap-2">
          <VscCopilotSuccess className="h-5 w-5 shrink-0 text-green-600" />

          <span className="text-xs text-neutral-600 sm:text-sm">
            Safe and secure purchase
          </span>
        </div>
      </div>
    </div>
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

  const [activeTab, setActiveTab] = useState("Introduction");
  const [showAllSpecs, setShowAllSpecs] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      const sections = TABS.map((tab) => ({
        id: tab.id,
        element: document.getElementById(tab.targetId),
      })).filter((item) => item.element);

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
    const element = document.getElementById(tab.targetId);

    if (!element) return;

    setActiveTab(tab.id);

    element.scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  };

  const allAttributes = selectedVariant?.attributes || [];

  const visibleAttributes = showAllSpecs
    ? allAttributes
    : allAttributes.slice(0, 4);

  const hasMoreSpecs = allAttributes.length > 4;

  return (
    <div className="w-full max-w-[1440px] mx-auto">
      <section className="relative" id="product-section">
        {/* TABS */}
        <div
          className="
            sticky
            top-0
            z-40
            w-full
            bg-white/95
            backdrop-blur-sm
            border-b
            border-neutral-200
            flex
            gap-1
            sm:gap-2
            overflow-x-auto
            whitespace-nowrap
            scrollbar-hide
          "
        >
          {TABS.map((tab) => (
            <button
              key={tab.id}
              type="button"
              onClick={() => handleTabClick(tab)}
              className={`
                shrink-0
                px-3
                sm:px-4
                py-3
                text-xs
                sm:text-sm
                font-medium
                border-b-2
                -mb-px
                transition-colors
                duration-200
                ${
                  activeTab === tab.id
                    ? "border-red-600 text-neutral-900"
                    : "border-transparent text-neutral-500 hover:text-neutral-700"
                }
              `}
            >
              {tab.label}
            </button>
          ))}
        </div>

        {/* MAIN GRID */}
        <div
          className="
            grid
            grid-cols-1
            lg:grid-cols-[minmax(0,1fr)_360px]
            gap-6
            lg:gap-8
            items-start
            mt-5
            sm:mt-6
          "
        >
          {/* CONTENT */}
          <div className="min-w-0 w-full">
            <ProductOverview data={data} />

            <InDepthReview />

            <ProductSpecifications data={data} />

            {hasMoreSpecs && (
              <div className="px-4 sm:px-5 lg:w-9/12 mt-4">
                <button
                  type="button"
                  onClick={() => setShowAllSpecs((prev) => !prev)}
                  className="
                    text-sm
                    font-medium
                    text-red-600
                    hover:text-red-700
                    transition-colors
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
                sm:mx-5
                my-8
                border-t
                border-neutral-200
              "
            />

            <SubmitComment
              id={data?.id}
              data={data}
            />
          </div>

          {/* SELLER */}
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