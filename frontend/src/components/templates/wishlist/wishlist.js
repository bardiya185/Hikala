// frontend/src/components/templates/wishlist/wishlist.js

"use client";

import React, { useMemo, useState } from "react";
import Link from "next/link";
import { motion } from "framer-motion";
import {
  ShoppingCart,
  Trash2,
  X,
  Heart,
  Loader2,
} from "lucide-react";

import { useGetWishlist } from "@/core/services/queries";
import { formatPrice } from "@/core/utils/formatPrice";
import { useAddToWishlist, useAddProductsBasket } from "@/core/services/mutations";
import { QueryClient, useQueryClient } from "@tanstack/react-query";
import toast from "react-hot-toast";

// ============================================================
// HELPERS
// ============================================================

function getProductPricing(product) {
  const variant = product?.variants?.[0] || {};
  const basePrice = Number(variant?.base_price || 0);
  const finalPrice = Number(variant?.final_price || 0);
  const discountPercent = Number(variant?.discount_percent || 0);
  const hasDiscount = finalPrice > 0 && basePrice > 0 && finalPrice < basePrice;

  return { basePrice, finalPrice, discountPercent, hasDiscount };
}

function hasFreeShipping(product) {
  return product?.free_shipping === true;
}

function ProductImage({ product, priority = false }) {
  const imageUrl = product?.images?.[0] || "/icons/test.webp";
  
  return (
    <div className="relative aspect-square w-full overflow-hidden rounded-xl bg-neutral-100">
      <img
        src={imageUrl}
        alt={product?.title || "Product"}
        className="h-full w-full object-cover"
        loading={priority ? "eager" : "lazy"}
      />
    </div>
  );
}

function ProductRating({ rating }) {
  if (!rating || rating === 0) return null;
  
  return (
    <div className="flex items-center gap-1 text-xs text-yellow-500">
      <span>⭐</span>
      <span>{rating.toFixed(1)}</span>
    </div>
  );
}

// ============================================================
// PRODUCT CARD
// ============================================================

function WishlistProductCard({ 
  product, 
  index, 
  onDelete, 
  onAddToCart,
  isAddingToCart 
}) {
  const { basePrice, finalPrice, discountPercent, hasDiscount } = getProductPricing(product);
  const freeShipping = hasFreeShipping(product);
  const [isAdding, setIsAdding] = useState(false);

  const handleAddToCart = async () => {
    if (isAdding) return;
    
    const variant = product?.variants?.[0];
    if (!variant) {
      toast.error("Product variant not available");
      return;
    }

    setIsAdding(true);
    await onAddToCart(product, variant);
    setIsAdding(false);
  };

  return (
    <motion.div
      initial={{ opacity: 0, y: 10 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{
        ease: "easeOut",
        duration: 0.3,
        delay: Math.min(index * 0.03, 0.3),
      }}
      className="group min-w-0 rounded-2xl border border-neutral-100 bg-white p-1.5 sm:rounded-[20px] sm:p-2 lg:p-3"
    >
      <div className="flex h-full flex-col justify-between rounded-xl border border-neutral-100 p-2 sm:rounded-[10px] sm:p-3 lg:p-4">
        <Link href={`/product/${product?.id}`} className="block">
          <ProductImage product={product} priority={index < 4} />

          <div className="mt-3 flex items-start justify-between gap-1.5 sm:mt-4 sm:gap-2 lg:mt-5">
            <h3 className="min-w-0 flex-1 overflow-hidden text-xs font-bold leading-5 text-neutral-800 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:2] sm:text-sm sm:leading-6">
              {product?.title}
            </h3>

            <ProductRating rating={product?.rating} />
          </div>
        </Link>

        <div dir="ltr" className="mt-3 flex flex-wrap items-center justify-between gap-2 sm:mt-4">
          <div className="flex min-w-0 flex-wrap items-center gap-1.5 sm:gap-2">
            <p className="text-sm font-bold text-green-600 sm:text-base">
              ${finalPrice}
            </p>

            {hasDiscount && (
              <span className="text-[10px] text-neutral-400 line-through sm:text-xs">
                ${formatPrice(basePrice)}
              </span>
            )}
          </div>

          <div className="flex flex-wrap items-center gap-1.5">
            {freeShipping && (
              <span className="rounded-full bg-green-50 px-2 py-1 text-[9px] font-medium text-green-600">
                Free Shipping
              </span>
            )}

            {hasDiscount && discountPercent > 0 && (
              <span className="rounded-full bg-red-50 px-2 py-1 text-[9px] font-medium text-red-600">
                {discountPercent}% OFF
              </span>
            )}
          </div>
        </div>

        <div className="mt-4 flex items-center gap-2">
          <button
            type="button"
            onClick={handleAddToCart}
            disabled={isAdding || isAddingToCart}
            className="flex h-10 flex-1 items-center justify-center gap-2 rounded-lg border border-red-600 bg-white px-3 text-xs font-bold text-red-600 transition-all hover:bg-red-600 hover:text-white disabled:cursor-not-allowed disabled:opacity-50 sm:text-sm"
          >
            {isAdding || isAddingToCart ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
                <span>Adding...</span>
              </>
            ) : (
              <>
                <ShoppingCart className="h-4 w-4" />
                <span>Add to Cart</span>
              </>
            )}
          </button>

          <button
            type="button"
            onClick={() => onDelete(product)}
            disabled={isAdding}
            className="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-neutral-300 bg-white text-neutral-500 transition-all hover:border-red-500 hover:text-red-500 disabled:cursor-not-allowed disabled:opacity-50"
            aria-label="Remove from wishlist"
          >
            <Trash2 className="h-4 w-4" />
          </button>
        </div>
      </div>
    </motion.div>
  );
}

