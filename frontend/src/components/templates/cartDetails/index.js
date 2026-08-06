"use client";
import { useCart } from "@/core/services/queries";
import { useUpdateCartItem } from "@/core/services/mutations";
import { useRemoveCartItem } from "@/core/services/mutations";
import toast from "react-hot-toast";
import CartItem from "../cart";

export default function CartDetails() {
  const { data: cart, isLoading } = useCart();

  const { mutate: updateCartItem } = useUpdateCartItem();
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
    updateCartItem({ itemId: item.id,  quantity: item.quantity + 1 });
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
      <div className="max-w-4xl mx-auto p-4 text-center text-gray-400 py-16">
        Your cart is empty.
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
        />
      ))}

      
    </div>
  );
}