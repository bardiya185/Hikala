"use client";

import { useRef, useState, useCallback, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";

import { CiMobile1, CiSearch } from "react-icons/ci";
import {
  TbDeviceLaptop,
  TbFridge,
  TbShirt,
  TbChevronRight,
} from "react-icons/tb";
import { GiGoldBar, GiCarKey } from "react-icons/gi";
import { MdShoppingCartCheckout } from "react-icons/md";
import { RxHamburgerMenu } from "react-icons/rx";

import { useRouter, usePathname } from "next/navigation";

import AuthForm from "../AuthForm";

import {
  useCart,
  useGetMainCategories,
  useGetSubCategory,
} from "@/core/services/queries";

import SearchBar from "@/components/atom/SearchBar";
import MiniCart from "../miniCart";
import MobileBottomNav from "./MobileBottomNav";

const iconMap = {
  mobile: CiMobile1,
  laptops: TbDeviceLaptop,
  digital: TbDeviceLaptop,
  "home-kitchen": TbFridge,
  fashion: TbShirt,
  "gold-jewelry": GiGoldBar,
  vehicles: GiCarKey,
};

function CategoryIcon({ iconKey, className }) {
  const IconComponent = iconMap[iconKey];

  if (!IconComponent) return null;

  return <IconComponent className={className} />;
}

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState(null);
  const [isOpenMiniCart, setIsOpenMiniCart] = useState(false);

  const closeTimer = useRef(null);

  const router = useRouter();
  const pathname = usePathname();

  const { data: cart } = useCart();

  const totalCount =  cart?.data ?  cart?.data.items_count : 0 ;

  const { data: categoriess } = useGetMainCategories();

  const mainDataArray = categoriess?.data?.data || [];

  const { data: categoryMenu, isLoading: isSubLoading } =
    useGetSubCategory(activeId);

  const subDataArray = categoryMenu?.data?.data || [];

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

    closeTimer.current = setTimeout(() => {
      setIsOpen(false);
    }, 150);
  }, [clearCloseTimer]);

  const openMenu = useCallback(() => {
    clearCloseTimer();

    setIsOpen(true);
  }, [clearCloseTimer]);

  const activeCategory =
    mainDataArray?.find((c) => c.id === activeId) || mainDataArray[0];

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
    : subDataArray?.children ||
      subDataArray?.subs ||
      subDataArray?.subcategories ||
      [];

  return (
    <>
      <header
        dir="ltr"
        className="
        w-full
        bg-white
        font-sans
        select-none
      
        w-full
        bg-white
        font-sans
        select-none
      "
>
       
        <div className="w-full overflow-hidden">
          <Image
            src="/icons/1.png"
            width={1270}
            height={60}
            alt="banner"
            priority
            className="
            block
            w-full
            h-[45px]
            sm:h-[50px]
            md:h-[60px]
            object-cover
     
            block
            w-full
            h-[45px]
            sm:h-[50px]
            md:h-[60px]
            object-cover
          "
          />
        </div>

        {/* ==================================================
          MAIN HEADER
      ================================================== */}
          MAIN HEADER
      ================================================== */}

        <div
          className="
          w-full

          bg-white
          font-sans
          select-none

          px-3
          sm:px-4
          lg:px-6

          mt-3
          sm:mt-4

     
          w-full
          px-3
          sm:px-4
          lg:px-6

          mt-3
          sm:mt-4
        "
        >
          <div
            className="
            flex
            items-center
            justify-between

            gap-2
            sm:gap-3
            lg:gap-6

            w-full
            px-3
            sm:mt-4
            sm:px-4
            lg:px-6
        
            flex
            items-center
            justify-between

            gap-2
            sm:gap-3
            lg:gap-6

            w-full
          "
          >


            <div
              className="
              flex

              w-full
              items-center
              justify-between
              gap-2
              sm:gap-3
              lg:gap-6

              items-center

              gap-2
              sm:gap-4
              lg:gap-6

              flex-1
              min-w-0

               flex
              items-center

              gap-2
              sm:gap-4
              lg:gap-6

              flex-1
              min-w-0
            "
            >
     

              <Link
                href="/"
                className="
              hidden
                shrink-0
                lg:flex
                items-center
             
              hidden
                shrink-0
                lg:flex
                items-center
              "
              >
                <Image
                  src="/icons/en-logo.svg"
                  width={195}
                  height={30}
                  alt="logo"
                  priority
                  className="
                  w-[195px]
                  h-auto
               
                  w-[195px]
                  h-auto
                "
                />
              </Link>

              {}

              <div
                className="
                flex-1
                min-w-0
              "
              >
              {}

              <div
                className="
                flex-1
                min-w-0
              "
              >
                <SearchBar />
              </div>
            </div>

            {/* ==============================================
              LOGIN + CART
          ============================================== */}
            {/* ==============================================
              LOGIN + CART
          ============================================== */}

            <div
              className="hidden
              lg:flex
              items-center

              gap-1
              sm:gap-2

              shrink-0
            "
              className="hidden
              lg:flex
              items-center

              gap-1
              sm:gap-2

              shrink-0
            "
            >
              {}

              {}

              <AuthForm />

              {}

              {}

              {}

              <div
                onMouseEnter={() => setIsOpenMiniCart(true)}
                onMouseLeave={() => setIsOpenMiniCart(false)}
                className=" hidden
                relative
                lg:flex
                items-center
                gap-2
                sm:gap-4
                lg:gap-6
              "
                onMouseEnter={() => setIsOpenMiniCart(true)}
                onMouseLeave={() => setIsOpenMiniCart(false)}
                className=" hidden
                relative
                lg:flex
                items-center
                shrink-0
              "
              >
                <Link
                  href="/checkout/cart"
                  className="
                  relative
                  block
                "
                  className="
                  relative
                  block
                "
                >
                  <div
                    className="
                    p-1.5
                    sm:p-2

                    rounded-full

                    hover:bg-neutral-100

                    transition-colors
                  "
                    p-1.5
                    sm:p-2

                    rounded-full

                    hover:bg-neutral-100

                    transition-colors
                  "
                  >
                    <MdShoppingCartCheckout
                      className="
                      w-[21px]
                      h-[21px]

                      sm:w-[24px]
                      sm:h-[24px]

                      text-neutral-700
                    "
                      w-[21px]
                      h-[21px]

                      sm:w-[24px]
                      sm:h-[24px]

                      text-neutral-700
                    "
                    />
                  </div>

                  {}

                  {}

                  <span
                    className="
                    absolute

                    -top-1
                    -right-1

                    flex
                    items-center
                    justify-center

                    h-4
                    w-4

                    sm:h-5
                    sm:w-5

                    rounded-full

                    bg-red-500

                    text-[9px]
                    sm:text-[11px]

                    font-bold
                    text-white

                    shadow-sm
                  "
                    absolute

                    -top-1
                    -right-1

                    flex
                    items-center
                    justify-center

                    h-4
                    w-4

                    sm:h-5
                    sm:w-5

                    rounded-full

                    bg-red-500

                    text-[9px]
                    sm:text-[11px]

                    font-bold
                    text-white

                    shadow-sm
                  "
                  >
                    {totalCount > 99 ? "+99" : totalCount}
                    {totalCount > 99 ? "+99" : totalCount}
                  </span>
                </Link>

                {}

                {}

                {isOpenMiniCart && (
                  <div
                    className="
                    hidden
                    sm:block
                  "
                  >
                  <div
                    className="
                    hidden
                    sm:block
                  "
                  >
                    <MiniCart />
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* ==================================================
          CATEGORY MENU
      ================================================== */}
          CATEGORY MENU
      ================================================== */}

        <div
          className="
          relative

          mt-3
          sm:mt-4

          px-3
          sm:px-4
          lg:px-6
        "
          relative

          mt-3
          sm:mt-4

          px-3
          sm:px-4
          lg:px-6
        "
          onMouseLeave={scheduleClose}
        >
          {}
          {}

          <button
            type="button"
            onMouseEnter={openMenu}
            onClick={() => setIsOpen((prev) => !prev)}
            className="
          hidden
            lg:flex
            items-center

            gap-2

            cursor-pointer

            py-2

            text-neutral-800
            hover:text-red-600

            transition-colors
          "
          >
            <RxHamburgerMenu
              className="
              w-5
              h-5
            "
            />
          <button
            type="button"
            onMouseEnter={openMenu}
            onClick={() => setIsOpen((prev) => !prev)}
            className="
          hidden
            lg:flex
            items-center

            gap-2

            cursor-pointer

            py-2

            text-neutral-800
            hover:text-red-600

            transition-colors
          "
          >
            <RxHamburgerMenu
              className="
              w-5
              h-5
            "
            />

            <span
              className="
              text-xs
              sm:text-sm

              font-bold
            "
            >
              Categories
            </span>
          </button>
            <span
              className="
              text-xs
              sm:text-sm

              font-bold
            "
            >
              Categories
            </span>
          </button>

          {/* ==================================================
            DESKTOP CATEGORY MEGA MENU
        ================================================== */}
          {/* ==================================================
            DESKTOP CATEGORY MEGA MENU
        ================================================== */}

          {isOpen && (
            <div
              onMouseEnter={clearCloseTimer}
              className="
              absolute

              top-[calc(100%+4px)]
              left-3
              sm:left-4

              z-[80]

             flex
              w-auto
              
              lg:w-[750px]

              lg:h-[350px]

              bg-white

              rounded-xl

              shadow-2xl

              overflow-hidden

              border
              border-neutral-100
            "
              absolute

              top-[calc(100%+4px)]
              left-3
              sm:left-4

              z-[80]

             flex
              w-auto
              
              lg:w-[750px]

              lg:h-[350px]

              bg-white

              rounded-xl

              shadow-2xl

              overflow-hidden

              border
              border-neutral-100
            "
            >
              {/* ============================================
                MAIN CATEGORIES
            ============================================ */}
              {/* ============================================
                MAIN CATEGORIES
            ============================================ */}

              <nav
                className="
                flex
                flex-col

                w-[210px]
                lg:w-[220px]

                shrink-0

                overflow-y-auto

                border-r
                border-neutral-100

                py-2
                text-neutral-800
                transition-colors
                hover:text-red-600
                lg:flex
              "
                flex
                flex-col

                w-[210px]
                lg:w-[220px]

                shrink-0

                overflow-y-auto

                border-r
                border-neutral-100

                py-2

                bg-neutral-50
              "
              >
                {mainDataArray?.map((c) => {
                  const isActive = activeId === c.id;
                {mainDataArray?.map((c) => {
                  const isActive = activeId === c.id;

                  return (
                    <div
                      key={c.id}
                      onMouseEnter={() => setActiveId(c.id)}
                      key={c.id}
                      onMouseEnter={() => setActiveId(c.id)}
                      className={`
                      flex
                      items-center
                      justify-between

                      px-4
                      py-2.5

                      text-[13px]

                      font-semibold

                      cursor-pointer

                      transition-colors

                      ${
                        isActive
                          ? "bg-white text-red-600 border-l-4 border-l-red-500"
                          : "text-neutral-900 hover:bg-neutral-100"
                      }
                    `}
                      flex
                      items-center
                      justify-between

                      px-4
                      py-2.5

                      text-[13px]

                      font-semibold

                      cursor-pointer

                      transition-colors

                      ${
                        isActive
                          ? "bg-white text-red-600 border-l-4 border-l-red-500"
                          : "text-neutral-900 hover:bg-neutral-100"
                      }
                    `}
                    >
                      <div
                        className="
                        flex
                        items-center
                        gap-1
                        whitespace-nowrap
                        text-[13px]
                        font-bold
                        text-red-600
                        hover:underline
                      "
                        flex
                        items-center
                        gap-2
                        min-w-0
                      "
                      >
                        <CategoryIcon
                          iconKey={c.icon_key}
                          iconKey={c.icon_key}
                          className={`
                          w-[18px]
                          h-[18px]
                          shrink-0

                          ${isActive ? "text-red-600" : "text-neutral-500"}
                        `}
                          w-[18px]
                          h-[18px]
                          shrink-0

                          ${isActive ? "text-red-600" : "text-neutral-500"}
                        `}
                        />

                        <span className="truncate">{c.name}</span>
                        <span className="truncate">{c.name}</span>
                      </div>

                      <TbChevronRight
                        className={`
                        w-3.5
                        h-3.5
                        shrink-0

                        ${isActive ? "text-red-500" : "text-neutral-300"}
                      `}
                        w-3.5
                        h-3.5
                        shrink-0

                        ${isActive ? "text-red-500" : "text-neutral-300"}
                      `}
                      />
                    </div>
                  );
                })}
              </nav>

              {/* ============================================
                SUB CATEGORIES
            ============================================ */}
              {/* ============================================
                SUB CATEGORIES
            ============================================ */}

              <div
                className="
                flex-1

                overflow-y-auto

                px-5
                lg:px-6

                py-5

                bg-white

                relative
              "
                flex-1

                overflow-y-auto

                px-5
                lg:px-6

                py-5

                bg-white

                relative
              "
              >
                {isSubLoading ? (
                  <div
                    className="
                    absolute
                    inset-0

                    flex
                    items-center
                    justify-center

                    bg-white/60
                  "
                    absolute
                    inset-0

                    flex
                    items-center
                    justify-center

                    bg-white/60
                  "
                  >
                    <div
                      className="
                      w-6
                      h-6

                      border-2
                      border-red-500
                      border-t-transparent

                      rounded-full

                      animate-spin
                    "
                      w-6
                      h-6

                      border-2
                      border-red-500
                      border-t-transparent

                      rounded-full

                      animate-spin
                    "
                    />
                  </div>
                ) : (
                  <>
                    {}

                    {}

                    <Link
                      href={`/search/${
                        targetCategory?.slug || "all"
                      }?category_id=${targetCategory?.id || ""}`}
                        targetCategory?.slug || "all"
                      }?category_id=${targetCategory?.id || ""}`}
                      className="
                      flex
                      items-center
                      gap-1

                      mb-4

                      text-[13px]
                      font-bold

                      text-red-600

                      whitespace-nowrap

                      hover:underline
                    "
                      flex
                      items-center
                      gap-1

                      mb-4

                      text-[13px]
                      font-bold

                      text-red-600

                      whitespace-nowrap

                      hover:underline
                    "
                    >
                      All {activeCategory?.name} Products
                      All {activeCategory?.name} Products
                      <TbChevronRight
                        className="
                        w-3.5
                        h-3.5
                      "
                        className="
                        w-3.5
                        h-3.5
                      "
                      />
                    </Link>

                    {}

                    {}

                    <div
                      className="
                      grid

                      grid-cols-2
                      lg:grid-cols-3

                      gap-5
                      lg:gap-6
                    "
                      grid

                      grid-cols-2
                      lg:grid-cols-3

                      gap-5
                      lg:gap-6
                    "
                    >
                      {finalSubList?.map((col, idx) => (
                        <div
                          key={col.id || idx}
                          className="
                            flex
                            flex-col

                            min-w-0
                          "
                        >
                          <Link
                            href={`/search/${
                              col.slug || "category"
                            }?category_id=${col.id}`}
                            className="
                              flex
<<<<<<< Updated upstream
                              min-w-0
                              flex-col
=======
                              items-center
                              justify-between

                              mb-2
                              py-1

                              text-sm
                              font-bold

                              text-neutral-900

                              border-b
                              border-neutral-100

                              group
>>>>>>> Stashed changes
                            "
                          >
                            <span
                              className="
                                truncate

                                group-hover:text-red-600

                                transition-colors
                              "
                            >
                              {col.name}
                            </span>
                      {finalSubList?.map((col, idx) => (
                        <div
                          key={col.id || idx}
                          className="
                            flex
                            flex-col

                            min-w-0
                          "
                        >
                          <Link
                            href={`/search/${
                              col.slug || "category"
                            }?category_id=${col.id}`}
                            className="
                              flex
                              items-center
                              justify-between

                              mb-2
                              py-1

                              text-sm
                              font-bold

                              text-neutral-900

                              border-b
                              border-neutral-100

                              group
                            "
                          >
                            <span
                              className="
                                truncate

                                group-hover:text-red-600

                                transition-colors
                              "
                            >
                              {col.name}
                            </span>

                            <TbChevronRight
                              className="
                                w-3.5
                                h-3.5

                                shrink-0

                                text-neutral-400

                                group-hover:text-red-600

                                transition-colors
                              "
                            />
                          </Link>
                            <TbChevronRight
                              className="
                                w-3.5
                                h-3.5

                                shrink-0

                                text-neutral-400

                                group-hover:text-red-600

                                transition-colors
                              "
                            />
                          </Link>

                          {(col.children || col.subs || col.leaves || []).map(
                            (leaf, lIdx) => (
                              <Link
                                key={leaf.id || lIdx}
                                href={`/search/${
                                  leaf.slug || "child"
                                }?category_id=${leaf.id}`}
                                className="
                                  py-1

                                  text-[13px]

                                  text-neutral-500

                                  hover:text-red-600

                                  transition-colors

                                  truncate
                                "
                              >
                                {leaf.name}
                              </Link>
                            ),
                          )}
                        </div>
                      ))}
                          {(col.children || col.subs || col.leaves || []).map(
                            (leaf, lIdx) => (
                              <Link
                                key={leaf.id || lIdx}
                                href={`/search/${
                                  leaf.slug || "child"
                                }?category_id=${leaf.id}`}
                                className="
                                  py-1

                                  text-[13px]

                                  text-neutral-500

                                  hover:text-red-600

                                  transition-colors

                                  truncate
                                "
                              >
                                {leaf.name}
                              </Link>
                            ),
                          )}
                        </div>
                      ))}
                    </div>
                  </>
                )}
              </div>
            </div>
          )}

          {/* ==================================================
            MOBILE CATEGORY MENU
        ================================================== */}
            MOBILE CATEGORY MENU
        ================================================== */}

          {isOpen && (
            <div
              className="
              md:hidden

              absolute

              top-[calc(100%+4px)]
              left-3
              right-3

              z-[80]

              bg-white

              rounded-xl

              shadow-2xl

              border
              border-neutral-100

              overflow-hidden
            "
              md:hidden

              absolute

              top-[calc(100%+4px)]
              left-3
              right-3

              z-[80]

              bg-white

              rounded-xl

              shadow-2xl

              border
              border-neutral-100

              overflow-hidden
            "
            >
              <div
                className="
                max-h-[70vh]

                overflow-y-auto
              "
                max-h-[70vh]

                overflow-y-auto
              "
              >
                {mainDataArray?.map((c) => {
                  return (
                    <Link
                      key={c.id}
                      href={`/search/${c.slug}?category_id=${c.id}`}
                      onClick={() => setIsOpen(false)}
                      className="
                {mainDataArray?.map((c) => {
                  return (
                    <Link
                      key={c.id}
                      href={`/search/${c.slug}?category_id=${c.id}`}
                      onClick={() => setIsOpen(false)}
                      className="
                      flex
                      items-center
                      justify-between

                      px-4
                      py-3

                      border-b
                      border-neutral-100

                      text-sm
                      font-medium

                      text-neutral-800

                      hover:bg-neutral-50

                      active:bg-neutral-100
                    "
                    >
                      <div
                        className="
                    >
                      <div
                        className="
                        flex
                        items-center
                        gap-3
                      "
                      >
                        <CategoryIcon
                          iconKey={c.icon_key}
                          className="
                          w-5
                          h-5
                      >
                        <CategoryIcon
                          iconKey={c.icon_key}
                          className="
                          w-5
                          h-5

                          text-neutral-500
                        "
                        />
                        />

                        <span>{c.name}</span>
                      </div>
                        <span>{c.name}</span>
                      </div>

                      <TbChevronRight
                        className="
                        w-4
                        h-4
                      <TbChevronRight
                        className="
                        w-4
                        h-4

                        text-neutral-400
                      "
                      />
                    </Link>
                  );
                })}
                      />
                    </Link>
                  );
                })}
              </div>
            </div>
          )}
        </div>
      </header>

      <MobileBottomNav
        totalCount={totalCount}
        openMenu={() => setIsOpen(true)}
      />
    </>
  );
}

export default Header;
