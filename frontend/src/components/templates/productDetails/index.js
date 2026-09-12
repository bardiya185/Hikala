"use client";

import Image from "next/image";
import React, { useEffect, useMemo, useState } from "react";
import Link from "next/link";

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
import ProductReviewModal from "@/components/ProductReviewModal";
import WishlistButton from "@/components/WishlistButton";

import {
  useAddProductsBasket,
  useRemoveCartItem,
  useUpdateCartItem,
  useAddToWishlist,
} from "@/core/services/mutations";

import {
  useCart,
  useWishlistIds,
} from "@/core/services/queries";

import toast from "react-hot-toast";

import { Minus, Plus, Trash2 } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";
import { formatPrice } from "@/core/utils/formatPrice";

gsap.registerPlugin(SplitText);

/* -------------------------------------------------------------------------- */
/*                               Helper Functions                             */
/* -------------------------------------------------------------------------- */

function getDefaultVariant(variants) {
  if (!Array.isArray(variants) || variants.length === 0) {
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
    basePrice: Number(variant?.base_price || 0),
    finalPrice: Number(variant?.final_price || 0),
    discountPercent: Number(
      variant?.discount_percent || 0,
    ),
  };
}

/**
 * Returns the main product image from the API.
 *
 * API structure:
 *
 * images: [
 *   {
 *     id: 58,
 *     image_url: "...",
 *     alt: "...",
 *     sort_order: 1,
 *     is_main: true
 *   }
 * ]
 */
function getProductImage(product) {
  if (
    !Array.isArray(product?.images) ||
    product.images.length === 0
  ) {
    return "/icons/test.webp";
  }

  const mainImage = product.images.find(
    (image) =>
      image?.is_main === true ||
      image?.is_main === 1,
  );

  const sortedImage = [...product.images]
    .filter(
      (image) =>
        typeof image?.image_url === "string" &&
        image.image_url.trim().length > 0,
    )
    .sort(
      (a, b) =>
        Number(a?.sort_order || 0) -
        Number(b?.sort_order || 0),
    )[0];

  const image = mainImage || sortedImage;

  if (
    typeof image?.image_url === "string" &&
    image.image_url.trim().length > 0
  ) {
    return image.image_url;
  }

  return "/icons/test.webp";
}

/* -------------------------------------------------------------------------- */
/*                              Seller Box                                    */
/* -------------------------------------------------------------------------- */

