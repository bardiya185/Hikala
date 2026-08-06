"use client"
import { useCart } from '@/core/services/queries'
import React from 'react'
import { RiErrorWarningLine } from 'react-icons/ri'

function PlaceAnOrder() {

    const {data:ll} = useCart()
    const finalPrice = ll?.data?.summary
  return (
    <div className='w-[400px] border border-neutral-400 bg-neutral-100 rounded-lg mx-auto'>
        <p className='px-5 pt-5'>Payment details</p>
        <div className='flex justify-between px-5 mt-7 text-neutral-400'>
            <p>Total price of goods</p>
            <span>${finalPrice?.final_total}</span>
        </div>
        <div className='flex justify-between px-5 mt-5'>
            <p>Shopping Cart Total</p>
            <span>${finalPrice?.final_total}</span>

        </div>
        <div className='px-5 mt-5'>

        <button className='bg-red-500 text-white w-[350px] h-[41px] rounded-lg  '>Place an Order</button>
        </div>
        <div className='flex items-center px-5 mt-5 mb-5 gap-1 text-neutral-400 text-[12px]'>
            <RiErrorWarningLine className='flex-shrink-0' />
            <p>The order has not yet been paid for, and if items go out of stock, they will be removed from the cart.</p>

        </div>
    </div>
  )
}

export default PlaceAnOrder