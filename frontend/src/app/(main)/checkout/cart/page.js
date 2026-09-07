import PlaceAnOrder from '@/components/Place an Order'
import CartDetails from '@/components/templates/cartDetails'

import React from 'react'

export default function Cart() {
  return (
    <div
      className="
        w-full
        min-h-screen

        mx-auto
    
        flex
        flex-col

        lg:flex-row
        lg:items-start
        lg:justify-center

        gap-4
        lg:gap-6
      "
    >
      {/* Products */}
      <CartDetails />

      {/* Order */}
      <PlaceAnOrder />
    </div>
  );
}
