"use client";

import React, { useCallback, useEffect, useRef, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import ThemeToggle from "@/components/ThemeToggle";

import { CiMobile1, CiLocationOn } from "react-icons/ci";
import {
  TbDeviceLaptop,
  TbFridge,
  TbShirt,
  TbChevronRight,
  TbHeartbeat,
  TbTools,
  TbTrophy,
} from "react-icons/tb";

import { RiNotification3Line } from "react-icons/ri";
import { GiGoldBar, GiCarKey, GiLipstick } from "react-icons/gi";

import { MdShoppingCartCheckout } from "react-icons/md";
import { RxHamburgerMenu } from "react-icons/rx";

import {
  FaFire,
  FaStore,
  FaGem,
  FaChartLine,
  FaShoppingBag,
  FaTag,
} from "react-icons/fa";

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

/* =========================================================
   Category Icons
========================================================= */

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

/* =========================================================
   Notification
========================================================= */

function NotificationButton() {
  return (
    <Link
      href="/profile/notification"
      aria-label="Notifications"
      className="
        flex h-10 w-10 shrink-0 items-center justify-center
        rounded-full
        text-neutral-700
        transition-all duration-200
        hover:bg-neutral-100
        hover:text-red-500
        dark:text-neutral-300
        dark:hover:bg-neutral-800
        dark:hover:text-red-400
      "
    >
      <RiNotification3Line className="h-[23px] w-[23px]" />
    </Link>
  );
}

/* =========================================================
   Recursive Category Tree
========================================================= */

function CategoryTree({
  items,
  onNavigate,
  level = 0,
}) {
  const [collapsedItems, setCollapsedItems] = useState({});

  if (!Array.isArray(items) || items.length === 0) {
    return null;
  }

  const toggleCategory = (categoryId) => {
    setCollapsedItems((prev) => ({
      ...prev,
      [categoryId]: !prev[categoryId],
    }));
  };

  return (
    <div
      className={
        level > 0
          ? `
            ml-4 space-y-1
            border-l border-neutral-200
            pl-3
            dark:border-neutral-700
          `
          : "space-y-1"
      }
    >
      {items.map((category) => {
        const hasChildren =
          Array.isArray(category?.children) &&
          category.children.length > 0;

        const isCollapsed = Boolean(
          collapsedItems[category.id]
        );

        return (
          <div key={category.id}>
            <div className="flex items-center gap-1">
              <Link
                href={`/search/${category.slug}?category_id=${category.id}`}
                onClick={onNavigate}
                className="
                  group flex min-w-0 flex-1
                  items-center justify-between
                  gap-3 rounded-lg
                  px-3 py-2.5
                  text-sm
                  text-neutral-700
                  transition-all duration-200

                  hover:bg-red-50
                  hover:text-red-600

                  dark:text-neutral-200
                  dark:hover:bg-neutral-800
                  dark:hover:text-red-400
                "
              >
                <span className="min-w-0 truncate">
                  {category.name}
                </span>
              </Link>

              {hasChildren && (
                <button
                  type="button"
                  aria-label={
                    isCollapsed
                      ? `Open ${category.name}`
                      : `Close ${category.name}`
                  }
                  aria-expanded={!isCollapsed}
                  onClick={(event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    toggleCategory(category.id);
                  }}
                  className="
                    flex h-9 w-9 shrink-0
                    items-center justify-center
                    rounded-lg
                    text-neutral-400
                    transition-all duration-200

                    hover:bg-red-50
                    hover:text-red-600

                    dark:text-neutral-500
                    dark:hover:bg-neutral-800
                    dark:hover:text-red-400
                  "
                >
                  <TbChevronRight
                    className={`
                      h-4 w-4
                      transition-transform duration-200
                      ${isCollapsed ? "" : "rotate-90"}
                    `}
                  />
                </button>
              )}
            </div>

            {hasChildren && !isCollapsed && (
              <CategoryTree
                items={category.children}
                onNavigate={onNavigate}
                level={level + 1}
              />
            )}
          </div>
        );
      })}
    </div>
  );
}

/* =========================================================
   Header
========================================================= */

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState(null);

  const [isOpenMiniCart, setIsOpenMiniCart] = useState(false);

  const [isLocationModalOpen, setIsLocationModalOpen] =
    useState(false);

  const [selectedAddress, setSelectedAddress] = useState(null);

  const closeTimer = useRef(null);
  const miniCartTimer = useRef(null);

  /* =======================================================
     Cart
  ======================================================= */

  const { data: cart } = useCart();

  const totalCount = cart?.data?.items_count || 0;

  /* =======================================================
     User
  ======================================================= */

  const { data: user } = useGetUserData();

  void user;

  /* =======================================================
     Main Categories
  ======================================================= */

  const { data: categoriess } =
    useGetMainCategories();

  const mainDataArray =
    categoriess?.data?.data || [];

  /* =======================================================
     Active Category / Children
  ======================================================= */

  const {
    data: categoryMenu,
    isLoading: isSubLoading,
  } = useGetSubCategory(activeId);

  const activeCategory =
    categoryMenu?.data?.data || null;

  const finalSubList =
    Array.isArray(activeCategory?.children)
      ? activeCategory.children
      : [];

  /* =======================================================
     Default Active Category
  ======================================================= */

  useEffect(() => {
    if (
      mainDataArray.length > 0 &&
      !activeId
    ) {
      const targetCategory =
        mainDataArray.find(
          (category) =>
            category.slug === "mobile"
        );

      setActiveId(
        targetCategory?.id ||
          mainDataArray[0]?.id
      );
    }
  }, [mainDataArray, activeId]);

  /* =======================================================
     Menu Timers
  ======================================================= */

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

  /* =======================================================
     Cleanup
  ======================================================= */

  useEffect(() => {
    return () => {
      if (closeTimer.current) {
        clearTimeout(closeTimer.current);
      }

      if (miniCartTimer.current) {
        clearTimeout(miniCartTimer.current);
      }
    };
  }, []);

  /* =======================================================
     Mini Cart
  ======================================================= */

  const openMiniCart = useCallback(() => {
    if (miniCartTimer.current) {
      clearTimeout(miniCartTimer.current);
      miniCartTimer.current = null;
    }

    setIsOpenMiniCart(true);
  }, []);

  const closeMiniCart = useCallback(() => {
    if (miniCartTimer.current) {
      clearTimeout(miniCartTimer.current);
    }

    miniCartTimer.current = setTimeout(() => {
      setIsOpenMiniCart(false);
    }, 150);
  }, []);

  const handleCartClick = () => {
    setIsOpenMiniCart(false);
  };

  /* =======================================================
     Location
  ======================================================= */

  const handleLocationSaved = (address) => {
    setSelectedAddress(address);
    setIsLocationModalOpen(false);
  };

  /* =======================================================
     Navigation Items
  ======================================================= */

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

  /* =======================================================
     Render
  ======================================================= */

  return (
    <>
      <header
        dir="ltr"
        className="
          relative z-[100] w-full
          bg-white
          text-neutral-900
          transition-colors duration-200

          dark:bg-neutral-950
          dark:text-neutral-100
        "
      >
        {/* =================================================
            Top Banner
        ================================================== */}

        <div
          className="
            relative h-[45px] w-full overflow-hidden
            sm:h-[50px]
            md:h-[60px]
          "
        >
          <Image
            src="/icons/1.png"
            alt="Promotional banner"
            fill
            priority
            sizes="100vw"
            className="object-cover"
          />
        </div>

        {/* =================================================
            Main Header
        ================================================== */}

        <div
          className="
            w-full
            border-b border-neutral-100
            bg-white
            transition-colors duration-200

            dark:border-neutral-800
            dark:bg-neutral-950
          "
        >
          <div
            className="
              mx-auto flex w-full items-center
              gap-2
              px-3 py-3

              sm:gap-3 sm:px-4
              lg:gap-5 lg:px-6
            "
          >
            {/* Logo */}

            <Link
              href="/"
              aria-label="Home"
              className="
                hidden shrink-0
                items-center
                lg:flex
              "
            >
              <Image
                src="/icons/en-logo.svg"
                alt="Gandom"
                width={140}
                height={45}
                className="
                  h-auto w-[120px]
                  object-contain
                  xl:w-[140px]
                "
                priority
              />
            </Link>

            {/* Search */}

            <div className="min-w-0 flex-1">
              <SearchBar />
            </div>

            {/* Mobile Actions */}

            <div
              className="
                flex shrink-0 items-center gap-1
                lg:hidden
              "
            >
              <ThemeToggle />

              <NotificationButton />
            </div>

            {/* Authentication */}

            <div
              className="
                hidden shrink-0
                items-center
                lg:flex
              "
            >
              <AuthForm />
            </div>

            {/* Desktop Actions */}

            <div
              className="
                hidden shrink-0
                items-center gap-1
                lg:flex
              "
            >
              <ThemeToggle />

              <NotificationButton />
            </div>

            {/* Cart */}

            <div
              className="
                relative hidden shrink-0
                items-center
                lg:flex
              "
              onMouseEnter={openMiniCart}
              onMouseLeave={closeMiniCart}
            >
              <Link
                href="/checkout/cart"
                onClick={handleCartClick}
                aria-label={`Shopping cart${
                  totalCount > 0
                    ? `, ${totalCount} items`
                    : ""
                }`}
                className="
                  relative flex h-10 w-10
                  items-center justify-center
                  rounded-full

                  text-neutral-700
                  transition-all duration-200

                  hover:bg-neutral-100
                  hover:text-red-500

                  dark:text-neutral-300
                  dark:hover:bg-neutral-800
                  dark:hover:text-red-400
                "
              >
                <MdShoppingCartCheckout
                  className="h-6 w-6"
                />

                {totalCount > 0 && (
                  <span
                    className="
                      absolute -right-1 -top-1
                      flex h-5 min-w-5
                      items-center justify-center
                      rounded-full
                      bg-red-500
                      px-1
                      text-[10px]
                      font-bold
                      text-white
                      shadow-sm
                    "
                  >
                    {totalCount > 99
                      ? "99+"
                      : totalCount}
                  </span>
                )}
              </Link>

              {isOpenMiniCart && (
                <MiniCart
                  onClose={() =>
                    setIsOpenMiniCart(false)
                  }
                />
              )}
            </div>
          </div>
        </div>

        {/* =================================================
            Navigation
        ================================================== */}

        <div
          className="
            relative w-full
            border-b border-neutral-100
            bg-white
            transition-colors duration-200

            dark:border-neutral-800
            dark:bg-neutral-950
          "
        >
          <div
            className="
              mx-auto flex w-full
              items-center gap-2
              overflow-x-auto
              px-3 py-2
              scrollbar-hide

              sm:px-4
              lg:gap-5 lg:px-6
            "
          >
            {/* Desktop Categories Button */}

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
                hidden shrink-0
                items-center gap-2
                whitespace-nowrap
                rounded-lg
                px-3 py-2
                text-sm font-semibold

                text-neutral-800
                transition-all duration-200

                hover:bg-neutral-100
                hover:text-red-600

                dark:text-neutral-100
                dark:hover:bg-neutral-800
                dark:hover:text-red-400

                lg:flex
              "
            >
              <RxHamburgerMenu className="h-5 w-5" />

              <span>Categories</span>

              <TbChevronRight
                className={`
                  h-4 w-4
                  transition-transform duration-200

                  ${
                    isOpen
                      ? "rotate-90"
                      : ""
                  }
                `}
              />
            </button>

            {/* Navigation Items */}

            <div
              className="
                hidden items-center
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
                      flex shrink-0
                      items-center gap-2
                      whitespace-nowrap
                      rounded-lg
                      px-3 py-2
                      text-sm

                      text-neutral-700
                      transition-all duration-200

                      hover:bg-neutral-100
                      hover:text-red-600

                      dark:text-neutral-300
                      dark:hover:bg-neutral-800
                      dark:hover:text-red-400
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
              onClick={() =>
                setIsLocationModalOpen(true)
              }
              className="
                ml-auto hidden shrink-0
                items-center gap-2
                whitespace-nowrap
                rounded-lg
                px-3 py-2
                text-sm

                text-neutral-700
                transition-all duration-200

                hover:bg-neutral-100
                hover:text-red-600

                dark:text-neutral-300
                dark:hover:bg-neutral-800
                dark:hover:text-red-400

                lg:flex
              "
            >
              <CiLocationOn className="h-5 w-5" />

              <span>
                {selectedAddress?.title ||
                  "Select Location"}
              </span>
            </button>
          </div>

          {/* =================================================
              Desktop Mega Menu
          ================================================== */}

          {isOpen && (
            <div
              id="category-menu"
              onMouseEnter={clearCloseTimer}
              onMouseLeave={scheduleClose}
              className="
                absolute left-3 right-auto
                top-[calc(100%+4px)]
                z-[80]

                hidden h-[350px]
                w-[750px]
                overflow-hidden
                rounded-2xl

                border border-neutral-200
                bg-white

                shadow-2xl
                shadow-neutral-900/10

                dark:border-neutral-800
                dark:bg-neutral-900
                dark:shadow-black/40

                lg:flex
              "
            >
              {/* Main Categories */}

              <nav
                className="
                  flex w-[220px]
                  shrink-0 flex-col
                  overflow-y-auto

                  border-r border-neutral-100
                  bg-neutral-50
                  py-2

                  dark:border-neutral-800
                  dark:bg-neutral-950
                "
                aria-label="Product categories"
              >
                {mainDataArray.map(
                  (category) => (
                    <button
                      key={category.id}
                      type="button"
                      onMouseEnter={() =>
                        setActiveId(
                          category.id
                        )
                      }
                      onClick={() =>
                        setActiveId(
                          category.id
                        )
                      }
                      className={`
                        flex w-full
                        items-center
                        justify-between
                        gap-2
                        px-4 py-3
                        text-left
                        text-sm
                        font-medium
                        transition-all duration-200

                        ${
                          activeId ===
                          category.id
                            ? `
                              bg-white
                              text-red-600
                              shadow-sm

                              dark:bg-neutral-900
                              dark:text-red-400
                            `
                            : `
                              text-neutral-700

                              hover:bg-white
                              hover:text-red-600

                              dark:text-neutral-300
                              dark:hover:bg-neutral-900
                              dark:hover:text-red-400
                            `
                        }
                      `}
                    >
                      <div
                        className="
                          flex min-w-0
                          items-center gap-2
                        "
                      >
                        <CategoryIcon
                          iconKey={
                            category.slug
                          }
                          className="
                            h-5 w-5 shrink-0
                          "
                        />

                        <span className="truncate">
                          {category.name}
                        </span>
                      </div>

                      <TbChevronRight
                        className="
                          h-4 w-4 shrink-0
                          text-neutral-400
                          dark:text-neutral-500
                        "
                      />
                    </button>
                  )
                )}
              </nav>

              {/* Desktop Category Content */}

              <div
                className="
                  relative min-w-0
                  flex-1 overflow-y-auto

                  bg-white
                  px-6 py-5

                  dark:bg-neutral-900
                "
              >
                {/* Close */}

                <button
                  type="button"
                  onClick={closeMenu}
                  aria-label="Close categories"
                  className="
                    absolute right-4 top-4
                    z-20
                    flex h-9 w-9
                    items-center justify-center
                    rounded-full

                    bg-neutral-100
                    text-neutral-600

                    transition-all duration-200

                    hover:bg-neutral-200
                    hover:text-neutral-900

                    dark:bg-neutral-800
                    dark:text-neutral-300
                    dark:hover:bg-neutral-700
                    dark:hover:text-white

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

                {/* Active Category */}

                <div className="mb-5 pr-12">
                  {activeCategory ? (
                    <Link
                      href={`/search/${activeCategory.slug}?category_id=${activeCategory.id}`}
                      onClick={closeMenu}
                      className="
                        inline-flex
                        items-center gap-1
                        text-base font-bold

                        text-neutral-900
                        transition-colors

                        hover:text-red-600

                        dark:text-white
                        dark:hover:text-red-400
                      "
                    >
                      {activeCategory.name}

                      <TbChevronRight className="h-4 w-4" />
                    </Link>
                  ) : (
                    <span
                      className="
                        text-sm
                        font-semibold

                        text-neutral-500
                        dark:text-neutral-400
                      "
                    >
                      Categories
                    </span>
                  )}
                </div>

                {/* Loading */}

                {isSubLoading ? (
                  <div
                    className="
                      flex items-center
                      justify-center
                      py-10
                    "
                  >
                    <div
                      className="
                        h-7 w-7
                        animate-spin
                        rounded-full
                        border-2
                        border-neutral-200
                        border-t-red-500

                        dark:border-neutral-700
                        dark:border-t-red-400
                      "
                    />
                  </div>
                ) : finalSubList.length > 0 ? (
                  <CategoryTree
                    items={finalSubList}
                    onNavigate={closeMenu}
                  />
                ) : (
                  <div
                    className="
                      flex min-h-[180px]
                      items-center
                      justify-center
                      text-sm

                      text-neutral-500
                      dark:text-neutral-400
                    "
                  >
                    No subcategories available.
                  </div>
                )}

                {/* View All */}

                {activeCategory && (
                  <Link
                    href={`/search/${activeCategory.slug}?category_id=${activeCategory.id}`}
                    onClick={closeMenu}
                    className="
                      mt-5 flex
                      items-center
                      justify-center
                      gap-1
                      rounded-lg

                      bg-neutral-50
                      px-4 py-3

                      text-sm
                      font-semibold
                      text-red-600

                      transition-all duration-200

                      hover:bg-red-50

                      dark:bg-neutral-800
                      dark:text-red-400
                      dark:hover:bg-red-950/40
                    "
                  >
                    View all products

                    <TbChevronRight className="h-4 w-4" />
                  </Link>
                )}
              </div>
            </div>
          )}

          {/* =================================================
              Mobile Category Drawer
          ================================================== */}

          {isOpen && (
            <div
              className="
                fixed inset-0
                z-[999]
                flex
                lg:hidden
              "
              role="dialog"
              aria-modal="true"
              aria-label="Product categories"
            >
              {/* Overlay */}

              <button
                type="button"
                aria-label="Close categories"
                onClick={closeMenu}
                className="
                  absolute inset-0
                  bg-black/50
                  backdrop-blur-[2px]
                "
              />

              {/* Drawer */}

              <div
                className="
                  relative z-10
                  flex h-full
                  w-[88%]
                  max-w-[380px]
                  flex-col

                  bg-white
                  shadow-2xl

                  dark:bg-neutral-950
                "
                dir="ltr"
              >
                {/* Mobile Header */}

                <div
                  className="
                    flex shrink-0
                    items-center
                    justify-between

                    border-b
                    border-neutral-200

                    px-4 py-4

                    dark:border-neutral-800
                  "
                >
                  <div
                    className="
                      flex items-center gap-2
                    "
                  >
                    <RxHamburgerMenu
                      className="
                        h-5 w-5
                        text-red-600
                        dark:text-red-400
                      "
                    />

                    <h2
                      className="
                        text-base
                        font-bold

                        text-neutral-900
                        dark:text-white
                      "
                    >
                      Categories
                    </h2>
                  </div>

                  <button
                    type="button"
                    onClick={closeMenu}
                    aria-label="Close categories"
                    className="
                      flex h-9 w-9
                      items-center
                      justify-center
                      rounded-full

                      bg-neutral-100
                      text-neutral-600

                      transition-all duration-200

                      hover:bg-neutral-200

                      dark:bg-neutral-800
                      dark:text-neutral-300
                      dark:hover:bg-neutral-700

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
                </div>

                {/* Mobile Categories */}

                <div
                  className="
                    min-h-0
                    flex-1
                    overflow-y-auto
                  "
                >
                  {mainDataArray.length === 0 ? (
                    <div
                      className="
                        flex items-center
                        justify-center
                        py-12
                      "
                    >
                      <div
                        className="
                          h-7 w-7
                          animate-spin
                          rounded-full
                          border-2

                          border-neutral-200
                          border-t-red-500

                          dark:border-neutral-700
                          dark:border-t-red-400
                        "
                      />
                    </div>
                  ) : (
                    <div
                      className="
                        divide-y
                        divide-neutral-100

                        dark:divide-neutral-800
                      "
                    >
                      {mainDataArray.map(
                        (category) => {
                          const isActive =
                            activeId ===
                            category.id;

                          return (
                            <div
                              key={category.id}
                            >
                              {/* Main Category */}

                              <button
                                type="button"
                                onClick={() =>
                                  setActiveId(
                                    isActive
                                      ? null
                                      : category.id
                                  )
                                }
                                className={`
                                  flex w-full
                                  items-center
                                  justify-between
                                  gap-3
                                  px-4 py-4
                                  text-left
                                  transition-all duration-200

                                  ${
                                    isActive
                                      ? `
                                        bg-red-50
                                        text-red-600

                                        dark:bg-red-950/30
                                        dark:text-red-400
                                      `
                                      : `
                                        text-neutral-800

                                        hover:bg-neutral-50

                                        dark:text-neutral-200
                                        dark:hover:bg-neutral-900
                                      `
                                  }
                                `}
                              >
                                <div
                                  className="
                                    flex min-w-0
                                    items-center
                                    gap-3
                                  "
                                >
                                  <CategoryIcon
                                    iconKey={
                                      category.slug
                                    }
                                    className={`
                                      h-5 w-5
                                      shrink-0

                                      ${
                                        isActive
                                          ? `
                                            text-red-600
                                            dark:text-red-400
                                          `
                                          : `
                                            text-neutral-500
                                            dark:text-neutral-400
                                          `
                                      }
                                    `}
                                  />

                                  <span
                                    className="
                                      truncate
                                      text-sm
                                      font-medium
                                    "
                                  >
                                    {category.name}
                                  </span>
                                </div>

                                <TbChevronRight
                                  className={`
                                    h-5 w-5
                                    shrink-0
                                    transition-transform duration-200

                                    ${
                                      isActive
                                        ? `
                                          rotate-90
                                          text-red-600
                                          dark:text-red-400
                                        `
                                        : `
                                          text-neutral-400
                                          dark:text-neutral-500
                                        `
                                    }
                                  `}
                                />
                              </button>

                              {/* Active Category Children */}

                              {isActive && (
                                <div
                                  className="
                                    bg-neutral-50
                                    px-4 pb-4

                                    dark:bg-neutral-900
                                  "
                                >
                                  {isSubLoading ? (
                                    <div
                                      className="
                                        flex
                                        justify-center
                                        py-6
                                      "
                                    >
                                      <div
                                        className="
                                          h-6 w-6
                                          animate-spin
                                          rounded-full
                                          border-2

                                          border-neutral-200
                                          border-t-red-500

                                          dark:border-neutral-700
                                          dark:border-t-red-400
                                        "
                                      />
                                    </div>
                                  ) : finalSubList.length > 0 ? (
                                    <div className="pt-3">
                                      <CategoryTree
                                        items={
                                          finalSubList
                                        }
                                        onNavigate={
                                          closeMenu
                                        }
                                      />
                                    </div>
                                  ) : (
                                    <div
                                      className="
                                        py-5
                                        text-center
                                        text-sm

                                        text-neutral-500
                                        dark:text-neutral-400
                                      "
                                    >
                                      No subcategories
                                      available.
                                    </div>
                                  )}

                                  {/* View All Products */}

                                  <Link
                                    href={
                                      activeCategory
                                        ? `/search/${activeCategory.slug}?category_id=${activeCategory.id}`
                                        : "#"
                                    }
                                    onClick={
                                      closeMenu
                                    }
                                    className="
                                      mt-3
                                      flex
                                      items-center
                                      justify-center
                                      gap-1
                                      rounded-lg

                                      bg-white
                                      px-3 py-3

                                      text-sm
                                      font-semibold
                                      text-red-600

                                      shadow-sm
                                      transition-all duration-200

                                      hover:bg-red-50

                                      dark:bg-neutral-800
                                      dark:text-red-400
                                      dark:hover:bg-red-950/40
                                    "
                                  >
                                    View all products

                                    <TbChevronRight
                                      className="
                                        h-4 w-4
                                      "
                                    />
                                  </Link>
                                </div>
                              )}
                            </div>
                          );
                        }
                      )}
                    </div>
                  )}
                </div>
              </div>
            </div>
          )}
        </div>
      </header>

      {/* =====================================================
          Location Modal
      ====================================================== */}

      {isLocationModalOpen && (
        <LocationModal
          onClose={() =>
            setIsLocationModalOpen(false)
          }
          onLocationSaved={
            handleLocationSaved
          }
        />
      )}

      {/* =====================================================
          Mobile Bottom Navigation
      ====================================================== */}

      <MobileBottomNav
        totalCount={totalCount}
        openMenu={openMenu}
      />
    </>
  );
}

export default Header;