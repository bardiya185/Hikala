"use client";

import Image from "next/image";
import React, { useState, useEffect } from "react";
import ProductReviewModal from "@/components/ProductReviewModal";

import { FcRating } from "react-icons/fc";
import { TbBrandSpeedtest } from "react-icons/tb";
import { FaFire } from "react-icons/fa6";
import { VscCopilotSuccess } from "react-icons/vsc";
import { IoWarningOutline } from "react-icons/io5";
import { FaRegStar, FaHeart } from "react-icons/fa";
import { BsDot } from "react-icons/bs";
import { gsap } from "gsap";
import { SplitText } from "gsap/SplitText";

import ColorSwatchSelector from "@/components/atom/ColorSwatchSelector";
import ViewDetailsButton from "@/components/atom/ViewDetailsButton";
import ProductMoreDetials from "@/components/organisms/ProductMoreDetials";

import {
  useAddProductsBasket,
  useRemoveCartItem,
  useUpdateCartItem,
  useAddToWishlist,
} from "@/core/services/mutations";

import { useCart, useWishlistIds } from "@/core/services/queries";

import toast from "react-hot-toast";

import {
  Minus,
  Plus,
  Trash2,
} from "lucide-react";

import { RotatingLines } from "react-loader-spinner";

import { formatPrice } from "@/core/utils/formatPrice";
import WishlistButton from "@/components/WishlistButton";
import { useParams } from "next/navigation";
import { useMemo } from "react";
import Link from "next/link";

gsap.registerPlugin(SplitText);

/* =========================================================
   HELPERS
========================================================= */

function getDefaultVariant(variants) {
  if (
    !Array.isArray(variants) ||
    variants.length === 0
  ) {
    return null;
  }

  return (
    variants.find(
      (variant) =>
        variant?.is_default === true &&
        variant?.is_active === true,
    ) ||
    variants.find(
      (variant) => variant?.is_active === true,
    ) ||
    variants[0]
  );
}

function getVariantPrice(variant) {
  return {
    basePrice: Number(
      variant?.base_price || 0,
    ),

    finalPrice: Number(
      variant?.final_price || 0,
    ),

    discountPercent: Number(
      variant?.discount_percent || 0,
    ),
  };
}

/* =========================================================
   MAIN COMPONENT
========================================================= */


