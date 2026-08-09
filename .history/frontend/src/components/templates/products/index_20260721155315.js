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
import { SplitText } from "gsap/SplitText"; // ایمپورت مستقیم از خود gsap
import { Link } from "lucide-react";

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

  // useEffect(() => {
  //   if (isLoading || !containerRef.current) return;

  //   const cards = containerRef.current.querySelectorAll(".product-card");
  //   const textTargets = containerRef.current.querySelectorAll(
  //     ".animate-text-split",
  //   );

  //   if (cards.length === 0) return;

    // const tl = gsap.timeline();

    // // انیمیشن کارت‌ها با استفاده از autoAlpha برای جلوگیری از پرش یا تداخل با استایل وب‌سایت
    // tl.from(cards, {
    //   duration: 0.7,
    //   y: 40,
    //   autoAlpha: 0, // ترکیبی هوشمند از opacity و visibility
    //   stagger: 0.06,
    //   ease: "power3.out", // یک Ease نرم‌تر برای حرکت روان کارت‌ها
    // });

  //   if (textTargets.length > 0) {
  //     const split = new SplitText(textTargets, {
  //       type: "words",
  //       wordsClass: "inline-block overflow-hidden pt-1",
  //     });

  //     tl.from(
  //       split.words,
  //       {
  //         duration: 0.5,
  //         y: 15,
  //         autoAlpha: 0,
  //         stagger: 0.01,
  //         ease: "power2.out",
  //       },
  //       "-=0.4",
  //     ); // شروع انیمیشن متن کمی قبل از اتمام حرکت کارت‌ها

  //     return () => {
  //       tl.kill();
  //       split.revert();
  //     };
  //   }

  //   return () => {
  //     tl.kill();
  //   };
  // }, [data, isLoading]);

  return (
    <>
      {/* بخش مرتب سازی */}
      <div className="flex gap-4 pl-4 mb-4 items-center" dir="ltr">
        <div className="flex items-center gap-2 text-neutral-700">
          <TfiAlignLeft size={18} />
          <span className="text-sm font-semibold">Sort:</span>
        </div>
        <button
          className={`text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors ${
            current_sort === "price" && current_sortorder === "asc"
              ? "text-red-500 bg-red-50"
              : "text-neutral-400 hover:text-neutral-600"
          }`}
          onClick={() => handleSortChange("price", "asc")}
        >
          The cheapest
        </button>
        <button
          className={`text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors ${
            current_sort === "price" && current_sortorder === "desc"
              ? "text-red-500 bg-red-50"
              : "text-neutral-400 hover:text-neutral-600"
          }`}
          onClick={() => handleSortChange("price", "desc")}
        >
          The most expensive
        </button>
      </div>

      {/* ⚡ اضافه شدن کانتینر رفرنس به گرید اصلی کارت‌ها */}
      <div
        ref={containerRef}
        className="max-w-[1270px] grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
        dir="rtl"
      >
        {isLoading
          ? Array.from({ length: 8 }).map((_, index) => (
              <ProductSkeleton key={index} />
            ))
          : data.map((ddd , index) => {
            const variant = product.variants[0];

            variant.base_price;
            variant.price;
            variant.discount_amount;
            variant.discount_percent;

            {
              "data": {
                "id": 1,
                "title": "iPhone 16",
                "slug": "iphone-16-N1WmEW",
                "short_description": "Premium iPhone 16 with high-quality features. Perfect for everyday use.",
                "description": "<p>Premium iPhone 16 with high-quality features. Perfect for everyday use.</p>",
                "status": "active",
                "meta_title": "iPhone 16 | Buy with best price",
                "meta_keywords": "iPhone 16, buy, shop, best price, Apple",
                "meta_description": "Buy iPhone 16 with best price.",
                "view_count": 43291,
                "rating": 5,
                "sort_order": 0,
                "is_active": 1,
                "created_at": "2026-07-20T17:43:19.000000Z",
                "updated_at": "2026-07-20T17:43:19.000000Z",
                "brand": {
                  "id": 1,
                  "name": "Apple",
                  "slug": "apple",
                  "logo": null,
                  "description": null,
                  "sort_order": 0,
                  "is_active": true,
                  "created_at": "2026-07-20T17:43:08.000000Z"
                },
                "categories": [
                  {
                    "id": 4,
                    "name": "iPhone 16",
                    "slug": "iphone-16"
                  },
                  {
                    "id": 2,
                    "name": "Select Mobile",
                    "slug": "select-mobile"
                  }
                ],
                "images": [],
                "variants": [
                  {
                    "id": 1,
                    "sku": "SKU-1-1-3Bg7",
                    "barcode": "2581066988333",
                    "base_price": 2335,
                    "final_price": 2335,
                    "discount_amount": 0,
                    "discount_percent": 0,
                    "stock": 36,
                    "weight": 359,
                    "is_active": 1,
                    "attributes": [
                      {
                        "id": 9,
                        "attribute_id": 1,
                        "attribute_name": "Color",
                        "attribute_slug": "color",
                        "value": "Purple",
                        "slug": "purple",
                        "color_code": "#9b59b6",
                        "image": null,
                        "sort_order": 9,
                        "is_active": true
                      },
                      {
                        "id": 31,
                        "attribute_id": 3,
                        "attribute_name": "Material",
                        "attribute_slug": "material",
                        "value": "Wool",
                        "slug": "wool",
                        "color_code": null,
                        "image": null,
                        "sort_order": 12,
                        "is_active": true
                      },
                      {
                        "id": 37,
                        "attribute_id": 4,
                        "attribute_name": "Weight",
                        "attribute_slug": "weight",
                        "value": "300-500g",
                        "slug": "300-500g",
                        "color_code": null,
                        "image": null,
                        "sort_order": 4,
                        "is_active": true
                      }
                    ]
                  },
                  {
                    "id": 2,
                    "sku": "SKU-1-2-G3xx",
                    "barcode": "1124001067602",
                    "base_price": 2113,
                    "final_price": 2113,
                    "discount_amount": 0,
                    "discount_percent": 0,
                    "stock": 47,
                    "weight": 266,
                    "is_active": 1,
                    "attributes": [
                      {
                        "id": 8,
                        "attribute_id": 1,
                        "attribute_name": "Color",
                        "attribute_slug": "color",
                        "value": "Pink",
                        "slug": "pink",
                        "color_code": "#fd79a8",
                        "image": null,
                        "sort_order": 8,
                        "is_active": true
                      },
                      {
                        "id": 29,
                        "attribute_id": 3,
                        "attribute_name": "Material",
                        "attribute_slug": "material",
                        "value": "Cotton",
                        "slug": "cotton",
                        "color_code": null,
                        "image": null,
                        "sort_order": 10,
                        "is_active": true
                      },
                      {
                        "id": 38,
                        "attribute_id": 4,
                        "attribute_name": "Weight",
                        "attribute_slug": "weight",
                        "value": "500-1000g",
                        "slug": "500-1000g",
                        "color_code": null,
                        "image": null,
                        "sort_order": 5,
                        "is_active": true
                      }
                    ]
                  },
                  {
                    "id": 3,
                    "sku": "SKU-1-3-vNii",
                    "barcode": "5389948668519",
                    "base_price": 3780,
                    "final_price": 3780,
                    "discount_amount": 0,
                    "discount_percent": 0,
                    "stock": 40,
                    "weight": 442,
                    "is_active": 1,
                    "attributes": [
                      {
                        "id": 7,
                        "attribute_id": 1,
                        "attribute_name": "Color",
                        "attribute_slug": "color",
                        "value": "Silver",
                        "slug": "silver",
                        "color_code": "#bdc3c7",
                        "image": null,
                        "sort_order": 7,
                        "is_active": true
                      },
                      {
                        "id": 29,
                        "attribute_id": 3,
                        "attribute_name": "Material",
                        "attribute_slug": "material",
                        "value": "Cotton",
                        "slug": "cotton",
                        "color_code": null,
                        "image": null,
                        "sort_order": 10,
                        "is_active": true
                      },
                      {
                        "id": 34,
                        "attribute_id": 4,
                        "attribute_name": "Weight",
                        "attribute_slug": "weight",
                        "value": "Under 100g",
                        "slug": "under-100g",
                        "color_code": null,
                        "image": null,
                        "sort_order": 1,
                        "is_active": true
                      }
                    ]
                  }
                ],
                "discounts": []
              }
            }

              return (
                <motion.div
                initial={{opacity:0 , y:20 , filter:'blur(10px)'}}
                animate={{ opacity:100 , y:0 , filter:'blur(0px)'}}
                transition={{ ease:'easeInOut', duration:0.5, delay: 0.1 * index  }}
                  className="product-card group rounded-[20px] bg-white border border-neutral-100 p-3"
                  key={ddd.id}
                >
                  <div className="rounded-[10px] w-full h-full border border-solid border-neutral-100 p-4 flex flex-col justify-between">
                    <div>
                      {/* تصویر محصول */}
                      <div className="w-full overflow-hidden rounded-[20px] aspect-[4/5] relative flex items-center justify-center">
                        <Image
                          src="/icons/images.jfif"
                          className="object-contain transform transition-transform duration-500 group-hover:scale-105"
                          width={200}
                          height={250}
                          alt={ddd?.title || "product"}
                          priority
                        />
                      </div>

                      {/* ⚡ عنوان محصول با کلاس متحرک‌سازی */}
                      <div className="flex justify-between items-start mt-5 gap-2">
                        <h3 className="font-bold text-sm text-neutral-800 line-clamp-2 leading-6 h-12 animate-text-split">
                          {ddd?.title}
                        </h3>
                        <div className="flex items-center shrink-0">
                          <ReactStars
                            count={5}
                            value={Number(ddd.rating) || 0}
                            size={18}
                            color2="#fbbf24"
                            edit={false}
                            half={true}
                          />
                        </div>
                      </div>
                    </div>

                    <div>
                      {/* ⚡ قیمت‌ها با کلاس متحرک‌سازی */}
                      <div
                        className="flex justify-between items-center mt-4"
                        dir="ltr"
                      >
                        <div className="flex items-center gap-2">
                          <p className="text-green-600 font-bold text-base animate-text-split">
                            ${price.toLocaleString()}
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

                      {/* ⚡ توضیحات کوتاه با کلاس متحرک‌سازی */}
                      <p className="w-full mt-3 line-clamp-2 text-xs text-neutral-500 leading-5 animate-text-split">
                        {ddd?.short_description}
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
