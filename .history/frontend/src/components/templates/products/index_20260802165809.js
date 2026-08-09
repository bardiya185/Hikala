"use client";
import Image from "next/image";
import React from "react";
import { TfiAlignLeft } from "react-icons/tfi";
import { useRouter } from "next/navigation";
import { usePathname } from "next/navigation";
import { formatPrice } from "@/core/utils/formatPrice";
import ReactStars from "react-stars";
import { useEffect, useRef } from "react";
import { motion } from "framer-motion";
import { gsap } from "gsap";
import { SplitText } from "gsap/SplitText"; 
import Link from "next/link";

gsap.registerPlugin(SplitText);

function ProductSkeleton() {
  return (
    <div className="w-full h-auto rounded-[20px] border border-neutral-100 bg-white p-3 animate-pulse">
      <div className="rounded-[10px] w-full h-full border border-solid border-neutral-100 p-4">
        <div className="w-full aspect-[4/5] bg-neutral-200 rounded-[20px]" />

        <div className="flex justify-between items-center mt-5">
          <div className="h-4 bg-neutral-200 rounded w-1/2" />
          <div className="h-5 bg-neutral-200 rounded w-1/4" />
        </div>

        <div className="flex justify-between items-center mt-4">
          <div className="h-4 bg-neutral-200 rounded w-1/3" />
          <div className="h-5 bg-neutral-200 rounded-md w-1/5" />
        </div>

        <div className="space-y-2 mt-4">
          <div className="h-3 bg-neutral-200 rounded w-full" />
          <div className="h-3 bg-neutral-200 rounded w-5/6" />
        </div>
      </div>
    </div>
  );
}

function Products({ data, current_sort, current_sortorder }) {
  const router = useRouter();
  const pathname = usePathname();
  const containerRef = useRef(null);

  const handleSortChange = (sort_by, sort_order) => {
    const params = new URLSearchParams(window.location.search);

    if (sort_by && sort_order) {
      params.set("sort_by", sort_by);
      params.set("sort_order", sort_order);
    } else {
      params.delete("sort_by");
      params.delete("sort_order");
    }
    router.push(`${pathname}?${params.toString()}`);
  };

  const isLoading = !data || data.length === 0;


  return (
    <>
      
      <div className="flex gap-4 pl-4 mb-4 items-center" dir="ltr">
        <div className="flex items-center gap-2 text-neutral-700">
          <TfiAlignLeft size={18} />
          <span className="text-sm font-semibold">Sort:</span>
        </div>
        <button
          className={`text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors ${
            current_sort === "base_price" && current_sortorder === "asc"
              ? "text-red-500 bg-red-50"
              : "text-neutral-400 hover:text-neutral-600"
          }`}
          onClick={() => handleSortChange("base_price", "asc")}
        >
          The cheapest
        </button>
        <button
          className={`text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors ${
            current_sort === "base_price" && current_sortorder === "desc"
              ? "text-red-500 bg-red-50"
              : "text-neutral-400 hover:text-neutral-600"
          }`}
          onClick={() => handleSortChange("base_price", "desc")}
        >
          The most expensive
        </button>
      </div>

      
      <div
        ref={containerRef}
        className="max-w-[1270px] grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
        dir="ltr"
      >
        {isLoading
          ? Array.from({ length: 8 }).map((_, index) => (
              <ProductSkeleton key={index} />
            ))
          : data.map((product , index) => {
            const variant =
            product.variants?.find(v => v.is_default && v.is_active) ??
            product.variants?.find(v => v.is_active) ??
            product.variants?.[0];
    
        const basePrice = variant?.base_price ?? 0;
        const finalPrice = variant?.final_price ?? 0;
        const discountPercent = variant?.discount_percent ?? 0;
        const discountAmount = variant?.discount_amount ?? 0;
    
        // ✅ چک واقعی تخفیف
        const hasDiscount = finalPrice < basePrice && basePrice > 0;
    

              return (
                <motion.div 
                initial={{opacity:0 , y:20 , filter:'blur(10px)'}}
                animate={{ opacity:100 , y:0 , filter:'blur(0px)'}}
                transition={{ ease:'easeInOut', duration:0.5, delay: 0.1 * index , layout{{ duration }}  }}
                  className="product-card group rounded-[20px] bg-white border border-neutral-100 p-3"
                  layout
                  key={product.id}
                >
                  <div className="rounded-[10px] w-full h-full border border-solid border-neutral-100 p-4 flex flex-col justify-between">
                    <div>
                      <Link href={`/product/${product?.id}`}>
                      
                      <div className="w-full overflow-hidden rounded-[20px] aspect-[4/5] relative flex items-center justify-center">
                        <Image
                          src="/icons/images.jfif"
                          className="object-contain transform transition-transform duration-500 group-hover:scale-105"
                          width={200}
                          height={250}
                          alt={product?.title || "product"}
                          priority
                        />
                      </div>

                      
                      <div className="flex justify-between items-start mt-5 gap-2">
                        <h3 className="font-bold text-sm text-neutral-800 line-clamp-2 leading-6 h-12 animate-text-split">
                          {product?.title}
                        </h3>
                        <div className="flex items-center shrink-0 gap-1">
                          <ReactStars


                            count={1}
                            value={product?.rating || 0}
                            size={18}
                            color2="#fbbf24"
                            edit={false}
                            half={true}
                          />
                          <span>{product?.rating}</span>
                        </div>
                      </div>
                    </Link>
                    </div>

                    <div>
                      
                      <div
                        className="flex justify-between items-center mt-4"
                        dir="ltr"
                      >
                        <div className="flex items-center gap-2">
                          <p className="text-green-600 font-bold text-base animate-text-split">
                            ${formatPrice(finalPrice)}
                          </p>
                          {discountPercent > 0 && (
                            <span className="text-neutral-400 line-through text-xs animate-text-split">
                              ${formatPrice(basePrice)}
                            </span>
                          )}
                        </div>

                        {discountPercent > 0 && (
                          <div className="flex items-center text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-md">
                            <svg
                              className="w-3 h-3 fill-current mr-1"
                              viewBox="0 0 24 24"
                            >
                              <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" />
                            </svg>
                            <span className="animate-text-split">
                              {discountPercent}% Off
                            </span>
                          </div>
                        )}
                      </div>

                    
                      <p className="w-full mt-3 line-clamp-2 text-xs text-neutral-500 leading-5 animate-text-split">
                        {product?.short_description}
                      </p>
                    </div>
                  </div>
                </motion.div>
              );
            })}
      </div>
    </>
  );
}

export default Products;
