import CategoryPage from "@/components/templates/CategoryPage";
import Products from "@/components/templates/products";

async function getCategoryProducts(category_id, sort_by, sort_order,brands) {
  try {
    
    const sortBy = sort_by || "created_at";
    const sortOrder = sort_order || "asc";

    // ساخت URL
    let url = `${process.env.NEXT_PUBLIC_BASE_URL}/api/search?`;

    
    const params = new URLSearchParams();

    if (category_id) {
      params.append("category_id", category_id);
    }
    if(brands){
      params.append("brand_id",brands)
    }  
    params.append("sort_by", sortBy);
    params.append("sort_order", sortOrder);
    params.append("per_page", 50);

    url += params.toString();

    const res = await fetch(url, {
      cache: "no-store",
    });

    if (!res.ok) {
      throw new Error("failed");
    }

    const data = await res.json();
    return data?.data || data || [];
  } catch (error) {
    console.error("Error fetching..", error);
    return [];
  }
}

export default async function SearchResultPage({ params, searchParams }) {

  const { category_id, sort_by, sort_order,brands } = await searchParams;
  const { slug, id } = await params;


  const products = await getCategoryProducts(category_id, sort_by, sort_order,brands);

  return (
    <div>
      <CategoryPage
        data={products}
        current_sort={sort_by || "created_at"}
        current_sortorder={sort_order || "desc"}
        category_id={category_id || ""}
      />
    </div>
  );
}