"use client";
import Image from "next/image";
import React, { useState, useEffect, useRef } from "react";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import { gsap } from "gsap";
import Link from "next/link";

function Banner({ data }) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const slideRef = useRef(null);
  const autoPlayRef = useRef(null);

  const positions = data?.data || data || [];


  const middleBannersPosition = positions.find(
    (pos) => pos.key === "home_middle_4"
  );

  const banners = middleBannersPosition?.banners || [];

  
  const nextSlide = () => {
    if (banners.length === 0) return;
    setCurrentIndex((prevIndex) =>
      prevIndex === banners.length - 1 ? 0 : prevIndex + 1
    );
  };


  const prevSlide = () => {
    if (banners.length === 0) return;
    setCurrentIndex((prevIndex) =>
      prevIndex === 0 ? banners.length - 1 : prevIndex - 1
    );
  };

  
  useEffect(() => {
    if (banners.length <= 1) return;
    autoPlayRef.current = setInterval(nextSlide, 3000);

    return () => {
      if (autoPlayRef.current) clearInterval(autoPlayRef.current);
    };
  }, [currentIndex, banners.length]);


  useEffect(() => {
    if (slideRef.current) {
      gsap.fromTo(
        slideRef.current,
        { opacity: 0.3, scale: 1.02 },
        { opacity: 1, scale: 1, duration: 0.5, ease: "power2.out" }
      );
    }
  }, [currentIndex]);

  if (!banners || banners.length === 0) {
    return null;
  }

  const currentBanner = banners[currentIndex];

  return (
    <div className="relative w-full h-[350px] md:h-[500px] overflow-hidden rounded-[20px] group select-none my-6">
      
      <div ref={slideRef} className="w-full h-full relative">
        {currentBanner?.image ? (
          <Link href={currentBanner?.url || "#"}>
            <Image
              src={currentBanner.image}
              fill
              alt={currentBanner?.alt_text || currentBanner?.title || "Banner"}
              className="object-cover rounded-[20px]"
              priority
            />
          </Link>
        ) : null}
      </div>

      
      {banners.length > 1 && (
        <button
          onClick={prevSlide}
          className="absolute left-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-neutral-800 p-2.5 rounded-full shadow-md transition-all opacity-0 group-hover:opacity-100 z-10"
        >
          <IoIosArrowBack size={22} />
        </button>
      )}

      
      {banners.length > 1 && (
        <button
          onClick={nextSlide}
          className="absolute right-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-neutral-800 p-2.5 rounded-full shadow-md transition-all opacity-0 group-hover:opacity-100 z-10"
        >
          <IoIosArrowForward size={22} />
        </button>
      )}

      
      {banners.length > 1 && (
        <div className="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
          {banners.map((_, index) => (
            <span
              key={index}
              onClick={() => setCurrentIndex(index)}
              className={`h-2.5 rounded-full transition-all duration-300 cursor-pointer ${
                currentIndex === index
                  ? "w-7 bg-white"
                  : "w-2.5 bg-white/50 hover:bg-white/80"
              }`}
            />
          ))}
        </div>
      )}
    </div>
  );
}

export default Banner;