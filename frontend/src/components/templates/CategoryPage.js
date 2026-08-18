"use client";
import React from "react";
import DigikalaFilterSidebar from "./DigikalaFilterSidebar";
import Products from "./products";
import { useSearchParams } from "next/navigation";
import { usegetBrandsFilter } from "@/core/services/queries";

export default function CategoryPage({ data, current_sort, current_sortorder,brands,isFromBanner,bannerId }) {
  const searchParams = useSearchParams()
  const seletedBrands = searchParams.get("brands")||""
  const {data:productsData,isLoading} = usegetBrandsFilter()
  return (
    
    <div className="max-w-[1440px] mx-auto px-4 py-6" lang="en" dir="ltr">
      
      
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
      
        
        <aside className="lg:col-span-1">
          <DigikalaFilterSidebar products={data?.data?.data || data} />
        </aside>

        
        
        <main className="lg:col-span-3 space-y-4">
          <Products 
            data={data} 
            current_sort={current_sort} 
            current_sortorder={current_sortorder} 
            isFromBanner={isFromBanner}
            bannerId={bannerId}
          />
        </main>

      </div>
    </div>
  );
}