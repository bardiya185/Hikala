
"use client";

import Link from "next/link";
import { RxHamburgerMenu } from "react-icons/rx";
import { MdOutlineHome } from "react-icons/md";
import { MdShoppingCartCheckout } from "react-icons/md";
import { FaRegUser } from "react-icons/fa6";

import { useGetUserData } from "@/core/services/queries";

function MobileBottomNav({ totalCount, openMenu }) {
  const { data: userData, isLoading } = useGetUserData();

  const isLoggedIn = Boolean(userData);

  /*
   * Don't decide the destination while the authentication
   * status is still being checked.
   */
  const profileHref = isLoading
    ? "#"
    : isLoggedIn
      ? "/profile"
      : "/auth/login/sendOtp";

  const handleProfileClick = (event) => {
    if (isLoading) {
      event.preventDefault();
    }
  };

  const navItemClass = `
    group
    flex
    min-w-[64px]
    flex-1
    flex-col
    items-center
    justify-center
    gap-1

    rounded-xl
    py-1.5

    text-neutral-600

    transition-all
    duration-200

    hover:bg-neutral-100
    hover:text-neutral-950

    active:scale-[0.96]
    active:bg-neutral-100

    focus-visible:outline-none
    focus-visible:ring-2
    focus-visible:ring-red-500/30

    dark:text-neutral-400
    dark:hover:bg-neutral-900
    dark:hover:text-white
    dark:active:bg-neutral-800

    sm:min-w-[72px]
  `;

  const iconClass = `
    transition-transform
    duration-200
    group-hover:-translate-y-0.5
  `;

  return (
    <nav
      className="
        fixed
        inset-x-0
        bottom-0
        z-[100]

        flex
        min-h-16
        w-full
        items-center
        justify-around

        border-t
        border-neutral-200/80

        bg-white/95

        px-2
        pt-1.5

        pb-[calc(0.375rem+env(safe-area-inset-bottom))]

        shadow-[0_-8px_30px_rgba(0,0,0,0.06)]

        backdrop-blur-xl

        dark:border-neutral-800
        dark:bg-neutral-950/95
        dark:shadow-[0_-8px_30px_rgba(0,0,0,0.3)]

        lg:hidden
      "
      aria-label="Mobile navigation"
    >
      {/* =====================================================
          HOME
      ====================================================== */}
      <Link
        href="/"
        aria-label="Home"
        className={navItemClass}
      >
        <MdOutlineHome
          className={`
            ${iconClass}

            h-[23px]
            w-[23px]

            sm:h-6
            sm:w-6
          `}
        />

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium
            leading-none

            sm:text-xs
          "
        >
          Home
        </span>
      </Link>

      {/* =====================================================
          CATEGORIES
      ====================================================== */}
      <button
        type="button"
        onClick={openMenu}
        aria-label="Open categories"
        className={navItemClass}
      >
        <RxHamburgerMenu
          className={`
            ${iconClass}

            h-[22px]
            w-[22px]

            sm:h-6
            sm:w-6
          `}
        />

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium
            leading-none

            sm:text-xs
          "
        >
          Categories
        </span>
      </button>

      {/* =====================================================
          MY DIGIKALA
      ====================================================== */}
      <Link
        href={profileHref}
        onClick={handleProfileClick}
        aria-label={
          isLoading
            ? "Loading profile"
            : isLoggedIn
              ? "My Digikala"
              : "Login"
        }
        aria-disabled={isLoading}
        className={`
          ${navItemClass}

          ${
            isLoading
              ? `
                cursor-wait
                opacity-60
                hover:bg-transparent
                dark:hover:bg-transparent
              `
              : ""
          }
        `}
      >
        <span className="relative">
          <FaRegUser
            className={`
              ${iconClass}

              h-[21px]
              w-[21px]

              sm:h-[23px]
              sm:w-[23px]
            `}
          />

          {/* Loading indicator */}
          {isLoading && (
            <span
              className="
                absolute
                -right-1
                -top-1

                h-2
                w-2

                animate-pulse

                rounded-full

                bg-neutral-400

                dark:bg-neutral-600
              "
              aria-hidden="true"
            />
          )}
        </span>

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium
            leading-none

            sm:text-xs
          "
        >
          My Digikala
        </span>
      </Link>

      {/* =====================================================
          CART
      ====================================================== */}
      <Link
        href="/checkout/cart"
        aria-label={`Shopping cart${
          totalCount > 0 ? `, ${totalCount} items` : ""
        }`}
        className={navItemClass}
      >
        <span className="relative">
          <MdShoppingCartCheckout
            className={`
              ${iconClass}

              h-[23px]
              w-[23px]

              sm:h-6
              sm:w-6
            `}
          />

          {/* =================================================
              Cart Badge
          ================================================== */}
          {totalCount > 0 && (
            <span
              className="
                absolute
                -right-2
                -top-2

                flex
                h-[17px]
                min-w-[17px]
                items-center
                justify-center

                rounded-full

                border-2
                border-white

                bg-red-500

                px-1

                text-[8px]
                font-bold
                leading-none
                text-white

                shadow-sm

                dark:border-neutral-950

                sm:h-[19px]
                sm:min-w-[19px]
                sm:text-[9px]
              "
            >
              {totalCount > 99 ? "+99" : totalCount}
            </span>
          )}
        </span>

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium
            leading-none

            sm:text-xs
          "
        >
          Cart
        </span>
      </Link>
    </nav>
  );
}

export default MobileBottomNav;

