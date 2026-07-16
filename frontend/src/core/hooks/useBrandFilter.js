import { useMemo } from "react";
import { useRouter, usePathname, useSearchParams } from "next/navigation";

export function useBrandFilter({ products, allBrandsFromApi, searchTerm }) {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  // ۱. استخراج برندهای فعال در URL
  const selectedBrands = useMemo(() => {
    return searchParams.get("brands")?.split(",") || [];
  }, [searchParams]);

  // ۲. ساخت Set از برندهای مجاز بر اساس محصولات موجود
  const allowedBrandIds = useMemo(() => {
    const items = Array.isArray(products) ? products : products?.data || [];
    if (!items.length) return new Set();

    const ids = items.map((p) => String(p?.brand?.id || p?.brand_id || "")).filter(Boolean);
    return new Set(ids);
  }, [products]);

  // ۳. فیلتر کردن برندها بر اساس جستجو و ساختار جدید دیتای بک‌آند
  const filteredBrands = useMemo(() => {
    const brandsArray = allBrandsFromApi?.data || allBrandsFromApi;
    
    if (!Array.isArray(brandsArray)) return [];
    
    return brandsArray.filter((brand) => {
      const brandId = String(brand.id);
      const isChecked = selectedBrands.includes(brandId);
      
      const isAllowed = allowedBrandIds.has(brandId) || isChecked;
      if (!isAllowed) return false;

      if (!searchTerm.trim()) return true;

      const term = searchTerm.toLowerCase();
      const name = brand.name?.toLowerCase() || "";
      const slug = brand.slug?.toLowerCase() || "";

      return name.includes(term) || slug.includes(term);
    });
  }, [allBrandsFromApi, allowedBrandIds, searchTerm, selectedBrands]);

  // ۴. مدیریت تغییر فیلترها در URL
  const handleCheckboxChange = (id) => {
    const stringId = String(id);
    const updated = selectedBrands.includes(stringId)
      ? selectedBrands.filter((b) => b !== stringId)
      : [...selectedBrands, stringId];

    const params = new URLSearchParams(searchParams);
    if (updated.length) params.set("brands", updated.join(","));
    else params.delete("brands");

    router.push(`${pathname}?${params}`);
  };

  // هر چیزی که کامپوننت اصلی برای رندر نیاز دارد را ریترن می‌کنیم
  return {
    selectedBrands,
    filteredBrands,
    handleCheckboxChange,
  };
}