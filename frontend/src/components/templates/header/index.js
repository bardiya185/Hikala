
"use client";

import React, { useCallback, useEffect, useRef, useState } from "react";
import Image from "next/image";
import Link from "next/link";

import { CiMobile1 } from "react-icons/ci";
import { CiLocationOn } from "react-icons/ci";
import { TbDeviceLaptop } from "react-icons/tb";
import { TbFridge } from "react-icons/tb";
import { TbShirt } from "react-icons/tb";
import { TbChevronRight } from "react-icons/tb";
import { TbHeartbeat } from "react-icons/tb";
import { TbTools } from "react-icons/tb";
import { TbTrophy } from "react-icons/tb";

import { RiNotification3Line } from "react-icons/ri";
import { GiGoldBar } from "react-icons/gi";
import { GiCarKey } from "react-icons/gi";
import { GiLipstick } from "react-icons/gi";

import { MdShoppingCartCheckout } from "react-icons/md";
import { RxHamburgerMenu } from "react-icons/rx";

import { FaFire } from "react-icons/fa";
import { FaStore } from "react-icons/fa";
import { FaGem } from "react-icons/fa";
import { FaChartLine } from "react-icons/fa";
import { FaShoppingBag } from "react-icons/fa";
import { FaTag } from "react-icons/fa";

import AuthForm from "../AuthForm";
import SearchBar from "@/components/atom/SearchBar";
import MiniCart from "../miniCart";
import MobileBottomNav from "./MobileBottomNav";
import LocationModal from "./location/LocationModal";

import {
  useCart,
  useGetMainCategories,
  useGetSubCategory,
  useGetUserData,
} from "@/core/services/queries";

const iconMap = {
  mobile: CiMobile1,
  laptops: TbDeviceLaptop,
  digital: TbDeviceLaptop,
  "home-kitchen": TbFridge,
  fashion: TbShirt,
  "gold-jewelry": GiGoldBar,
  vehicles: GiCarKey,
  "home-appliances": TbFridge,
  "beauty-health": GiLipstick,
  "health-medical": TbHeartbeat,
  "tools-equipment": TbTools,
  "sports-travel": TbTrophy,
};

function CategoryIcon({ iconKey, className }) {
  const IconComponent = iconMap[iconKey];

  if (!IconComponent) return null;

  return <IconComponent className={className} />;
}

