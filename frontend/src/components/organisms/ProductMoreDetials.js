"use client";
import { FaStar, FaStarHalfAlt, FaRegStar, FaFire } from "react-icons/fa";

import { ChevronDown, X } from "lucide-react";
import { useState } from "react";
import Image from "next/image";
import { useEffect } from "react";
import { BsSortDownAlt } from "react-icons/bs";
import { useGetCommentProduct } from "@/core/services/queries";
import { AiOutlineLike, AiTwotoneDislike } from "react-icons/ai";
import { Trash2 } from "lucide-react";
import { Minus } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";
import { Plus } from "lucide-react";
import { VscCopilotSuccess } from "react-icons/vsc";

function ProductOverview({ data }) {
  return (
    <div className="flex   items-center">
      <div className=" flex flex-col pl-5">
        <p className="mt-12">Introduction</p>
        <p className="w-9/12">{data?.description}</p>
      </div>
    </div>
  );
}

function ProductSpecifications({ data }) {
  const attr = data?.variants[0]?.attributes || [];
  console.log(attr);

  return (
    <div id="specifications-section" className="w-9/12 pl-5">
      <div className="flex flex-col">
        <p className="mt-5">Specifications</p>
        <div className=" w-[78] border-2 border-solid border-red-500"></div>
        <h1 className="mt-5">General Specifications</h1>
      </div>
      <div className="grid grid-cols-2">
        <div className="flex flex-col">
          {attr?.map((attr) => (
            <p className="mt-4 pl-32 text-neutral-400" key={attr?.id}>
              {attr?.attribute_name}
            </p>
          ))}
        </div>
        <div className="flex flex-col">
          {attr?.map((attr) => (
            <>
              <p className="mt-4" key={attr.id}>
                {attr?.value}
              </p>
              <div className="border border-b border-neutral-400   "></div>
            </>
          ))}
        </div>
      </div>
    </div>
  );
}

function InDepthReview() {
  return (
    <>
      <div id="In-depth Review-section">
        <h1 className="pl-5">In-depth Review</h1>
        <div className="w-[70px] mt-2 border-2 ml-5 border-b border-red-500"></div>
        <div className=" mt-8 pl-36 ">
          <Image
            src="/icons/b.webp"
            width={1200}
            height={1300}
            alt="n"
            className=" h-[600px] w-8/12  "
          />
        </div>
      </div>
    </>
  );
}

function RatingStars() {
  return (
    <div className="flex items-center gap-1 text-amber-400 dir-ltr">
      {Array.from({ length: maxStars }, (_, index) => {
        const starValue = index + 1;

        if (rating >= starValue) {
          // ستاره کامل
          return <FaStar key={index} className="w-4 h-4" />;
        } else if (rating >= starValue - 0.5) {
          // نیم ستاره
          return <FaStarHalfAlt key={index} className="w-4 h-4" />;
        } else {
          // ستاره خالی
          return <FaRegStar key={index} className="w-4 h-4 text-gray-300" />;
        }
      })}
      <span className="text-xs font-bold text-gray-600 mr-1">{rating}</span>
    </div>
  );
}

function SubmitComment({ id }) {
  return (
    <>
      <div className="pl-5 max-w-[1270px]   ">
        <div className="  flex items-center gap-64 ">
          <p className="">4 out of 5</p>
          <Reviews id={id} />
        </div>
        <p className="mt-7">You too can leave a review for this product.</p>
        <button className=" w-[320px] h-[41px] border border-solid border-red-500 rounded-lg mt-7 text-red-600 text-center">
          Submit a comment
        </button>
      </div>
    </>
  );
}

