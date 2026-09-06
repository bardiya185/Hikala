"use client";

import { useMemo } from "react";
import { TbHeart, TbHeartFilled } from "react-icons/tb";
import { useAddToWishlist } from "@/core/services/mutations";
import { useWishlistIds } from "@/core/services/queries";
import toast from "react-hot-toast";


export default function WishlistButton({
    productId,
    isInWishlist: explicitIsInWishlist,
    className = "",
}) {
    const { data: wishlistIds = [] } = useWishlistIds();
    const { mutate, isPending , isError } = useAddToWishlist();

    // بررسی دقیق و هوشمندانه وضعیت لایک
    const isFav = useMemo(() => {
        // ۱. اگر از بیرون مقدار صریح داده شده باشد
        if (explicitIsInWishlist !== undefined) return Boolean(explicitIsInWishlist);

        // ۲. اگر productId وجود ندارد
        if (!productId) return false;

        // ۳. چک کردن در لیست با تبدیل اجباری همه IDها به String
        const idsArray = Array.isArray(wishlistIds) ? wishlistIds : [];
        return idsArray.some((id) => String(id) === String(productId));
    }, [explicitIsInWishlist, wishlistIds, productId]);

    const handleWishlist = (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (!productId || isPending) return;
        mutate(productId);
        if (isError) {
            toast.error("Failed to update wishlist");
        } else if (isFav) {
            toast.success("Removed from Wishlist");
        } else {
            toast.success("Added to Wishlist");
        }

    };
    return (
        <button
            type="button"
            onClick={handleWishlist}
            disabled={isPending}
            className={`absolute top-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full 
            bg-[#f0f0f0b3] shadow-sm backdrop-blur-sm transition-all duration-300 
            hover:text-red-500 hover:scale-95 active:scale-100
            ${isPending ? "opacity-75 hover:scale-100 bg-[#bfbfbfb3]" : "hover:bg-[#f0f0f0b3]"}`}
        >
            {/* حالت پر شده (HeartFilled) */}
            <TbHeartFilled
                className={`absolute h-5 w-5 text-red-500 transition-opacity duration-300 
                ${isFav ? "opacity-100" : "opacity-0"}`}
            />

            {/* حالت خالی (Heart) */}
            <TbHeart
                className={`absolute h-5 w-5 transition-opacity duration-300 
                ${isFav ? "opacity-0" : "opacity-100"}`}
            />
        </button>
    );
}