function ProductsDe({ data }) {


  /* =======================================================
     PRODUCT DATA
  ======================================================= */

  const variants = Array.isArray(data?.variants)
    ? data.variants
    : [];



  const [selectedVariant, setSelectedVariant] =
    useState(() =>
      getDefaultVariant(variants),
    );

  const [quantity, setQuantity] =
    useState(1);

  /* =======================================================
     WISHLIST
  ======================================================= */

  const [isFavorite, setIsFavorite] =
    useState(false);

    const {
      mutate: toggleWishlist, 
      isPending: isWishlistLoading,
    } = useAddToWishlist();

  /* =======================================================
     CART
  ======================================================= */

  const { data: cart } = useCart();

  const {
    data: addedProduct,
    isPending: isAdding,
    mutate: addProductToCart,
  } = useAddProductsBasket();

  const {
    mutate: updateCartItem,
    isPending: isUpdating,
  } = useUpdateCartItem();

  const {
    mutate: removeCartItem,
    isPending: isRemoving,
  } = useRemoveCartItem();

  /* =======================================================
     CART ITEMS
  ======================================================= */

  const cartItems = Array.isArray(
    cart?.data?.items,
  )
    ? cart.data.items
    : [];

  const cartItem = useMemo(() => {
    if (!selectedVariant?.id) {
      return null;
    }

    return (
      cartItems.find(
        (item) =>
          item?.variant?.id ===
          selectedVariant.id,
      ) || null
    );
  }, [
    cartItems,
    selectedVariant,
  ]);

  /* =======================================================
     STOCK
  ======================================================= */

  const stock = Number(
    selectedVariant?.stock || 0,
  );

  /* =======================================================
     PRICE
  ======================================================= */

  const {
    basePrice,
    finalPrice,
    discountPercent,
  } = useMemo(() => {
    return getVariantPrice(
      selectedVariant,
    );
  }, [selectedVariant]);

  const hasDiscount =
    finalPrice > 0 &&
    basePrice > 0 &&
    finalPrice < basePrice;

  /* =======================================================
     ATTRIBUTES
  ======================================================= */

  const attributes = Array.isArray(
    selectedVariant?.attributes,
  )
    ? selectedVariant.attributes
    : [];

  /* =======================================================
     WISHLIST
  ======================================================= */

  const handleToggleWishlist = () => {
    if (!data?.id) {
      toast.error("Product not found");
      return;
    }

    if (isWishlistLoading) {
      return;
    }

    toggleWishlist(data.id, {
      onSuccess: (response) => {
        console.log(
          "Wishlist success:",
          response,
        );

        /*
         * Toggle local state
         */
        setIsFavorite(
          (previousState) =>
            !previousState,
        );

        /*
         * چون state در همین لحظه هنوز
         * مقدار قبلی را دارد، از آن برای
         * پیام استفاده می‌کنیم.
         */
        if (isFavorite) {
          toast.success(
            "Removed from wishlist",
          );
        } else {
          toast.success(
            "Added to wishlist",
          );
        }
      },

      onError: (error) => {
        console.error(
          "Wishlist error:",
          error,
        );

        toast.error(
          error?.message ||
            "Failed to update wishlist",
        );
      },
    });
  };

  /* =======================================================
     SELECT VARIANT
  ======================================================= */

  const handleSelectVariant = (
    variant,
  ) => {
    if (!variant) {
      return;
    }

    setSelectedVariant(variant);

    const variantStock = Number(
      variant?.stock || 0,
    );

    setQuantity(
      variantStock > 0 ? 1 : 0,
    );
  };

  /* =======================================================
     QUANTITY SYNC
  ======================================================= */

  useEffect(() => {
    if (!selectedVariant) {
      setQuantity(0);
      return;
    }

    const currentStock = Number(
      selectedVariant?.stock || 0,
    );

    if (currentStock <= 0) {
      setQuantity(0);
      return;
    }

    setQuantity(
      (currentQuantity) => {
        if (currentQuantity <= 0) {
          return 1;
        }

        if (
          currentQuantity >
          currentStock
        ) {
          return currentStock;
        }

        return currentQuantity;
      },
    );
  }, [selectedVariant]);

  /* =======================================================
     ADD TO CART
  ======================================================= */

  const handleAddToCart = () => {
    if (!data) {
      toast.error(
        "Product not found",
      );
      return;
    }

    if (!selectedVariant) {
      toast.error(
        "Please select a variant",
      );
      return;
    }

    if (stock <= 0) {
      toast.error(
        "This product is out of stock",
      );
      return;
    }

    if (quantity <= 0) {
      toast.error(
        "Invalid quantity",
      );
      return;
    }

    if (quantity > stock) {
      toast.error(
        "Not enough stock",
      );
      return;
    }

    addProductToCart(
      {
        product_variant_id:
          selectedVariant.id,

        quantity,
      },
      {
        onSuccess: () => {
          toast.success(
            "Added to basket successfully",
          );
        },

        onError: (error) => {
          console.error(
            "Add to cart error:",
            error,
          );

          toast.error(
            "Could not add product to basket",
          );
        },
      },
    );
  };

  /* =======================================================
     INCREASE CART QUANTITY
  ======================================================= */

  const handleIncrease = () => {
    if (
      !cartItem ||
      !selectedVariant
    ) {
      return;
    }

    const currentQuantity =
      Number(
        cartItem.quantity || 0,
      );

    const currentStock =
      Number(
        selectedVariant.stock || 0,
      );

    if (
      currentQuantity >=
      currentStock
    ) {
      toast.error(
        "No more items available",
      );

      return;
    }

    updateCartItem({
      itemId: cartItem.id,
      quantity:
        currentQuantity + 1,
    });
  };

  /* =======================================================
     DECREASE CART QUANTITY
  ======================================================= */

  const handleDecrease = () => {
    if (!cartItem) {
      return;
    }

    const currentQuantity =
      Number(
        cartItem.quantity || 0,
      );

    if (currentQuantity <= 1) {
      removeCartItem(
        cartItem.id,
      );

      return;
    }

    updateCartItem({
      itemId: cartItem.id,
      quantity:
        currentQuantity - 1,
    });
  };

  /* =======================================================
     GSAP TEXT ANIMATION
  ======================================================= */

  useEffect(() => {
    const elements =
      document.querySelectorAll(
        ".animate-split",
      );

    if (!elements.length) {
      return;
    }

    const splits = [];

    elements.forEach(
      (element) => {
        try {
          const split =
            new SplitText(
              element,
              {
                type: "words",
                wordsClass:
                  "inline-block overflow-hidden",
              },
            );

          splits.push(split);

          gsap.from(
            split.words,
            {
              duration: 0.8,
              y: 20,
              opacity: 0,
              stagger: 0.08,
              ease:
                "back.out(1.7)",
            },
          );
        } catch (error) {
          console.error(
            "GSAP SplitText error:",
            error,
          );
        }
      },
    );

    return () => {
      splits.forEach(
        (split) => {
          try {
            split.revert();
          } catch (error) {
            console.error(
              "GSAP cleanup error:",
              error,
            );
          }
        },
      );
    };
  }, []);

  /* =======================================================
     LOADING
  ======================================================= */

  const isCartUpdating =
    isUpdating ||
    isRemoving;

  /* =======================================================
     RENDER
  ======================================================= */

  return (
    <main
      dir="ltr"
      className="
        mx-auto
        w-full
        max-w-[1440px]
        px-3
        sm:px-5
        lg:px-8
      "
    >
      {/* =================================================
          MAIN PRODUCT AREA
      ================================================= */}

      <div
        className="
          grid
          grid-cols-1
          gap-5
          lg:grid-cols-[minmax(0,1fr)_360px]
          lg:gap-6
          xl:grid-cols-[minmax(0,450px)_minmax(0,1fr)_360px]
        "
      >
        {/* =================================================
            PRODUCT IMAGE
        ================================================= */}

        <section
          className="
            order-1
            min-w-0
          "
        >
          {/* =================================================
              SPECIAL SALE + WISHLIST
          ================================================= */}

          <div
            className="
              flex
              h-10
              items-center
              justify-between
              rounded-t-xl
              bg-red-500/10
              px-3
              sm:h-[50px]
            "
          >

            {/* WISHLIST BUTTON */}

            <button
              type="button"
              onClick={
                handleToggleWishlist
              }
              disabled={
                isWishlistLoading
              }
              aria-label={
                isFavorite
                  ? "Remove from wishlist"
                  : "Add to wishlist"
              }
              aria-pressed={
                isFavorite
              }
              className="
                flex
                h-9
                w-9
                items-center
                justify-center
                rounded-full
                bg-white
                shadow-sm
                transition-all
                duration-200
                hover:scale-110
                active:scale-95
                disabled:cursor-not-allowed
                disabled:opacity-50
              "
            >
              {isWishlistLoading ? (
                <span
                  className="
                    h-4
                    w-4
                    animate-spin
                    rounded-full
                    border-2
                    border-neutral-300
                    border-t-red-500
                  "
                />
              ) : (
                <FaHeart
                  className={`
                    h-5
                    w-5
                    transition-all
                    duration-200
                    ${
                      isFavorite
                        ? "scale-110 text-red-500"
                        : "text-neutral-400"
                    }
                  `}
                />
              )}
            </button>

            {/* TITLE */}


            <p
              className="
                text-sm
                font-bold
                text-red-600
              "
            >
              فروش ویژه
            </p>

            {/* BALANCE FOR CENTERING */}


            <div
              className="
                h-9
                w-9
              "
            />
          </div>

          {/* PRODUCT IMAGE */}

          <div
            className="
              relative
              flex
              aspect-square
              w-full
              items-center
              justify-center
              overflow-hidden
              rounded-b-xl
              border
              border-neutral-100
              bg-white
              sm:aspect-[4/5]
              xl:aspect-[450/500]
            "
          >

            <Image
              src="/icons/test.webp"
              fill
              sizes="
                (max-width: 640px) 100vw,
                (max-width: 1024px) 50vw,
                450px
              "
              alt={
                data?.title ||
                "product"
              }
              priority

>

            <h1
              className="
                animate-split
                text-base
                font-bold
                leading-7
                text-neutral-900

                sm:text-lg
                sm:leading-8

                lg:text-xl
              "
            >
              {data?.description}
            </h1>

            {}

            <p

              className="
                object-contain
                p-4
                sm:p-6
                lg:p-8
              "
            />
          </div>
        </section>


        {/* =================================================
            PRODUCT INFORMATION
        ================================================= */}


        <section
          className="
            order-2
            min-w-0
          "
        >
          {/* TITLE */}

          <h1
            className="
              animate-split
              text-base
              font-bold
              leading-7
              text-neutral-900
              sm:text-lg
              sm:leading-8
              lg:text-xl
            "
          >
            {data?.title ||
              data?.description ||
              "-"}
          </h1>


          {/* DESCRIPTION */}

          <p
            className="
              mt-2
              text-sm
              leading-6
              text-neutral-400
              sm:mt-3
              sm:leading-7
            "
          >
            {data?.short_description ||
              data?.description ||
              "-"}
          </p>

          <div
            className="
              mt-4
              h-px
              w-full
              bg-neutral-200
            "
          />

          {/* RATING */}

          <div
            className="
              mt-4
              flex
              flex-wrap
              items-center
              gap-x-3
              gap-y-2
              text-xs
              text-neutral-500
              sm:text-sm
            "
          >
            <div
              className="
                flex
                items-center
                gap-1.5
              "
            >
              <FcRating
                className="
                  h-5
                  w-5
                "
              />

              <span>
                {data?.rating ||
                  "0"}
              </span>
            </div>

            <span>
              {data?.buyers_count ||
                0}
              خریدار
            </span>

            <Link
              href="#reviews"
              className="
                text-blue-600
                hover:underline
              "
            >
              {data?.reviews_count ||
                0}{" "}
              دیدگاه
            </Link>

            <Link
              href="#questions"
              className="
                text-blue-600
                hover:underline
              "
            >
              {data?.questions_count ||
                0}{" "}
              پرسش
            </Link>
          </div>

          {/* COLOR / VARIANT */}

          {variants.length > 0 && (
            <div className="mt-6">
              <span
                className="
                  text-sm
                  font-semibold
                  text-neutral-800
                "
              >
                Color
              </span>

              <div className="mt-3">
                <ColorSwatchSelector
                  variants={
                    variants
                  }
                  selectedVariant={
                    selectedVariant
                  }
                  onSelectVariant={
                    handleSelectVariant
                  }
                />
              </div>
            </div>
          )}

          {/* FAST DELIVERY */}

          <div
            className="
              mt-6
              flex
              min-h-[60px]
              items-center
              gap-2
              rounded-xl
              border
              border-blue-100
              bg-blue-400/10
              px-3
              py-3
              sm:min-h-[75px]
              sm:px-4
            "
          >
            <TbBrandSpeedtest
              className="
                h-6
                w-6
                shrink-0
                text-blue-600
              "
            />

            <p
              className="
                text-xs
                leading-5
                text-neutral-700
                sm:text-sm
              "
            >
              تحویل امروز با
              ارسال سریع
              دیجی‌کالا
            </p>
          </div>

          {/* FEATURES */}

          <p
            className="
              mt-7
              text-sm
              font-bold
              text-neutral-800
            "
          >
            ویژگی ها
          </p>

          <div
            className="
              mt-4
              grid
              grid-cols-1
              gap-2.5
              sm:grid-cols-2
              xl:grid-cols-3
            "
          >
            <FeatureBox
              title="Display technology"
              value={
                attributes?.[5]
                  ?.value
              }
            />

            <FeatureBox
              title="Operating system version"
              value={
                attributes?.[1]
                  ?.value ||
                "Dynamic LTPO AMOLED 2"
              }
            />

            <FeatureBox
              title="Main camera resolution"
              value={
                attributes?.[6]
                  ?.value
              }
            />

            <FeatureBox
              title="Size"
              value={
                attributes?.[2]
                  ?.value ||
                "Dynamic LTPO AMOLED 2"
              }
            />

            <FeatureBox
              title="Display technology"
              value={
                attributes?.[3]
                  ?.value ||
                "Dynamic LTPO AMOLED 2"
              }
            />
          </div>

          <div className="mt-4">
            <ViewDetailsButton />
          </div>

          {/* RETURN WARNING */}

          <div
            className="
              mt-5
              flex
              items-start
              gap-2
            "
          >
            <IoWarningOutline
              className="
                mt-0.5
                h-5
                w-5
                shrink-0
                text-neutral-400
              "
            />

            <p
              className="
                max-w-[700px]
                text-justify
                text-[11px]
                leading-5
                text-neutral-400
                sm:text-[13px]
                sm:leading-6
              "
            >
              The possibility of
              returning goods in
              the mobile category
              with the reason
              "cancellation of
              purchase" is only
              accepted if the
              product seal has not
              been opened. All
              Digikala phones have
              a registry guarantee.
              In case of a registry
              problem, you can
              return the purchased
              phone after the
              30-day legal deadline.
            </p>
          </div>

          {/* PLUS MEMBERS */}

          <div
            className="
              mt-6
              overflow-hidden
              rounded-2xl
              border
              border-neutral-300
              p-3
              sm:p-5
            "
          >
            <div
              className="
                flex
                items-center
                gap-2
              "
            >
              <FaRegStar
                className="
                  h-6
                  w-6
                  shrink-0
                  text-purple-600
                "
              />

              <p
                className="


                  text-xs
                  leading-5
                  text-neutral-700

                  sm:text-sm
                "
              >
                تحویل امروز با ارسال سریع دیجی‌کالا
              </p>
            </div>

            {/* =================================
                FEATURES
            ================================= */}

            <p className="mt-7 text-sm font-bold text-neutral-800">ویژگی ها</p>

            <div
              className="
                mt-4
                grid
                grid-cols-1
                gap-2.5

                sm:grid-cols-2

                xl:grid-cols-3
              "
            >
              {}

              <FeatureBox
                title="Display technology"
                value={attributes?.[5]?.value}
              />

              {}

              <FeatureBox
                title="Operating system version"
                value="dynamic LTPO AMOLED 2"
              />

              {}

              <FeatureBox
                title="Main camera resolution"
                value={attributes?.[6]?.value}
              />

              {}

              <FeatureBox title="Size" value="dynamic LTPO AMOLED 2" />

              {}

              <FeatureBox
                title="Display technology"
                value="dynamic LTPO AMOLED 2"
              />
            </div>

            {}

            <div className="mt-4">
              <ViewDetailsButton />
            </div>

            {/* =================================
                RETURN WARNING
            ================================= */}

            <div
              className="
                mt-5
                flex
                items-start
                gap-2
              "
            >
              <IoWarningOutline
                className="
                  mt-0.5
                  h-5
                  w-5
                  shrink-0
                  text-neutral-400
                "
              />

              <p
                className="
                  max-w-[700px]
                  text-justify
                  text-[11px]
                  leading-5
                  text-neutral-400

                  sm:text-[13px]
                  sm:leading-6
                "
              >
                The possibility of returning goods in the mobile category with
                the reason "cancellation of purchase" is only accepted if the
                product seal has not been opened. All Digikala phones have a
                registry guarantee. In case of a registry problem, you can
                return the purchased phone after the 30-day legal deadline.
              </p>
            </div>

            {/* =================================
                PLUS MEMBERS
            ================================= */}

            <div
              className="
                mt-6
                overflow-hidden
                rounded-2xl
                border
                border-neutral-300
                p-3

                sm:p-5
              "
            >
              {}

              <div className="flex items-center gap-2">
                <FaRegStar
                  className="
                    h-6
                    w-6
                    shrink-0
                    text-purple-600
                  "
                />

                <p
                  className="
                    text-sm
                    font-bold
                    text-purple-600

                    sm:text-base
                    lg:text-lg
                  "
                >
                  Free shipping for Plus members
                </p>
              </div>

              {}

              <div className="mt-3 space-y-2">
                <PlusItem>4 Free digital delivery</PlusItem>

                <PlusItem>2 Supermarket delivery</PlusItem>

                <PlusItem>4 free 45-minute deliveries</PlusItem>

                <PlusItem>Dedicated support</PlusItem>

                <PlusItem>
                  Fast and free delivery of digital goods (Tehran and Karaj
                  only)
                </PlusItem>
              </div>

              {}

              <button
                type="button"
                className="
                  mt-3
                  text-xs
                  text-blue-500
                  hover:underline
                "
              >
                Buy a subscription
              </button>

              {}

              <div className="mt-2 flex justify-end">
                <Image
                  src="/icons/free-delivery.svg"
                  width={80}
                  height={80}
                  alt="delivery"
                />
              </div>
            </div>
          </section>

          {/* ===================================
              SELLER BOX
          =================================== */}

          <aside
            className="
              order-3
              min-w-0

              lg:sticky
              lg:top-8
              lg:self-start
            "
          >
            <div
              className="
                w-full
                rounded-2xl
                border
                border-neutral-300
                bg-white
                p-4

                sm:p-5
              "
            >
              {}

              <div className="flex items-center justify-between gap-3">
                <p className="text-sm font-semibold">Seller</p>

                <span className="animate-split text-xs font-medium text-orange-400">
                  3 other sellers
                </span>
              </div>

              {}

              <div className="mt-5 flex items-center gap-2">
                <Image
                  src="/icons/idigi.jfif"
                  width={22}
                  height={22}
                  alt="icon"
                  className="rounded-lg"
                />

                <span className="text-sm">Digikala</span>
              </div>

              {}

              <div className="mt-3 flex items-center gap-2 pl-7">
                <p className="text-[10px] text-neutral-400">Performance</p>

                <span className="text-xs text-green-600">Excellent</span>
              </div>

              <div className="mt-4 h-px w-full bg-neutral-200" />

              {}

              <div className="mt-5 flex items-center gap-2">
                <span
                  className="
                    rounded-md
                    bg-red-600
                    px-2
                    py-1
                    text-xs
                    font-bold
                    text-white
                  "
                >
                  3%
                </span>

                <del className="text-xs text-neutral-400">3.500 $</del>
              </div>

              {}

              <span
                className="
                  mt-3
                  block
                  text-xl
>>>>>>> Stashed changes
                  font-bold
                  text-purple-600
                  sm:text-base
                  lg:text-lg
                "
              >


                {data?.variants?.base_price} $
              </span>

              {}

              <div className="mt-3 flex items-start gap-2">
                <FaFire
                  className="
                    mt-0.5
                    h-5
                    w-5
                    shrink-0
                    text-orange-600
                  "
                />

                <span className="animate-split text-xs font-medium leading-5 text-orange-400">
                  Only 1 item left in stock.
                </span>
              </div>

              {/* =================================
                  CART
              ================================= */}

              {cartItem ? (
                <div className="mt-5">
                  <div
                    className="
                      flex
                      h-11
                      w-full
                      items-center
                      justify-between
                      rounded-lg
                      bg-red-500
                      px-3
                    "
                  >
                    {}

                    <button
                      type="button"
                      onClick={handleDeacrease}
                      className="text-white"
                    >
                      {cartItem.quantity === 1 ? (
                        <Trash2 size={18} />
                      ) : (
                        <Minus size={18} />
                      )}
                    </button>

                    {}

                    {!up ? (
                      <span className="text-sm font-medium text-white">
                        {cartItem.quantity}
                      </span>
                    ) : (
                      <RotatingLines
                        visible={true}
                        height="25"
                        width="25"
                        color="white"
                        strokeWidth="5"
                        animationDuration="0.75"
                        ariaLabel="loading"
                      />
                    )}

                    {}

                    <button
                      type="button"
                      onClick={handleIncrease}
                      disabled={
                        cartItem.quantity >= (selectedVariant?.stock ?? 0)
                      }
                      className="
                        text-white
                        disabled:opacity-30
                      "
                    >
                      <Plus size={18} />
                    </button>
                  </div>
                </div>
              ) : (
                <div className="mt-5">
                  <button
                    type="button"
                    onClick={handleAddToCarts}
                    disabled={
                      isPending ||
                      !selectedVariant ||
                      selectedVariant.stock === 0
                    }
                    className="
                      h-11
                      w-full
                      rounded-lg
                      bg-red-500
                      px-4
                      text-sm
                      font-semibold
                      text-white
                      transition-colors
                      hover:bg-red-600
                      disabled:cursor-not-allowed
                      disabled:opacity-50
                    "
                  >
                    {isPending ? "در حال افزودن..." : "Add to Basket"}
                  </button>
                </div>
              )}

          

              <div className="mt-5">
                <div className="flex items-center gap-3 text-neutral-400">
                  <VscCopilotSuccess
                    className="
                      h-5
                      w-5
                      shrink-0
                    "
                  />

                  <span className="text-xs">Sadrtel 18-month warranty</span>
                </div>
              </div>

            </div>

            <div
              className="
                mt-3
                space-y-2
              "
            >
              <PlusItem>
                4 Free digital
                delivery
              </PlusItem>

              <PlusItem>
                2 Supermarket
                delivery
              </PlusItem>

              <PlusItem>
                4 free 45-minute
                deliveries
              </PlusItem>

              <PlusItem>
                Dedicated support
              </PlusItem>

              <PlusItem>
                Fast and free
                delivery of digital
                goods (Tehran and
                Karaj only)
              </PlusItem>
            </div>

            <button
              type="button"
              className="
                mt-3
                text-xs
                text-blue-500
                hover:underline
              "
            >
              Buy a subscription
            </button>

            <div
              className="
                mt-2
                flex
                justify-end
              "
            >
              <Image
                src="/icons/free-delivery.svg"
                width={80}
                height={80}
                alt="delivery"
              />
            </div>
          </div>
        </section>

        {/* =================================================
            SELLER
        ================================================= */}

        <SellerBox
          data={data}
          selectedVariant={
            selectedVariant
          }
          cartItem={cartItem}
          stock={stock}
          basePrice={basePrice}
          finalPrice={finalPrice}
          discountPercent={
            discountPercent
          }
          hasDiscount={
            hasDiscount
          }
          isAdding={isAdding}
          isCartUpdating={
            isCartUpdating
          }
          handleAddToCart={
            handleAddToCart
          }
          handleDecrease={
            handleDecrease
          }
          handleIncrease={
            handleIncrease
          }
        />
      </div>

      {/* ===================================================
          SERVICE FEATURES
      =================================================== */}

      <div
        className="
          mt-8
          border-t
          border-neutral-200
          pt-5
          sm:mt-10
        "
      >
        <div
          className="
            grid
            grid-cols-2
            gap-5
            sm:grid-cols-3
            lg:grid-cols-5
          "
        >
          <ServiceItem
            image="/icons/express-delivery.svg"
            text="Express delivery possible"
          />

          <ServiceItem
            image="/icons/support.svg"
            text="24 hours a day, 7 days a week"
          />

          <ServiceItem
            image="/icons/cash-on-delivery.svg"
            text="Possibility of payment on site"
          />

          <ServiceItem
            image="/icons/days-return.svg"
            text="Seven-day return guarantee"
          />


            <ServiceItem
              image="/icons/cash-on-delivery.svg"
              text="Possibility of payment on site"
            />

            <ServiceItem
              image="/icons/days-return.svg"
              text="Seven-day return guarantee"
            />

            <ServiceItem
              image="/icons/original-products.svg"
              text="Guarantee of authenticity of the product"
            />
          </div>
        </div>

        {}

        <div className="mt-8 border-b border-neutral-200" />

        {/* =====================================
            MORE DETAILS
        ===================================== */}

        <div className="mt-6">
          <ProductMoreDetials
            data={data}
            selectedVariant={selectedVariant}
            cartItem={cartItem}
            handleAddToCarts={handleAddToCarts}
            handleDeacrease={handleDeacrease}
            handleIncrease={handleIncrease}
            up={up}
            isPending={isPending}

          />
        </div>
      </div>

      <div
        className="
          mt-8
          border-b
          border-neutral-200
        "
      />

      {/* ===================================================
          MORE DETAILS
      =================================================== */}

      <div className="mt-6">
        <ProductMoreDetials
          data={data}
          selectedVariant={
            selectedVariant
          }
          cartItem={cartItem}
          handleAddToCarts={
            handleAddToCart
          }
          handleDeacrease={
            handleDecrease
          }
          handleIncrease={
            handleIncrease
          }
          up={isCartUpdating}
          isPending={isAdding}
        />
      </div>
    </main>
  );
}

