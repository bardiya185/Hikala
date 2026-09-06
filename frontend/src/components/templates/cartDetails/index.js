"use client";

import { useCart } from "@/core/services/queries";
import {
  useUpdateCartItem,
  useRemoveCartItem,
} from "@/core/services/mutations";
import toast from "react-hot-toast";
import CartItem from "../cart";
import Image from "next/image";
import Link from "next/link";
import { IoHome } from "react-icons/io5";

export default function CartDetails() {
  const { data: cart, isLoading } = useCart();

  const { mutate: updateCartItem, isPending } = useUpdateCartItem();
  const { mutate: removeCartItem } = useRemoveCartItem();

  const items = cart?.data?.items || [];

  const handleIncrease = (item) => {
    const stock = item?.variant?.stock ?? 1;

    if (item?.quantity >= stock) return;

    updateCartItem({
      itemId: item.id,
      quantity: item.quantity + 1,
    });
  };

  const handleDecrease = (item) => {
    if (item?.quantity === 1) {
      removeCartItem(item.id, {
        onSuccess: () => toast.success("Removed from cart"),
      });
      return;
    }

    updateCartItem({
      itemId: item.id,
      quantity: item.quantity - 1,
    });
  };

  const handleRemove = (item) => {
    removeCartItem(item.id, {
      onSuccess: () => toast.success("Removed from cart"),
    });
  };

  const grandTotal = items.reduce((sum, item) => {
    const price =
      item.product_variant?.price ?? item.product_variant?.final_price ?? 0;

    return sum + price * item.quantity;
  }, 0);
  if (isLoading) {
    return (
      <div className="w-full max-w-4xl mx-auto px-4 py-8 text-center text-gray-400">
        Loading cart...
      </div>
    );
  }
  if (items.length === 0) {
    return (
      <div className="w-full max-w-4xl mx-auto px-3 sm:px-4 py-4">
        <div className="w-full border border-neutral-200 rounded-xl overflow-hidden bg-white">
          {}
          <div className="border-b border-neutral-200 px-4 sm:px-6 py-4">
            <h1 className="text-base sm:text-lg font-semibold">
              Shopping Cart
            </h1>
          </div>

          {}
          <div className="flex flex-col items-center justify-center text-center px-4 py-12 sm:py-20">
            <Image
              src="/icons/hand-basket.svg"
              width={150}
              height={150}
              alt="basket"
              className="w-28 h-28 sm:w-36 sm:h-36"
            />

            <h1 className="mt-5 text-base sm:text-lg font-semibold">
              Your Digikala cart is empty!
            </h1>

            <p className="mt-4 max-w-md text-sm leading-6 text-neutral-400">
              If you visit the pages below, you’ll come away with plenty of
              valuable information.
            </p>

            <Link
              href="/"
              className="
                mt-6
                px-4
                py-2
                text-blue-500
                flex
                gap-2
                rounded-lg
                items-center
                justify-center
                border-2
                border-blue-500
                text-sm
                sm:text-base
                hover:bg-blue-50
                transition
              "
            >
              <IoHome />
              <span>Home Page</span>
            </Link>
          </div>
        </div>
      </div>
    );
  }
 return (
  <div
    className="
      w-full
      max-w-4xl
      mx-auto

      px-3
      sm:px-4
      md:px-6

      pt-1
      sm:pt-2

      pb-24
      lg:pb-6

      space-y-3
      sm:space-y-4

      font-sans
      dir-ltr
    "
  >
    {items.map((item) => (
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
