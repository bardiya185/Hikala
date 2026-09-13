"use client";

import { useEffect, useId, useState } from "react";
import Image from "next/image";
import { ArrowLeft, Loader2 } from "lucide-react";

import { useCreateProductReview } from "@/core/services/mutations";

export default function ProductReviewModal({
  isOpen,
  onClose,
  product,
}) {
  const [review, setReview] = useState("");
  const titleId = useId();
  const reviewId = useId();

  const { mutate, isPending } = useCreateProductReview();

  useEffect(() => {
    if (!isOpen) {
      setReview("");
      return;
    }

    const handleKeyDown = (event) => {
      if (event.key === "Escape" && !isPending) {
        onClose();
      }
    };

    const originalOverflow = document.body.style.overflow;

    document.addEventListener("keydown", handleKeyDown);
    document.body.style.overflow = "hidden";

    return () => {
      document.removeEventListener("keydown", handleKeyDown);
      document.body.style.overflow = originalOverflow;
    };
  }, [isOpen, isPending, onClose]);

  if (!isOpen) {
    return null;
  }

  const productImage =
    product?.images?.[0]?.url ||
    product?.images?.[0]?.image ||
    (typeof product?.images?.[0] === "string"
      ? product.images[0]
      : null) ||
    "/icons/ip17.jpg";

  const productTitle =
    product?.title || "Product";

  const handleSubmit = (event) => {
    event.preventDefault();

    const trimmedReview = review.trim();

    if (!trimmedReview || !product?.id || isPending) {
      return;
    }

    mutate(
      {
        product_id: product.id,
        body: trimmedReview,
        rating: 5,
        advantages: [],
        disadvantages: [],
      },
      {
        onSuccess: () => {
          setReview("");
          onClose();
        },
      }
    );
  };

  const handleOverlayMouseDown = (event) => {
    if (
      event.target === event.currentTarget &&
      !isPending
    ) {
      onClose();
    }
  };

  return (
    <div
      className="
        fixed inset-0 z-[100]
        flex items-end justify-center
        bg-black/50 p-0
        backdrop-blur-sm
        sm:items-center sm:p-4
      "
      onMouseDown={handleOverlayMouseDown}
    >
      <div
        role="dialog"
        aria-modal="true"
        aria-labelledby={titleId}
        className="
          relative flex w-full max-w-[520px]
          max-h-[90vh] flex-col
          overflow-hidden
          rounded-t-2xl
          border border-neutral-200
          bg-white
          shadow-2xl
          animate-review-slide-in
          dark:border-neutral-800
          dark:bg-neutral-900
          sm:rounded-2xl
        "
        onMouseDown={(event) => event.stopPropagation()}
      >
        {/* Header */}
        <div
          className="
            flex shrink-0 items-center gap-3
            border-b border-neutral-100
            px-4 py-3.5
            sm:px-5
            dark:border-neutral-800
          "
        >
          <button
            type="button"
            onClick={onClose}
            disabled={isPending}
            aria-label="Go back"
            className="
              flex h-9 w-9 shrink-0
              items-center justify-center
              rounded-full
              text-neutral-600
              transition-colors
              hover:bg-neutral-100
              hover:text-neutral-900
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              disabled:cursor-not-allowed
              disabled:opacity-50
              dark:text-neutral-300
              dark:hover:bg-neutral-800
              dark:hover:text-white
            "
          >
            <ArrowLeft
              aria-hidden="true"
              className="h-5 w-5"
            />
          </button>

          <h2
            id={titleId}
            className="
              text-base font-semibold
              text-neutral-900
              dark:text-white
            "
          >
            Write a Review
          </h2>
        </div>

        {/* Content */}
        <form
          onSubmit={handleSubmit}
          className="
            overflow-y-auto
            px-4 py-5
            sm:px-6 sm:py-6
          "
        >
          {/* Product */}
          <div
            className="
              flex items-center gap-3
              rounded-xl
              border border-neutral-100
              bg-neutral-50 p-3
              dark:border-neutral-800
              dark:bg-neutral-800/60
            "
          >
            <div
              className="
                relative h-[68px] w-[68px]
                shrink-0 overflow-hidden
                rounded-lg
                border border-neutral-100
                bg-white
                dark:border-neutral-700
                dark:bg-neutral-900
              "
            >
              <Image
                src={productImage}
                alt={productTitle}
                fill
                sizes="68px"
                className="object-contain p-2"
              />
            </div>

            <div className="min-w-0">
              <p
                className="
                  text-[11px] font-medium
                  text-neutral-400
                  dark:text-neutral-500
                "
              >
                Product
              </p>

              <h3
                className="
                  mt-1 line-clamp-2
                  text-sm font-semibold
                  leading-5
                  text-neutral-900
                  dark:text-neutral-100
                "
              >
                {productTitle}
              </h3>
            </div>
          </div>

          {/* Review */}
          <div className="mt-6">
            <div className="mb-2 flex items-center justify-between">
              <label
                htmlFor={reviewId}
                className="
                  text-sm font-semibold
                  text-neutral-800
                  dark:text-neutral-100
                "
              >
                Your Review
              </label>

              <span
                className="
                  text-[11px]
                  text-neutral-400
                  dark:text-neutral-500
                "
              >
                {review.length}/1000
              </span>
            </div>

            <textarea
              id={reviewId}
              value={review}
              onChange={(event) =>
                setReview(event.target.value)
              }
              placeholder="Share your experience with this product..."
              rows={5}
              maxLength={1000}
              disabled={isPending}
              autoComplete="off"
              className="
                min-h-[130px] w-full
                resize-none rounded-xl
                border border-neutral-200
                bg-white px-4 py-3
                text-sm leading-6
                text-neutral-800
                outline-none
                transition-all
                placeholder:text-neutral-400
                hover:border-neutral-300
                focus:border-red-400
                focus:ring-4
                focus:ring-red-100
                disabled:cursor-not-allowed
                disabled:bg-neutral-50
                dark:border-neutral-700
                dark:bg-neutral-900
                dark:text-neutral-100
                dark:placeholder:text-neutral-500
                dark:hover:border-neutral-600
                dark:focus:border-red-500
                dark:focus:ring-red-950/40
                dark:disabled:bg-neutral-800
              "
            />
          </div>

          {/* Submit */}
          <button
            type="submit"
            disabled={
              !review.trim() ||
              !product?.id ||
              isPending
            }
            className="
              mt-5 flex h-11 w-full
              items-center justify-center
              gap-2 rounded-xl
              bg-red-500 px-6
              text-sm font-semibold
              text-white
              shadow-sm
              transition-all duration-200
              hover:bg-red-600
              hover:shadow-md
              active:scale-[0.99]
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              disabled:cursor-not-allowed
              disabled:bg-red-300
              disabled:shadow-none
              dark:focus-visible:ring-offset-neutral-900
              dark:disabled:bg-red-950
            "
          >
            {isPending ? (
              <>
                <Loader2
                  aria-hidden="true"
                  className="h-4 w-4 animate-spin"
                />
                Submitting...
              </>
            ) : (
              "Submit Review"
            )}
          </button>
        </form>
      </div>
    </div>
  );
}