"use client";
import Image from "next/image";
import React, { useState, useEffect, useRef } from "react";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io"; // ناوبری راست‌به‌چپ و چپ‌به‌راست
import { gsap } from "gsap";

// آرایه آدرس تصاویر بنر برای تمیزی کد و جلوگیری از تکرار تگ‌ها
const BANNER_IMAGES = [
  "/icons/banner1.webp",
  "/icons/banner2.webp",
  "/icons/banner3.webp",
  "/icons/banner4.webp",
  "/icons/banner5.webp",
  "/icons/banner6.webp",
  "/icons/banner7.webp",
  "/icons/banner8.webp",
  "/icons/banner9.webp",
  
];

function Banner() {
  const [currentIndex, setCurrentIndex] = useState(0);
  const slideRef = useRef(null);
  const autoPlayRef = useRef(null);


  const nextSlide = () => {
    setCurrentIndex((prevIndex) =>
      prevIndex === BANNER_IMAGES.length - 1 ? 0 : prevIndex + 1
    );
  };


  const prevSlide = () => {
    setCurrentIndex((prevIndex) =>
      prevIndex === 0 ? BANNER_IMAGES.length - 1 : prevIndex - 1
    );
  };

  
  useEffect(() => {
  
    autoPlayRef.current = setInterval(nextSlide, 3000);

  
    return () => {
      if (autoPlayRef.current) clearInterval(autoPlayRef.current);
    };
  }, [currentIndex]); 

  
  useEffect(() => {
    if (slideRef.current) {
      gsap.fromTo(
        slideRef.current,
        { opacity: 0, scale: 1.02 }, 
        { opacity: 1, scale: 1, duration: 0.5, ease: "power2.out" } 
      );
    }
  }, [currentIndex]);

  return (
    <div className="relative w-[500px] h-[500px] overflow-hidden rounded-[20px] group select-none">
  
      <div ref={slideRef} className="w-full h-full relative">
        <Image
          src={BANNER_IMAGES[currentIndex]}
          width={1270}
          height={500}
          alt={`banner-${currentIndex + 1}`}
          className="w-full h-[500px] object-cover"
          priority 
        />
      </div>

      
      <button
        onClick={prevSlide}
        className="absolute left-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-neutral-800 p-2.5 rounded-full shadow-md transition-all opacity-0 group-hover:opacity-100 z-10"
      >
        <IoIosArrowBack size={22} />
      </button>

      
      <button
        onClick={nextSlide}
        className="absolute right-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-neutral-800 p-2.5 rounded-full shadow-md transition-all opacity-0 group-hover:opacity-100 z-10"
      >
        <IoIosArrowForward size={22} />
      </button>

      
      <div className="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        {BANNER_IMAGES.map((_, index) => (
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
    </div>
  );
}

export default Banner;

