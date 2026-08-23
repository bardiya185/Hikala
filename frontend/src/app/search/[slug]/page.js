import CategoryPage from "@/components/templates/CategoryPage";

async function getCategoryProducts(searchParams = {}, slug = "") {
  try {
   

    const {
      search,
      category_id,
      category_ids,
      sort_by,
      sort_order,
      brands,
      source,
      bannerId,
      banner_id,
      slug,

      
      min_price,
      max_price,

      
      available,
    } = searchParams;

    

    const sortBy = sort_by || "created_at";
    const sortOrder = sort_order || "desc";

    

    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL;

    const url = `${baseUrl}/api/products`;

    const params = new URLSearchParams();

   

    if (category_ids) {
      params.set("category_ids", String(category_ids));
    } else if (category_id) {
      params.set("category_id", String(category_id));
    }

    /*
    |--------------------------------------------------------------------------
    | BRAND
    |--------------------------------------------------------------------------
    */

    if (brands) {
      params.set("brand_id", String(brands));
    }
if (search) {
  params.set("search", String(search));
} else if (slug && !category_id && !category_ids) {
  params.set("search", decodeURIComponent(String(slug)));
}


    if (min_price !== undefined && min_price !== null && min_price !== "") {
      params.set("min_price", String(min_price));
    }

    if (max_price !== undefined && max_price !== null && max_price !== "") {
      params.set("max_price", String(max_price));
    }

   

    if (available === "1" || available === true) {
      params.set("available", "1");
    }

    

    const finalBannerId = bannerId || banner_id;

    if (source === "banner" || finalBannerId) {
      params.set("source", "banner");

      if (finalBannerId) {
        params.set("banner_id", String(finalBannerId));
      }
    }
    

  

    params.set("sort_by", String(sortBy));
    params.set("sort_order", String(sortOrder));

    

    params.set("per_page", "50");

    

    const finalUrl = `${url}?${params.toString()}`;

    console.log("PRODUCT API URL:", finalUrl);

  

    const res = await fetch(finalUrl, {
      cache: "no-store",
    });

    if (!res.ok) {
      console.error("Products API Error:", res.status, res.statusText);

      return [];
    }

    const data = await res.json();

   

    return data?.data || data || [];
  } catch (error) {
    console.error("Error fetching category products:", error);

    return [];
  }
}



export default async function SearchResultPage({ params, searchParams }) {
  const resolvedSearchParams = await searchParams;
  const resolvedParams = await params;


  const bannerId =
    resolvedSearchParams?.bannerId || resolvedSearchParams?.banner_id || null;

  const isFromBanner =
    Boolean(bannerId) || resolvedSearchParams?.source === "banner";

  

  const products = await getCategoryProducts({
  ...resolvedSearchParams,
  slug: resolvedParams?.slug,
});

  

  console.log("SEARCH PARAMS:", resolvedSearchParams);

  console.log("PRODUCT COUNT:", Array.isArray(products) ? products.length : 0);

  

  return (
    <div>
      <CategoryPage
        data={products}
        current_sort={resolvedSearchParams?.sort_by || "created_at"}
        current_sortorder={resolvedSearchParams?.sort_order || "desc"}
        category_id={resolvedSearchParams?.category_id || ""}
        isFromBanner={isFromBanner}
        bannerId={bannerId}
      />
    </div>
  );
}
