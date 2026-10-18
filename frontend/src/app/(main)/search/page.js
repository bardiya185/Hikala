import CategoryPage from "@/components/templates/CategoryPage";

async function getProducts(searchParams = {}) {
  const { sort_by, sort_order, search, category_id } = searchParams;

  const baseUrl =
    process.env.SERVER_BASE_URL || process.env.NEXT_PUBLIC_BASE_URL;

  const params = new URLSearchParams();
  params.set("sort_by", sort_by || "created_at");
  params.set("sort_order", sort_order || "desc");
  params.set("per_page", "50");
  if (search) params.set("search", String(search));
  if (category_id) params.set("category_id", String(category_id));

  try {
    const res = await fetch(`${baseUrl}/api/products?${params.toString()}`, {
      cache: "no-store",
    });
    if (!res.ok) return [];
    const data = await res.json();
    return data?.data || data || [];
  } catch (error) {
    console.error("Error fetching search products:", error);
    return [];
  }
}

export default async function SearchIndexPage({ searchParams }) {
  const sp = await searchParams;
  const products = await getProducts(sp);

  return (
    <CategoryPage
      data={products}
      current_sort={sp?.sort_by || "created_at"}
      current_sortorder={sp?.sort_order || "desc"}
      category_id={sp?.category_id || ""}
    />
  );
}
