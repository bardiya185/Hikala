"use client";

import Image from "next/image";
import React, { useState, useEffect, useRef, useCallback } from "react";
import {
  IoIosArrowBack,
  IoIosArrowForward,
} from "react-icons/io";
import { gsap } from "gsap";

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

  /* =========================================
     NEXT SLIDE
  ========================================= */

  const nextSlide = useCallback(() => {
    setCurrentIndex((prevIndex) =>
      prevIndex === BANNER_IMAGES.length - 1
        ? 0
        : prevIndex + 1,
    );
  }, []);

  /* =========================================
     PREVIOUS SLIDE
  ========================================= */

  const prevSlide = useCallback(() => {
    setCurrentIndex((prevIndex) =>
      prevIndex === 0
        ? BANNER_IMAGES.length - 1
        : prevIndex - 1,
    );
  }, []);

  /* =========================================
     AUTOPLAY
  ========================================= */

  useEffect(() => {
    autoPlayRef.current = setInterval(
      nextSlide,
      3000,
    );

    return () => {
      if (autoPlayRef.current) {
        clearInterval(autoPlayRef.current);
      }
    };
  }, [nextSlide]);

  /* =========================================
     GSAP ANIMATION
  ========================================= */

  useEffect(() => {
    if (!slideRef.current) return;

    gsap.fromTo(
      slideRef.current,
      {
        opacity: 0,
        scale: 1.02,
      },
      {
        opacity: 1,
        scale: 1,
        duration: 0.5,
        ease: "power2.out",
      },
    );
  }, [currentIndex]);

  /* =========================================
     GO TO SLIDE
  ========================================= */

  const goToSlide = (index) => {
    setCurrentIndex(index);
  };

  return (
    <div
      className="
        group
        relative
        w-full
        overflow-hidden
        rounded-xl
        select-none

        sm:rounded-2xl

        lg:rounded-[20px]
      "
    >
      {/* =====================================
          SLIDER
      ===================================== */}

      <div
        ref={slideRef}
        className="
          relative
          aspect-[16/8]
          w-full
          overflow-hidden

          sm:aspect-[16/7.5]

          lg:aspect-[1270/500]
        "
      >
        <Image
          src={BANNER_IMAGES[currentIndex]}
          fill
          sizes="
            100vw
          "
          alt={`banner-${currentIndex + 1}`}
          priority
          className="
            object-cover
          "
        />
      </div>

      {/* =====================================
          PREVIOUS BUTTON
      ===================================== */}

      <button
        type="button"
        onClick={prevSlide}
        aria-label="Previous banner"
        className="
          absolute
          left-2
          top-1/2
          z-10
          flex
          h-8
          w-8
          -translate-y-1/2
          items-center
          justify-center
          rounded-full
          bg-white/80
          text-neutral-800
          shadow-md
          transition-all

          hover:bg-white

          sm:left-3
          sm:h-9
          sm:w-9

          lg:left-4
          lg:h-10
          lg:w-10

          lg:opacity-0
          lg:group-hover:opacity-100
        "
      >
        <IoIosArrowBack
          className="h-4 w-4 sm:h-5 sm:w-5"
        />
      </button>

      {/* =====================================
          NEXT BUTTON
      ===================================== */}

      <button
        type="button"
        onClick={nextSlide}
        aria-label="Next banner"
        className="
          absolute
          right-2
          top-1/2
          z-10
          flex
          h-8
          w-8
          -translate-y-1/2
          items-center
          justify-center
          rounded-full
          bg-white/80
          text-neutral-800
          shadow-md
          transition-all

          hover:bg-white

          sm:right-3
          sm:h-9
          sm:w-9

          lg:right-4
          lg:h-10
          lg:w-10

          lg:opacity-0
          lg:group-hover:opacity-100
        "
      >
        <IoIosArrowForward
          className="h-4 w-4 sm:h-5 sm:w-5"
        />
      </button>

      {/* =====================================
          DOTS
      ===================================== */}

      <div
        className="
          absolute
          bottom-2
          left-1/2
          z-10
          flex
          max-w-[90%]
          -translate-x-1/2
          items-center
          gap-1
          overflow-hidden

          sm:bottom-3
          sm:gap-1.5

          lg:bottom-5
          lg:gap-2
        "
      >
        {BANNER_IMAGES.map((_, index) => (
          <button
            key={index}
            type="button"
            onClick={() => goToSlide(index)}
            aria-label={`Go to banner ${index + 1}`}
            className={`
              h-1.5
              shrink-0
              rounded-full
              transition-all
              duration-300

              sm:h-2

              lg:h-2.5

              ${
                currentIndex === index
                  ? "w-5 bg-white sm:w-6 lg:w-7"
                  : "w-1.5 bg-white/50 hover:bg-white/80 sm:w-2 lg:w-2.5"
              }
            `}
          />
        ))}
      </div>
    </div>
  );
}

export default Banner;
