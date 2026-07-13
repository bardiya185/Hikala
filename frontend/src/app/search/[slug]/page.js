import Products from "@/components/templates/products";


async function getCategoryProducts(category_id,sort_by,sort_order) {
  try {
    
    const sortByParam = sort_by || "price";
    const sortOrderParam = sort_order || "desc";
    let url = `${process.env.NEXT_PUBLIC_BASE_URL}/api/products/infinite?category_id=${category_id}`;
    if(category_id,sort_by&&sort_order){
        url+= `&sort_by=${sort_by}&sort_order=${sort_order}`
    }
   
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

export default async function searchResultPage({ params,searchParams }) {
    const{category_id,sort_by,sort_order} = await searchParams
    console.log(params)
  const { slug, id } = await params;
  const products = await getCategoryProducts(category_id,sort_by,sort_order);

  return (
    <div>
      <Products data={products} current_sort={sort_by||""} current_sortorder={sort_order||""}category_id={category_id||""} />
    </div>
  );
}