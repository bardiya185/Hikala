"use client";
import { Link } from "lucide-react";
import Image from "next/image";
import React from "react";
import { useState } from "react";
import { FcRating } from "react-icons/fc";
import { TbBrandSpeedtest } from "react-icons/tb";
import { RiErrorWarningFill } from "react-icons/ri";
import { IoMdCheckmark, IoMdCheckmarkCircle } from "react-icons/io";
import { FaFire } from "react-icons/fa6";
import { VscCopilotSuccess } from "react-icons/vsc";
import { gsap } from "gsap";

import { SplitText } from "gsap/SplitText"; 
import { useEffect } from "react";

gsap.registerPlugin(SplitText);

function ProductsDe() {
  const colors = [
    { id: 1, name: "White", hex: "#ffffff", borderClass: "border-neutral-300" },
    { id: 2, name: "Black", hex: "#000000", borderClass: "border-black" },
    { id: 3, name: "Purple", hex: "#6366f1", borderClass: "border-indigo-500" }, 
    { id: 4, name: "Blue", hex: "#3b82f6", borderClass: "border-blue-500" },
  ];
  const [selectedColor, setSelectedColor] = useState(colors[2]);


useEffect(() => {
    
    const split = new SplitText(".animate-split", { 
      type: "words",
      wordsClass: "inline-block overflow-hidden" 
    });

    
    gsap.from(split.words, {
      duration: 0.8,
      y: 20,
      opacity: 0,
      stagger: 0.1, 
      ease: "back.out(1.7)",
    });

    
    return () => {
      split.revert();
    };
  }, []);

  return (
    <div className="max-w-[1440px] ">
      <div className="flex gap-3">
        <div className="flex flex-col">
          <p className="text-red-600 bg-red-500/30 w-4/4 h-[50px] pt-2">
            فروش ویژه
          </p>
          <Image src="/icons/vvv.webp" width={450} height={400} alt="phone" />
        </div>
        <div className="flex flex-col">
          <p>
            Samsung Galaxy S26 Ultra Dual SIM Mobile Phone – 256GB Storage, 12GB
            RAM – Vietnam
          </p>
          <p className="text-neutral-400 mt-3">
            Samsung Galaxy S26 Ultra Dual SIM Storage 256GB and RAM 12GB Mobile
            Phone - Vietnam
          </p>

          <div className="w-4/4 border border-solid border-neutral-500 mt-3"></div>
          <div className="flex items-center gap-3 mt-4">
            <FcRating className="w-[22px] h-[22px] text-yellow-500" />
            <span>4.3</span>
            <span>31 خریدار</span>
            <Link href="/" className="">
              109 دیدگاه
            </Link>
            <Link href="/">260 پرسش</Link>
          </div>
          <div className="flex flex-col ">
            <span className="mt-5">Color:{selectedColor.name}</span>
            <div className="flex items-center gap-3">
              {colors.map((color) => {
                const isSelected = selectedColor.id === color.id;

                return (
                  <button
                    key={color.id}
                    onClick={() => setSelectedColor(color)}
                    className={`
                relative w-8 h-8 rounded-full border flex items-center justify-center transition-all duration-200 mt-5
                ${isSelected ? `ring-2 ring-offset-2 ring-blue-500 scale-105` : "hover:scale-105"}
              `}
                    style={{
                      backgroundColor: color.hex,
                      borderColor:
                        color.hex === "#ffffff" ? "#e5e5e5" : color.hex,
                    }}
                    title={color.name}
                  >
                    {/* اگر این رنگ انتخاب شده بود، تیک وسطش را نشان بده */}
                    {isSelected && (
                      <IoMdCheckmark
                        className={`text-sm ${
                          color.hex === "#ffffff"
                            ? "text-neutral-900"
                            : "text-white"
                        }`}
                      />
                    )}
                  </button>
                );
              })}
            </div>
            <div className="flex mt-5 items-center h-[75px] border border-solid borde-neutral-400 mt-8 bg-blue-400/20 gap-2 ">
              <TbBrandSpeedtest className="text-blue-600 w-[25px] h-[25px]  " />
              <p>تحویل امروز با ارسال سریع دیجی‌کالا</p>
            </div>
            <p className="mt-7">ویژگی ها</p>
            <div className="  grid grid-cols-3 gap-y-3 mt-4">
              <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                <div className="flex flex-col">
                  <p className="text-neutral-500 text-[12px]">
                    Display technology
                  </p>
                  <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                </div>
              </div>
              <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                <div className="flex flex-col">
                  <p className="text-neutral-500 text-[12px]">
                    Display technology
                  </p>
                  <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                </div>
              </div>
              <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                <div className="flex flex-col">
                  <p className="text-neutral-500 text-[12px]">
                    Display technology
                  </p>
                  <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                </div>
              </div>
              <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                <div className="flex flex-col">
                  <p className="text-neutral-500 text-[12px]">
                    Display technology
                  </p>
                  <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                </div>
              </div>
              <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                <div className="flex flex-col">
                  <p className="text-neutral-500 text-[12px]">
                    Display technology
                  </p>
                  <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="w-[360px] h-[500px] border border-solid border-neutral-500 rounded-lg mt-16">
          <div className="flex justify-between px-5 pt-5">
            <p>Seller</p>
            <span className="text-orange-400 animate-split font-medium">3 other sellers</span>
          </div>
          <div className="flex items-center gap-2 pl-5 mt-5">
            <Image
              src="/icons/ii.png"
              width={22}
              height={22}
              alt="icon"
              className="rounded-lg"
            />
            <span>Digikala</span>
          </div>
          <div className="flex items-center gap-2 pl-12 mt-3">
            <p className="text-[10px] text-neutral-400">Performance</p>
            <span className="text-[14px] text-green-600">Excellent</span>
          </div>
          <div className=" w-[300] ml-5 border border-solid border-neutral-400"></div>
          <div className="flex items-center pl-5 mt-5 gap-2">
            <span className="w-[30px] bg-red-600 rounded-md text-center text-white ">
              3%
            </span>
            <del className="text-neutral-400">3.500 $</del>
          </div>
          <span className="pl-5 mt-3 inline-block text-[20px]">2.500 $</span>
          <div className="pl-5 mt-3 flex items-center gap-2">
            <FaFire className="w-[22px] h-[22px] text-orange-600 " />
            <span className="text-orange-400 animate-split font-me">Only 1 item left in stock.</span>

          </div>
          <div className="px-5 mt-4">

          <button className="w-[310px] h-[40px]  bg-red-500 rounded-lg px-5 pl-5 text-white">Add to Basket</button>
          <div className="flex items-center text-neutral-400 mt-4 gap-3">
            <VscCopilotSuccess className="w-[22px] h-[22px] text-neutral-400 gap-2 " />
            <span>Sadrtel 18-month warranty</span>

          </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ProductsDe;
