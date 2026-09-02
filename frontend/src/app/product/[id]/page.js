import Breadcrumb from "@/components/common/Breadcrumb";
import ProductsDe from "@/components/templates/productDetails";

async function getProductDetails(id) {
  try {
    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL;

    const response = await fetch(`${baseUrl}/api/products/${id}`, {
      cache: "no-store",
    });

    if (!response.ok) {
      console.error(
        "Product Details API Error:",
        response.status,
        response.statusText
      );

      return null;
    }

    const data = await response.json();

    return data?.data || null;
  } catch (error) {
    console.error("Error fetching product details:", error);

    return null;
  }
}

export default async function ProductDetails({ params }) {
  const { id } = await params;

  const product = await getProductDetails(id);

  if (!product) {
    return null;
  }

  const category = product?.categories?.[0];
  const parentCategory = category?.parent;

  const breadcrumbItems = [
    {
      title: "DigiKala",
      href: "/",
    },

    ...(parentCategory
      ? [
          {
            title: parentCategory.name,
            href: `/search/${parentCategory.slug}`,
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

    {
      title: product.title,
    },
  ];

  return (
    <div>
      <Breadcrumb items={breadcrumbItems} />

      <ProductsDe data={product} />
    </div>
  );
}