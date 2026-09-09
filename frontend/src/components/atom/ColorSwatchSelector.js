"use client";

import React from "react";
import {
  getUniqueColorOptions,
  findVariantByColor,
} from "@/core/utils/variantAttributes";
import { Check } from "lucide-react";

function isLightColor(hex) {
  if (!hex) return true;

  const cleanHex = hex.replace("#", "");

  if (cleanHex.length !== 6) return true;

  const r = parseInt(cleanHex.substring(0, 2), 16);
  const g = parseInt(cleanHex.substring(2, 4), 16);
  const b = parseInt(cleanHex.substring(4, 6), 16);

  if ([r, g, b].some(Number.isNaN)) return true;

  const luminance =
    (0.299 * r + 0.587 * g + 0.114 * b) / 255;

  return luminance > 0.7;
}

export default function ColorSwatchSelector({
  variants = [],
  selectedVariant = null,
  onSelectVariant,
}) {
  const safeVariants = Array.isArray(variants) ? variants : [];

  const colorOptions = getUniqueColorOptions(safeVariants);

  const selectedColorSlug = selectedVariant
    ? safeVariants
        .find((variant) => variant?.id === selectedVariant?.id)
        ?.attributes?.find(
          (attribute) => attribute?.attribute_slug === "color",
        )?.slug
    : null;

  if (colorOptions.length === 0) return null;

  const handleSelect = (colorSlug) => {
    const matchedVariant = findVariantByColor(
      safeVariants,
      colorSlug,
    );

    if (matchedVariant && typeof onSelectVariant === "function") {
      onSelectVariant(matchedVariant);
    }
  };

  return (
    <div className="flex flex-wrap gap-3">
      {colorOptions.map((color) => {
        const isSelected =
          selectedColorSlug === color?.slug;

        const isLight = isLightColor(color?.colorCode);

        return (
          <button
            key={color?.slug}
            type="button"
            onClick={() => handleSelect(color?.slug)}
            aria-label={color?.value || "Color"}
            title={color?.value || "Color"}
            className={`relative flex h-9 w-9 items-center justify-center rounded-full transition-all ${
              isSelected
                ? "ring-2 ring-red-600 ring-offset-2"
                : "ring-1 ring-neutral-200 ring-offset-1 hover:ring-neutral-300"
            }`}
            style={{
              backgroundColor:
                color?.colorCode || "#e5e5e5",
            }}
          >
            {isSelected && (
              <Check
                size={16}
                strokeWidth={3}
                className={
                  isLight
                    ? "text-neutral-800"
                    : "text-white"
                }
              />
            )}
          </button>
        );
      })}
    </div>
  );
}