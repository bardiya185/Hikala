"use client";

import { useRef, useState, useCallback, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";

import {
  CiMobile1,
  CiSearch,
  CiLocationOn,
} from "react-icons/ci";

import {
  TbDeviceLaptop,
  TbFridge,
  TbShirt,
  TbChevronRight,
  TbMapPin,
} from "react-icons/tb";

import { GiGoldBar, GiCarKey } from "react-icons/gi";

import { MdShoppingCartCheckout } from "react-icons/md";

import { RxHamburgerMenu } from "react-icons/rx";

import { IoClose } from "react-icons/io5";

import {
  FaFire,
  FaStore,
  FaGem,
  FaChartLine,
  FaShoppingBag,
  FaTag,
  FaChevronRight,
} from "react-icons/fa";

import AuthForm from "../AuthForm";

import {
  useCart,
  useGetMainCategories,
  useGetSubCategory,
  useGetUserData,
} from "@/core/services/queries";

import SearchBar from "@/components/atom/SearchBar";

import MiniCart from "../miniCart";

import MobileBottomNav from "./MobileBottomNav";

import LocationModal from "./location/LocationModal";

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

  if (!IconComponent) {
    return null;
  }

  return <IconComponent className={className} />;
}

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState(null);
  const [isOpenMiniCart, setIsOpenMiniCart] = useState(false);

  const [isLocationModalOpen, setIsLocationModalOpen] =
    useState(false);

  const [selectedAddress, setSelectedAddress] =
    useState(null);

  const closeTimer = useRef(null);

  // =========================
  // Cart
  // =========================

  const { data: cart } = useCart();

  const totalCount =
    cart?.data?.items_count || 0;

  // =========================
  // User
  // =========================

  const { data: user } = useGetUserData();

  // =========================
  // Categories
  // =========================

  const { data: categoriess } =
    useGetMainCategories();

  const mainDataArray =
    categoriess?.data?.data || [];

  const {
    data: categoryMenu,
    isLoading: isSubLoading,
  } = useGetSubCategory(activeId);

  const subDataArray =
    categoryMenu?.data?.data || [];

  // =========================
  // Default Category
  // =========================

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

  // =========================
  // Mega Menu
  // =========================

  const clearCloseTimer =
    useCallback(() => {
      if (closeTimer.current) {
        clearTimeout(
          closeTimer.current
        );

        closeTimer.current = null;
      }
    }, []);

  const scheduleClose =
    useCallback(() => {
      clearCloseTimer();

      closeTimer.current =
        setTimeout(() => {
          setIsOpen(false);
        }, 150);
    }, [clearCloseTimer]);

  const openMenu =
    useCallback(() => {
      clearCloseTimer();
      setIsOpen(true);
    }, [clearCloseTimer]);

  useEffect(() => {
    return () => {
      if (closeTimer.current) {
        clearTimeout(
          closeTimer.current
        );
      }
    };
  }, []);

  // =========================
  // Active Category
  // =========================

  const activeCategory =
    mainDataArray?.find(
      (category) =>
        category.id === activeId
    ) || mainDataArray[0];

  // =========================
  // Target Category
  // =========================

  const getTargetCategory = (
    category
  ) => {
    if (!category) {
      return null;
    }

    if (
      category.children &&
      category.children.length > 0
    ) {
      return category.children[0];
    }

    return category;
  };

  const targetCategory =
    getTargetCategory(activeCategory);

  // =========================
  // Sub Categories
  // =========================

  const finalSubList =
    Array.isArray(subDataArray)
      ? subDataArray
      : subDataArray?.children ||
        subDataArray?.subs ||
        subDataArray?.subcategories ||
        [];

  // =========================
  // Location
  // =========================

  const handleLocationSaved = (
    address
  ) => {
    console.log(
      "Saved address:",
      address
    );

    setSelectedAddress(address);

    setIsLocationModalOpen(false);
  };

  // =========================
  // Extra Navigation Items
  // =========================

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
        className="w-full select-none bg-white font-sans"
      >
        {/* =====================================
            Top Banner
        ====================================== */}

        <div className="w-full overflow-hidden">
          <Image
            src="/icons/1.png"
            width={1270}
            height={60}
            alt="banner"
            priority
            className="block h-[45px] w-full object-cover sm:h-[50px] md:h-[60px]"
          />
        </div>

        {/* =====================================
            Main Header
        ====================================== */}

        <div className="flex items-center gap-3 px-3 py-3 sm:gap-4 sm:px-4 md:py-4 lg:gap-6 lg:px-6">

          {/* Logo */}

          <Link
            href="/"
            className="hidden shrink-0 items-center lg:flex"
          >
            <Image
              src="/icons/en-logo.svg"
              width={195}
              height={30}
              alt="logo"
              priority
              className="h-auto w-[195px]"
            />
          </Link>

          {/* Search */}

          <div className="min-w-0 flex-1">
            <SearchBar />
          </div>

          {/* Desktop Auth */}

          <div className="hidden shrink-0 items-center lg:flex">
            <AuthForm />
          </div>

          {/* Desktop Cart */}

          <div
            onMouseEnter={() =>
              setIsOpenMiniCart(true)
            }
            onMouseLeave={() =>
              setIsOpenMiniCart(false)
            }
            className="relative hidden shrink-0 items-center lg:flex"
          >
            <Link
              href="/checkout/cart"
              className="relative block"
            >
              <div className="rounded-full p-1.5 transition-colors hover:bg-neutral-100 sm:p-2">
                <MdShoppingCartCheckout className="h-[21px] w-[21px] text-neutral-700 sm:h-[24px] sm:w-[24px]" />
              </div>

              <span className="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white shadow-sm sm:h-5 sm:w-5 sm:text-[11px]">
                {totalCount > 99
                  ? "+99"
                  : totalCount}
              </span>
            </Link>

            {isOpenMiniCart && (
              <div className="hidden sm:block">
                <MiniCart />
              </div>
            )}
          </div>
        </div>

        {/* =====================================
            Categories + Navigation + Location
        ====================================== */}

        <div
          className="relative mt-3 px-3 sm:mt-4 sm:px-4 lg:px-6"
          onMouseLeave={scheduleClose}
        >
          <div className="flex items-center gap-5">

            {/* =========================
                Categories
            ========================== */}

            <button
              type="button"
              onMouseEnter={openMenu}
              onClick={() =>
                setIsOpen(
                  (prev) => !prev
                )
              }
              className="hidden cursor-pointer items-center gap-2 whitespace-nowrap border-r border-neutral-200 py-2 pr-5 text-neutral-800 transition-colors hover:text-red-600 lg:flex"
            >
              <RxHamburgerMenu className="h-5 w-5" />

              <span className="text-xs font-bold sm:text-sm">
                Categories
              </span>
            </button>

            {/* =========================
                Extra Navigation
            ========================== */}

            <nav className="hidden min-w-0 flex-1 items-center gap-5 lg:flex">

              {navigationItems.map(
                (item) => {
                  const Icon =
                    item.icon;

                  return (
                    <Link
                      key={item.label}
                      href={item.href}
                      className="group flex shrink-0 cursor-pointer items-center gap-1.5 whitespace-nowrap py-2 text-xs font-medium text-neutral-600 transition-colors hover:text-red-600"
                    >
                      <Icon className="h-[15px] w-[15px] text-neutral-500 transition-colors group-hover:text-red-600" />

                      <span>
                        {item.label}
                      </span>
                    </Link>
                  );
                }
              )}
            </nav>

            {/* =========================
                Location - Last / Right
            ========================== */}

            <button
              type="button"
              onClick={() =>
                setIsLocationModalOpen(
                  true
                )
              }
              className="ml-auto hidden shrink-0 cursor-pointer items-center gap-1.5 py-2 text-sm font-medium text-orange-500 transition-colors hover:text-orange-600 lg:flex"
            >
              <CiLocationOn className="h-[20px] w-[20px] shrink-0 text-orange-400" />

              <span className="max-w-[250px] truncate">
                {selectedAddress?.address ||
                  "Select Location"}
              </span>
            </button>
          </div>

          {/* =====================================
              Desktop Mega Menu
          ====================================== */}

          {isOpen && (
            <div
              className="absolute left-3 top-[calc(100%+4px)] z-[80] hidden h-[350px] w-[750px] overflow-hidden rounded-xl border border-neutral-100 bg-white shadow-2xl lg:flex"
              onMouseEnter={openMenu}
            >
              {/* Left Categories */}

              <div className="w-[220px] shrink-0 overflow-y-auto border-r border-neutral-100 bg-neutral-50">
                {mainDataArray?.map(
                  (category) => {
                    const isActive =
                      activeId ===
                      category.id;

                    return (
                      <button
                        key={
                          category.id
                        }
                        type="button"
                        onMouseEnter={() =>
                          setActiveId(
                            category.id
                          )
                        }
                        className={`flex w-full cursor-pointer items-center justify-between px-4 py-3 text-left transition-colors ${
                          isActive
                            ? "bg-white text-red-600"
                            : "text-neutral-700 hover:bg-white hover:text-red-600"
                        }`}
                      >
                        <div className="flex min-w-0 items-center gap-3">
                          <CategoryIcon
                            iconKey={
                              category.icon_key
                            }
                            className={`h-5 w-5 shrink-0 ${
                              isActive
                                ? "text-red-600"
                                : "text-neutral-500"
                            }`}
                          />

                          <span className="truncate text-xs font-medium">
                            {
                              category.name
                            }
                          </span>
                        </div>

                        <TbChevronRight
                          className={`h-4 w-4 shrink-0 ${
                            isActive
                              ? "text-red-600"
                              : "text-neutral-400"
                          }`}
                        />
                      </button>
                    );
                  }
                )}
              </div>

              {/* Right Sub Categories */}

              <div className="min-w-0 flex-1 overflow-y-auto bg-white p-5">
                {isSubLoading ? (
                  <div className="flex h-full items-center justify-center">
                    <div className="h-7 w-7 animate-spin rounded-full border-2 border-neutral-200 border-t-red-500" />
                  </div>
                ) : (
                  <>
                    {/* All Products */}

                    <Link
                      href={`/search/${
                        targetCategory?.slug ||
                        "all"
                      }?category_id=${
                        targetCategory?.id ||
                        ""
                      }`}
                      className="mb-4 flex items-center gap-1 whitespace-nowrap text-[13px] font-bold text-red-600 hover:underline"
                    >
                      All{" "}
                      {
                        activeCategory?.name
                      }{" "}
                      Products

                      <TbChevronRight className="h-3.5 w-3.5" />
                    </Link>

                    {/* Sub Category Grid */}

                    <div className="grid grid-cols-2 gap-x-6 gap-y-6 lg:grid-cols-3">
                      {finalSubList?.map(
                        (col) => {
                          const nestedItems =
                            col.children ||
                            col.subs ||
                            col.leaves ||
                            [];

                          return (
                            <div
                              key={col.id}
                              className="min-w-0"
                            >
                              {/* Column Title */}

                              <Link
                                href={`/search/${
                                  col.slug ||
                                  "category"
                                }?category_id=${
                                  col.id
                                }`}
                                className="mb-2 block truncate text-[12px] font-bold text-neutral-800 transition-colors hover:text-red-600"
                              >
                                {col.name}
                              </Link>

                              {/* Leaves */}

                              {nestedItems?.length >
                                0 && (
                                <div className="flex flex-col gap-1.5">
                                  {nestedItems.map(
                                    (
                                      leaf
                                    ) => (
                                      <Link
                                        key={
                                          leaf.id
                                        }
                                        href={`/search/${
                                          leaf.slug ||
                                          "child"
                                        }?category_id=${
                                          leaf.id
                                        }`}
                                        className="block truncate text-[11px] text-neutral-500 transition-colors hover:text-red-500"
                                      >
                                        {
                                          leaf.name
                                        }
                                      </Link>
                                    )
                                  )}
                                </div>
                              )}
                            </div>
                          );
                        }
                      )}
                    </div>
                  </>
                )}
              </div>
            </div>
          )}

          {/* =====================================
              Mobile Category Menu
          ====================================== */}

          {isOpen && (
            <div className="absolute left-3 right-3 top-[calc(100%+4px)] z-[80] overflow-hidden rounded-xl border border-neutral-100 bg-white shadow-2xl lg:hidden">
              <div className="max-h-[70vh] overflow-y-auto">
                {mainDataArray?.map(
                  (category) => (
                    <Link
                      key={category.id}
                      href={`/search/${category.slug}?category_id=${category.id}`}
                      onClick={() =>
                        setIsOpen(false)
                      }
                      className="flex items-center justify-between border-b border-neutral-100 px-4 py-3 text-sm font-medium text-neutral-800 hover:bg-neutral-50 active:bg-neutral-100"
                    >
                      <div className="flex items-center gap-3">
                        <CategoryIcon
                          iconKey={
                            category.icon_key
                          }
                          className="h-5 w-5 text-neutral-500"
                        />

                        <span>
                          {
                            category.name
                          }
                        </span>
                      </div>

                      <TbChevronRight className="h-4 w-4 text-neutral-400" />
                    </Link>
                  )
                )}
              </div>
            </div>
          )}
        </div>
      </header>

      {/* =====================================
          Location Modal
      ====================================== */}

      {isLocationModalOpen && (
        <LocationModal
          isOpen={
            isLocationModalOpen
          }
          onClose={() =>
            setIsLocationModalOpen(
              false
            )
          }
          onSuccess={
            handleLocationSaved
          }
        />
      )}

      {/* =====================================
          Mobile Bottom Navigation
      ====================================== */}

      <MobileBottomNav
        totalCount={totalCount}
        openMenu={() =>
          setIsOpen(true)
        }
      />
    </>
  );
}

export default Header;