function SellerBox({ data }) {
  return (
    <div className="rounded-xl border border-neutral-200 bg-white p-4">
      <div className="mb-4 flex items-center justify-between">
        <h3 className="text-sm font-bold text-neutral-800">
          Seller
        </h3>

        <span className="text-xs text-neutral-500">
          Official Store
        </span>
      </div>

      <div className="flex items-center gap-3">
        <div className="relative h-12 w-12 shrink-0 overflow-hidden rounded-lg border border-neutral-200 bg-white">
          <Image
            src="/icons/idigi.jfif"
            alt="Seller"
            fill
            sizes="48px"
            className="object-contain p-1"
          />
        </div>

        <div className="min-w-0">
          <p className="truncate text-sm font-bold text-neutral-800">
            Digi Seller
          </p>

          <p className="mt-1 text-xs text-neutral-500">
            Trusted seller
          </p>
        </div>
      </div>

      <div className="mt-4 grid grid-cols-3 divide-x divide-neutral-200 rounded-lg bg-neutral-50 py-3 text-center">
        <div>
          <p className="text-xs text-neutral-500">
            Rating
          </p>

          <p className="mt-1 text-sm font-bold text-neutral-800">
            95%
          </p>
        </div>

        <div>
          <p className="text-xs text-neutral-500">
            Delivery
          </p>

          <p className="mt-1 text-sm font-bold text-neutral-800">
            Fast
          </p>
        </div>

        <div>
          <p className="text-xs text-neutral-500">
            Products
          </p>

          <p className="mt-1 text-sm font-bold text-neutral-800">
            100+
          </p>
        </div>
      </div>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/*                           Cart Quantity Control                            */
/* -------------------------------------------------------------------------- */

function CartQuantityControl({
  quantity,
  onIncrease,
  onDecrease,
  isLoading,
}) {
  return (
    <div className="flex h-11 items-center justify-between rounded-lg border border-neutral-200 bg-white px-2">
      <button
        type="button"
        onClick={onIncrease}
        disabled={isLoading}
        aria-label="Increase quantity"
        className="flex h-8 w-8 items-center justify-center rounded-md text-neutral-700 transition hover:bg-neutral-100 disabled:cursor-not-allowed disabled:opacity-50"
      >
        <Plus className="h-4 w-4" />
      </button>

      <span className="min-w-8 text-center text-sm font-bold text-neutral-800">
        {isLoading ? (
          <span className="mx-auto block h-4 w-4 animate-spin rounded-full border-2 border-neutral-300 border-t-red-500" />
        ) : (
          quantity
        )}
      </span>

      <button
        type="button"
        onClick={onDecrease}
        disabled={isLoading}
        aria-label={
          quantity <= 1
            ? "Remove product"
            : "Decrease quantity"
        }
        className="flex h-8 w-8 items-center justify-center rounded-md text-neutral-700 transition hover:bg-neutral-100 disabled:cursor-not-allowed disabled:opacity-50"
      >
        {quantity <= 1 ? (
          <Trash2 className="h-4 w-4" />
        ) : (
          <Minus className="h-4 w-4" />
        )}
      </button>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/*                               Feature Box                                  */
/* -------------------------------------------------------------------------- */

function FeatureBox({
  icon,
  title,
  description,
}) {
  return (
    <div className="flex items-center gap-3 rounded-xl border border-neutral-100 bg-white p-3">
      <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-500">
        {icon}
      </div>

      <div className="min-w-0">
        <p className="text-sm font-semibold text-neutral-800">
          {title}
        </p>

        {description && (
          <p className="mt-1 text-xs leading-5 text-neutral-500">
            {description}
          </p>
        )}
      </div>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/*                                Plus Item                                   */
/* -------------------------------------------------------------------------- */

function PlusItem({
  title,
  description,
  icon,
}) {
  return (
    <div className="flex items-center gap-3 rounded-lg border border-neutral-100 p-3">
      <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-neutral-100">
        {icon}
      </div>

      <div className="min-w-0">
        <p className="text-sm font-medium text-neutral-800">
          {title}
        </p>

        {description && (
          <p className="mt-1 text-xs text-neutral-500">
            {description}
          </p>
        )}
      </div>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/*                              Service Item                                  */
/* -------------------------------------------------------------------------- */

function ServiceItem({
  icon,
  title,
  description,
}) {
  return (
    <div className="flex items-start gap-3">
      <div className="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-neutral-100">
        {icon}
      </div>

      <div>
        <p className="text-sm font-medium text-neutral-800">
          {title}
        </p>

        {description && (
          <p className="mt-1 text-xs leading-5 text-neutral-500">
            {description}
          </p>
        )}
      </div>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/*                              Main Component                                */
/* -------------------------------------------------------------------------- */

function ProductsDe({ data }) {
  const variants = Array.isArray(data?.variants)
    ? data.variants
    : [];

  const [selectedVariant, setSelectedVariant] =
    useState(() => getDefaultVariant(variants));

  const [quantity, setQuantity] = useState(1);

  const [isFavorite, setIsFavorite] = useState(false);

  const {
    mutate: toggleWishlist,
    isPending: isWishlistLoading,
  } = useAddToWishlist();

  const { data: cart } = useCart();

  const {
    mutate: addProductToCart,
    isPending: isAdding,
  } = useAddProductsBasket();

  const {
    mutate: updateCartItem,
    isPending: isUpdating,
  } = useUpdateCartItem();

  const {
    mutate: removeCartItem,
    isPending: isRemoving,
  } = useRemoveCartItem();

  const cartItems = Array.isArray(
    cart?.data?.items,
  )
    ? cart.data.items
    : [];

  /* ------------------------------------------------------------------------ */
  /*                              Product Image                               */
  /* ------------------------------------------------------------------------ */

  const productImage = useMemo(() => {
    return getProductImage(data);
  }, [data]);

  const productImageAlt = useMemo(() => {
    const mainImage = Array.isArray(data?.images)
      ? data.images.find(
          (image) =>
            image?.is_main === true ||
            image?.is_main === 1,
        )
      : null;

    return (
      mainImage?.alt ||
      data?.title ||
      "Product"
    );
  }, [data]);

  /* ------------------------------------------------------------------------ */
  /*                                Cart Item                                 */
  /* ------------------------------------------------------------------------ */

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
  }, [cartItems, selectedVariant]);

  /* ------------------------------------------------------------------------ */
  /*                               Variant Data                               */
  /* ------------------------------------------------------------------------ */

  const stock = Number(
    selectedVariant?.stock || 0,
  );

  const {
    basePrice,
    finalPrice,
    discountPercent,
  } = useMemo(() => {
    return getVariantPrice(selectedVariant);
  }, [selectedVariant]);

  const hasDiscount =
    finalPrice > 0 &&
    basePrice > 0 &&
    finalPrice < basePrice;

  const attributes = Array.isArray(
    selectedVariant?.attributes,
  )
    ? selectedVariant.attributes
    : [];

  const colorAttributes = attributes.filter(
    (attribute) =>
      attribute?.attribute_slug === "color",
  );

  const hasColors =
    colorAttributes.length > 0;

  /* ------------------------------------------------------------------------ */
  /*                           Wishlist                                       */
  /* ------------------------------------------------------------------------ */

  const handleToggleWishlist = () => {
    if (!data?.id) {
      toast.error("Product not found");
      return;
    }

    if (isWishlistLoading) {
      return;
    }

    toggleWishlist(data.id, {
      onSuccess: () => {
        setIsFavorite(
          (previousState) =>
            !previousState,
        );

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

  /* ------------------------------------------------------------------------ */
  /*                          Variant Selection                               */
  /* ------------------------------------------------------------------------ */

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

  /* ------------------------------------------------------------------------ */
  /*                             Quantity                                     */
  /* ------------------------------------------------------------------------ */

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

  /* ------------------------------------------------------------------------ */
  /*                              Add To Cart                                 */
  /* ------------------------------------------------------------------------ */

  const handleAddToCart = () => {
    if (!data) {
      toast.error("Product not found");
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
      toast.error("Invalid quantity");
      return;
    }

    if (quantity > stock) {
      toast.error("Not enough stock");
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
            "Added to cart successfully",
          );
        },

        onError: (error) => {
          console.error(
            "Add to cart error:",
            error,
          );

          toast.error(
            "Could not add product to cart",
          );
        },
      },
    );
  };

  /* ------------------------------------------------------------------------ */
  /*                         Cart Quantity                                    */
  /* ------------------------------------------------------------------------ */

  const handleIncrease = () => {
    if (!cartItem || !selectedVariant) {
      return;
    }

    const currentQuantity = Number(
      cartItem.quantity || 0,
    );

    const currentStock = Number(
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

  const handleDecrease = () => {
    if (!cartItem) {
      return;
    }

    const currentQuantity = Number(
      cartItem.quantity || 0,
    );

    if (currentQuantity <= 1) {
      removeCartItem(cartItem.id);
      return;
    }

    updateCartItem({
      itemId: cartItem.id,
      quantity:
        currentQuantity - 1,
    });
  };

  /* ------------------------------------------------------------------------ */
  /*                              Animation                                   */
  /* ------------------------------------------------------------------------ */

  useEffect(() => {
    const elements =
      document.querySelectorAll(
        ".animate-split",
      );

    if (!elements.length) {
      return;
    }

    const splits = [];

    elements.forEach((element) => {
      try {
        const split = new SplitText(
          element,
          {
            type: "words",
            wordsClass:
              "inline-block overflow-hidden",
          },
        );

        splits.push(split);

        gsap.from(split.words, {
          duration: 0.8,
          y: 20,
          opacity: 0,
          stagger: 0.08,
          ease: "back.out(1.7)",
        });
      } catch (error) {
        console.error(
          "GSAP SplitText error:",
          error,
        );
      }
    });

    return () => {
      splits.forEach((split) => {
        try {
          split.revert();
        } catch (error) {
          console.error(
            "GSAP cleanup error:",
            error,
          );
        }
      });
    };
  }, []);

  const isCartUpdating =
    isUpdating || isRemoving;

  /* ------------------------------------------------------------------------ */
  /*                                 Render                                   */
  /* ------------------------------------------------------------------------ */

  return (
    <main
      dir="ltr"
      className="mx-auto w-full max-w-[1440px] px-3 sm:px-5 lg:px-8"
    >
      <div className="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1fr)_360px] lg:gap-6 xl:grid-cols-[minmax(0,450px)_minmax(0,1fr)_360px]">

        {/* ---------------------------------------------------------------- */}
        {/* Product Image                                                     */}
        {/* ---------------------------------------------------------------- */}

        <section className="order-1 min-w-0">
          <div className="flex h-10 items-center justify-between rounded-t-xl bg-red-500/10 px-3 sm:h-[50px]">
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
              aria-pressed={isFavorite}
              className="flex h-9 w-9 items-center justify-center rounded-full bg-white shadow-sm transition-all duration-200 hover:scale-110 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
            >
              {isWishlistLoading ? (
                <span className="h-4 w-4 animate-spin rounded-full border-2 border-neutral-300 border-t-red-500" />
              ) : (
                <FaHeart
                  className={`h-5 w-5 transition-all duration-200 ${
                    isFavorite
                      ? "scale-110 text-red-500"
                      : "text-neutral-400"
                  }`}
                />
              )}
            </button>

            <p className="text-sm font-bold text-red-600">
              Special Offer
            </p>

            <div className="h-9 w-9" />
          </div>

          {/* PRODUCT IMAGE */}
          <div className="relative flex aspect-square w-full items-center justify-center overflow-hidden rounded-b-xl border border-neutral-100 bg-white sm:aspect-[4/5] xl:aspect-[450/500]">
            <Image
              src={productImage}
              fill
              sizes="
                (max-width: 640px) 100vw,
                (max-width: 1024px) 50vw,
                450px
              "
              alt={productImageAlt}
              priority
              className="object-contain p-4 sm:p-6 lg:p-8"
            />
          </div>

          {/* Product image information */}
          <div className="mt-3 flex items-center justify-between text-xs text-neutral-500">
            <span>
              Product ID: {data?.id || "-"}
            </span>

            <span>
              {Array.isArray(data?.images)
                ? data.images.length
                : 0}{" "}
              image
              {Array.isArray(data?.images) &&
              data.images.length !== 1
                ? "s"
                : ""}
            </span>
          </div>
        </section>

        {/* ---------------------------------------------------------------- */}
        {/* Product Details                                                   */}
        {/* ---------------------------------------------------------------- */}

        <section className="order-2 min-w-0">
          <div className="rounded-xl border border-neutral-200 bg-white p-4 sm:p-5 lg:p-6">

            <div className="mb-3 flex items-center gap-2">
              {data?.brand?.name && (
                <span className="text-sm font-semibold text-neutral-500">
                  {data.brand.name}
                </span>
              )}

              {data?.rating && (
                <>
                  <BsDot className="text-neutral-400" />

                  <div className="flex items-center gap-1">
                    <FcRating className="h-4 w-4" />

                    <span className="text-xs font-semibold text-neutral-700">
                      {data.rating}
                    </span>
                  </div>
                </>
              )}
            </div>

            <h1 className="animate-split text-lg font-bold leading-8 text-neutral-900 sm:text-xl lg:text-2xl">
              {data?.title ||
                "Product"}
            </h1>

            {data?.short_description && (
              <p className="mt-3 text-sm leading-7 text-neutral-500">
                {data.short_description}
              </p>
            )}

            {/* Product statistics */}
            <div className="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 border-b border-neutral-100 pb-5">
              <div className="flex items-center gap-1.5 text-xs text-neutral-500">
                <FaRegStar className="text-amber-400" />
                <span>
                  Rating:{" "}
                  {data?.rating || 0}
                </span>
              </div>

              <div className="flex items-center gap-1.5 text-xs text-neutral-500">
                <BsDot />
                <span>
                  {data?.reviews_count ||
                    0}{" "}
                  Reviews
                </span>
              </div>

              <div className="flex items-center gap-1.5 text-xs text-neutral-500">
                <BsDot />
                <span>
                  {data?.view_count || 0}{" "}
                  Views
                </span>
              </div>
            </div>

            {/* Variants */}
            {variants.length > 0 && (
              <div className="mt-6">
                <div className="mb-4 flex items-center justify-between">
                  <h2 className="text-sm font-bold text-neutral-800">
                    Product Variants
                  </h2>

                  <span className="text-xs text-neutral-500">
                    {variants.length} options
                  </span>
                </div>

                <div className="space-y-3">
                  {variants.map(
                    (variant) => {
                      const isSelected =
                        selectedVariant?.id ===
                        variant.id;

                      const variantPrice =
                        getVariantPrice(
                          variant,
                        );

                      const variantColor =
                        variant.attributes?.find(
                          (attribute) =>
                            attribute?.attribute_slug ===
                            "color",
                        );

                      return (
                        <button
                          key={variant.id}
                          type="button"
                          onClick={() =>
                            handleSelectVariant(
                              variant,
                            )
                          }
                          className={`w-full rounded-xl border p-3 text-left transition-all ${
                            isSelected
                              ? "border-red-500 bg-red-50/50"
                              : "border-neutral-200 bg-white hover:border-neutral-300"
                          }`}
                        >
                          <div className="flex items-center justify-between gap-3">
                            <div className="min-w-0">
                              <div className="flex flex-wrap items-center gap-2">
                                {variantColor?.value && (
                                  <span className="text-sm font-semibold text-neutral-800">
                                    {
                                      variantColor.value
                                    }
                                  </span>
                                )}

                                {variant.sku && (
                                  <span className="text-xs text-neutral-400">
                                    {variant.sku}
                                  </span>
                                )}
                              </div>

                              <div className="mt-2 flex flex-wrap gap-2">
                                {variant.attributes
                                  ?.slice(0, 4)
                                  .map(
                                    (
                                      attribute,
                                    ) => (
                                      <span
                                        key={
                                          attribute.id
                                        }
                                        className="rounded-md bg-neutral-100 px-2 py-1 text-[11px] text-neutral-600"
                                      >
                                        {
                                          attribute.value
                                        }
                                      </span>
                                    ),
                                  )}
                              </div>
                            </div>

                            <div className="shrink-0 text-right">
                              <p className="text-sm font-bold text-neutral-900">
                                {formatPrice(
                                  variantPrice.finalPrice,
                                )}
                              </p>

                              {variantPrice.basePrice >
                                variantPrice.finalPrice && (
                                <p className="mt-1 text-xs text-neutral-400 line-through">
                                  {formatPrice(
                                    variantPrice.basePrice,
                                  )}
                                </p>
                              )}
                            </div>
                          </div>
                        </button>
                      );
                    },
                  )}
                </div>
              </div>
            )}

            {/* Colors */}
            {hasColors && (
              <div className="mt-6">
                <h2 className="mb-3 text-sm font-bold text-neutral-800">
                  Color
                </h2>

                <div className="flex flex-wrap gap-3">
                  {colorAttributes.map(
                    (attribute) => (
                      <ColorSwatchSelector
                        key={
                          attribute.id
                        }
                        color={
                          attribute.color_code
                        }
                        label={
                          attribute.value
                        }
                        selected={
                          selectedVariant?.attributes?.some(
                            (
                              selectedAttribute,
                            ) =>
                              selectedAttribute?.attribute_slug ===
                                "color" &&
                              selectedAttribute?.value ===
                                attribute.value,
                          )
                        }
                      />
                    ),
                  )}
                </div>
              </div>
            )}

            {/* Selected variant attributes */}
            {attributes.length > 0 && (
              <div className="mt-6">
                <h2 className="mb-3 text-sm font-bold text-neutral-800">
                  Specifications
                </h2>

                <div className="grid grid-cols-1 gap-2 sm:grid-cols-2">
                  {attributes.map(
                    (attribute) => (
                      <div
                        key={
                          attribute.id
                        }
                        className="flex items-center justify-between gap-3 rounded-lg bg-neutral-50 px-3 py-2.5"
                      >
                        <span className="text-xs text-neutral-500">
                          {
                            attribute.attribute_name
                          }
                        </span>

                        <span className="text-xs font-semibold text-neutral-800">
                          {
                            attribute.value
                          }
                        </span>
                      </div>
                    ),
                  )}
                </div>
              </div>
            )}

            {/* Price */}
            <div className="mt-6 rounded-xl bg-neutral-50 p-4">
              <div className="flex items-center justify-between gap-3">
                <div>
                  <p className="text-xs text-neutral-500">
                    Final Price
                  </p>

                  <p className="mt-1 text-xl font-extrabold text-neutral-900 sm:text-2xl">
                    {formatPrice(
                      finalPrice,
                    )}
                  </p>
                </div>

                {hasDiscount && (
                  <div className="flex flex-col items-end gap-1">
                    <span className="rounded-md bg-red-500 px-2 py-1 text-xs font-bold text-white">
                      {discountPercent}%
                    </span>

                    <span className="text-xs text-neutral-400 line-through">
                      {formatPrice(
                        basePrice,
                      )}
                    </span>
                  </div>
                )}
              </div>
            </div>

            {/* Stock */}
            <div className="mt-4 flex items-center gap-2">
              {stock > 0 ? (
                <>
                  <VscCopilotSuccess className="text-green-500" />

                  <span className="text-sm font-medium text-green-600">
                    In stock
                  </span>

                  <span className="text-xs text-neutral-400">
                    ({stock} available)
                  </span>
                </>
              ) : (
                <>
                  <IoWarningOutline className="text-red-500" />

                  <span className="text-sm font-medium text-red-500">
                    Out of stock
                  </span>
                </>
              )}
            </div>

            {/* Cart */}
            <div className="mt-5">
              {cartItem ? (
                <div className="space-y-3">
                  <CartQuantityControl
                    quantity={
                      cartItem.quantity
                    }
                    onIncrease={
                      handleIncrease
                    }
                    onDecrease={
                      handleDecrease
                    }
                    isLoading={
                      isCartUpdating
                    }
                  />

                  <Link
                    href="/checkout/cart"
                    className="flex h-11 items-center justify-center rounded-lg border border-red-500 text-sm font-bold text-red-500 transition hover:bg-red-50"
                  >
                    View Cart
                  </Link>
                </div>
              ) : (
                <button
                  type="button"
                  onClick={
                    handleAddToCart
                  }
                  disabled={
                    isAdding ||
                    stock <= 0
                  }
                  className="flex h-12 w-full items-center justify-center rounded-lg bg-red-500 text-sm font-bold text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:bg-neutral-300"
                >
                  {isAdding ? (
                    <RotatingLines
                      visible
                      width="22"
                      strokeWidth="5"
                      animationDuration="0.75"
                      ariaLabel="Loading"
                    />
                  ) : (
                    "Add to Cart"
                  )}
                </button>
              )}
            </div>
          </div>
        </section>

        {/* ---------------------------------------------------------------- */}
        {/* Right Sidebar                                                     */}
        {/* ---------------------------------------------------------------- */}

        <aside className="order-3 min-w-0 space-y-4">
          <SellerBox data={data} />

          <div className="rounded-xl border border-neutral-200 bg-white p-4">
            <h2 className="mb-4 text-sm font-bold text-neutral-800">
              Delivery Services
            </h2>

            <div className="space-y-4">
              {selectedVariant?.shipping_features?.map(
                (feature) => (
                  <ServiceItem
                    key={feature.id}
                    icon={
                      feature.type ===
                      "fast" ? (
                        <TbBrandSpeedtest className="h-4 w-4" />
                      ) : (
                        <VscCopilotSuccess className="h-4 w-4" />
                      )
                    }
                    title={
                      feature.title
                    }
                    description={
                      feature.description
                    }
                  />
                ),
              )}
            </div>
          </div>

          <div className="rounded-xl border border-neutral-200 bg-white p-4">
            <h2 className="mb-4 text-sm font-bold text-neutral-800">
              Product Features
            </h2>

            <div className="space-y-3">
              <FeatureBox
                icon={
                  <FaFire className="h-4 w-4" />
                }
                title="Special Offer"
                description="This product is currently available with a special offer."
              />

              <FeatureBox
                icon={
                  <VscCopilotSuccess className="h-4 w-4" />
                }
                title="Original Product"
                description="Product supplied through our trusted seller network."
              />

              <FeatureBox
                icon={
                  <TbBrandSpeedtest className="h-4 w-4" />
                }
                title="Fast Delivery"
                description="Fast delivery options may be available."
              />
            </div>
          </div>
        </aside>
      </div>

      {/* ------------------------------------------------------------------ */}
      {/* Additional Product Details                                        */}
      {/* ------------------------------------------------------------------ */}

      <div className="mt-6">
        <ProductMoreDetials data={data}
    selectedVariant={selectedVariant}
    cartItem={cartItem}
    handleAddToCarts={handleAddToCart}
    handleDeacrease={handleDecrease}
    handleIncrease={handleIncrease}
    up={isCartUpdating}
    isPending={isAdding} />
      </div>

      {/* ------------------------------------------------------------------ */}
      {/* Reviews                                                            */}
      {/* ------------------------------------------------------------------ */}

      <div className="mt-6">
        <ProductReviewModal
          productId={data?.id}
        />
      </div>
    </main>
  );
}

export default ProductsDe;