

export const MIN_SEARCH_LENGTH = 2;


export function getDisplayPrice(product) {
  if (!product.variants?.length) return null;
  return product.variants.reduce((cheapest, current) =>
    !cheapest || current.price < cheapest.price ? current : cheapest
  , null);
}

export function getSearchKeyword(title) {
  return title.trim().split(/\s+/)[0];
}


export function isCategoryMatch(categoryName, query) {
  return categoryName.toLowerCase().includes(query?.trim().toLowerCase());
}

export function getUniqueCategories(products) {
  const allCategories = products.flatMap((product) => product.categories || []);
  const uniqueCategoriesMap = new Map(allCategories.map((cat) => [cat.id, cat]));
  return Array.from(uniqueCategoriesMap.values());
}
