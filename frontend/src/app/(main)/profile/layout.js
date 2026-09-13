"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";

import { CiEdit, CiGift } from "react-icons/ci";
import { IoChevronBack } from "react-icons/io5";
import { HiOutlineHome } from "react-icons/hi";
import { BsBag } from "react-icons/bs";
import { GrFavorite } from "react-icons/gr";
import { FaRegComment } from "react-icons/fa";
import { SiGooglestreetview } from "react-icons/si";
import { RiNotification3Line } from "react-icons/ri";
import { MdAccessTime } from "react-icons/md";
import { FaRegUser } from "react-icons/fa6";

const menuProfile = [
  {
    id: "active",
    label: "Activity Overview",
    href: "/profile",
    icon: HiOutlineHome,
  },
  {
    id: "orders",
    label: "Orders",
    href: "/profile/orders/",
    icon: BsBag,
  },
  {
    id: "lists",
    label: "Lists",
    href: "/profile/lists/",
    icon: GrFavorite,
  },
  {
    id: "comments",
    label: "Reviews & Questions",
    href: "/profile/comments/",
    icon: FaRegComment,
  },
  {
    id: "address",
    label: "Addresses",
    href: "/profile/addresses/",
    icon: SiGooglestreetview,
  },
  {
    id: "cartGift",
    label: "Gift Cards",
    href: "/profile/gift-cards/",
    icon: CiGift,
  },
  {
    id: "messages",
    label: "Messages",
    href: "/profile/notification/",
    icon: RiNotification3Line,
  },
  {
    id: "time",
    label: "Recently Viewed",
    href: "/profile/user-history/",
    icon: MdAccessTime,
  },
  {
    id: "user",
    label: "Account Information",
    href: "/profile/personal-info/",
    icon: FaRegUser,
  },
];

const normalizePath = (path = "") =>
  path.replace(/\/+$/, "") || "/";

const isMenuActive = (pathname, href) => {
  const currentPath = normalizePath(pathname);
  const menuPath = normalizePath(href);

  if (menuPath === "/profile") {
    return currentPath === "/profile";
  }

  return (
    currentPath === menuPath ||
    currentPath.startsWith(`${menuPath}/`)
  );
};

