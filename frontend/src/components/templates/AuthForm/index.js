"use client";
import ModalContainer from "@/components/partials/container/ModalContainer";
import React, { useState } from "react";
import { TbLogin } from "react-icons/tb";
import SendOtpForm from "./SendOtpForm";
import CheckOtpForm from "./CheckOtpForm";
import { HiUser } from "react-icons/hi2";
import { AiOutlineCaretDown } from "react-icons/ai";
import Link from "next/link";
import Image from "next/image";
import { IoLogOutOutline } from "react-icons/io5";
import { menuItems } from "@/core/config/menu";
import { useGetUserData } from "@/core/services/queries";

function AuthForm() {
  const [step, setStep] = useState(0);
  const [isOpen, setIsOpen] = useState(false);
  const [mobile, setMobile] = useState("");

  const {data} = useGetUserData()
    const { data: userData } = data || {};
  console.log(userData)
 

  
  if (userData)
    return (
      <div className="relative  cursor-pointer">
        <div onClick={() => setIsOpen(!isOpen)} className="flex items-center">
          <HiUser className="w-[24px] h-[24px]" />
          <AiOutlineCaretDown className="w-[20px] h-[24px]" />
        </div>
        {isOpen && (
          <div className="absolute left-0 top-full w-[256px] h-auto mt-2 overflow-hidden shadow-lg rounded-lg bg-white pb-2 z-50 border border-neutral-100 flex flex-col  ">
            <Link
              className="block text-neutral-700 hover:bg-neutral-50"
              href="/profile"
            >
              <div className="flex mx-4 py-4 border-b mt-[15px] border-neutral-200 justify-between items-center">
                
                <span className=" font-iranyekanbold text-sm font-bold text-neutral-800 text-[]">
        {userData?.data?.mobile}
                </span>
                
              </div>
            </Link>

            <ul className="flex flex-1 flex-col grow justify-between ">
              <li className="px-4 cursor-pointer w-full hover:bg-neutral-50">
                <Link
                  href="/digiclub/"
                  className="flex items-center text-neutral-700 w-full  border-b border-neutral-200 py-2  "
                >
                  <div className="w-8 pl-3">
                    {/* <Image src="/icons/club-point.svg" width={24} height={24} alt="club"   /> */}
                    <img className="" src="/icons/club.svg" />
                  </div>
                  <div className="flex-1 flex justify-between items-center">
                    <span className=" font-iranyekanbold text-lg  ">دیجی کلاب</span>
                    <span className="text-lg font-bold text-neutral-700  ">
                      0
                      <small className= " font-iranyekanbold text-neutral-400 mr-1">
                        امتیاز
                      </small>
                    </span>
                  </div>
                </Link>
              </li>

              {menuItems.map((item) => {
                // نام متغیر آیکون را با حرف بزرگ ذخیره می‌کنیم
                const IconComponent = item.icon;

                return (
                  <li
                    key={item.id}
                    className="px-4 cursor-pointer w-full hover:bg-neutral-50"
                  >
                    <Link
                      href={item.href}
                      className="flex items-center text-neutral-700 w-full py-2 border-b  border-neutral-200"
                    >
                      <div className="w-8 pl-3 flex justify-center items-center">
                        <IconComponent className="w-6 h-6 text-neutral-600" />
                      </div>
                      <div className="flex-1 text-lg  font-iranyekanbold ">
                        {item.label}
                      </div>
                    </Link>
                  </li>
                );
              })}
              <li className="px-4 cursor-pointer w-full hover:bg-neutral-50 text-red-500 ">
                <div className="flex items-center w-full py-3">
                  <div className="w-8 pl-3">
                    <IoLogOutOutline className="w-[24px] h-[24px]"/>

                  </div>
                  <div className="flex-1  font-iranyekanbold ">خروج از حساب کاربری</div>
                </div>
              </li>
            </ul>
          </div>
        )}
      </div>
    );
  return (
    <>
      <button
    onClick={() => setStep(1)}
    className="group flex items-center justify-center gap-2 w-[120px] h-[40px] border border-neutral-200/80 hover:border-neutral-900 bg-white hover:bg-neutral-900 text-neutral-700 hover:text-white rounded-xl text-sm font-medium shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition-all duration-300 ease-in-out cursor-pointer"
  >
    <TbLogin className="w-5 h-5 text-neutral-500 group-hover:text-white transition-colors duration-300" />
    <span>Sign In</span>
  </button>
      {step === 1 && (
        <ModalContainer isOpen={isOpen} setIsOpen={setIsOpen}>
          <SendOtpForm
            setStep={setStep}
            mobile={mobile}
            setMobile={setMobile}
          />
        </ModalContainer>
      )}

      {step === 2 && (
        <ModalContainer isOpen={isOpen} setIsOpen={setIsOpen}>
          <CheckOtpForm
            mobile={mobile}
            setStep={setStep}
            setIsOpen={setIsOpen}
          />
        </ModalContainer>
      )}
    </>
  );
}

export default AuthForm;