// ============================================================
// DELETE MODAL
// ============================================================

function DeleteWishlistModal({ 
  product, 
  isOpen, 
  onClose, 
  onConfirm, 
  isDeleting 
}) {
  if (!isOpen || !product) return null;

  return (
    <div
      className="fixed inset-0 z-[999] flex items-center justify-center bg-black/40 px-4"
      onClick={onClose}
    >
      <motion.div
        initial={{ opacity: 0, scale: 0.95, y: 10 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        transition={{ duration: 0.2 }}
        onClick={(event) => event.stopPropagation()}
        className="w-full max-w-[430px] rounded-2xl bg-white p-5 shadow-2xl sm:p-6"
      >
        <div className="flex items-center justify-between">
          <h2 className="text-lg font-bold text-neutral-800">Remove from Wishlist</h2>

          <button
            type="button"
            onClick={onClose}
            disabled={isDeleting}
            className="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-800 disabled:cursor-not-allowed disabled:opacity-50"
            aria-label="Close"
          >
            <X className="h-5 w-5" />
          </button>
        </div>

        <div className="mx-2 my-4 border-t border-neutral-200" />

        <div className="text-center">
          <p className="text-sm leading-6 text-neutral-600">
            Are you sure you want to remove this product from your wishlist?
          </p>

          {product?.title && (
            <p className="mt-2 line-clamp-2 text-sm font-bold text-neutral-800">
              {product.title}
            </p>
          )}
        </div>

        <div className="mt-6 flex flex-col gap-2 sm:flex-row">
          <button
            type="button"
            disabled={isDeleting}
            onClick={onConfirm}
            className="flex h-11 flex-1 cursor-pointer items-center justify-center rounded-lg border border-red-600 bg-red-600 px-4 text-sm font-bold text-white transition-colors hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
          >
            {isDeleting ? (
              <>
                <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                Removing...
              </>
            ) : (
              "Remove Product"
            )}
          </button>

          <button
            type="button"
            disabled={isDeleting}
            onClick={onClose}
            className="flex h-11 flex-1 cursor-pointer items-center justify-center rounded-lg border border-red-600 bg-white px-4 text-sm font-bold text-red-600 transition-colors hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-60"
          >
            Cancel
          </button>
        </div>
      </motion.div>
    </div>
  );
}

// ============================================================
// MAIN WISHLIST COMPONENT
// ============================================================

export default function Wishlist() {
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
  const [isDeleting, setIsDeleting] = useState(false);
  const [addingToCartId, setAddingToCartId] = useState(null);

  const queryClient = useQueryClient();

  // Queries
  const { data: GetWishlist, isLoading, error } = useGetWishlist();
  
  // Mutations
  const { 
    mutate: toggleWishlist, 
    isPending: isTogglingWishlist 
  } = useAddToWishlist();

  const { 
    mutate: addToCart, 
    isPending: isAddingToCart 
  } = useAddProductsBasket();

  console.log("WISHLIST COMPONENT DATA:", GetWishlist);

  // Extract products from response
  const products = useMemo(() => {
    if (!GetWishlist) return [];

    if (Array.isArray(GetWishlist?.data)) return GetWishlist.data;
    if (Array.isArray(GetWishlist?.data?.data)) return GetWishlist.data.data;
    if (Array.isArray(GetWishlist?.wishlist)) return GetWishlist.wishlist;
    if (Array.isArray(GetWishlist?.data?.wishlist)) return GetWishlist.data.wishlist;
    if (Array.isArray(GetWishlist)) return GetWishlist;

    return [];
  }, [GetWishlist]);

  console.log("WISHLIST PRODUCTS:", products);

  // ============================================================
  // HANDLE DELETE FROM WISHLIST
  // ============================================================

  const handleDeleteClick = (product) => {
    setSelectedProduct(product);
    setIsDeleteModalOpen(true);
  };

  const handleConfirmDelete = () => {
    if (!selectedProduct) return;

    setIsDeleting(true);

    toggleWishlist(selectedProduct.id, {
      onSuccess: () => {
        toast.success("Removed from wishlist");
        queryClient.invalidateQueries({ queryKey: ["wishlist"] });
        handleCloseModal();
      },
      onError: (error) => {
        toast.error(error?.message || "Failed to remove from wishlist");
        setIsDeleting(false);
      },
      onSettled: () => {
        setIsDeleting(false);
      },
    });
  };

  const handleCloseModal = () => {
    setIsDeleteModalOpen(false);
    setSelectedProduct(null);
  };

  // ============================================================
  // HANDLE ADD TO CART
  // ============================================================

  const handleAddToCart = (product, variant) => {
    if (!variant) {
      toast.error("Product variant not available");
      return;
    }

    setAddingToCartId(product.id);

    addToCart(
      {
        product_variant_id: variant.id,
        quantity: 1,
      },
      {
        onSuccess: () => {
          toast.success("Added to basket successfully");
          // Optionally remove from wishlist after adding to cart
          // Or keep it in wishlist
        },
        onError: (error) => {
          toast.error(error?.message || "Failed to add to basket");
        },
        onSettled: () => {
          setAddingToCartId(null);
        },
      }
    );
  };

  // ============================================================
  // REFRESH WISHLIST
  // ============================================================
  queryClient.invalidateQueries({ queryKey: ["wishlist"] });
  const refreshWishlist = () => {
    queryClient.invalidateQueries({ queryKey: ["wishlist"] });
  };

  // ============================================================
  // LOADING
  // ============================================================

  if (isLoading) {
    return (
      <section className="w-full">
        <div className="rounded-2xl border border-neutral-200 bg-white p-4 sm:p-6">
          <div className="flex items-center justify-between">
            <div className="h-7 w-44 animate-pulse rounded bg-neutral-100" />
            <div className="h-7 w-7 animate-pulse rounded-full bg-neutral-100" />
          </div>
          <div className="mt-6 h-px w-full bg-neutral-200" />
          <div className="mt-6 grid grid-cols-2 gap-3 sm:gap-4 lg:gap-5">
            {[1, 2, 3, 4].map((item) => (
              <div key={item} className="h-[300px] animate-pulse rounded-2xl bg-neutral-100" />
            ))}
          </div>
        </div>
      </section>
    );
  }

  // ============================================================
  // ERROR
  // ============================================================

  if (error) {
    return (
      <section className="w-full">
        <div className="rounded-2xl border border-neutral-200 bg-white p-6 text-center">
          <Heart className="mx-auto h-12 w-12 text-neutral-300" />
          <h2 className="mt-3 text-base font-bold text-neutral-800">Unable to load wishlist</h2>
          <p className="mt-2 text-sm text-neutral-500">
            {error?.message || "Please try again later."}
          </p>
          <button
            type="button"
            onClick={refreshWishlist}
            className="mt-4 rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white transition-colors hover:bg-red-700"
          >
            Try Again
          </button>
        </div>
      </section>
    );
  }

  // ============================================================
  // EMPTY WISHLIST
  // ============================================================

  if (!products || products.length === 0) {
    return (
      <section className="w-full">
        <div className="rounded-2xl border border-neutral-200 bg-white p-4 sm:p-6">
          <h1 className="text-xl font-bold text-neutral-800 sm:text-2xl">Lists</h1>
          
          <div className="mt-5">
            <button
              type="button"
              className="relative pb-3 text-sm font-bold text-red-600"
            >
              Wishlist
              <span className="absolute bottom-0 left-0 h-[3px] w-full rounded-full bg-red-600" />
            </button>
          </div>

          <div className="border-t border-neutral-200" />

          <div className="flex min-h-[300px] flex-col items-center justify-center text-center">
            <Heart className="h-12 w-12 text-neutral-300" />
            <h2 className="mt-4 text-base font-bold text-neutral-800">Your wishlist is empty</h2>
            <p className="mt-2 text-sm text-neutral-500">
              Products you add to your wishlist will appear here.
            </p>
            <Link
              href="/"
              className="mt-4 rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white transition-colors hover:bg-red-700"
            >
              Start Shopping
            </Link>
          </div>
        </div>
      </section>
    );
  }

  // ============================================================
  // RENDER WISHLIST
  // ============================================================

  const isDeletingLoading = isTogglingWishlist || isDeleting;

  return (
    <>
      <section className="w-full">
        <div className="rounded-2xl border border-neutral-200 bg-white p-4 sm:p-6">
          <div className="flex items-center justify-between">
            <h1 className="text-xl font-bold text-neutral-800 sm:text-2xl">Lists</h1>
            
            <button
              type="button"
              onClick={refreshWishlist}
              disabled={isTogglingWishlist}
              className="flex items-center gap-2 text-sm text-neutral-500 transition-colors hover:text-neutral-700 disabled:opacity-50"
            >
              {isTogglingWishlist ? (
                <Loader2 className="h-4 w-4 animate-spin" />
              ) : (
                "Refresh"
              )}
            </button>
          </div>

          <div className="mt-5">
            <button
              type="button"
              className="relative pb-3 text-sm font-bold text-red-600"
            >
              Wishlist
              <span className="absolute bottom-0 left-0 h-[3px] w-full rounded-full bg-red-600" />
            </button>
          </div>

          <div className="border-t border-neutral-200" />

          <div className="mt-6 grid grid-cols-2 gap-3 sm:gap-4 lg:gap-5">
            {products.map((product, index) => (
              <WishlistProductCard
                key={product?.id || index}
                product={product}
                index={index}
                onDelete={handleDeleteClick}
                onAddToCart={handleAddToCart}
                isAddingToCart={addingToCartId === product?.id}
              />
            ))}
          </div>

          {isTogglingWishlist && !isDeleting && (
            <div className="mt-4 flex items-center justify-center gap-2 text-xs text-neutral-400">
              <Loader2 className="h-3 w-3 animate-spin" />
              Updating wishlist...
            </div>
          )}
        </div>
      </section>

      <DeleteWishlistModal
        product={selectedProduct}
        isOpen={isDeleteModalOpen}
        onClose={handleCloseModal}
        onConfirm={handleConfirmDelete}
        isDeleting={isDeletingLoading}
      />
    </>
  );
}