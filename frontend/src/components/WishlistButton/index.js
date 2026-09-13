"use client";

import { useMemo } from "react";
import { TbHeart, TbHeartFilled } from "react-icons/tb";
import toast from "react-hot-toast";

import { useAddToWishlist } from "@/core/services/mutations";
import { useWishlistIds } from "@/core/services/queries";

export default function WishlistButton({
  productId,
  isInWishlist: explicitIsInWishlist,
  className = "",
}) {
  const { data: wishlistIds = [], isLoading } = useWishlistIds();
  const { mutate, isPending } = useAddToWishlist();

  const isFav = useMemo(() => {
    if (explicitIsInWishlist !== undefined) {
      return Boolean(explicitIsInWishlist);
    }

    if (!productId) {
      return false;
    }

    const idsArray = Array.isArray(wishlistIds)
      ? wishlistIds
      : [];

    return idsArray.some(
      (id) => String(id) === String(productId)
    );
  }, [explicitIsInWishlist, wishlistIds, productId]);

  const handleWishlist = (event) => {
    event.preventDefault();
    event.stopPropagation();

    if (!productId || isPending || isLoading) {
      return;
    }

    mutate(productId, {
      onSuccess: () => {
        toast.success(
          isFav
            ? "Removed from Wishlist"
            : "Added to Wishlist"
        );
      },
      onError: () => {
        toast.error("Failed to update wishlist");
      },
    });
  };

  const isDisabled = isPending || isLoading;

  return (
    <button
      type="button"
      onClick={handleWishlist}
      disabled={isDisabled}
      aria-label={
        isFav
          ? "Remove from wishlist"
          : "Add to wishlist"
      }
      aria-pressed={isFav}
      className={`
        absolute right-2 top-2 z-10
        flex h-8 w-8 items-center justify-center
        rounded-full
        bg-white/75
        text-neutral-700
        shadow-sm
        backdrop-blur-md
        transition-all duration-200
        hover:scale-95
        hover:bg-white
        hover:text-red-500
        active:scale-100
        focus:outline-none
        focus-visible:ring-2
        focus-visible:ring-red-500
        focus-visible:ring-offset-2
        disabled:cursor-not-allowed
        disabled:opacity-60
        dark:bg-neutral-900/75
        dark:text-neutral-200
        dark:hover:bg-neutral-800
        dark:hover:text-red-400
        dark:focus-visible:ring-red-400
        dark:focus-visible:ring-offset-neutral-900
        ${className}
      `}
    >
      <TbHeartFilled
        aria-hidden="true"
        className={`
          absolute h-5 w-5 text-red-500
          transition-all duration-200
          dark:text-red-400
          ${isFav
            ? "scale-100 opacity-100"
            : "scale-75 opacity-0"}
        `}
      />

      <TbHeart
        aria-hidden="true"
        className={`
          absolute h-5 w-5
          transition-all duration-200
          ${isFav
            ? "scale-75 opacity-0"
            : "scale-100 opacity-100"}
        `}
      />

      {isPending && (
        <span
          aria-hidden="true"
          className="
            absolute inset-0
            animate-pulse rounded-full
            bg-white/30
            dark:bg-black/20
          "
        />
      )}
    </button>
  );
}