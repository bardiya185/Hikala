export function getVariantAttribute(variant, attributeSlug) {
  return variant.attributes?.find(
    (attr) => attr.attribute_slug === attributeSlug,
  );
}
export function getUniqueColorOptions(variants) {
  const colorMap = new Map();

  variants.forEach((variant) => {
    const colorAttr = getVariantAttribute(variant, "color");
    if (!colorAttr) return;

    if (!colorMap.has(colorAttr.slug)) {
      colorMap.set(colorAttr.slug, {
        slug: colorAttr.slug,
        value: colorAttr.value,
        colorCode: colorAttr.color_code,
      });
    }
  });

  return Array.from(colorMap.values());
}
export function findVariantByColor(variants, colorSlug) {
  return variants.find((variant) => {
    const colorAttr = getVariantAttribute(variant, "color");
    return colorAttr?.slug === colorSlug;
  });
}
