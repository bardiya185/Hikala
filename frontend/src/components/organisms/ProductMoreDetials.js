"use client";

import { ChevronDown, X } from "lucide-react";
import { useState } from "react";
import Image from "next/image";
import { useEffect } from "react";

function ProductOverview({ data }) {
  return (
    <div className=" flex flex-col pl-5">
      <p className="mt-12">Introduction</p>
      <p className="w-9/12">{data?.description}</p>
    </div>
  );
}

function ProductSpecifications({data}) {
  
  const attr = data?.variants[0]?.attributes
 || []
 console.log(attr)

  return (

    <div id="specifications-section" className="w-9/12 pl-5">
      <div className="flex flex-col">
        <p className="mt-5">Specifications</p>
        <div className=" w-[78] border-2 border-solid border-red-500"></div>
        <h1 className="mt-5">General Specifications</h1>
      </div>
      <div className="grid grid-cols-2">

      <div className="flex flex-col">
        {attr?.map((attr)=>(
          <p className="mt-4 pl-32 text-neutral-400" key={attr.id}>{attr?.attribute_name}</p>
        ))}

      </div>
      <div className="flex flex-col">
        {attr?.map((attr)=>(
          <>
          <p className="mt-4" key={attr.id}>{attr?.value}</p>
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

const INITIAL_SPECS_COUNT = 4;

export default function ProductMoreDetails({
  data,
  selectedVariant,
  maxlength = 150,
}) {
  console.log(data)
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
    { id: "reviews", label: "Reviews" },
    { id: "Viewpoint", label: "Viewpoint" },
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
      <section id="">
        <div
          className={` bg-white sticky top-0  left-0 z-50 transition-all duration-300 border border-b border-neutral-400  flex gap-2 ${isVisible ? " opacity-100 pointer-events-auto" : "opacity-0 pointer-events-none -translate-y-4"}`}
        >
          {TABS.map((tab) => (
            <button
              className={`px-4 py-3 text-sm font-medium border-b-2  -mb-px transition-colors ${
                activeTab === tab.id
                  ? "border-red-600 "
                  : "border-transparent text-neutral-500 hover:text-neutral-700"
              }`}
              onClick={() => handleTabClick(tab)}
              key={tab.id}
            >
              {tab.label}
            </button>
          ))}
          {/* <div className="mt-2 border border-b border-neutral-400"></div> */}
        </div>
        <div className="min-h-[150px]">
          {activeTab === "Introduction" && <ProductOverview data={data} />}
        </div>
        <InDepthReview />
        <ProductSpecifications data={data} />
        <div className="mx-5  border-2 border-solid border-neutral-400"></div>
      </section>
    </div>
  );
}