function NotificationButton() {
  return (
    <Link
      href="/profile/notification"
      aria-label="Notifications"
      className="
        flex
        h-10
        w-10
        shrink-0
        items-center
        justify-center
        rounded-full
        text-neutral-700
        transition-colors
        hover:bg-neutral-100
        hover:text-red-500
      "
    >
      <RiNotification3Line className="h-[23px] w-[23px]" />
    </Link>
  );
}

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState(null);
  const [isOpenMiniCart, setIsOpenMiniCart] = useState(false);
  const [isLocationModalOpen, setIsLocationModalOpen] = useState(false);
  const [selectedAddress, setSelectedAddress] = useState(null);

  const closeTimer = useRef(null);

  const { data: cart } = useCart();

  const totalCount = cart?.data?.items_count || 0;

  const { data: user } = useGetUserData();

  void user;

  const { data: categoriess } = useGetMainCategories();

  const mainDataArray = categoriess?.data?.data || [];

  const {
    data: categoryMenu,
    isLoading: isSubLoading,
  } = useGetSubCategory(activeId);

  const subDataArray = categoryMenu?.data?.data || [];

  useEffect(() => {
    if (mainDataArray.length > 0 && !activeId) {
      const targetCategory = mainDataArray.find(
        (category) => category.slug === "mobile"
      );

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

    closeTimer.current = setTimeout(() => {
      setIsOpen(false);
    }, 150);
  }, [clearCloseTimer]);

  const openMenu = useCallback(() => {
    clearCloseTimer();
    setIsOpen(true);
  }, [clearCloseTimer]);

  const closeMenu = useCallback(() => {
    clearCloseTimer();
    setIsOpen(false);
  }, [clearCloseTimer]);

  useEffect(() => {
    return () => {
      if (closeTimer.current) {
        clearTimeout(closeTimer.current);
      }
    };
  }, []);

  const activeCategory =
    mainDataArray.find((category) => category.id === activeId) ||
    mainDataArray[0];

  const getTargetCategory = (category) => {
    if (!category) return null;

    if (
      Array.isArray(category.children) &&
      category.children.length > 0
    ) {
      return category.children[0];
    }

    return category;
  };

  const targetCategory = getTargetCategory(activeCategory);

  const finalSubList = Array.isArray(subDataArray)
    ? subDataArray
    : subDataArray?.children ||
      subDataArray?.subs ||
      subDataArray?.subcategories ||
      [];

  const handleLocationSaved = (address) => {
    console.log("Saved address:", address);

    setSelectedAddress(address);
    setIsLocationModalOpen(false);
  };

  const navigationItems = [
    {
      label: "Amazing Offers",
      icon: FaFire,
      href: "/",
    },
    {
      label: "Supermarket",
      icon: FaStore,
      href: "/",
    },
    {
      label: "Digital Gold & Silver",
      icon: FaGem,
      href: "/",
    },
    {
      label: "Best Sellers",
      icon: FaChartLine,
      href: "/",
    },
    {
      label: "Digistyle",
      icon: FaShoppingBag,
      href: "/",
    },
    {
      label: "Sell on Gandom",
      icon: FaTag,
      href: "/",
    },
  ];

  return (
    <>
      <header
        dir="ltr"
        className="relative z-[100] w-full bg-white"
      >
        {/* Top Banner */}
        <div className="relative h-[45px] w-full overflow-hidden sm:h-[50px] md:h-[60px]">
          <Image
            src="/icons/1.png"
            alt="Promotional banner"
            fill
            priority
            sizes="100vw"
            className="object-cover"
          />
        </div>

        {/* Main Header */}
        <div className="w-full border-b border-neutral-100 bg-white">
          <div
            className="
              mx-auto
              flex
              w-full
              items-center
              gap-2
              px-3
              py-3
              sm:gap-3
              sm:px-4
              lg:gap-5
              lg:px-6
            "
          >
            {/* Logo */}
            <Link
              href="/"
              aria-label="Home"
              className="
                hidden
                shrink-0
                items-center
                lg:flex
              "
            >
              <Image
                src="/icons/logo.png"
                alt="Gandom"
                width={140}
                height={45}
                className="h-auto w-[120px] object-contain xl:w-[140px]"
                priority
              />
            </Link>

            {/* Search */}
            <div className="min-w-0 flex-1">
              <SearchBar />
            </div>

            {/* Mobile Notification */}
            <div className="flex shrink-0 items-center lg:hidden">
              <NotificationButton />
            </div>

            {/* Authentication */}
            <div className="hidden shrink-0 items-center lg:flex">
              <AuthForm />
            </div>

            {/* Desktop Notification */}
            <div className="hidden shrink-0 items-center lg:flex">
              <NotificationButton />
            </div>

            {/* Cart */}
            <div className="hidden shrink-0 items-center lg:flex">
              <button
                type="button"
                onClick={() =>
                  setIsOpenMiniCart((prev) => !prev)
                }
                aria-label={`Shopping cart${
                  totalCount > 0
                    ? `, ${totalCount} items`
                    : ""
                }`}
                className="
                  relative
                  flex
                  h-10
                  w-10
                  items-center
                  justify-center
                  rounded-full
                  text-neutral-700
                  transition-colors
                  hover:bg-neutral-100
                  hover:text-red-500
                "
              >
                <MdShoppingCartCheckout className="h-6 w-6" />

                {totalCount > 0 && (
                  <span
                    className="
                      absolute
                      -right-1
                      -top-1
                      flex
                      h-5
                      min-w-5
                      items-center
                      justify-center
                      rounded-full
                      bg-red-500
                      px-1
                      text-[10px]
                      font-bold
                      text-white
                    "
                  >
                    {totalCount > 99 ? "99+" : totalCount}
                  </span>
                )}
              </button>

              {isOpenMiniCart && (
                <MiniCart
                  onClose={() => setIsOpenMiniCart(false)}
                />
              )}
            </div>
          </div>
        </div>

        {/* Navigation */}
        <div className="relative w-full border-b border-neutral-100 bg-white">
          <div
            className="
              mx-auto
              flex
              w-full
              items-center
              gap-2
              overflow-x-auto
              px-3
              py-2
              scrollbar-hide
              sm:px-4
              lg:gap-5
              lg:px-6
            "
          >
            {/* Categories Button - Desktop Only */}
            <button
              type="button"
              onClick={() => {
                if (isOpen) {
                  closeMenu();
                } else {
                  openMenu();
                }
              }}
              onMouseEnter={openMenu}
              aria-expanded={isOpen}
              aria-controls="category-menu"
              className="
                hidden
                shrink-0
                items-center
                gap-2
                whitespace-nowrap
                rounded-lg
                px-3
                py-2
                text-sm
                font-semibold
                text-neutral-800
                transition-colors
                hover:bg-neutral-100
                hover:text-red-600
                lg:flex
              "
            >
              <RxHamburgerMenu className="h-5 w-5" />

              <span>Categories</span>

              <TbChevronRight
                className={`
                  h-4
                  w-4
                  transition-transform
                  ${isOpen ? "rotate-90" : ""}
                `}
              />
            </button>

            {/* Navigation Items */}
            <div
              className="
                hidden
                items-center
                gap-1
                lg:flex
              "
            >
              {navigationItems.map((item) => {
                const Icon = item.icon;

                return (
                  <Link
                    key={item.label}
                    href={item.href}
                    className="
                      flex
                      shrink-0
                      items-center
                      gap-2
                      whitespace-nowrap
                      rounded-lg
                      px-3
                      py-2
                      text-sm
                      text-neutral-700
                      transition-colors
                      hover:bg-neutral-100
                      hover:text-red-600
                    "
                  >
                    <Icon className="h-4 w-4" />

                    <span>{item.label}</span>
                  </Link>
                );
              })}
            </div>

            {/* Location */}
            <button
              type="button"
              onClick={() => setIsLocationModalOpen(true)}
              className="
                ml-auto
                hidden
                shrink-0
                items-center
                gap-2
                whitespace-nowrap
                rounded-lg
                px-3
                py-2
                text-sm
                text-neutral-700
                transition-colors
                hover:bg-neutral-100
                hover:text-red-600
                lg:flex
              "
            >
              <CiLocationOn className="h-5 w-5" />

              <span>
                {selectedAddress?.title || "Select Location"}
              </span>
            </button>
          </div>

          {/* Mega Menu - Desktop Only */}
          {isOpen && (
            <div
              id="category-menu"
              onMouseEnter={clearCloseTimer}
              onMouseLeave={scheduleClose}
              className="
                absolute
                left-3
                right-auto
                top-[calc(100%+4px)]
                z-[80]
                hidden
                h-[350px]
                w-[750px]
                overflow-hidden
                rounded-xl
                border
                border-neutral-200
                bg-white
                shadow-2xl
                lg:flex
              "
            >
              {/* Main Categories */}
              <nav
                className="
                  flex
                  w-[220px]
                  shrink-0
                  flex-col
                  overflow-y-auto
                  border-r
                  border-neutral-100
                  bg-neutral-50
                  py-2
                "
                aria-label="Product categories"
              >
                {mainDataArray.map((category) => (
                  <button
                    key={category.id}
                    type="button"
                    onMouseEnter={() =>
                      setActiveId(category.id)
                    }
                    onClick={() =>
                      setActiveId(category.id)
                    }
                    className={`
                      flex
                      w-full
                      items-center
                      justify-between
                      gap-2
                      px-4
                      py-3
                      text-left
                      text-sm
                      font-medium
                      transition-colors
                      ${
                        activeId === category.id
                          ? "bg-white text-red-600 shadow-sm"
                          : "text-neutral-700 hover:bg-white hover:text-red-600"
                      }
                    `}
                  >
                    <div className="flex min-w-0 items-center gap-2">
                      <CategoryIcon
                        iconKey={category.slug}
                        className="h-5 w-5 shrink-0"
                      />

                      <span className="truncate">
                        {category.name}
                      </span>
                    </div>

                    <TbChevronRight className="h-4 w-4 shrink-0" />
                  </button>
                ))}
              </nav>

              {/* Sub Categories */}
              <div
                className="
                  relative
                  min-w-0
                  flex-1
                  overflow-y-auto
                  bg-white
                  px-6
                  py-5
                "
              >
                {/* Close Button */}
                <button
                  type="button"
                  onClick={closeMenu}
                  aria-label="Close categories"
                  className="
                    absolute
                    right-4
                    top-4
                    z-20
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-full
                    bg-neutral-100
                    text-neutral-600
                    transition-all
                    hover:bg-neutral-200
                    hover:text-neutral-900
                    active:scale-95
                  "
                >
                  <span
                    aria-hidden="true"
                    className="
                      text-2xl
                      font-light
                      leading-none
                    "
                  >
                    ×
                  </span>
                </button>

                {/* Sub Category Header */}
                <div className="mb-4 pr-12">
                  {targetCategory ? (
                    <Link
                      href={`/search/${targetCategory.slug}?category_id=${targetCategory.id}`}
                      onClick={closeMenu}
                      className="
                        inline-flex
                        items-center
                        gap-1
                        text-sm
                        font-semibold
                        text-red-600
                        transition-colors
                        hover:text-red-700
                      "
                    >
                      All Products

                      <TbChevronRight className="h-4 w-4" />
                    </Link>
                  ) : (
                    <span
                      className="
                        text-sm
                        font-semibold
                        text-neutral-500
                      "
                    >
                      Products
                    </span>
                  )}
                </div>

                {/* Loading */}
                {isSubLoading ? (
                  <div className="flex items-center justify-center py-10">
                    <div
                      className="
                        h-7
                        w-7
                        animate-spin
                        rounded-full
                        border-2
                        border-neutral-200
                        border-t-red-500
                      "
                    />
                  </div>
                ) : finalSubList.length > 0 ? (
                  <div
                    className="
                      grid
                      grid-cols-3
                      gap-6
                    "
                  >
                    {finalSubList.map((subCategory) => (
                      <Link
                        key={subCategory.id}
                        href={`/search/${subCategory.slug}?category_id=${subCategory.id}`}
                        onClick={closeMenu}
                        className="
                          group
                          flex
                          items-center
                          justify-between
                          gap-2
                          rounded-lg
                          border
                          border-neutral-100
                          px-3
                          py-3
                          text-sm
                          text-neutral-700
                          transition-all
                          hover:border-red-100
                          hover:bg-red-50
                          hover:text-red-600
                        "
                      >
                        <span className="truncate">
                          {subCategory.name}
                        </span>

                        <TbChevronRight
                          className="
                            h-4
                            w-4
                            shrink-0
                            text-neutral-400
                            transition-transform
                            group-hover:translate-x-1
                            group-hover:text-red-500
                          "
                        />
                      </Link>
                    ))}
                  </div>
                ) : (
                  <div
                    className="
                      flex
                      min-h-[180px]
                      items-center
                      justify-center
                      text-sm
                      text-neutral-500
                    "
                  >
                    No subcategories available.
                  </div>
                )}
              </div>
            </div>
          )}
        </div>
      </header>

      {/* Location Modal */}
      {isLocationModalOpen && (
        <LocationModal
          onClose={() => setIsLocationModalOpen(false)}
          onLocationSaved={handleLocationSaved}
        />
      )}

      {/* Mobile Bottom Navigation */}
      <MobileBottomNav
        totalCount={totalCount}
        openMenu={openMenu}
      />
    </>
  );
}

export default Header;

