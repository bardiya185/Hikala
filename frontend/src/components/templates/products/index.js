"use client";
import Image from "next/image";
import React from "react";
import { SiLocalsend } from "react-icons/si";
import { MdStarRate } from "react-icons/md";
import { TfiAlignLeft } from "react-icons/tfi";
import { useRouter } from "next/navigation";
import { usePathname } from "next/navigation";

function Products({ data, current_sort, current_sortorder }) {
  const router = useRouter();
  const pathname = usePathname();

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
      <div className="max-w-[1270px] grid grid-cols-4 gap-3">
        {data?.map((ddd) => (
          <div key={ddd.id}>
            <div className="w-full w-[300px] h-auto  rounded-[20px]">
              <div className="w-full h-fit px-3 py-3 rounded-[20px] ">
                <div className="  rounded-[10px] w-full h-full border border-solid  px-4 py-4  ">
                  <Image
                    src="/icons/images.jfif"
                    className="w-50 flex self-center m-auto object-cover rounded-[20px] bg-none "
                    width={200}
                    height={250}
                    alt="e"
                  />
                  <div className="flex justify-between">
                    <p className="mt-5">{ddd?.title}</p>
                    <div className="flex items-center gap-2">
                      <MdStarRate className="text-yellow-400" />
                      <span className="text-[12px] pt-1">4.5</span>
                    </div>
                  </div>
                  <div className="flex justify-between items-center">
                    <div className="flex items-center gap-2">
                      <p className="text-green-600">1400.00 $</p>
                      <span className="text-neutral-400 text-[11px]">
                        1200.00 $
                      </span>
                    </div>
                    <div className="flex items-center text-[12px]">
                      <span className=" flex items-center text-red-600 gap-2 ">
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                          <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" />
                        </svg>
                        20% Off
                      </span>
                    </div>
                  </div>
                  <p className="w-full h-fit mt-3 line-clamp-2">
                    {ddd?.short_description}
                  </p>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* <div className="flex gap-3 pl-4">
      <div className="flex items-center gap-2">
      <TfiAlignLeft/>
      <p className="cursor-pointer">Sort:</p>

      </div>
      <button className="text-neutral-400" onClick={()=>handleSortChange("price","asc")}>thecheapset</button>
      <button className="text-neutral-400"  onClick={()=>handleSortChange("price","desc")}>themostexpensive</button>

      
    </div> */}

      {/* <div className="max-w-[1270px] grid grid-cols-4  gap-2 gap-y-3  ">
      
      {data?.map((ddd) => (
        <div key={ddd.id} className=" ">
          <div className="w-[300px] h-full border border-solid border-neutral-400 rounded-md bg-slate-50 hover:bg-white ">
            <Image
              src="/icons/images.jfif"
              width={380}
              height={200}
              alt="p"
              className="w-[200px] h-auto mx-auto mt-10 "
              />
            <div className="pl-3 mt-9">
              <p>{ddd?.title}</p>
              <p className="mt-3 truncate">{ddd?.short_description}</p>
            </div>
            <div className="flex justify-between">
              <div className="flex gap-2 items-center px-3">
                <SiLocalsend className="w-[22px] h-[22px] text-blue-600 " />
                <p>ارسال سریع دیجی کالا</p>
              </div>
              <div className="flex items-center gap-2 pr-3">
                <MdStarRate className="w-[22px] h-[22px] text-yellow-400" />
                <p>3.5</p>
              </div>
            </div>
            <span className="mt-4 inline-block pl-3 ">{ddd?.variants?.[0]?.sale_price 
    ? Number(ddd?.variants?.[0]?.sale_price).toLocaleString() 
    : (ddd?.variants?.[0]?.price ? Number(ddd?.variants?.[0]?.price).toLocaleString() : "0")} $</span>
          </div>
        </div>
      ))}
    </div> */}
    </>
  );
}

export default Products;
