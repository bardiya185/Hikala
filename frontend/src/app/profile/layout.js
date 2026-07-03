"use client"
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
  { id: "active", label: "خلاصه فعالیت ها", href: "/", icon: HiOutlineHome },
  { id: "orders", label: "سفارش ها", href: "/", icon: BsBag },
  { id: "lists", label: "لیست ها", href: "/", icon: GrFavorite },
  { id: "comments", label: "دیگاه و پرسش ها", href: "/", icon: FaRegComment },
  { id: "address", label: "ادرس ها", href: "/", icon: SiGooglestreetview },
  { id: "cartGift", label: "کارت های هدیه", href: "/", icon: CiGift },
  { id: "messages", label: "پیام ها", href: "/", icon: RiNotification3Line },
  { id: "time", label: "بازدید های اخیر", href: "/", icon: MdAccessTime },
  { id: "user", label: "اطلاعات حساب کاربری", href: "/", icon: FaRegUser },
];

function ProfileLayout({children}) {
  const pathname = usePathname()
  const isAccountInfopage = pathname === "/profile/account-info"
  const [personlInformationsStatus,setPersoanlInformations] = useState(false)
  return (
    

    <div className="max-w-[1270px] mx-auto my-10 px-4 flex flex-row gap-6 ">

    
    <div className="w-[331px] h-[937px] border border-solid border-neutral-400 rounded-lg flex flex-col h-full">
      <div className="w-full flex items-center">
        <div className="w-full flex flex-col">
          <div className="w-full flex  items-center justify-between px-10 pt-5">
            <span>09123456789</span>
            <Link href="/profile/personal-info"  className="cursor-pointer text-blue-500">
            <CiEdit width={30} hanging={30} className="cursor-pointer" />

            </Link>
          </div>
          <div className="flex justify-between px-10 mt-[30px]">
            <span>طلا ونقره دیجیتال</span>
            <span>-ریال</span>
          </div>
          <div className="flex">
            <Link
              className="flex items-center text-blue-500 px-10 mt-[20px]"
              href="/"
            >
              افزایش موجودی
              <IoChevronBack />
            </Link>
          </div>

          <Link
            href="/"
            className=" mt-[30px] flex flex-col items-center  px-10"
          >
            <div className="flex justify-between w-full">
              <h6>کیف پول</h6>
              <span>0 تومان</span>
            </div>
            <div className="w-full flex items-center text-blue-500 mt-[10px]  ">
              <h6 className="flex  mt-[10px]">افزایش موجودی</h6>
              <IoChevronBack className="mt-[14px]" />
            </div>
          </Link>

          <Link
            className="mt-[30px] flex flex-col items-center px-10 "
            href="/"
          >
            <div className="w-full flex justify-between ">
              <h6>دیجی کلاب</h6>
              <span>-امتیاز</span>
            </div>
            <div className="w-full flex items-center text-blue-500 mt-[10px] ">
              <h6 className="mt-[10px]">مشاهده ماموریت ها</h6>
              <IoChevronBack className="mt-[14px]" />
            </div>
          </Link>
          <div className="border-b border-neutral-400 w-full mt-[10px]"></div>
          <div className="flex flex-1 flex-col justify-between ">
          {menuProfile.map((menu) => {
            const Iconmenu = menu.icon;
            return (
              <div key={menu.id} className="">
                <Link
                  className="flex items-center gap-2 border-b border-neutral-400 px-10 py-2"
                  href={menu.href}
                >
                  <Iconmenu />
                  <span>{menu.label}</span>
                </Link>
              </div>
            );
          })}
          </div>
        </div>
      </div>
    </div>
      <main className="flex-1 w-full">
        {children}
      </main>
    </div>
    
  );
}

export default ProfileLayout;