function ProfileLayout({ children }) {
  const pathname = usePathname();

  return (
    <div
      dir="ltr"
      className="
        mx-auto w-full max-w-[1270px]
        px-3 py-4
        sm:px-4 sm:py-6
        md:px-5 md:py-8
        lg:px-6 lg:py-10
      "
    >
      <div
        className="
          flex w-full flex-col
          gap-4
          sm:gap-5
          lg:flex-row
          lg:items-start
          lg:gap-6
        "
      >
        {/* Profile Sidebar */}
        <aside
          aria-label="Profile navigation"
          className="
            w-full overflow-hidden
            rounded-xl
            border border-neutral-200
            bg-white
            shadow-sm
            dark:border-neutral-800
            dark:bg-neutral-900
            dark:shadow-black/20

            lg:w-[331px]
            lg:shrink-0
            lg:rounded-lg
            lg:shadow-sm
          "
        >
          {/* User Info */}
          <div className="w-full">
            <div
              className="
                flex items-center
                justify-between gap-3
                px-4 py-4
                sm:px-5 sm:py-5
                lg:px-10 lg:pt-5
              "
            >
              <div className="min-w-0">
                <span
                  className="
                    block truncate
                    text-sm font-medium
                    text-neutral-800
                    dark:text-neutral-100
                    sm:text-base
                  "
                >
                  09123456789
                </span>

                <span
                  className="
                    mt-1 block text-xs
                    text-neutral-500
                    dark:text-neutral-400
                    lg:hidden
                  "
                >
                  Account
                </span>
              </div>

              <Link
                href="/profile/personal-info/"
                aria-label="Edit account information"
                className="
                  flex h-9 w-9 shrink-0
                  items-center justify-center
                  rounded-full
                  text-blue-500
                  transition-all duration-200
                  hover:bg-blue-50
                  hover:text-blue-600
                  active:scale-95
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-blue-500
                  dark:text-blue-400
                  dark:hover:bg-blue-950/30
                  dark:hover:text-blue-300
                  sm:h-10 sm:w-10
                "
              >
                <CiEdit
                  aria-hidden="true"
                  className="h-6 w-6 sm:h-7 sm:w-7"
                />
              </Link>
            </div>

            {/* Digital Gold */}
            <div
              className="
                px-4 pb-4
                sm:px-5 sm:pb-5
                lg:px-10 lg:pb-0
              "
            >
              <div className="flex items-center justify-between gap-3">
                <span
                  className="
                    truncate text-sm font-medium
                    text-neutral-800
                    dark:text-neutral-100
                    sm:text-base
                  "
                >
                  Digital Gold & Silver
                </span>

                <span
                  className="
                    shrink-0 text-sm
                    text-neutral-500
                    dark:text-neutral-400
                  "
                >
                  - IRR
                </span>
              </div>

              <Link
                href="/"
                className="
                  mt-3 inline-flex
                  items-center gap-1
                  rounded-md
                  text-sm font-medium
                  text-blue-500
                  transition-colors
                  hover:text-blue-600
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-blue-500
                  focus-visible:ring-offset-2
                  dark:text-blue-400
                  dark:hover:text-blue-300
                  dark:focus-visible:ring-offset-neutral-900
                "
              >
                <span>Add Balance</span>

                <IoChevronBack
                  aria-hidden="true"
                  className="h-4 w-4"
                />
              </Link>
            </div>

            {/* Wallet + Club */}
            <div
              className="
                grid grid-cols-2 gap-2
                border-y border-neutral-200
                px-3 py-3
                dark:border-neutral-800

                sm:gap-3 sm:px-5 sm:py-4

                lg:block
                lg:border-0
                lg:px-10 lg:py-0
              "
            >
              {/* Wallet */}
              <Link
                href="/"
                className="
                  group min-w-0
                  rounded-lg
                  bg-neutral-50 p-3
                  transition-colors
                  hover:bg-neutral-100
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-blue-500
                  dark:bg-neutral-800/60
                  dark:hover:bg-neutral-800

                  lg:mt-[30px]
                  lg:block
                  lg:rounded-none
                  lg:bg-transparent
                  lg:p-0
                  lg:dark:bg-transparent
                "
              >
                <div className="flex w-full items-center justify-between gap-2">
                  <h6
                    className="
                      truncate text-sm font-semibold
                      text-neutral-800
                      dark:text-neutral-100
                      sm:text-base
                    "
                  >
                    Wallet
                  </h6>

                  <span
                    className="
                      shrink-0 text-xs
                      text-neutral-500
                      dark:text-neutral-400
                      sm:text-sm
                    "
                  >
                    0 Toman
                  </span>
                </div>

                <div
                  className="
                    mt-2 flex items-center
                    text-xs font-medium
                    text-blue-500
                    transition-colors
                    group-hover:text-blue-600
                    dark:text-blue-400
                    dark:group-hover:text-blue-300

                    lg:mt-[10px]
                    lg:text-sm
                  "
                >
                  <span>Add Balance</span>

                  <IoChevronBack
                    aria-hidden="true"
                    className="ml-0.5 h-4 w-4"
                  />
                </div>
              </Link>

              {/* Digikala Club */}
              <Link
                href="/"
                className="
                  group min-w-0
                  rounded-lg
                  bg-neutral-50 p-3
                  transition-colors
                  hover:bg-neutral-100
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-blue-500
                  dark:bg-neutral-800/60
                  dark:hover:bg-neutral-800

                  lg:mt-[30px]
                  lg:block
                  lg:rounded-none
                  lg:bg-transparent
                  lg:p-0
                  lg:dark:bg-transparent
                "
              >
                <div className="flex w-full items-center justify-between gap-2">
                  <h6
                    className="
                      truncate text-sm font-semibold
                      text-neutral-800
                      dark:text-neutral-100
                      sm:text-base
                    "
                  >
                    Digikala Club
                  </h6>

                  <span
                    className="
                      shrink-0 text-xs
                      text-neutral-500
                      dark:text-neutral-400
                      sm:text-sm
                    "
                  >
                    - Points
                  </span>
                </div>

                <div
                  className="
                    mt-2 flex items-center
                    text-xs font-medium
                    text-blue-500
                    transition-colors
                    group-hover:text-blue-600
                    dark:text-blue-400
                    dark:group-hover:text-blue-300

                    lg:mt-[10px]
                    lg:text-sm
                  "
                >
                  <span>View Missions</span>

                  <IoChevronBack
                    aria-hidden="true"
                    className="ml-0.5 h-4 w-4"
                  />
                </div>
              </Link>
            </div>

            {/* Desktop Divider */}
            <div
              className="
                mt-[10px] hidden w-full
                border-b border-neutral-200
                dark:border-neutral-800
                lg:block
              "
            />

            {/* Mobile / Tablet Menu */}
            <div className="block lg:hidden">
              <nav
                aria-label="Profile sections"
                className="
                  flex w-full gap-2
                  overflow-x-auto
                  px-3 py-3
                  [scrollbar-width:none]
                  [&::-webkit-scrollbar]:hidden
                  sm:px-5
                "
              >
                {menuProfile.map((menu) => {
                  const Icon = menu.icon;
                  const isActive = isMenuActive(
                    pathname,
                    menu.href
                  );

                  return (
                    <Link
                      key={menu.id}
                      href={menu.href}
                      aria-current={
                        isActive ? "page" : undefined
                      }
                      className={`
                        relative flex min-w-fit shrink-0
                        items-center gap-2
                        rounded-full border
                        px-3 py-2
                        text-xs font-semibold
                        outline-none
                        transition-all duration-200
                        active:scale-[0.98]
                        focus-visible:ring-2
                        focus-visible:ring-red-500
                        focus-visible:ring-offset-2
                        dark:focus-visible:ring-offset-neutral-900
                        sm:px-4 sm:py-2.5 sm:text-sm

                        ${
                          isActive
                            ? `
                              border-red-500
                              bg-red-50
                              text-red-500
                              dark:border-red-500
                              dark:bg-red-950/30
                              dark:text-red-400
                            `
                            : `
                              border-neutral-200
                              bg-white
                              text-neutral-700
                              hover:border-neutral-300
                              hover:bg-neutral-50
                              dark:border-neutral-700
                              dark:bg-neutral-900
                              dark:text-neutral-300
                              dark:hover:border-neutral-600
                              dark:hover:bg-neutral-800
                            `
                        }
                      `}
                    >
                      <Icon
                        aria-hidden="true"
                        className={`
                          h-[18px] w-[18px] shrink-0
                          ${
                            isActive
                              ? "text-red-500 dark:text-red-400"
                              : "text-neutral-500 dark:text-neutral-400"
                          }
                        `}
                      />

                      <span className="whitespace-nowrap">
                        {menu.label}
                      </span>
                    </Link>
                  );
                })}
              </nav>
            </div>

            {/* Desktop Menu */}
            <nav
              aria-label="Profile sections"
              className="hidden lg:flex lg:flex-col"
            >
              {menuProfile.map((menu) => {
                const Icon = menu.icon;
                const isActive = isMenuActive(
                  pathname,
                  menu.href
                );

                return (
                  <Link
                    key={menu.id}
                    href={menu.href}
                    aria-current={
                      isActive ? "page" : undefined
                    }
                    className={`
                      group relative flex min-h-[58px]
                      items-center gap-3
                      border-b border-neutral-200
                      px-10 py-4
                      outline-none
                      transition-all duration-200
                      focus-visible:z-10
                      focus-visible:ring-2
                      focus-visible:ring-inset
                      focus-visible:ring-red-500
                      dark:border-neutral-800

                      ${
                        isActive
                          ? `
                            bg-neutral-100
                            dark:bg-neutral-800
                            before:absolute
                            before:right-0
                            before:top-1/2
                            before:h-1/2
                            before:w-[5px]
                            before:-translate-y-1/2
                            before:rounded-l
                            before:bg-red-500
                          `
                          : `
                            text-neutral-700
                            hover:bg-neutral-50
                            dark:text-neutral-300
                            dark:hover:bg-neutral-800/60
                          `
                      }
                    `}
                  >
                    <Icon
                      aria-hidden="true"
                      className={`
                        h-[20px] w-[22px] shrink-0
                        transition-colors
                        ${
                          isActive
                            ? "text-red-500 dark:text-red-400"
                            : "text-neutral-600 dark:text-neutral-400"
                        }
                      `}
                    />

                    <span
                      className={`
                        text-base font-bold
                        transition-colors
                        ${
                          isActive
                            ? "text-red-500 dark:text-red-400"
                            : "text-neutral-700 dark:text-neutral-200"
                        }
                      `}
                    >
                      {menu.label}
                    </span>
                  </Link>
                );
              })}
            </nav>
          </div>
        </aside>

        {/* Main Content */}
        <main
          className="
            min-w-0 w-full flex-1
            overflow-hidden
          "
        >
          {children}
        </main>
      </div>
    </div>
  );
}

export default ProfileLayout;