/* =========================================================
   SELLER BOX
========================================================= */

function SellerBox({
  data,
  selectedVariant,
  cartItem,
  stock,
  basePrice,
  finalPrice,
  discountPercent,
  hasDiscount,
  isAdding,
  isCartUpdating,
  handleAddToCart,
  handleDecrease,
  handleIncrease,
}) {
  return (
    <aside
      className="
        order-3
        min-w-0
        lg:sticky
        lg:top-8
        lg:self-start
      "
    >
      <div
        className="
          w-full
          rounded-2xl
          border
          border-neutral-300
          bg-white
          p-4
          sm:p-5
        "
      >
        {/* SELLER HEADER */}

        <div
          className="
            flex
            items-center
            justify-between
            gap-3
          "
        >
          <p
            className="
              text-sm
              font-semibold
            "
          >
            Seller
          </p>

          <span
            className="
              animate-split
              text-xs
              font-medium
              text-orange-400
            "
          >
            3 other sellers
          </span>
        </div>

        {/* SELLER NAME */}

        <div
          className="
            mt-5
            flex
            items-center
            gap-2
          "
        >
          <Image
            src="/icons/idigi.jfif"
            width={22}
            height={22}
            alt="Digikala"
            className="rounded-lg"
          />

          <span className="text-sm">
            {data?.seller?.name ||
              "Digikala"}
          </span>
        </div>

        {/* SELLER PERFORMANCE */}

        <div
          className="
            mt-3
            flex
            items-center
            gap-2
            pl-7
          "
        >
          <p
            className="
              text-[10px]
              text-neutral-400
            "
          >
            Performance
          </p>

          <span
            className="
              text-xs
              text-green-600
            "
          >
            Excellent
          </span>
        </div>

        <div
          className="
            mt-4
            h-px
            w-full
            bg-neutral-200
          "
        />

        {/* PRICE */}

        <div
          className="
            mt-5
            flex
            items-center
            gap-2
          "
        >
          {hasDiscount && (
            <span
              className="
                rounded-md
                bg-red-600
                px-2
                py-1
                text-xs
                font-bold
                text-white
              "
            >
              {discountPercent}%
            </span>
          )}

          {hasDiscount && (
            <del
              className="
                text-xs
                text-neutral-400
              "
            >
              {formatPrice(
                basePrice,
              )}{" "}
              $
            </del>
          )}
        </div>

        <span
          className="
            mt-3
            block
            text-xl
            font-bold
            text-neutral-800
          "
        >
          {finalPrice > 0
            ? `${formatPrice(
                finalPrice,
              )} $`
            : "-"}
        </span>

        {/* STOCK */}

        <div
          className="
            mt-3
            flex
            items-start
            gap-2
          "
        >
          <FaFire
            className="
              mt-0.5
              h-5
              w-5
              shrink-0
              text-orange-600
            "
          />

          <span
            className="
              animate-split
              text-xs
              font-medium
              leading-5
              text-orange-400
            "
          >
            {stock > 0
              ? `Only ${stock} item${
                  stock > 1
                    ? "s"
                    : ""
                } left in stock.`
              : "Out of stock"}
          </span>
        </div>

        {/* CART */}

        {cartItem ? (
          <CartQuantityControl
            cartItem={cartItem}
            stock={stock}
            loading={
              isCartUpdating
            }
            onDecrease={
              handleDecrease
            }
            onIncrease={
              handleIncrease
            }
          />
        ) : (
          <button
            type="button"
            onClick={
              handleAddToCart
            }
            disabled={
              isAdding ||
              !selectedVariant ||
              stock <= 0
            }
            className="
              mt-5
              h-11
              w-full
              rounded-lg
              bg-red-500
              px-4
              text-sm
              font-semibold
              text-white
              transition-colors
              hover:bg-red-600
              disabled:cursor-not-allowed
              disabled:opacity-50
            "
          >
            {isAdding
              ? "در حال افزودن..."
              : stock <= 0
                ? "Out of stock"
                : "Add to Basket"}
          </button>
        )}

        {/* WARRANTY */}

        <div className="mt-5">
          <div
            className="
              flex
              items-center
              gap-3
              text-neutral-400
            "
          >
            <VscCopilotSuccess
              className="
                h-5
                w-5
                shrink-0
              "
            />

            <span className="text-xs">
              Sadrtel 18-month
              warranty
            </span>
          </div>
        </div>
      </div>
    </aside>
  );
}

