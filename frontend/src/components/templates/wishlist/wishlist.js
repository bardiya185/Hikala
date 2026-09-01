"use client";

import React, { useMemo, useState } from "react";
import Link from "next/link";
import { motion } from "framer-motion";
import {
  ShoppingCart,
  Trash2,
  X,
  Heart,
} from "lucide-react";

import { useToggleWishlist } from "@/core/services/mutations";

import { ProductImage } from "../products";
import { ProductRating } from "../products";
import { getProductPricing } from "../products";
import { hasFreeShipping } from "../products";
import { useGetWishlist } from "@/core/services/queries";


// import {
//   ProductImage,
//   ProductRating,
//   getProductPricing,
//   hasFreeShipping,
//   formatPrice,
// } from "../products";

function WishlistProductCard({
  product,
  index,
  onDelete,
}) {
  const {
    basePrice,
    finalPrice,
    discountPercent,
    hasDiscount,
  } = getProductPricing(product);

  const freeShipping = hasFreeShipping(product);

  return (
    <motion.div
      initial={{
        opacity: 0,
        y: 10,
      }}
      animate={{
        opacity: 1,
        y: 0,
      }}
      transition={{
        ease: "easeOut",
        duration: 0.3,
        delay: Math.min(index * 0.03, 0.3),
      }}
      className="
        group
        min-w-0
        rounded-2xl
        border
        border-neutral-100
        bg-white
        p-1.5
        sm:rounded-[20px]
        sm:p-2
        lg:p-3
      "
    >
      <div
        className="
          flex
          h-full
          flex-col
          justify-between
          rounded-xl
          border
          border-neutral-100
          p-2
          sm:rounded-[10px]
          sm:p-3
          lg:p-4
        "
      >
        {/* PRODUCT */}

        <Link
          href={`/product/${product?.id}`}
          className="block"
        >
          <ProductImage
            product={product}
            priority={index < 4}
          />

          <div
            className="
              mt-3
              flex
              items-start
              justify-between
              gap-1.5
              sm:mt-4
              sm:gap-2
              lg:mt-5
            "
          >
            <h3
              className="
                min-w-0
                flex-1
                overflow-hidden
                text-xs
                font-bold
                leading-5
                text-neutral-800
                [display:-webkit-box]
                [-webkit-box-orient:vertical]
                [-webkit-line-clamp:2]
                sm:text-sm
                sm:leading-6
              "
            >
              {product?.title}
            </h3>

            <ProductRating
              rating={product?.rating}
            />
          </div>
        </Link>

        {/* PRICE */}

        <div
          dir="ltr"
          className="
            mt-3
            flex
            flex-wrap
            items-center
            justify-between
            gap-2
            sm:mt-4
          "
        >
          <div
            className="
              flex
              min-w-0
              flex-wrap
              items-center
              gap-1.5
              sm:gap-2
            "
          >
            <p
              className="
                text-sm
                font-bold
                text-green-600
                sm:text-base
              "
            >
              ${finalPrice}
            </p>

            {hasDiscount && (
              <span
                className="
                  text-[10px]
                  text-neutral-400
                  line-through
                  sm:text-xs
                "
              >
                ${formatPrice(basePrice)}
              </span>
            )}
          </div>

          <div
            className="
              flex
              flex-wrap
              items-center
              gap-1.5
            "
          >
            {freeShipping && (
              <span
                className="
                  rounded-full
                  bg-green-50
                  px-2
                  py-1
                  text-[9px]
                  font-medium
                  text-green-600
                "
              >
                Free Shipping
              </span>
            )}

            {hasDiscount &&
              discountPercent > 0 && (
                <span
                  className="
                    rounded-full
                    bg-red-50
                    px-2
                    py-1
                    text-[9px]
                    font-medium
                    text-red-600
                  "
                >
                  {discountPercent}% OFF
                </span>
              )}
          </div>
        </div>

        {/* ACTIONS */}

        <div
          className="
            mt-4
            flex
            items-center
            gap-2
          "
        >
          {/* ADD TO CART */}

          <Link
            href={`/product/${product?.id}`}
            className="
              flex
              h-10
              flex-1
              items-center
              justify-center
              gap-2
              rounded-lg
              border
              border-red-600
              bg-white
              px-3
              text-xs
              font-bold
              text-red-600
              transition-all
              hover:bg-red-600
              hover:text-white
              sm:text-sm
            "
          >
            <ShoppingCart className="h-4 w-4" />

            <span>
              Add to Cart
            </span>
          </Link>

          {/* DELETE */}

          <button
            type="button"
            onClick={() => onDelete(product)}
            className="
              flex
              h-10
              w-10
              shrink-0
              cursor-pointer
              items-center
              justify-center
              rounded-lg
              border
              border-neutral-300
              bg-white
              text-neutral-500
              transition-all
              hover:border-red-500
              hover:text-red-500
            "
            aria-label="Remove from wishlist"
          >
            <Trash2 className="h-4 w-4" />
          </button>
        </div>
      </div>
    </motion.div>
  );
}


