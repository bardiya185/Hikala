// utils/variantAttributes.js

// از یک variant، مقدار یک attribute خاص (مثلا رنگ) رو پیدا می‌کنه
export function getVariantAttribute(variant, attributeSlug) {
  return variant.attributes?.find(
    (attr) => attr.attribute_slug === attributeSlug
  );
}

// از بین همه variantهای یک محصول، رنگ‌های یکتا رو استخراج می‌کنه
// (چون ممکنه چند variant رنگ یکسان ولی حافظه متفاوت داشته باشن)
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

// وقتی کاربر یه رنگ رو انتخاب کرد، اولین variant که اون رنگ رو داره برمی‌گردونه
// (اگه بعدا فیلتر حافظه/رم هم اضافه کردی، باید بقیه attributeهای انتخاب‌شده رو هم چک کنه)
export function findVariantByColor(variants, colorSlug) {
  return variants.find((variant) => {
    const colorAttr = getVariantAttribute(variant, "color");
    return colorAttr?.slug === colorSlug;
  });
}
