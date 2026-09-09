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
   *
   * In that case we keep the user on the current page.
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

  return (
    <nav
      className="
        fixed
        inset-x-0
        bottom-0
        z-[100]

        flex
        h-16
        w-full
        items-center
        justify-around

        border-t
        border-neutral-200
        bg-white

        px-2
        pb-[env(safe-area-inset-bottom)]

        shadow-[0_-2px_10px_rgba(0,0,0,0.04)]

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
        className="
          flex
          min-w-[64px]
          flex-1
          flex-col
          items-center
          justify-center
          gap-1

          rounded-lg
          py-1

          text-neutral-700

          transition-colors
          duration-200

          hover:bg-neutral-50
          hover:text-red-500

          active:bg-neutral-100

          sm:min-w-[72px]
        "
      >
        <MdOutlineHome
          className="
            h-[23px]
            w-[23px]

            sm:h-6
            sm:w-6
          "
        />

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium

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
        aria-label="Categories"
        className="
          flex
          min-w-[64px]
          flex-1
          flex-col
          items-center
          justify-center
          gap-1

          rounded-lg
          py-1

          text-neutral-700

          transition-colors
          duration-200

          hover:bg-neutral-50
          hover:text-red-500

          active:bg-neutral-100

          sm:min-w-[72px]
        "
      >
        <RxHamburgerMenu
          className="
            h-[22px]
            w-[22px]

            sm:h-6
            sm:w-6
          "
        />

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium

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
        aria-label="My Digikala"
        aria-disabled={isLoading}
        className={`
          flex
          min-w-[64px]
          flex-1
          flex-col
          items-center
          justify-center
          gap-1

          rounded-lg
          py-1

          transition-colors
          duration-200

          sm:min-w-[72px]

          ${
            isLoading
              ? "cursor-wait text-neutral-400"
              : "text-neutral-700 hover:bg-neutral-50 hover:text-red-500 active:bg-neutral-100"
          }
        `}
      >
        <FaRegUser
          className="
            h-[21px]
            w-[21px]

            sm:h-[23px]
            sm:w-[23px]
          "
        />

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium

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
        className="
          relative
          flex
          min-w-[64px]
          flex-1
          flex-col
          items-center
          justify-center
          gap-1

          rounded-lg
          py-1

          text-neutral-700

          transition-colors
          duration-200

          hover:bg-neutral-50
          hover:text-red-500

          active:bg-neutral-100

          sm:min-w-[72px]
        "
      >
        <div className="relative">
          <MdShoppingCartCheckout
            className="
              h-[23px]
              w-[23px]

              sm:h-6
              sm:w-6
            "
          />

          {/* Cart Badge */}
          {totalCount > 0 && (
            <span
              className="
                absolute
                -right-2
                -top-2

                flex
                h-4
                min-w-4
                items-center
                justify-center

                rounded-full
                bg-red-500
                px-1

                text-[9px]
                font-bold
                leading-none
                text-white

                shadow-sm

                sm:h-5
                sm:min-w-5
                sm:text-[10px]
              "
            >
              {totalCount > 99 ? "+99" : totalCount}
            </span>
          )}
        </div>

        <span
          className="
            whitespace-nowrap
            text-[10px]
            font-medium

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