function Reviews({ id }) {
  const { data: comments } = useGetCommentProduct(id);
  console.log(comments);
  return (
    <div className="" id="viewpoint-section">
      <div className="w-full">
        <div className="flex items-center mt-7 gap-4   ">
          <BsSortDownAlt />

          <button>Latest</button>
          <button>Customer Reviews</button>
          <button>Most useful</button>
        </div>
        <div className=" w-[750px] flex items-center justify-between ">
          <div className="flex items-center mt-5 gap-2 ">
            <p className="">Digikala user</p>
            <span className="w-[50px] text-center bg-green-400/20 text-green-400 rounded-md">
              Buyer
            </span>
          </div>
          <div>14مرداد</div>
        </div>
      </div>

      <div className="max-w-[750px]">
        <p className="w-fit h-fit mt-2 text-[13px]">
          {comments?.data?.body}My phone arrived promptly on the scheduled date.
          I was quite worried it might have issues—I’d never bought a phone
          online before—but before opening the box, I checked the serial number
          on Apple’s website. Once I was reassured that it hadn't been
          previously opened or activated, I unboxed and turned it on. Since it’s
          a dual-SIM model, I verified both the serial number and IMEI against
          the details shown on the phone itself. Then, I activated it by dialing
          *7777# and entering the activation code, and transferred all my data
          from my old phone to the new one via iCloud. I really love the color,
          too. Kudos to Digikala—don't be afraid to place an order.
        </p>
      </div>
      <div className="flex items-center gap-2 mt-3">
        <AiOutlineLike className="w-[25px] h-[25px] text-neutral-400" />
        <AiTwotoneDislike className="w-[25px] h-[25px] text-neutral-400" />
      </div>
    </div>
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
  console.log(data);
  const TABS = [
    { id: "Introduction", label: "Introduction" },
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
    { id: "description", label: "Description" },
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
  const [isExpanded, setIsExpanded] = useState(false);
  console.log(selectedVariant);
  console.log(data);

  useEffect(() => {
    const handleScroll = () => {
      // 🎯 گرفتن دیو یا المانی که نقطه پایان نمایش هدر است
      const targetElement = document.getElementById("specifications-section");
      if (!targetElement) return;

      // محاسبه موقعیت المان نسبت به بالای مرورگر
      const rect = targetElement.getBoundingClientRect();

      // اگر المان مقصد به بالای مرورگر رسید یا رد شد، هدر مخفی می‌شود
      if (rect.top <= 0) {
        setIsVisible(false);
      } else {
        setIsVisible(true);
      }
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const handleTabClick = (tab) => {
    if (tab.actionType === "tab") {
      // تغییر تب فعلی در همان مکان
      setActiveTab(tab.id);
    } else if (tab.actionType === "scroll") {
      // اسکرول به المان مقصد در پایین صفحه
      const element = document.getElementById(tab.targetId);
      if (element) {
        element.scrollIntoView({ behavior: "smooth" });
      }
    }
  };

  const allAttributes = selectedVariant?.attributes || [];
  const visibleAttributes = showAllSpecs
    ? allAttributes
    : allAttributes.slice(0, INITIAL_SPECS_COUNT);
  const hasMoreSpecs = allAttributes.length > INITIAL_SPECS_COUNT;

  return (
    <div>
      <section className="relative" id="product-section">
        <div
          className={`
          sticky top-0 z-50
          bg-white
          border-b border-neutral-300
          flex gap-2
          transition-all duration-300
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
              px-4 py-3
              text-sm font-medium
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

        <div
          className="
          grid
          grid-cols-1
          lg:grid-cols-[minmax(0,1fr)_360px]
          gap-8
          items-start
          mt-6
        "
        >
          <div className="min-w-0">
            {activeTab === "Introduction" && <ProductOverview data={data} />}

            <InDepthReview />

            <ProductSpecifications data={data} />

            <div className="mx-5 my-8 border-t-2 border-neutral-300" />

            <SubmitComment id={data?.id} />
          </div>

          <aside className="sticky top-8 w-full  pr-5 ">
            <div
              className="
              w-full
              h-[400px]
              border
              border-neutral-300
              rounded-lg
              bg-white
              overflow-hidden
            "
            >
              <div className="flex justify-between px-5 pt-5">
                <p className="font-medium">Seller</p>

                <span className="text-orange-400 font-medium">
                  3 other sellers
                </span>
              </div>

              <div className="flex items-center gap-2 pl-5 mt-5">
                <Image
                  src="/icons/idigi.jfif"
                  width={22}
                  height={22}
                  alt="icon"
                  className="rounded-lg"
                />

                <span>Digikala</span>
              </div>

              <div className="flex items-center gap-2 pl-12 mt-3">
                <p className="text-[10px] text-neutral-400">Performance</p>

                <span className="text-sm text-green-600">Excellent</span>
              </div>

              <div className="w-[300px]   ml-5 mt-4 border-t border-neutral-300" />

              <div className="flex items-center gap-2 pl-5 mt-5">
                <span
                  className="
                  w-[30px]
                  bg-red-600
                  rounded-md
                  text-center
                  text-white
                "
                >
                  3%
                </span>

                <del className="text-neutral-400">3.500 $</del>
              </div>

              <span className="pl-5 mt-3 inline-block text-[20px] font-medium">
                {data?.variants?.base_price} $
              </span>

              <div className="pl-5 mt-3 flex items-center gap-2">
                <FaFire
                  className="
                  w-[22px]
                  h-[22px]
                  text-orange-600
                "
                />

                <span className="text-orange-400 font-medium">
                  Only 1 item left in stock.
                </span>
              </div>

              {cartItem ? (
                <div className="px-5">
                  <div
                    className="
                    flex
                    items-center
                    justify-between
                    w-full
                    h-[40px]
                    px-3
                    mt-5
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
                        <Minus className="text-white" size={18} />
                      )}
                    </button>

                    {!up ? (
                      <span className="text-sm font-medium text-white">
                        {cartItem.quantity}
                      </span>
                    ) : (
                      <RotatingLines
                        visible={true}
                        height="30"
                        width="30"
                        color="white"
                        strokeWidth="5"
                        animationDuration="0.75"
                        ariaLabel="rotating-lines-loading"
                        wrapperStyle={{}}
                        wrapperClass=""
                      />
                    )}

                    <button
                      onClick={handleIncrease}
                      disabled={cartItem.quantity === 1}
                      className="
                      text-neutral-600
                      disabled:opacity-30
                    "
                    >
                      <Plus size={18} className="text-white" />
                    </button>
                  </div>
                </div>
              ) : (
                <div className="px-5 mt-5">
                  <button
                    onClick={handleAddToCarts}
                    disabled={
                      isPending ||
                      !selectedVariant ||
                      selectedVariant.stock === 0
                    }
                    className="
                    w-full
                    h-[40px]
                    bg-red-500
                    disabled:opacity-50
                    rounded-lg
                    px-5
                    text-white
                  "
                  >
                    {isPending ? "در حال افزودن..." : "Add to Basket"}
                  </button>
                </div>
              )}

              <div className="px-5 mt-4">
                <div
                  className="
                  flex
                  items-center
                  text-neutral-400
                  mt-4
                  gap-3
                "
                >
                  <VscCopilotSuccess
                    className="
                    w-[22px]
                    h-[22px]
                    text-neutral-400
                  "
                  />

                  <span>Sadrtel 18-month warranty</span>
                </div>
              </div>
            </div>
          </aside>
        </div>
      </section>
    </div>
  );
}