/* =========================================================
   CART QUANTITY
========================================================= */

function CartQuantityControl({
  cartItem,
  stock,
  loading,
  onDecrease,
  onIncrease,
}) {
  const quantity = Number(
    cartItem?.quantity || 0,
  );

  return (
    <div className="mt-5">
      <div
        className="
          flex
          h-11
          w-full
          items-center
          justify-between
          rounded-lg
          bg-red-500
          px-3
        "
      >
        <button
          type="button"
          onClick={
            onDecrease
          }
          disabled={loading}
          className="
            text-white
            disabled:opacity-40
          "
          aria-label="Decrease quantity"
        >
          {quantity <= 1 ? (
            <Trash2 size={18} />
          ) : (
            <Minus size={18} />
          )}
        </button>

        {!loading ? (
          <span
            className="
              text-sm
              font-medium
              text-white
            "
          >
            {quantity}
          </span>
        ) : (
          <RotatingLines
            visible
            height="25"
            width="25"
            color="white"
            strokeWidth="5"
            animationDuration="0.75"
            ariaLabel="loading"
          />
        )}

        <button
          type="button"
          onClick={
            onIncrease
          }
          disabled={
            loading ||
            quantity >= stock
          }
          className="
            text-white
            disabled:opacity-30
          "
          aria-label="Increase quantity"
        >
          <Plus size={18} />
        </button>
      </div>
    </div>
  );
}



