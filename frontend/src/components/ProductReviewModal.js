"use client";

import { useEffect, useState } from "react";
import Image from "next/image";
import { ArrowLeft, Loader2, X } from "lucide-react";
import { useCreateProductReview } from "@/core/services/mutations"; 

export default function ProductReviewModal({
  isOpen,
  onClose,
  product,
}) {
  const [review, setReview] = useState("");

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

    document.addEventListener("keydown", handleKeyDown);

    const originalOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";

    return () => {
      document.removeEventListener("keydown", handleKeyDown);
      document.body.style.overflow = originalOverflow;
    };
  }, [isOpen, isPending, onClose]);

  if (!isOpen) {
    return null;
  }

  const productImage = product?.images?.[0]?.url ||
    product?.images?.[0]?.image ||
    product?.images?.[0] ||
    "/icons/ip17.jpg";

  const handleSubmit = (event) => {
    event.preventDefault();

    const trimmedReview = review.trim();

    if (!trimmedReview || isPending) {
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

  return (
    <div
      className="
        fixed
        inset-0
        z-[100]
        flex
        items-end
        sm:items-center
        justify-center
        bg-black/50
        backdrop-blur-[2px]
        p-0
        sm:p-4
      "
      onMouseDown={(event) => {
        if (event.target === event.currentTarget && !isPending) {
          onClose();
        }
      }}
    >
      <div
        role="dialog"
        aria-modal="true"
        aria-labelledby="review-modal-title"
        className="
        
    relative
    w-full
    max-w-[520px]
    max-h-[90vh]
    overflow-y-auto
    rounded-2xl
    bg-white
    shadow-2xl
    animate-review-slide-in
  "
        
      >
        {/* Header */}
        <div className="flex items-center gap-3 border-b border-neutral-100 px-5 py-4">
          <button
            type="button"
            onClick={onClose}
            disabled={isPending}
            aria-label="Go back"
            className="
              flex
              h-9
              w-9
              shrink-0
              items-center
              justify-center
              rounded-full
              text-neutral-600
              transition-colors
              hover:bg-neutral-100
              disabled:cursor-not-allowed
              disabled:opacity-50
            "
          >
            <ArrowLeft className="h-5 w-5" />
          </button>

          <h2
            id="review-modal-title"
            className="text-base font-semibold text-neutral-900"
          >
            Write a Review
          </h2>
        </div>

        {/* Content */}
        <form onSubmit={handleSubmit} className="px-5 py-5 sm:px-6 sm:py-6">
          {/* Product */}
          <div className="flex items-center gap-4 rounded-xl bg-neutral-50 p-3">
            <div
              className="
                relative
                h-[72px]
                w-[72px]
                shrink-0
                overflow-hidden
                rounded-lg
                bg-white
              "
            >
              <Image
                src={productImage}
                alt={product?.title || "Product"}
                fill
                sizes="72px"
                className="object-contain p-2"
              />
            </div>

            <div className="min-w-0">
              <p className="text-xs text-neutral-400">
                Product
              </p>

              <h3 className="mt-1 line-clamp-2 text-sm font-semibold text-neutral-900">
                {product?.title}
              </h3>
            </div>
          </div>

          {/* Review */}
          <div className="mt-6">
            <label
              htmlFor="product-review"
              className="mb-2 block text-sm font-medium text-neutral-800"
            >
              Your Review
            </label>

            <textarea
              id="product-review"
              value={review}
              onChange={(event) => setReview(event.target.value)}
              placeholder="Share your experience with this product..."
              rows={5}
              maxLength={1000}
              disabled={isPending}
              className="
                w-full
                resize-none
                rounded-xl
                border
                border-neutral-200
                bg-white
                px-4
                py-3
                text-sm
                text-neutral-800
                outline-none
                transition-all
                placeholder:text-neutral-400
                hover:border-neutral-300
                focus:border-sky-400
                focus:ring-4
                focus:ring-sky-100
                disabled:cursor-not-allowed
                disabled:bg-neutral-50
              "
            />

            <div className="mt-2 flex justify-end">
              <span className="text-[11px] text-neutral-400">
                {review.length}/1000
              </span>
            </div>
          </div>

          {/* Submit */}
          <button
            type="submit"
            disabled={!review.trim() || isPending}
            className="
              mt-5
              flex
              h-[44px]
              w-full
              items-center
              justify-center
              gap-2
              rounded-lg
              bg-red-500
              px-6
              text-sm
              font-medium
              text-white
              transition-all
              hover:bg-red-600
              active:scale-[0.99]
              disabled:cursor-not-allowed
              disabled:bg-red-300
            "
          >
            {isPending ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
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