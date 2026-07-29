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
  return (
    <div>
        <ProductsDe data={product} />
    </div>
  )
}

export default ProductDetails