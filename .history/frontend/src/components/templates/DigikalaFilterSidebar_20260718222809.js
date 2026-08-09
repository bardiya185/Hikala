"use client";
import React, { useState, useMemo } from "react";
import * as Accordion from "@radix-ui/react-accordion";
import * as Switch from "@radix-ui/react-switch";
import * as Checkbox from "@radix-ui/react-checkbox";
import { ChevronDown, ChevronLeft, Search, Check } from "lucide-react";
import { LuMessageSquareWarning } from "react-icons/lu";
import { usegetBrandsFilter } from "@/core/services/queries";
import { useRouter, usePathname, useSearchParams } from "next/navigation";
import { useBrandFilter } from "@/core/hooks/useBrandFilter";


const FilterSection = ({ value, title, children }) => (
  <Accordion.Item value={value} className="px-5 py-1">
    <Accordion.Header className="flex">
      <Accordion.Trigger className="flex w-full items-center justify-between py-4 text-sm font-semibold text-neutral-700 hover:text-neutral-900 transition-colors group">
        <span>{title}</span>
        <div className="text-neutral-400">
          <span className="lg:hidden"><ChevronLeft size={20} /></span>
          <span className="hidden lg:inline-block transition-transform duration-300 group-data-[state=open]:rotate-180">
            <ChevronDown size={20} />
          </span>
        </div>
      </Accordion.Trigger>
    </Accordion.Header>
    <Accordion.Content className="data-[state=open]:animate-slideDown data-[state=closed]:animate-slideUp overflow-hidden text-sm text-neutral-600">
      {children}
    </Accordion.Content>
  </Accordion.Item>
);

export default function DigikalaFilterSidebar({ products }) {
  const { data: allBrandsFromApi, isLoading } = usegetBrandsFilter();
  const [searchTerm, setSearchTerm] = useState("");

  const { selectedBrands, filteredBrands, handleCheckboxChange } = useBrandFilter({
    products,
    allBrandsFromApi,
    searchTerm,
  });



  return (
    <div className="w-full bg-white lg:border  i lg:border-neutral-200 lg:rounded-xl font-sans text-right" dir="rtl">
      <div className="px-5 py-4 text-base font-bold text-neutral-800 border-b border-neutral-100">
        فیلترها
      </div>

      <Accordion.Root type="multiple" className="w-full divide-y divide-neutral-100">
        
        
        <FilterSection value="address" title="ارسال سریع">
          <div className="pb-4 space-y-3">
            <div className="w-full flex bg-amber-50 border border-amber-100 rounded-lg p-3 items-center gap-3">
              <LuMessageSquareWarning className="w-6 h-6 text-amber-500 shrink-0" />
              <p className="text-xs text-neutral-600 leading-relaxed">
                برای مشاهده کالاهای موجود در انبار نزدیک خود، لطفاً آدرس را مشخص کنید.
              </p>
            </div>
            <button className="w-full py-2 text-xs font-semibold text-red-500 border border-red-500 rounded-lg hover:bg-red-50 transition-colors">
              انتخاب آدرس
            </button>
          </div>
        </FilterSection>

      
        <FilterSection value="brand" title="برند">
          <div className="pb-4 pt-1">
            <div className="relative flex items-center mb-3 bg-neutral-100 rounded-lg px-3 py-2">
              <Search size={16} className="text-neutral-400 ml-2" />
              <input
                type="text"
                placeholder="جستجو برند..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="bg-transparent text-xs w-full outline-none text-neutral-700 font-sans"
              />
            </div>

            <div className="max-h-[220px] overflow-y-auto space-y-1 scrollbar-thin scrollbar-thumb-neutral-200">
              {isLoading ? (
                <p className="text-xs text-neutral-400 text-center py-2">در حال بارگذاری...</p>
              ) : filteredBrands.length ? (
                filteredBrands.map((brand) => {
                  const brandId = String(brand.id);
                  const isChecked = selectedBrands.includes(brandId);
                  return (
                    <div
                      key={brandId}
                      onClick={() => handleCheckboxChange(brandId)}
                      className="w-full flex items-center justify-start cursor-pointer hover:bg-neutral-50 rounded px-2 transition-colors"
                    >
                      <div className="ml-3 py-2" onClick={(e) => e.stopPropagation()}>
                        <Checkbox.Root
                          id={brandId}
                          checked={isChecked}
                          onCheckedChange={() => handleCheckboxChange(brandId)}
                          className="flex h-4 w-4 shrink-0 items-center justify-center rounded border border-neutral-300 bg-white data-[state=checked]:bg-red-500 data-[state=checked]:border-red-500 transition-colors outline-none"
                        >
                          <Checkbox.Indicator className="text-white">
                            <Check size={12} strokeWidth={3} />
                          </Checkbox.Indicator>
                        </Checkbox.Root>
                      </div>

                      <label
                        htmlFor={brandId}
                        className="grow flex items-center justify-between py-2 border-b border-neutral-100 text-xs font-semibold text-neutral-700 cursor-pointer select-none"
                      >
                        
                        <span>{brand.name}</span>
                        <span className="text-[10px] text-neutral-400 font-normal ltr text-left">
                          {brand.slug}
                        </span>
                      </label>
                    </div>
                  );
                })
              ) : (
                <p className="text-xs text-neutral-400 text-center py-2">برندی یافت نشد.</p>
              )}
            </div>
          </div>
        </FilterSection>

        
        <FilterSection value="price" title="محدوده قیمت">
          <div className="pb-5 pt-2">
            <p className="text-xs text-neutral-400 text-center">...</p>
          </div>
        </FilterSection>

      </Accordion.Root>

      
      <div className="divide-y divide-neutral-100 border-t border-neutral-100">
        <div className="px-5 py-4 flex items-center justify-between">
          <label className="text-sm font-semibold text-neutral-700 cursor-pointer" htmlFor="available-stock">
            
          </label>
          <Switch.Root
            id="available-stock"
            className="w-11 h-6 bg-neutral-200 data-[state=checked]:bg-cyan-500 rounded-full relative transition-colors duration-200 cursor-pointer outline-none"
          >
            <Switch.Thumb className="block w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 translate-x-[4px] data-[state=checked]:-translate-x-[24px]" />
          </Switch.Root>
        </div>
      </div>
    </div>
  );
}