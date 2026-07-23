"use client";
import React, { useRef } from "react";
import CountdownTimer from "../atom/CountDownTimer";
import Image from "next/image";

function AmazingSliders({ data }) {
  console.log(data)
  const sliderRef = useRef(null);

  
  const handleScroll = (direction) => {
    if (sliderRef.current) {
      const scrollAmount = direction === "next" ? 300 : -300;
      sliderRef.current.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  };

  
  const targetEndDate = data?.[0]?.ends_at;

  
 

  return (
    <div className="relative bg-red-600 rounded-2xl p-4 my-8 select-none ltr">
      <div className="flex flex-col lg:flex-row items-center gap-4">
      
        <div className="flex flex-col items-center justify-center text-white p-4 min-w-[200px] shrink-0">
          <h2 className="text-xl font-extrabold mb-2 text-center uppercase tracking-wide">
            Amazing
            <br />
            Offers
          </h2>

          {targetEndDate && <CountdownTimer targetDate={targetEndDate} />}
        </div>

        
        <div className="relative w-full overflow-hidden">
          
          <button
            onClick={() => handleScroll("prev")}
            className="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition-all"
            aria-label="Previous"
          >
            ❮
          </button>

          
          <div
            ref={sliderRef}
            className="flex items-center gap-4 overflow-x-auto scrollbar-hide scroll-smooth py-2 px-1"
            style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
          >
            {data?.map((p) => (
              <div
                key={p.id}
                className="w-[260px] h-[350px] bg-white rounded-2xl p-4 border border-neutral-400 shadow-sm shrink-0 flex flex-col justify-between hover:shadow-md transition-shadow"
              >
                <div className="w-full h-full bg-white border border-solid rounded-[20px] px-3">
                  <div className="w-full h-[180px] relative flex items-center justify-center bg-gray-50 rounded-xl overflow-hidden mb-3">
                    <Image
                      src="/icons/product1.webp"
                      alt={p.title || "product image"}
                      width={180}
                      height={180}
                      className="object-contain max-h-full"
                    />
                  </div>

                  <div className="flex flex-col gap-1">
                    <h3 className="font-semibold text-gray-800 text-sm line-clamp-2">
                      {p?.title}
                    </h3>
                    <p className="text-xs text-gray-400 line-clamp-1 mt-3">
                      {p.short_description}
                    </p>
                  </div>
                  <div className="flex items-center">
                    <del>{}</del>

                  </div>
              </div>
                </div>
            ))}
          </div>

          
          <button
            onClick={() => handleScroll("next")}
            className="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center transition-all"
            aria-label="Next"
          >
            ❯
          </button>
        </div>
      </div>
    </div>
  );
}

export default AmazingSliders;
