"use client";
import { useRef, useState, useCallback, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";

import { CiMobile1 } from "react-icons/ci";
import { TbDeviceLaptop, TbFridge, TbShirt } from "react-icons/tb";
import { GiGoldBar, GiCarKey } from "react-icons/gi";
import { CiSearch } from "react-icons/ci";
import { MdShoppingCartCheckout } from "react-icons/md";
import { RxHamburgerMenu } from "react-icons/rx";
import { TbChevronRight } from "react-icons/tb";
import { useRouter } from "next/navigation";
import { usePathname } from "next/navigation";

import AuthForm from "../AuthForm";

import { useGetMainCategories, useGetSubCategory } from "@/core/services/queries";

const iconMap = {
  "mobile": CiMobile1,
  "laptops": TbDeviceLaptop,
  "digital": TbDeviceLaptop,
  "home-kitchen": TbFridge,
  "fashion": TbShirt,
  "gold-jewelry": GiGoldBar,
  "vehicles": GiCarKey,
};

function CategoryIcon({ iconKey, className }) {
  const IconComponent = iconMap[iconKey];
  if (!IconComponent) return null;
  return <IconComponent className={className} />;
}

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState(null);
  const closeTimer = useRef(null);

  const router = useRouter();
  const pathname = usePathname();

  const { data: categoriess } = useGetMainCategories();
  const mainDataArray = categoriess?.data?.data || [];

  const { data: categoryMenu, isLoading: isSubLoading } = useGetSubCategory(activeId);
  console.log(categoryMenu);
  const subDataArray = categoryMenu?.data?.data || [];

// ✅ Initialize activeId with "mobile" category
  useEffect(() => {
    if (mainDataArray.length > 0 && !activeId) {
      const targetCategory = mainDataArray.find((c) => c.slug === "mobile");
      setActiveId(targetCategory?.id || mainDataArray[0]?.id);
    }
  }, [mainDataArray, activeId]);

  const clearCloseTimer = useCallback(() => {
    if (closeTimer.current) {
      clearTimeout(closeTimer.current);
      closeTimer.current = null;
    }
  }, []);

  const scheduleClose = useCallback(() => {
    clearCloseTimer();
    closeTimer.current = setTimeout(() => setIsOpen(false), 150);
  }, [clearCloseTimer]);

  const openMenu = useCallback(() => {
    clearCloseTimer();
    if (!isOpen) setIsOpen(true);
  }, [clearCloseTimer, isOpen]);

  const activeCategory = mainDataArray?.find((c) => c.id === activeId) || mainDataArray[0];

 // Helper function: if category has children, return the first child, otherwise return itself
  const getTargetCategory = (category) => {
    if (!category) return null;
    if (category.children && category.children.length > 0) {
      return category.children[0];
    }
    return category;
  };

  const targetCategory = getTargetCategory(activeCategory);

  const finalSubList = Array.isArray(subDataArray)
    ? subDataArray
    : subDataArray?.children || subDataArray?.subs || subDataArray?.subcategories || [];

  return (
    <div dir="ltr" className="lg:w-full font-sans select-none">
      {/* بنر */}
      <div>
        <Image
          src="/icons/1.png"
          width={1270}
          height={60}
          alt="banner"
          className="w-full inline-block"
        />
      </div>

      <div className="flex justify-between items-center px-[16px] mt-[17px]">
        <div className="flex items-center gap-7">
          <Image src="/icons/en-logo.svg" width={195} height={30} alt="logo" />
          <div className="relative flex items-center">
            <CiSearch className="absolute left-3 text-neutral-500 w-5 h-5" />
            <input
              placeholder="Search..."
              className="lg:w-[500px] lg:h-[44px] bg-neutral-100 rounded-full pl-[35px] pr-[15px] outline-none text-sm text-neutral-800"
            />
          </div>
        </div>

        <div className="flex items-center gap-7 pr-[20px]">
          <AuthForm />
          <Link href="/checkout">
            <div className="p-2 hover:bg-neutral-100 rounded-full transition-colors">
              <MdShoppingCartCheckout className="w-[24px] h-[24px] text-neutral-700" />
            </div>
          </Link>
        </div>
      </div>

      <div className="relative inline-block mt-4 px-[16px]" onMouseLeave={scheduleClose}>
        <button
          type="button"
          className="flex items-center gap-2 cursor-pointer py-2 text-neutral-800 hover:text-red-600 transition-colors"
          onMouseEnter={openMenu}
          onClick={() => setIsOpen((v) => !v)}
        >
          <RxHamburgerMenu className="w-5 h-5" />
          <span className="text-sm font-bold">Categories</span>
        </button>

        {isOpen && (
          <div
            className="absolute top-[calc(100%+4px)] left-4 z-30 flex w-[700px] h-[350px] bg-white rounded-lg shadow-xl overflow-hidden border border-neutral-100"
            onMouseEnter={clearCloseTimer}
          >
            <nav className="flex flex-col w-[220px] shrink-0 overflow-y-auto border-r border-neutral-100 py-2 bg-neutral-50">
              {mainDataArray?.map((c) => {
                const isActive = activeId === c.id;
                return (
                  <div
                    key={c.id}
                    onMouseEnter={() => setActiveId(c.id)}
                    className={`flex items-center justify-between px-4 py-2.5 text-[13px] font-semibold cursor-pointer transition-colors ${
                      isActive
                        ? "bg-white text-red-600 border-l-4 border-l-red-500"
                        : "text-neutral-900 hover:bg-neutral-100"
                    }`}
                  >
                    <div className="flex items-center gap-2">
                      <CategoryIcon
                        iconKey={c.icon_key}
                        className={`w-[18px] h-[18px] ${
                          isActive ? "text-red-600" : "text-neutral-500"
                        }`}
                      />
                      <span>{c.name}</span>
                    </div>
                    <TbChevronRight
                      className={`w-3.5 h-3.5 ${
                        isActive ? "text-red-500" : "text-neutral-300"
                      }`}
                    />
                  </div>
                );
              })}
            </nav>

            <div className="flex-1 overflow-y-auto px-6 py-5 bg-white relative">
              {isSubLoading ? (
                <div className="absolute inset-0 flex items-center justify-center bg-white/50">
                  <div className="w-6 h-6 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
              ) : (
                <>
                  <Link
                    href={`/search/${targetCategory?.slug || "all"}?category_id=${targetCategory?.id}`}
                    className="flex items-center gap-1 mb-4 text-[13px] font-bold text-red-600 whitespace-nowrap hover:underline"
                  >
                    All {activeCategory?.name} Products
                    <TbChevronRight className="w-3.5 h-3.5" />
                  </Link>
          
                  <div className="grid grid-cols-3 gap-6">
                    {finalSubList?.map((col, idx) => (
                      <div key={col.id || idx} className="flex flex-col whitespace-nowrap">
                        <Link
                          href={`/search/${col.slug || "category"}?category_id=${col.id}`}
                          className="flex items-center justify-between mb-2 py-1 text-sm font-bold text-neutral-900 border-b border-neutral-100 group"
                        >
                          <span className="group-hover:text-red-600 transition-colors">
                            {col.name}
                          </span>
                          <TbChevronRight className="w-3.5 h-3.5 text-neutral-400 group-hover:text-red-600 transition-colors" />
                        </Link>
            
                        {(col.children || col.subs || col.leaves)?.map((leaf, lIdx) => (
                          <Link
                            key={leaf.id || lIdx}
                            href={`/search/${leaf.slug || "child"}?category_id=${leaf.id}`}
                            className="py-1 text-[13px] text-neutral-500 hover:text-red-600 transition-colors"
                          >
                            {leaf.name}
                          </Link>
                        ))}
                      </div>
                    ))}
                  </div>
                </>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

export default Header;