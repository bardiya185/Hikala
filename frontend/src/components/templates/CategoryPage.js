"use client";

import DigikalaFilterSidebar from "./DigikalaFilterSidebar";
import Products from "./products";


export default function CategoryPage({
  data,
  current_sort,
  current_sortorder,
  isFromBanner,
  bannerId,
  
}) {
  return (
    <div
      className="mx-auto max-w-[1440px] px-4 py-6"
      lang="en"
      dir="ltr"
    >
      <div className="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <aside className="lg:col-span-1">
          <DigikalaFilterSidebar
            products={data?.data?.data || data}
          />
        </aside>

        <main className="space-y-4 lg:col-span-3">
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