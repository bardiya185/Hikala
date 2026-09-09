"use client";

import Link from "next/link";
import React, { useState } from "react";
import { CiEdit } from "react-icons/ci";
import { IoChevronBack } from "react-icons/io5";
import { HiOutlineHome } from "react-icons/hi";
import { BsBag } from "react-icons/bs";
import { GrFavorite } from "react-icons/gr";
import { FaRegComment } from "react-icons/fa";
import { SiGooglestreetview } from "react-icons/si";
import { CiGift } from "react-icons/ci";
import { RiNotification3Line } from "react-icons/ri";
import { MdAccessTime } from "react-icons/md";
import { FaRegUser } from "react-icons/fa6";
import { usePathname } from "next/navigation";

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

function ProfileLayout({ children }) {
  const pathname = usePathname();
  const isAccountInfopage = pathname === "/profile/account-info";
  const [personlInformationsStatus, setPersoanlInformations] = useState(false);

  return (
    <div className="max-w-[1270px] mx-auto my-10 px-4 flex flex-row gap-6">
      <div className="w-[331px] h-[937px] border border-solid border-neutral-400 rounded-lg flex flex-col h-full">
        <div className="w-full flex items-center">
          <div className="w-full flex flex-col">
            <div className="w-full flex items-center justify-between px-10 pt-5">
              <span>09123456789</span>

              <Link
                href="/profile/personal-info/"
                className="cursor-pointer text-blue-500"
              >
                <CiEdit
                  width={30}
                  hanging={30}
                  className="cursor-pointer"
                />
              </Link>
            </div>

            <div className="flex justify-between px-10 mt-[30px]">
              <span>Digital Gold & Silver</span>
              <span>- IRR</span>
            </div>

            <div className="flex">
              <Link
                className="flex items-center text-blue-500 px-10 mt-[20px]"
                href="/"
              >
                Add Balance
                <IoChevronBack />
              </Link>
            </div>

            <Link
              href="/"
              className="mt-[30px] flex flex-col items-center px-10"
            >
              <div className="flex justify-between w-full">
                <h6>Wallet</h6>
                <span>0 Toman</span>
              </div>

              <div className="w-full flex items-center text-blue-500 mt-[10px]">
                <h6 className="flex mt-[10px]">Add Balance</h6>
                <IoChevronBack className="mt-[14px]" />
              </div>
            </Link>

            <Link
              className="mt-[30px] flex flex-col items-center px-10"
              href="/"
            >
              <div className="w-full flex justify-between">
                <h6>Digikala Club</h6>
                <span>- Points</span>
              </div>

              <div className="w-full flex items-center text-blue-500 mt-[10px]">
                <h6 className="mt-[10px]">View Missions</h6>
                <IoChevronBack className="mt-[14px]" />
              </div>
            </Link>

            <div className="border-b border-neutral-400 w-full mt-[10px]" />

            <div className="flex flex-1 flex-col justify-between">
              {menuProfile.map((menu) => {
                const Iconmenu = menu.icon;
                const isActive = pathname === menu.href;

                return (
                  <div key={menu.id}>
                    <Link
                      className={`flex items-center gap-3 border-b border-neutral-200 px-10 py-4 transition-all duration-200 font-vazir relative
                        ${
                          isActive
                            ? "bg-neutral-100 text-red-500 before:absolute before:right-0 before:w-[5px] before:h-1/2 before:bg-red-500 before:rounded-l"
                            : "text-neutral-700 hover:bg-neutral-50 before:absolute before:right-0 before:w-[5px] before:h-0 before:bg-transparent"
                        }
                      `}
                      href={menu.href}
                    >
                      <Iconmenu
                        className={`w-[22px] h-[20px] ${
                          isActive
                            ? "text-red-500"
                            : "text-neutral-600"
                        }`}
                      />

                      <span className="text-base font-bold text-neutral-700">
                        {menu.label}
                      </span>
                    </Link>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>

      <main className="flex-1 w-full">{children}</main>
    </div>
  );
}

export default ProfileLayout;