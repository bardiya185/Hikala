"use client";

import { useMemo, useCallback } from "react";
import { TbHeart, TbHeartFilled } from "react-icons/tb";
import { Loader2 } from "lucide-react"; // یا هر اسپینر سبک دیگر
import toast from "react-hot-toast";
import { useAddToWishlist } from "@/core/services/mutations";
import { useWishlistIds } from "@/core/services/queries";

/**
 * WishlistButton Component
 * @param {string|number} productId 
 * @param {boolean} [isInWishlist] 
 * @param {string} [className] 
 */
export default function WishlistButton({
  productId,
  isInWishlist: explicitIsInWishlist,
  className = "",
}) {
  const { data: wishlistIds = [], isLoading: isFetchingIds } = useWishlistIds();
  const { mutate, isPending: isMutating } = useAddToWishlist();

    const isLoading = isFetchingIds || isMutating;

   const isFav = useMemo(() => {
    if (explicitIsInWishlist !== undefined) return Boolean(explicitIsInWishlist);
    if (!productId) return false;

    const idsArray = Array.isArray(wishlistIds) ? wishlistIds : [];
    return idsArray.some((id) => String(id) === String(productId));
  }, [explicitIsInWishlist, wishlistIds, productId]);

  const handleWishlist = useCallback(
    (e) => {
      e.preventDefault();
      e.stopPropagation();
      
      if (!productId || isLoading) return;

      const previousState = isFav;

      mutate(productId, {
        onSuccess: () => {
          toast.success(
            previousState ? "Removed from Wishlist" : "Added to Wishlist",
            { id: `wishlist-${productId}` } 
          );
        },
        onError: (error) => {
          const errorMessage =
            error?.response?.data?.message || "Failed to update wishlist";
          toast.error(errorMessage, { id: `wishlist-err-${productId}` });
        },
      });
    },
    [productId, isLoading, isFav, mutate]
  );

  return (
<button
        type="button"
        onClick={handleWishlist}
        disabled={isLoading}
        aria-label={isFav ? "Remove from wishlist" : "Add to wishlist"}
        aria-pressed={isFav}
        className={`absolute top-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full 
        bg-[#f0f0f0b3] shadow-sm backdrop-blur-sm transition-all duration-300 
         hover:scale-95 active:scale-100
         ${className || "absolute top-2 right-2"}`}
    >
      {isMutating ? (
        <Loader2 className="h-4 w-4 animate-spin text-neutral-500" />
      ) : (
        <>
          <TbHeartFilled
            className={`
              h-5 w-5 text-red-500 transition-all duration-300
              ${isFav ? "scale-100 opacity-100" : "absolute scale-50 opacity-0"}
            `}
          />
         <TbHeart
            className={`
              h-5 w-5 text-neutral-600 transition-all duration-300 group-hover:text-red-500
              ${isFav ? "absolute scale-50 opacity-0" : "scale-100 opacity-100"}
            `}
          />
        </>
      )}
    </button>
  );
}