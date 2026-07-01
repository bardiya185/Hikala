"use client";

import { useRef, useState, useCallback } from "react";
import Image from "next/image";
import Link from "next/link";
import { CiSearch, CiMobile1 } from "react-icons/ci";
import { MdShoppingCartCheckout } from "react-icons/md";
import { RxHamburgerMenu } from "react-icons/rx";
import { TbChevronLeft, TbDeviceLaptop, TbFridge, TbShirt } from "react-icons/tb";
import AuthForm from "../AuthForm";
const categories = [
  {
    key: "mobile",
    label: "موبایل و تبلت",
    icon: CiMobile1,
    href: "/search/category-mobile-phone/",
    columns: [
      {
        main: { label: "انتخاب موبایل", href: "/landing/mobile/" },
        leaves: [
          { label: "گوشی سامسونگ", href: "#" },
          { label: "گوشی شیائومی", href: "#" },
          { label: "گوشی اپل", href: "#" },
        ],
      },
      {
        main: { label: "لوازم جانبی موبایل", href: "#" },
        leaves: [
          { label: "قاب گوشی", href: "#" },
          { label: "شارژر گوشی", href: "#" },
        ],
      },
    ],
  },
  {
    key: "laptop",
    label: "لپ تاپ",
    icon: TbDeviceLaptop,
    href: "/landing/laptop/",
    columns: [
      {
        main: { label: "انتخاب لپ تاپ", href: "#" },
        leaves: [
          { label: "لپ تاپ ایسوس", href: "#" },
          { label: "لپ تاپ لنوو", href: "#" },
        ],
      },
    ],
  },
  {
    key: "appliance",
    label: "لوازم خانگی برقی",
    icon: TbFridge,
    href: "/landing/category-home-appliance/",
    columns: [
      {
        main: { label: "یخچال فریزر", href: "#" },
        leaves: [{ label: "ساید بای ساید", href: "#" }],
      },
    ],
  },
  {
    key: "fashion",
    label: "مد و پوشاک",
    icon: TbShirt,
    href: "/landing/apparel/",
    columns: [
      {
        main: { label: "پوشاک مردانه", href: "#" },
        leaves: [
          { label: "پیراهن مردانه", href: "#" },
          { label: "شلوار جین مردانه", href: "#" },
        ],
      },
    ],
  },
];

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeKey, setActiveKey] = useState(categories[0]?.key);
  const closeTimer = useRef(null);

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
    setIsOpen(true);
  }, [clearCloseTimer]);

  const activeCategory = categories.find((c) => c.key === activeKey) ?? categories[0];

  return (
    <div className="lg:w-full">
     
      <div>
        <Image
          src="/icons/1.png"
          width={1270}
          height={60}
          alt="banner"
          className="w-full inline-block"
        />
      </div>

      {/* هدر اصلی */}
      <div className="flex justify-between px-[16px] mt-[17px]">
        <div className="flex items-center gap-7">
          <Image src="/icons/logo.svg" width={195} height={30} alt="logo" />
          <div className="relative">
            <CiSearch className="absolute top-3 right-2" />
            <input
              placeholder="جستجو"
              className="lg:w-[500px] lg:h-[44px] bg-neutral-100 rounded-full pr-[27px] pb-[7px]"
            />
          </div>
        </div>
        <div className="flex items-center gap-7 pl-[20px]">
          <AuthForm />
          <Link href="/checkout">
            <MdShoppingCartCheckout className="w-[24px] h-[24px]" />
          </Link>
        </div>
      </div>

      
      <div
        className="relative inline-block mt-4 px-[16px]"
        dir="rtl"
        onMouseLeave={scheduleClose}
      >
        <button
          type="button"
          className="flex items-center gap-2 cursor-pointer"
          onMouseEnter={openMenu}
          onClick={() => setIsOpen((v) => !v)}
        >
          <RxHamburgerMenu />
          <span>دسته بندی ها</span>
        </button>

        {isOpen && (
          <div
            className="absolute top-[calc(100%+4px)] right-0 z-30 flex w-[700px] h-[282px] bg-white rounded-lg shadow-xl overflow-hidden"
            onMouseEnter={clearCloseTimer}
          >
            {/* ستون راست */}
            <nav className="flex flex-col w-[220px] shrink-0 overflow-y-auto border-l border-neutral-100 py-2">
              {categories.map((cat) => {
                const Icon = cat.icon;
                const isActive = activeKey === cat.key;
                return (
                  <Link
                    key={cat.key}
                    href={cat.href}
                    onMouseEnter={() => setActiveKey(cat.key)}
                    className={`flex items-center gap-2 px-4 py-2.5 text-[13px] font-semibold whitespace-nowrap ${
                      isActive ? "bg-red-50 text-red-600" : "text-neutral-900"
                    }`}
                  >
                    {Icon && (
                      <Icon
                        className={`w-[18px] h-[18px] ${
                          isActive ? "text-red-600" : "text-neutral-500"
                        }`}
                      />
                    )}
                    <span>{cat.label}</span>
                  </Link>
                );
              })}
            </nav>

            {/* بخش چپ */}
            {activeCategory && (
              <div className="flex-1 overflow-y-auto px-6 py-5">
                <Link
                  href={activeCategory.href}
                  className="flex items-center gap-1 mb-4 text-[13px] font-semibold text-red-600 whitespace-nowrap"
                >
                  همه محصولات {activeCategory.label}
                  <TbChevronLeft className="w-3.5 h-3.5" />
                </Link>

                <div className="grid grid-cols-3 gap-6">
                  {activeCategory.columns.map((col) => (
                    <div key={col.main.label} className="flex flex-col whitespace-nowrap">
                      <Link
                        href={col.main.href}
                        className="flex items-center justify-between mb-2 py-1 text-sm font-bold text-neutral-900"
                      >
                        {col.main.label}
                        <TbChevronLeft className="w-3.5 h-3.5 text-neutral-500" />
                      </Link>
                      {col.leaves.map((leaf) => (
                        <Link
                          key={leaf.label}
                          href={leaf.href}
                          className="py-1 text-[13px] text-neutral-500 hover:text-red-600 transition-colors"
                        >
                          {leaf.label}
                        </Link>
                      ))}
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}

export default Header;