function FeatureBox({
  title,
  value,
}) {
  return (
    <div
      className="
        min-h-[90px]
        rounded-xl
        bg-neutral-100
        px-3
        py-4
        text-center
        sm:min-h-[100px]
        sm:py-5
      "
    >
      <p
        className="
          text-[10px]
          leading-4
          text-neutral-500
          sm:text-xs
        "
      >
        {title}
      </p>

      <p
        className="
          mt-1
          line-clamp-2
          text-xs
          font-medium
          leading-5
          text-black/80
          sm:text-sm
        "
      >
        {value || "-"}
      </p>
    </div>
  );
}

/* =========================================================
   PLUS ITEM
========================================================= */

function PlusItem({
  children,
}) {
  return (
    <div
      className="
        flex
        items-start
        gap-1
      "
    >
      <BsDot
        className="
          mt-0.5
          h-5
          w-5
          shrink-0
          text-purple-600
        "
      />

      <p
        className="
          text-xs
          leading-5
          text-neutral-700
        "
      >
        {children}
      </p>
    </div>
  );
}

/* =========================================================
   SERVICE ITEM
========================================================= */

function ServiceItem({
  image,
  text,
}) {
  return (
    <div
      className="
        flex
        flex-col
        items-center
        justify-center
        gap-2
        text-center
      "
    >
      <Image
        src={image}
        width={60}
        height={60}
        alt=""
        className="
          h-12
          w-12
          object-contain
          sm:h-14
          sm:w-14
        "
      />

      <p
        className="
          text-[10px]
          leading-4
          text-neutral-500
          sm:text-xs
          sm:leading-5
        "
      >
        {text}
      </p>
    </div>
  );
}

export default ProductsDe;