"use client"
import Image from "next/image";
import React from "react";
import { SiLocalsend } from "react-icons/si";
import { MdStarRate } from "react-icons/md";
import { TfiAlignLeft } from "react-icons/tfi";
import { useRouter } from "next/navigation";
import { usePathname } from "next/navigation";

function Products({ data,current_sort,current_sortorder, }) {
  const router = useRouter()
  const pathname = usePathname()

  const handleSortChange = (sort_by,sort_order)=>{
    const params = new URLSearchParams(window.location.search)

   

    if(sort_by&&sort_order){
    
      params.set("sort_by",sort_by)
      params.set("sort_order",sort_order)
      
    }else{
      params.delete("sort_by")
      params.delete("sort_order")
     
    }
    router.push(`${pathname}?${params.toString()}`)

  }




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
    
    <div className="max-w-[1270px] grid grid-cols-4  gap-2 gap-y-3  ">
      
      {data?.map((ddd) => (
        <div key={ddd.id} className=" ">
          <div className="w-[300px] h-full border border-solid border-neutral-400 rounded-md ">
            <Image
              src="/icons/images.jfif"
              width={380}
              height={200}
              alt="p"
              className="w-[200px] h-auto mx-auto mt-10"
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
    </div>
      </>
  );
}

export default Products;
