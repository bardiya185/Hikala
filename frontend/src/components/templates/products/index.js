"use client";
import Image from "next/image";
import React from "react";
import { SiLocalsend } from "react-icons/si";
import { MdStarRate } from "react-icons/md";
import { TfiAlignLeft } from "react-icons/tfi";
import { useRouter } from "next/navigation";
import { usePathname } from "next/navigation";
import { formatPrice } from "@/core/utils/formatPrice";
import ReactStars from "react-stars";

function Products({ data, current_sort, current_sortorder }) {
  const router = useRouter();
  const pathname = usePathname();
  console.log({ data });

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

  console.log(data);
  return (
    <>
    <div className="flex gap-3 pl-4">
      <div className="flex items-center gap-2">
      <TfiAlignLeft/>
      <p className="cursor-pointer">Sort:</p>

      </div>
      <button className="text-neutral-400" onClick={()=>handleSortChange("price","asc")}>thecheapset</button>
      <button className="text-neutral-400"  onClick={()=>handleSortChange("price","desc")}>themostexpensive</button>

      
    </div>
      <div className="max-w-[1270px] grid grid-cols-4 gap-3">
        {data?.map((ddd) => {
          const mainVariant = ddd?.variants?.[0];
          const price = mainVariant ? Number(mainVariant.price) : 0;
          const salePrice = mainVariant ? Number(mainVariant.sale_price) : 0;

          const discountPercent =
            price > 0 && salePrice < price
              ? Math.round(((price - salePrice) / price) * 100)
              : 0;

          return (
            <div
              className="group transform transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-lg rounded-[20px]"
              key={ddd.id}
            >
              <div className="w-full h-auto rounded-[20px]">
                <div className="w-full h-fit px-3 py-3 rounded-[20px]">
                  <div className="rounded-[10px] w-full h-full border border-solid px-4 py-4">
                    <div className="w-full overflow-hidden rounded-[20px]">
                      <Image
                        src="/icons/images.jfif"
                        className="w-50 flex self-center m-auto object-cover transform transition-transform duration-500 group-hover:scale-105 bg-none"
                        width={200}
                        height={250}
                        alt={ddd?.title || "product"}
                      />
                    </div>

                    <div className="flex justify-between items-center mt-5">
                      <p className="font-bold text-[15px] text-neutral-800 truncate max-w-[160px]">
                        {ddd?.title}
                      </p>
                      <div className="flex items-center gap-1 bg-amber-50 px-2 py-0.5 rounded">
                       <ReactStars 
                         count={5}
                         value={3.1}
                         size={24}
                         color2="#fbbf24"
                         edit={false}
                         half={true} />
                        
                      </div>
                    </div>

                    <div
                      className="flex justify-between items-center mt-3"
                      dir="ltr"
                    >
                      <div className="flex flex-col gap-0.5">
                        <p className="text-green-600 font-bold text-[15px]">
                          ${formatPrice(salePrice || price)}
                        </p>

                        {discountPercent > 0 && (
                          <span className="text-neutral-400 line-through text-[11px]">
                            ${formatPrice(price)}
                          </span>
                        )}
                      </div>

                      {discountPercent > 0 && (
                        <div className="flex items-center text-[11px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md">
                          <svg
                            className="w-3 h-3 fill-current mr-1"
                            viewBox="0 0 24 24"
                          >
                            <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" />
                          </svg>
                          {discountPercent}% Off
                        </div>
                      )}
                    </div>

                    <p className="w-full h-fit mt-3 line-clamp-2 text-[13px] text-neutral-500">
                      {ddd?.short_description}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </>
  );
}

export default Products;
