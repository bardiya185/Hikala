import Products from "@/components/Products";

// ✅ تابع گرفتن محصولات از API
async function getProducts(searchParams) {
  const params = new URLSearchParams();

  // اضافه کردن همه پارامترها به URL
  if (searchParams.category_id) params.set('category_id', searchParams.category_id);
  if (searchParams.brand_id) params.set('brand_id', searchParams.brand_id);
  if (searchParams.min_discount) params.set('min_discount', searchParams.min_discount);
  if (searchParams.max_discount) params.set('max_discount', searchParams.max_discount);
  if (searchParams.has_discount) params.set('has_discount', searchParams.has_discount);
  if (searchParams.min_price) params.set('min_price', searchParams.min_price);
  if (searchParams.max_price) params.set('max_price', searchParams.max_price);
  if (searchParams.search) params.set('search', searchParams.search);
  if (searchParams.sort_by) params.set('sort_by', searchParams.sort_by);
  if (searchParams.sort_order) params.set('sort_order', searchParams.sort_order);
  if (searchParams.page) params.set('page', searchParams.page);

  try {
    const res = await fetch(
      `http://localhost:8000/api/products?${params.toString()}`,
      { cache: 'no-store' }
    );

    if (!res.ok) {
      console.error('API Error:', res.status);
      return { data: [], meta: null };
    }

    return await res.json();
  } catch (error) {
    console.error('Fetch failed:', error);
    return { data: [], meta: null };
  }
}

export default async function ProductsPage({ searchParams }) {
  // ✅ در Next.js 15 باید await کنی
  const params = await searchParams;
  
  const result = await getProducts(params);
  const products = result.data || [];

  // ✅ متن هدر بر اساس فیلتر
  const getPageTitle = () => {
    if (params.min_discount) {
      return `🔥 Products with ${params.min_discount}%+ Off`;
    }
    if (params.category_id) {
      return '📦 Category Products';
    }
    if (params.brand_id) {
      return '🏷️ Brand Products';
    }
    return '🛍️ All Products';
  };

  return (
    <div className="max-w-[1270px] mx-auto py-8 px-4" dir="rtl">
      {/* هدر */}
      <div className="mb-8 text-center">
        <h1 className="text-3xl font-bold text-neutral-800">
          {getPageTitle()}
        </h1>
        {result.meta?.total !== undefined && (
          <p className="text-neutral-600 mt-2">
            {result.meta.total} products found
          </p>
        )}
      </div>

      {/* محصولات */}
      {products.length > 0 ? (
        <Products
          data={products}
          current_sort={params.sort_by}
          current_sortorder={params.sort_order}
        />
      ) : (
        <div className="text-center py-16">
          <p className="text-neutral-500 text-lg">
            😔 No products found with these filters
          </p>
          <a
            href="/products"
            className="mt-4 inline-block text-blue-600 hover:underline"
          >
            View all products
          </a>
        </div>
      )}
    </div>
  );
}