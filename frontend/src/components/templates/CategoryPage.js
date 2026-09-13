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
  const products = Array.isArray(data)
    ? data
    : Array.isArray(data?.data?.data)
      ? data.data.data
      : Array.isArray(data?.data)
        ? data.data
        : [];

  return (
    <div
      lang="en"
      dir="ltr"
      className="mx-auto w-full max-w-[1440px] px-3 py-4 sm:px-4 sm:py-6"
    >
      <div className="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-6">
        <aside className="min-w-0 lg:col-span-1">
          <DigikalaFilterSidebar products={products} />
        </aside>

        <main className="min-w-0 space-y-4 lg:col-span-3">
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