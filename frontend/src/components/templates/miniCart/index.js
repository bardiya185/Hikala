"use client";
import { useRemoveCartItem, useUpdateCartItem } from "@/core/services/mutations";
import { useCart } from "@/core/services/queries";
import Image from "next/image";
import React from "react";
import toast from "react-hot-toast";
import { FaPlus, FaRegTrashAlt } from "react-icons/fa";

function MiniCart() {
    const { mutate: updateCartItem, isLoading: up } = useUpdateCartItem();
      const { mutate: removeCartItem } = useRemoveCartItem();
      const {data:ll} = useCart()
          console.log("ll",ll)
          const finalPrice = ll?.data?.summary
  const { data: cart, isLoading } = useCart();
  const items = cart?.data?.items || [];
  const product = cart?.data?.items[0]?.variant?.product || [];

   const handleDecrease = (item) => {
    if (item?.quantity === 1) {
      removeCartItem(item.id, {
        onSuccess: () => toast.success("Removed from cart"),
      });
      return;
    }
    updateCartItem({ itemId: item.id,  quantity: item.quantity - 1 });
  };
  if (isLoading) {
    return (
      <div className="absolute right-4 top-full mt-2 w-80 bg-white border border-gray-100 shadow-xl text-center text-xs text-gray-400 z-50">
        <p>در حال دریافت سبد خرید ...</p>
      </div>
    );
  }
  if (items.length === 0) {
    return (
      <div className="absolute  right-4 top-full mt-2 w-[400px] bg-white border border-gray-100 rounded-2xl shadow-xl p-6 text-center z-50">
        <div className="flex flex-col items-center gap-2">
          <p>سبد خرید شما خالی است</p>
        </div>
      </div>
    );
  }
  return (
    <div className="absolute  right-4 top-full mt-2 w-[550px] bg-white border border-gray-100 rounded-2xl shadow-xl p-6 text-center z-50">
      <div key={items.id} className=" flex items-center gap-2">
        <p>Your shopping cart summary</p>
        <span>{items?.length} item</span>
      </div>
      <div className="overflow-y-auto">

      {items?.map((item)=>(
        <>
      <div key={item.id} className="flex items-center">
        <Image
          src="/icons/miniimg.jpg"
          width={100}
          height={100}
          alt="minicart"
        />
        <p className="w-fit text-wrap text-sm">
         {product?.title}
        </p>
      </div>
        <div className="flex items-center justify-between ">
          <div className="w-[100px] flex items-center justify-center gap-3 h-[40px] border border-solid border-neutral-400 rounded-2xl">
            <button disabled={item.quantity === 1} >
              <FaPlus className="text-red-500" />
            </button>
            <span className=" text-red-500 text-center flex items-center">
              {item?.quantity}
            </span>
            <button onClick={()=>handleDecrease(item)}>
              <FaRegTrashAlt className="text-red-500" />
            </button>
          </div>
          <span className="text-green-500 text-lg">${item?.final_price}</span>
        </div>
        </>
      ))}
      
        <div className="flex items-center justify-between mt-7">
            <button className="w-[210px] h-[41px] bg-red-500 rounded-lg text-white text-center">
                Place an Order
            </button>
            <span className="text-green-500 text-lg">{finalPrice?.final_total}</span>
        </div>
      
    </div>
    </div>
  );
}

export default MiniCart;
