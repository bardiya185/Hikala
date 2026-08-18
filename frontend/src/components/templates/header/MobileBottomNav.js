import Link from "next/link";
import { RxHamburgerMenu } from "react-icons/rx";
import { MdOutlineHome } from "react-icons/md";
import { MdShoppingCartCheckout } from "react-icons/md";

function MobileBottomNav({ totalCount, openMenu }) {
  return (
    <div
      className="
        fixed
        bottom-0
        left-0
        right-0

        lg:hidden

        z-[100]

        bg-white
        border-t
        border-neutral-200

        h-16

        flex
        items-center
        justify-around
      "
    >
      {/* خانه */}
      <Link
        href="/"
        className="
          flex
          flex-col
          items-center
          gap-1
          text-xs
          text-neutral-700
        "
      >
        <MdOutlineHome className="w-6 h-6" />
        <span>خانه</span>
      </Link>

      {/* دسته بندی */}
      <button
        onClick={openMenu}
        className="
          flex
          flex-col
          items-center
          gap-1
          text-xs
          text-neutral-700
        "
      >
        <RxHamburgerMenu className="w-6 h-6" />
        <span>دسته‌بندی</span>
      </button>

      {/* سبد خرید */}
      <Link
        href="/checkout/cart"
        className="
          relative
          flex
          flex-col
          items-center
          gap-1
          text-xs
          text-neutral-700
        "
      >
        <MdShoppingCartCheckout className="w-6 h-6" />

        {totalCount > 0 && (
          <span
            className="
              absolute
              top-0
              right-1

              w-4
              h-4

              rounded-full
              bg-red-500

              text-white
              text-[10px]

              flex
              items-center
              justify-center
            "
          >
             {totalCount > 99 ? "+99" : totalCount}
          </span>
        )}

        <span>سبد</span>
      </Link>
    </div>
  );
}

export default MobileBottomNav;