/* =========================================================
   DELETE MODAL
========================================================= */

function DeleteWishlistModal({
  product,
  isOpen,
  onClose,
  onConfirm,
  isDeleting,
}) {
  if (!isOpen || !product) {
    return null;
  }

  return (
    <div
      className="
        fixed
        inset-0
        z-[999]
        flex
        items-center
        justify-center
        bg-black/40
        px-4
      "
      onClick={onClose}
    >
      <motion.div
        initial={{
          opacity: 0,
          scale: 0.95,
          y: 10,
        }}
        animate={{
          opacity: 1,
          scale: 1,
          y: 0,
        }}
        transition={{
          duration: 0.2,
        }}
        onClick={(event) =>
          event.stopPropagation()
        }
        className="
          w-full
          max-w-[430px]
          rounded-2xl
          bg-white
          p-5
          shadow-2xl
          sm:p-6
        "
      >
        {/* HEADER */}

        <div
          className="
            flex
            items-center
            justify-between
          "
        >
          <h2
            className="
              text-lg
              font-bold
              text-neutral-800
            "
          >
            Remove from Wishlist
          </h2>

          <button
            type="button"
            onClick={onClose}
            className="
              flex
              h-8
              w-8
              cursor-pointer
              items-center
              justify-center
              rounded-lg
              text-neutral-500
              transition-colors
              hover:bg-neutral-100
              hover:text-neutral-800
            "
            aria-label="Close"
          >
            <X className="h-5 w-5" />
          </button>
        </div>

        {/* SEPARATOR */}

        <div
          className="
            mx-2
            my-4
            border-t
            border-neutral-200
          "
        />

        {/* MESSAGE */}

        <div className="text-center">
          <p
            className="
              text-sm
              leading-6
              text-neutral-600
            "
          >
            Are you sure you want to remove this
            product from your wishlist?
          </p>

          {product?.title && (
            <p
              className="
                mt-2
                line-clamp-2
                text-sm
                font-bold
                text-neutral-800
              "
            >
              {product.title}
            </p>
          )}
        </div>

        {/* BUTTONS */}

        <div
          className="
            mt-6
            flex
            flex-col
            gap-2
            sm:flex-row
          "
        >
          <button
            type="button"
            disabled={isDeleting}
            onClick={onConfirm}
            className="
              flex
              h-11
              flex-1
              cursor-pointer
              items-center
              justify-center
              rounded-lg
              border
              border-red-600
              bg-red-600
              px-4
              text-sm
              font-bold
              text-white
              transition-colors
              hover:bg-red-700
              disabled:cursor-not-allowed
              disabled:opacity-60
            "
          >
            {isDeleting
              ? "Removing..."
              : "Remove Product"}
          </button>

          <button
            type="button"
            disabled={isDeleting}
            onClick={onClose}
            className="
              flex
              h-11
              flex-1
              cursor-pointer
              items-center
              justify-center
              rounded-lg
              border
              border-red-600
              bg-white
              px-4
              text-sm
              font-bold
              text-red-600
              transition-colors
              hover:bg-red-50
              disabled:cursor-not-allowed
              disabled:opacity-60
            "
          >
            Cancel
          </button>
        </div>
      </motion.div>
    </div>
  );
}


/* =========================================================
   WISHLIST
========================================================= */

