"use client";
import { useCart } from "@/core/services/queries";
import { useUpdateCartItem } from "@/core/services/mutations";
import { useRemoveCartItem } from "@/core/services/mutations";
import toast from "react-hot-toast";
import CartItem from "../cart";
import Image from "next/image";
import Link from "next/link";
import { IoHome } from "react-icons/io5";

export default function CartDetails() {
  const { data: cart, isLoading } = useCart();

  const { mutate: updateCartItem,isPending } = useUpdateCartItem();
  const { mutate: removeCartItem } = useRemoveCartItem();



 
  const items = cart?.data?.items || [];
  console.log("items-log",items)

  const handleIncrease = (item) => {
    console.log("increase item",item)
    const stock = item?.variant?.stock ?? 1;
    if (item?.quantity >= stock) return;
    updateCartItem({ itemId: item.id, quantity: item?.quantity + 1 });
  };

  const handleDecrease = (item) => {
    if (item?.quantity === 1) {
      removeCartItem(item.id, {
        onSuccess: () => toast.success("Removed from cart"),
      });
      return;
    }
    updateCartItem({ itemId: item.id,  quantity: item.quantity - 1 });
  };

  const handleRemove = (item) => {
    removeCartItem(item.id, {
      onSuccess: () => toast.success("Removed from cart"),
    });
  };

  const grandTotal = items.reduce((sum, item) => {
    const price = item.product_variant?.price ?? item.product_variant?.final_price ?? 0;
    return sum + price * item.quantity;
  }, 0);

  if (isLoading) {
    return (
      <div className="max-w-4xl mx-auto p-4 text-center text-gray-400">
        Loading cart...
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div className="w-[850px] h-fit mx-auto border border-solid border-neutral-400 rounded-lg">
        <div>
          <h1>Shopping Cart</h1>
        </div>
        <div className="flex flex-col  items-center pt-20 pb-20 ">
          <Image src="/icons/hand-basket.svg" className="flex " width={150} height={150} alt="basket" />
          <h1 className="mt-5">Your Digikala cart is empty!</h1>
          <p className="text-neutral-400 mt-5">If you visit the pages below, you’ll come away with plenty of valuable information.</p>
          <div className="flex mt-5 " >
            <Link  className=" px-3 py-1 text-blue-500 flex gap-2 rounded-lg text-center items-center border-2 border-solid border-blue-500 " href="/">
            <IoHome />
            Home Page

            </Link>

          </div>
        </div>


      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto p-4 space-y-4 font-sans dir-ltr">
      {items?.map((item) => (
        <CartItem
          key={item.id}
          item={item}
          onIncrease={() => handleIncrease(item)}
          onDecrease={() => handleDecrease(item)}
          onRemove={() => handleRemove(item)}
          isPending={isPending}
        />
      ))}

      
    </div>
  );
}