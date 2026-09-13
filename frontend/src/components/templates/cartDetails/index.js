"use client";

import Image from "next/image";
import Link from "next/link";
import { IoHome } from "react-icons/io5";
import toast from "react-hot-toast";

import { useCart } from "@/core/services/queries";
import {
  useUpdateCartItem,
  useRemoveCartItem,
} from "@/core/services/mutations";

import CartItem from "../cart";

export default function CartDetails() {
  const { data: cart, isLoading } = useCart();

  const {
    mutate: updateCartItem,
    isPending: isUpdating,
  } = useUpdateCartItem();

  const {
    mutate: removeCartItem,
    isPending: isRemoving,
  } = useRemoveCartItem();

  const items = Array.isArray(cart?.data?.items)
    ? cart.data.items
    : [];

  const isPending = isUpdating || isRemoving;

  const getStock = (item) => {
    const stock =
      item?.product_variant?.stock ??
      item?.variant?.stock ??
      item?.stock ??
      0;

    const parsedStock = Number(stock);

    return Number.isFinite(parsedStock)
      ? Math.max(0, parsedStock)
      : 0;
  };

  const getQuantity = (item) => {
    const quantity = Number(item?.quantity ?? 0);

    return Number.isFinite(quantity)
      ? Math.max(0, quantity)
      : 0;
  };

  const handleIncrease = (item) => {
    if (!item?.id || isPending) {
      return;
    }

    const stock = getStock(item);
    const quantity = getQuantity(item);

    if (stock <= 0 || quantity >= stock) {
      return;
    }

    updateCartItem({
      itemId: item.id,
      quantity: quantity + 1,
    });
  };

  const handleDecrease = (item) => {
    if (!item?.id || isPending) {
      return;
    }

    const quantity = getQuantity(item);

    if (quantity <= 1) {
      removeCartItem(item.id, {
        onSuccess: () => {
          toast.success("Removed from cart");
        },
      });

      return;
    }

    updateCartItem({
      itemId: item.id,
      quantity: quantity - 1,
    });
  };

  const handleRemove = (item) => {
    if (!item?.id || isPending) {
      return;
    }

    removeCartItem(item.id, {
      onSuccess: () => {
        toast.success("Removed from cart");
      },
    });
  };

  if (isLoading) {
    return (
      <section
        aria-label="Loading shopping cart"
        className="
          w-full px-3 py-6
          sm:px-4 sm:py-8
          md:px-6
        "
      >
        <div className="mx-auto flex w-full max-w-4xl flex-col gap-3 sm:gap-4">
          {Array.from({ length: 3 }).map((_, index) => (
            <div
              key={index}
              className="
                h-36 w-full animate-pulse
                rounded-xl
                border border-neutral-200
                bg-neutral-100
                dark:border-neutral-800
                dark:bg-neutral-900
              "
            />
          ))}
        </div>
      </section>
    );
  }

  if (items.length === 0) {
    return (
      <section
        aria-labelledby="empty-cart-title"
        className="
          w-full px-3 py-4
          sm:px-4
          md:px-6
        "
      >
        <div
          className="
            mx-auto w-full max-w-4xl
            overflow-hidden rounded-2xl
            border border-neutral-200
            bg-white
            shadow-sm
            dark:border-neutral-800
            dark:bg-neutral-900
            dark:shadow-black/20
          "
        >
          {/* Header */}
          <div
            className="
              border-b border-neutral-200
              px-4 py-4
              sm:px-6
              dark:border-neutral-800
            "
          >
            <h1
              id="empty-cart-title"
              className="
                text-base font-bold
                text-neutral-900
                dark:text-neutral-100
                sm:text-lg
              "
            >
              Shopping Cart
            </h1>
          </div>

          {/* Empty State */}
          <div
            className="
              flex flex-col items-center
              justify-center
              px-4 py-12 text-center
              sm:py-20
            "
          >
            <div
              className="
                relative flex
                h-28 w-28
                items-center justify-center
                sm:h-36 sm:w-36
              "
            >
              <Image
                src="/icons/hand-basket.svg"
                width={144}
                height={144}
                alt="Empty shopping cart"
                className="h-full w-full object-contain"
              />
            </div>

            <h2
              className="
                mt-5 text-base font-bold
                text-neutral-900
                dark:text-neutral-100
                sm:text-lg
              "
            >
              Your Digikala cart is empty!
            </h2>

            <p
              className="
                mt-3 max-w-md
                text-sm leading-6
                text-neutral-500
                dark:text-neutral-400
              "
            >
              Your cart is currently empty.
              Start shopping and add your favorite
              products to your cart.
            </p>

            <Link
              href="/"
              className="
                mt-6 inline-flex min-h-11
                items-center justify-center
                gap-2 rounded-xl
                border border-red-500
                px-5 py-2.5
                text-sm font-semibold
                text-red-600
                transition-all duration-200
                hover:bg-red-50
                active:scale-[0.98]
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                focus-visible:ring-offset-2
                dark:border-red-400
                dark:text-red-400
                dark:hover:bg-red-950/30
                dark:focus-visible:ring-offset-neutral-900
                sm:text-base
              "
            >
              <IoHome
                aria-hidden="true"
                className="h-5 w-5"
              />

              <span>Home Page</span>
            </Link>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section
      dir="ltr"
      aria-label="Shopping cart"
      className="
        mx-auto w-full max-w-4xl
        space-y-3 px-3 pt-1
        pb-24 font-sans
        sm:space-y-4 sm:px-4 sm:pt-2
        md:px-6
        lg:pb-6
      "
    >
      {items.map((item) => {
        if (!item?.id) {
          return null;
        }

        return (
          <CartItem
            key={item.id}
            item={item}
            onIncrease={() => handleIncrease(item)}
            onDecrease={() => handleDecrease(item)}
            onRemove={() => handleRemove(item)}
            isPending={isPending}
          />
        );
      })}
    </section>
  );
}