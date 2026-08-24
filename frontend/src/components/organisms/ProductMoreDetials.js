"use client";

import { FaStar, FaStarHalfAlt, FaRegStar, FaFire } from "react-icons/fa";

import { useState, useEffect } from "react";
import Image from "next/image";
import { BsSortDownAlt } from "react-icons/bs";
import { useGetCommentProduct } from "@/core/services/queries";
import { AiOutlineLike, AiTwotoneDislike } from "react-icons/ai";
import { Trash2, Minus, Plus } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";
import { VscCopilotSuccess } from "react-icons/vsc";
import ProductReviewModal from "../ProductReviewModal";

function ProductOverview({ data }) {
  console.log(data);
  return (
    <div className="w-full">
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
    <div id="specifications-section" className="w-full lg:w-9/12 px-4 sm:px-5">
      <div className="flex flex-col">
        <p className="mt-8 sm:mt-5 text-base font-semibold">Specifications</p>

        <div className="w-[70px] mt-2 border-2 border-red-500" />

        <h1 className="mt-5 text-sm sm:text-base font-semibold">
          General Specifications
        </h1>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 mt-4">
        {}
        <div className="flex flex-col">
          {attr?.map((item) => (
            <div
              key={`name-${item?.id}`}
              className="
                min-h-[50px]
                flex
                items-center
                text-xs
                sm:text-sm
                text-neutral-400
                sm:pl-8
                lg:pl-32
                border-b
                border-neutral-200
              "
            >
              {item?.attribute_name}
            </div>
          ))}
        </div>

        {}
        <div className="flex flex-col">
          {attr?.map((item) => (
            <div key={`value-${item?.id}`}>
              <div
                className="
                  min-h-[50px]
                  flex
                  items-center
                  text-xs
                  sm:text-sm
                  text-neutral-800
                  px-2
                "
              >
                {item?.value}
              </div>

              <div className="border-b border-neutral-200" />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

function InDepthReview() {
  return (
    <div id="In-depth Review-section" className="w-full mt-10">
      <h1 className="px-4 sm:px-5 text-base sm:text-lg font-semibold">
        In-depth Review
      </h1>

      <div className="w-[70px] mt-2 ml-4 sm:ml-5 border-2 border-red-500" />

      <div className="mt-6 sm:mt-8 px-4 sm:px-5 lg:pl-36">
        <Image
          src="/icons/b.webp"
          width={1200}
          height={1300}
          alt="product review"
          className="
            w-full
            lg:w-8/12
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

        return <FaRegStar key={index} className="w-4 h-4 text-gray-300" />;
      })}

      <span className="text-xs font-bold text-gray-600 mr-1">{rating}</span>
    </div>
  );
}

function SubmitComment({ id, data }) {
  const [isReviewModalOpen, setIsReviewModalOpen] = useState(false);

  return (
    <>
      <div className="px-4 sm:px-5 max-w-[1270px]">
        <div
          className="
            flex
            flex-col
            sm:flex-row
            sm:items-center
            gap-4
            sm:gap-10
            lg:gap-64
          "
        >
          <p className="font-medium">4 out of 5</p>

          <Reviews id={id} />
        </div>

        <p className="mt-7 text-sm">
          You too can leave a review for this product.
        </p>

        <button
          type="button"
          onClick={() => setIsReviewModalOpen(true)}
          className="
            w-full
            sm:w-[320px]
            h-[41px]
            border
            border-red-500
            rounded-lg
            mt-5
            sm:mt-7
            text-red-600
            text-center
            transition-all
            hover:bg-red-50
            active:scale-[0.99]
          "
        >
          Submit a comment
        </button>
      </div>

      <ProductReviewModal
        isOpen={isReviewModalOpen}
        onClose={() => setIsReviewModalOpen(false)}
        product={data}
      />
    </>
  );
}

function Reviews({ id }) {
  const { data: comments } = useGetCommentProduct(id);

  return (
    <div className="w-full" id="viewpoint-section">
      <div className="w-full">
        {}
        <div
          className="
            flex
            items-center
            gap-3
            sm:gap-4
            mt-5
            overflow-x-auto
            whitespace-nowrap
            pb-1
          "
        >
          <BsSortDownAlt className="shrink-0" />

          <button className="text-xs sm:text-sm shrink-0">Latest</button>

          <button className="text-xs sm:text-sm shrink-0">
            Customer Reviews
          </button>

          <button className="text-xs sm:text-sm shrink-0">Most useful</button>
        </div>

        {}
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
          "
        >
          <div className="flex items-center gap-2">
            <p className="text-sm">Digikala user</p>

            <span
              className="
                px-2
                py-1
                text-[10px]
                text-center
                bg-green-400/20
                text-green-500
                rounded-md
              "
            >
              Buyer
            </span>
          </div>

          <div className="text-xs text-neutral-400">14 مرداد</div>
        </div>
      </div>

      {}
      <div className="w-full max-w-[750px]">
        <p
          className="
            mt-3
            text-xs
            sm:text-[13px]
            leading-6
            text-neutral-700
          "
        >
          {comments?.data?.body}
          My phone arrived promptly on the scheduled date. I was quite worried
          it might have issues—I’d never bought a phone online before—but before
          opening the box, I checked the serial number on Apple’s website. Once
          I was reassured that it hadn't been previously opened or activated, I
          unboxed and turned it on. Since it’s a dual-SIM model, I verified both
          the serial number and IMEI against the details shown on the phone
          itself.
        </p>
      </div>

      {}
      <div className="flex items-center gap-3 mt-3">
        <AiOutlineLike className="w-5 h-5 text-neutral-400" />

        <AiTwotoneDislike className="w-5 h-5 text-neutral-400" />
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
  return (
    <aside
      className="
        w-full
        lg:w-[360px]
        lg:sticky
        lg:top-8
        lg:pr-5
      "
    >
      <div
        className="
          w-full
          min-h-[400px]
          border
          border-neutral-300
          rounded-xl
          bg-white
          overflow-hidden
          p-4
          sm:p-5
        "
      >
        {}
        <div className="flex justify-between items-center">
          <p className="font-medium">Seller</p>

          <span className="text-orange-400 text-xs sm:text-sm font-medium">
            3 other sellers
          </span>
        </div>

        {}
        <div className="flex items-center gap-2 mt-5">
          <Image
            src="/icons/idigi.jfif"
            width={22}
            height={22}
            alt="icon"
            className="rounded-lg"
          />

          <span className="text-sm">Digikala</span>
        </div>

        {}
        <div className="flex items-center gap-2 mt-3 ml-7">
          <p className="text-[10px] text-neutral-400">Performance</p>

          <span className="text-sm text-green-600">Excellent</span>
        </div>

        <div className="w-full mt-4 border-t border-neutral-300" />

        {}
        <div className="flex items-center gap-2 mt-5">
          <span
            className="
              w-[30px]
              py-1
              bg-red-600
              rounded-md
              text-center
              text-xs
              text-white
            "
          >
            3%
          </span>

          <del className="text-xs text-neutral-400">3.500 $</del>
        </div>

        {}
        <span className="mt-3 inline-block text-lg sm:text-xl font-medium">
          {selectedVariant?.base_price ?? 0} $
        </span>

        {}
        <div className="flex items-center gap-2 mt-3">
          <FaFire className="w-5 h-5 text-orange-600" />

          <span className="text-xs sm:text-sm text-orange-400 font-medium">
            Only 1 item left in stock.
          </span>
        </div>

        {}
        {cartItem ? (
          <div className="mt-5">
            <div
              className="
                flex
                items-center
                justify-between
                w-full
                h-[42px]
                px-3
                bg-red-500
                border
                border-neutral-300
                rounded-lg
              "
            >
              <button onClick={handleDeacrease} className="text-white">
                {cartItem.quantity === 1 ? (
                  <Trash2 size={18} className="text-white" />
                ) : (
                  <Minus size={18} className="text-white" />
                )}
              </button>

              {!up ? (
                <span className="text-sm font-medium text-white">
                  {cartItem.quantity}
                </span>
              ) : (
                <RotatingLines
                  visible={true}
                  height="25"
                  width="25"
                  color="white"
                  strokeWidth="5"
                  animationDuration="0.75"
                  ariaLabel="loading"
                />
              )}

              <button
                onClick={handleIncrease}
                className="text-white disabled:opacity-30"
              >
                <Plus size={18} className="text-white" />
              </button>
            </div>
          </div>
        ) : (
          <div className="mt-5">
            <button
              onClick={handleAddToCarts}
              disabled={
                isPending || !selectedVariant || selectedVariant.stock === 0
              }
              className="
                w-full
                h-[42px]
                bg-red-500
                disabled:opacity-50
                rounded-lg
                px-5
                text-white
                text-sm
                transition-colors
                hover:bg-red-600
              "
            >
              {isPending ? "در حال افزودن..." : "Add to Basket"}
            </button>
          </div>
        )}

        {}
        <div className="mt-5">
          <div className="flex items-center text-neutral-400 gap-3">
            <VscCopilotSuccess className="w-5 h-5 shrink-0" />

            <span className="text-xs sm:text-sm">
              Sadrtel 18-month warranty
            </span>
          </div>
        </div>
      </div>
    </aside>
  );
}

const INITIAL_SPECS_COUNT = 4;

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
    },
    {
      id: "In-depth Review",
      label: "In-depth Review",
      actionType: "scroll",
      targetId: "In-depth Review-section",
    },
    {
      id: "specifications",
      label: "Specifications",
      actionType: "scroll",
      targetId: "specifications-section",
    },
    {
      id: "description",
      label: "Description",
    },
    {
      id: "reviews",
      label: "Reviews",
      actionType: "scroll",
      targetId: "reviews-section",
    },
    {
      id: "Viewpoint",
      label: "Viewpoint",
      actionType: "scroll",
      targetId: "viewpoint-section",
    },
  ];

  const [activeTab, setActiveTab] = useState("Introduction");

  const [isVisible, setIsVisible] = useState(true);

  const [showAllSpecs, setShowAllSpecs] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      const targetElement = document.getElementById("specifications-section");

      if (!targetElement) return;

      const rect = targetElement.getBoundingClientRect();

      setIsVisible(rect.top > 0);
    };

    window.addEventListener("scroll", handleScroll, { passive: true });

    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const handleTabClick = (tab) => {
    if (tab.actionType === "scroll") {
      const element = document.getElementById(tab.targetId);

      if (element) {
        element.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }

      setActiveTab(tab.id);
    } else {
      setActiveTab(tab.id);
    }
  };

  const allAttributes = selectedVariant?.attributes || [];

  const visibleAttributes = showAllSpecs
    ? allAttributes
    : allAttributes.slice(0, INITIAL_SPECS_COUNT);

  const hasMoreSpecs = allAttributes.length > INITIAL_SPECS_COUNT;

  return (
    <div
      className="
        w-full
        max-w-[1440px]
        mx-auto
        overflow-x-hidden
      "
    >
      <section className="relative" id="product-section">
        {/* =================================================
            TABS
        ================================================= */}

        <div
          className={`
            sticky
            top-0
            z-50
            bg-white
            border-b
            border-neutral-300
            flex
            gap-1
            sm:gap-2
            overflow-x-auto
            whitespace-nowrap
            transition-all
            duration-300
            scrollbar-hide
            ${
              isVisible
                ? "opacity-100 pointer-events-auto"
                : "opacity-0 pointer-events-none -translate-y-4"
            }
          `}
        >
          {TABS.map((tab) => (
            <button
              key={tab.id}
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

        {/* =================================================
            MAIN GRID
        ================================================= */}

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
          {/* =================================================
              CONTENT
          ================================================= */}

          <div className="min-w-0 w-full">
            {activeTab === "Introduction" && <ProductOverview data={data} />}

            <InDepthReview />

            <ProductSpecifications data={data} />

            {}

            <div
              className="
                mx-4
                sm:mx-5
                my-8
                border-t-2
                border-neutral-300
              "
            />

            {}

            <SubmitComment id={data?.id} data={data} />
          </div>

          {/* =================================================
              SELLER
          ================================================= */}

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
