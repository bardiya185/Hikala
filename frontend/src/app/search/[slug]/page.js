import Breadcrumb from "@/components/common/Breadcrumb";
import CategoryPage from "@/components/templates/CategoryPage";

async function getCategoryProducts({
  slug: routeSlug = "",
  ...searchParams
} = {}) {
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
      min_price,
      max_price,
      available,
    } = searchParams;

    const sortBy = sort_by || "created_at";
    const sortOrder = sort_order || "desc";

    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL;
    const url = `${baseUrl}/api/products`;
    const params = new URLSearchParams();

    // Category
    if (category_ids) {
      params.set("category_ids", String(category_ids));
    } else if (category_id) {
      params.set("category_id", String(category_id));
    }

    // Brand
    if (brands) {
      params.set("brand_id", String(brands));
    }

    // Search
    if (search) {
      params.set("search", String(search));
    } else if (routeSlug && !category_id && !category_ids) {
      params.set("search", decodeURIComponent(String(routeSlug)));
    }

    // Price
    if (min_price !== undefined && min_price !== null && min_price !== "") {
      params.set("min_price", String(min_price));
    }

    if (max_price !== undefined && max_price !== null && max_price !== "") {
      params.set("max_price", String(max_price));
    }

    // Availability
    if (available === "1" || available === true) {
      params.set("available", "1");
    }

    // Banner
    const finalBannerId = bannerId || banner_id;

    if (source === "banner" || finalBannerId) {
      params.set("source", "banner");

      if (finalBannerId) {
        params.set("banner_id", String(finalBannerId));
      }
    }

    // Sorting & Pagination
    params.set("sort_by", sortBy);
    params.set("sort_order", sortOrder);
    params.set("per_page", "50");

    const response = await fetch(`${url}?${params.toString()}`, {
      cache: "no-store",
    });

    if (!response.ok) {
      console.error(
        "Products API Error:",
        response.status,
        response.statusText
      );

      return [];
    }

    const data = await response.json();

    return data?.data || data || [];
  } catch (error) {
    console.error("Error fetching category products:", error);

    return [];
  }
}

export default async function SearchResultPage({
  params,
  searchParams,
}) {
  const [resolvedParams, resolvedSearchParams] = await Promise.all([
    params,
    searchParams,
  ]);

  const bannerId =
    resolvedSearchParams?.bannerId ||
    resolvedSearchParams?.banner_id ||
    null;

  const isFromBanner =
    Boolean(bannerId) || resolvedSearchParams?.source === "banner";

  const products = await getCategoryProducts({
    ...resolvedSearchParams,
    slug: resolvedParams?.slug,
  });

  const category = products?.[0]?.categories?.[0];

  const breadcrumbItems = [
    {
      title: "Digikala",
      href: "/",
    },

    ...(category?.parent
      ? [
          {
            title: category.parent.name,
            href: `/search/${category.parent.slug}`,
          },
        ]
      : []),

    ...(category
      ? [
          {
            title: category.name,
            href: `/search/${category.slug}`,
          },
        ]
      : []),
  ];

  return (
    <div>
      <Breadcrumb items={breadcrumbItems} />

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