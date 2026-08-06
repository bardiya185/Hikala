import PlaceAnOrder from '@/components/Place an Order'
import CartDetails from '@/components/templates/cartDetails'

import React from 'react'

export default function Cart() {
  return (
    <div className='flex items-center mx-auto'>
        <CartDetails/>
        <PlaceAnOrder/>
    </div>
  )
}
