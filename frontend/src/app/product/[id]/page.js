import Breadcrumb from '@/components/common/Breadcrumb'
import ProductsDe from '@/components/templates/productDetails'
import React from 'react'


async function getProductDetails(id){
  const res = await fetch(process.env.NEXT_PUBLIC_BASE_URL + `/api/products/${id}`)

  const json = await res.json()
  return json?.data
}



export async function ProductDetails({params}) {
  const product = await getProductDetails(params.id) 
    const {id} = params
     const category = product?.categories?.[0];

  // دسته‌بندی والد
  const parentCategory = category?.parent;
  return (
    <div className=''>
      <Breadcrumb
        items={[
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
        ]}
      />
        <ProductsDe data={product} />
    </div>
  )
}

export default ProductDetails