export default function Wishlist() {
  const [selectedProduct, setSelectedProduct] =
    useState(null);

  const [isDeleteModalOpen, setIsDeleteModalOpen] =
    useState(false);

  /*
   * GET WISHLIST
   *
   * توجه:
   * useToggleWishlist در حال حاضر GET است.
   * بنابراین نباید به آن productId بدهیم.
   */

  const {
    data,
    isLoading,
    isFetching,
    isError,
    error,
  } =useGetWishlist(10)

  console.log(
    "WISHLIST COMPONENT DATA:",
    data
  );

  /*
   * Normalize API response
   */

  const products = useMemo(() => {
    if (!data) {
      return [];
    }

    // response.data
    if (Array.isArray(data?.data)) {
      return data.data;
    }

    // response.data.data
    if (Array.isArray(data?.data?.data)) {
      return data.data.data;
    }

    // response.wishlist
    if (Array.isArray(data?.wishlist)) {
      return data.wishlist;
    }

    // response.data.wishlist
    if (
      Array.isArray(data?.data?.wishlist)
    ) {
      return data.data.wishlist;
    }

    // خود response آرایه باشد
    if (Array.isArray(data)) {
      return data;
    }

    return [];
  }, [data]);

  console.log(
    "WISHLIST PRODUCTS:",
    products
  );

  /*
   * OPEN DELETE MODAL
   */

  const handleDeleteClick = (product) => {
    setSelectedProduct(product);
    setIsDeleteModalOpen(true);
  };

  /*
   * CLOSE MODAL
   */

  const handleCloseModal = () => {
    setIsDeleteModalOpen(false);
    setSelectedProduct(null);
  };

  /*
   * فعلاً حذف واقعی انجام نمی‌شود
   *
   * چون endpoint حذف wishlist را از بک‌اند نداریم.
   */

  const handleConfirmDelete = () => {
    console.log(
      "DELETE WISHLIST PRODUCT:",
      selectedProduct
    );

    handleCloseModal();
  };

  /*
   * LOADING
   */

  if (isLoading) {
    return (
      <section className="w-full">
        <div
          className="
            rounded-2xl
            border
            border-neutral-200
            bg-white
            p-4
            sm:p-6
          "
        >
          <div
            className="
              h-7
              w-44
              animate-pulse
              rounded
              bg-neutral-100
            "
          />

          <div
            className="
              mt-6
              h-px
              w-full
              bg-neutral-200
            "
          />

          <div
            className="
              mt-6
              grid
              grid-cols-2
              gap-3
              sm:gap-4
              lg:gap-5
            "
          >
            {[1, 2, 3, 4].map(
              (item) => (
                <div
                  key={item}
                  className="
                    h-[300px]
                    animate-pulse
                    rounded-2xl
                    bg-neutral-100
                  "
                />
              )
            )}
          </div>
        </div>
      </section>
    );
  }

  /*
   * ERROR
   */

  if (isError) {
    return (
      <section className="w-full">
        <div
          className="
            rounded-2xl
            border
            border-neutral-200
            bg-white
            p-6
            text-center
          "
        >
          <Heart
            className="
              mx-auto
              h-10
              w-10
              text-neutral-300
            "
          />

          <h2
            className="
              mt-3
              text-base
              font-bold
              text-neutral-800
            "
          >
            Unable to load wishlist
          </h2>

          <p
            className="
              mt-2
              text-sm
              text-neutral-500
            "
          >
            {error?.message ||
              "Please try again later."}
          </p>
        </div>
      </section>
    );
  }

  /*
   * MAIN
   */

  return (
    <>
      <section className="w-full">
        <div
          className="
            rounded-2xl
            border
            border-neutral-200
            bg-white
            p-4
            sm:p-6
          "
        >
          {/* TITLE */}

          <h1
            className="
              text-xl
              font-bold
              text-neutral-800
              sm:text-2xl
            "
          >
            Lists
          </h1>

          {/* WISHLIST TAB */}

          <div className="mt-5">
            <button
              type="button"
              className="
                relative
                cursor-pointer
                pb-3
                text-sm
                font-bold
                text-red-600
              "
            >
              Wishlist

              <span
                className="
                  absolute
                  bottom-0
                  left-0
                  h-[3px]
                  w-full
                  rounded-full
                  bg-red-600
                "
              />
            </button>
          </div>

          {/* LINE */}

          <div
            className="
              border-t
              border-neutral-200
            "
          />

          {/* PRODUCTS */}

          {products.length > 0 ? (
            <div
              className="
                mt-6
                grid
                grid-cols-2
                gap-3
                sm:gap-4
                lg:gap-5
              "
            >
              {products.map(
                (product, index) => (
                  <WishlistProductCard
                    key={
                      product?.id ||
                      index
                    }
                    product={product}
                    index={index}
                    onDelete={
                      handleDeleteClick
                    }
                  />
                )
              )}
            </div>
          ) : (
            <div
              className="
                flex
                min-h-[300px]
                flex-col
                items-center
                justify-center
                text-center
              "
            >
              <Heart
                className="
                  h-12
                  w-12
                  text-neutral-300
                "
              />

              <h2
                className="
                  mt-4
                  text-base
                  font-bold
                  text-neutral-800
                "
              >
                Your wishlist is empty
              </h2>

              <p
                className="
                  mt-2
                  text-sm
                  text-neutral-500
                "
              >
                Products you add to your
                wishlist will appear here.
              </p>
            </div>
          )}

          {/* FETCHING */}

          {isFetching &&
            !isLoading && (
              <div
                className="
                  mt-4
                  text-center
                  text-xs
                  text-neutral-400
                "
              >
                Updating wishlist...
              </div>
            )}
        </div>
      </section>

      {/* DELETE MODAL */}

      <DeleteWishlistModal
        product={selectedProduct}
        isOpen={isDeleteModalOpen}
        onClose={handleCloseModal}
        onConfirm={handleConfirmDelete}
        isDeleting={false}
      />
    </>
  );
}