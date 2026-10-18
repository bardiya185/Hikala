"use client";

import Image from "next/image";
import Link from "next/link";
import React, { useState, useEffect, useRef, useCallback, useMemo } from "react";
import {
  IoIosArrowBack,
  IoIosArrowForward,
} from "react-icons/io";
import { gsap } from "gsap";

const FALLBACK_IMAGES = [
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

// Build the slide list from the /api/banners payload (home_slider position),
// falling back to bundled local banners when none are configured.
function useSlides(data) {
  return useMemo(() => {
    const positions = Array.isArray(data?.data)
      ? data.data
      : Array.isArray(data)
        ? data
        : [];

    const slider =
      positions.find((p) => p?.key === "home_slider") || null;

    const banners = Array.isArray(slider?.banners)
      ? slider.banners.filter((b) => b?.image)
      : [];

    if (banners.length) {
      return banners.map((b) => ({
        src: b.image,
        href: b.custom_url || b.link || null,
        alt: b.alt_text || b.title || "banner",
      }));
    }

    return FALLBACK_IMAGES.map((src) => ({ src, href: null, alt: "banner" }));
  }, [data]);
}

function Banner({ data }) {
  const slides = useSlides(data);
  const count = slides.length;

  const [currentIndex, setCurrentIndex] = useState(0);

  const slideRef = useRef(null);
  const autoPlayRef = useRef(null);

  useEffect(() => {
    if (currentIndex > count - 1) setCurrentIndex(0);
  }, [count, currentIndex]);

  /* NEXT SLIDE */
  const nextSlide = useCallback(() => {
    setCurrentIndex((prev) => (prev >= count - 1 ? 0 : prev + 1));
  }, [count]);

  /* PREVIOUS SLIDE */
  const prevSlide = useCallback(() => {
    setCurrentIndex((prev) => (prev <= 0 ? count - 1 : prev - 1));
  }, [count]);

  /* AUTOPLAY */
  useEffect(() => {
    if (count <= 1) return;
    autoPlayRef.current = setInterval(nextSlide, 4000);
    return () => {
      if (autoPlayRef.current) clearInterval(autoPlayRef.current);
    };
  }, [nextSlide, count]);

  /* GSAP ANIMATION */
  useEffect(() => {
    if (!slideRef.current) return;
    gsap.fromTo(
      slideRef.current,
      { opacity: 0, scale: 1.02 },
      { opacity: 1, scale: 1, duration: 0.5, ease: "power2.out" }
    );
  }, [currentIndex]);

  const goToSlide = (index) => setCurrentIndex(index);

  const active = slides[currentIndex] || slides[0];
  if (!active) return null;

  const imageEl = (
    <Image
      src={active.src}
      fill
      sizes="100vw"
      alt={active.alt}
      priority
      unoptimized={/^https?:\/\//.test(active.src) && !active.src.includes("/_next/")}
      className="object-cover"
    />
  );

  return (
    <div className="group relative w-full mt-10 overflow-hidden rounded-xl select-none sm:rounded-2xl lg:rounded-[0px]">
      <div
        ref={slideRef}
        className="relative aspect-[16/8] w-full overflow-hidden sm:aspect-[16/7.5] lg:aspect-[1270/500]"
      >
        {active.href ? (
          <Link href={active.href} aria-label={active.alt} className="block h-full w-full">
            {imageEl}
          </Link>
        ) : (
          imageEl
        )}
      </div>

      {count > 1 && (
        <>
          <button
            type="button"
            onClick={prevSlide}
            aria-label="Previous banner"
            className="absolute left-2 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-neutral-800 shadow-md transition-all hover:bg-white sm:left-3 sm:h-9 sm:w-9 lg:left-4 lg:h-10 lg:w-10 lg:opacity-0 lg:group-hover:opacity-100"
          >
            <IoIosArrowBack className="h-4 w-4 sm:h-5 sm:w-5" />
          </button>

          <button
            type="button"
            onClick={nextSlide}
            aria-label="Next banner"
            className="absolute right-2 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-neutral-800 shadow-md transition-all hover:bg-white sm:right-3 sm:h-9 sm:w-9 lg:right-4 lg:h-10 lg:w-10 lg:opacity-0 lg:group-hover:opacity-100"
          >
            <IoIosArrowForward className="h-4 w-4 sm:h-5 sm:w-5" />
          </button>

          <div className="absolute bottom-2 left-1/2 z-10 flex max-w-[90%] -translate-x-1/2 items-center gap-1 overflow-hidden sm:bottom-3 sm:gap-1.5 lg:bottom-5 lg:gap-2">
            {slides.map((_, index) => (
              <button
                key={index}
                type="button"
                onClick={() => goToSlide(index)}
                aria-label={`Go to banner ${index + 1}`}
                className={`h-1.5 shrink-0 rounded-full transition-all duration-300 sm:h-2 lg:h-2.5 ${
                  currentIndex === index
                    ? "w-5 bg-white sm:w-6 lg:w-7"
                    : "w-1.5 bg-white/50 hover:bg-white/80 sm:w-2 lg:w-2.5"
                }`}
              />
            ))}
          </div>
        </>
      )}
    </div>
  );
}

export default Banner;
