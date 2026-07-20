"use client"
import React, { useRef } from "react";
import CountdownTimer from "../atom/CountDownTimer";
import { AMAZING_PRODUCTS } from "../templates/mocks/AmazingData";
import Image from "next/image";

function AmazingSliders({data}) {
  // console.log(data)
  const sliderRef = useRef(null);

  const handleScroll = (direction) => {
    if (sliderRef.current) {
      const scrollAmount = direction === "next" ? -300 : 300;
      sliderRef.current.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  };

  const targetEndDate = AMAZING_PRODUCTS[0]?.discountEndDate;

  return (
    <div className="relative bg-red-600 rounded-2xl p-4 my-8 select-none">
      <div className="flex flex-col lg:flex-row items-center gap-4">
        
        
        <div className="flex flex-col items-center justify-center text-white p-4 min-w-[200px] shrink-0">
          <h2 className="text-xl font-extrabold mb-2 text-center">
            پیشنهاد<br />شگفت‌انگیز
          </h2>
          
          <CountdownTimer targetDate={targetEndDate} />
        </div>

        
        <div className="relative w-full overflow-hidden">
          
        
          <button
            onClick={() => handleScroll("prev")}
            className="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition-all"
            aria-label="Previous"
          >
            ❯
          </button>

          
          <div
            ref={sliderRef}
            className="flex items-center gap-3 overflow-x-auto scrollbar-hide scroll-smooth py-2 px-1"
            style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
          >
            {AMAZING_PRODUCTS.map((product) => (
              <div
                key={product.id}
                className=""
              >
                <div className="w-[300px] bg-white h-[330px] border border-neutral-400 rounded-[20px] px-5 py-5 ">

                <div className="w-full h-full border bg-white border-solid border-neutral-300 rounded-[20px] pr-10">
                <div className="flex flex-col ">
                  <Image src="/icons/product1.webp" width={200} height={200} alt="w" className=" object-contain w-[280px] h-[200px]  rounded-[20px] flex justify-center   "  />
                  <p>description</p>
                </div>
                </div>
                </div>

                </div>
                
            ))}
          </div>

          
          <button
            onClick={() => handleScroll("next")}
            className="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition-all"
            aria-label="Next"
          >
            ❮
          </button>
        </div>

      </div>
    </div>
  );
}

export default AmazingSliders;