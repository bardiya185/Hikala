import ProductsDe from '@/components/templates/productDetails'
import React from 'react'

function ProductDetails({params}) {
    const {id} = params
  return (
    <div>
        <ProductsDe/>
    </div>
  )
}

export default ProductDetails