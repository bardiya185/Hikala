"use client";

import ModalContainer from "@/components/partials/container/ModalContainer";
import React, { useState } from "react";
import { TbLogin } from "react-icons/tb";
import SendOtpForm from "./SendOtpForm";
import CheckOtpForm from "./CheckOtpForm";
import { HiUser } from "react-icons/hi2";
import { AiOutlineCaretDown } from "react-icons/ai";
import Link from "next/link";
import { IoLogOutOutline } from "react-icons/io5";
import { menuItems } from "@/core/config/menu";
import { useGetUserData } from "@/core/services/queries";

function AuthForm() {
  const [step, setStep] = useState(0);
  const [isOpen, setIsOpen] = useState(false);
  const [mobile, setMobile] = useState("");

  const { data } = useGetUserData();
  const { data: userData } = data || {};
  if (userData) {
    return (
      <div className="relative shrink-0">
        <button
          onClick={() => setIsOpen(!isOpen)}
          className="
            flex
            items-center
            justify-center
            gap-1
            min-w-[44px]
            h-10
            px-2
            rounded-xl
            border
            border-neutral-200
            hover:bg-neutral-50
            transition-colors
            cursor-pointer
          "
        >
          <HiUser className="w-5 h-5 text-neutral-700" />

          <AiOutlineCaretDown
            className={`w-4 h-4 text-neutral-500 transition-transform ${
              isOpen ? "rotate-180" : ""
            }`}
          />
        </button>

        {isOpen && (
          <div
            className="
              absolute
              right-0
              top-full
              mt-2
              w-[256px]
              max-w-[calc(100vw-24px)]
              max-h-[80vh]
              overflow-y-auto
              rounded-xl
              bg-white
              shadow-xl
              border
              border-neutral-100
              z-[100]
            "
          >
            <Link
              href="/profile"
              className="block text-neutral-700 hover:bg-neutral-50"
            >
              <div className="mx-4 py-4 border-b border-neutral-200">
                <span className="text-sm font-bold text-neutral-800">
                  {userData?.data?.mobile}
                </span>
              </div>
            </Link>

            <ul className="flex flex-col">
              <li className="px-4 hover:bg-neutral-50">
                <Link
                  href="/digiclub/"
                  className="flex items-center text-neutral-700 w-full border-b border-neutral-200 py-3"
                >
                  <div className="w-8 pl-2">
                    <img src="/icons/club.svg" alt="club" />
                  </div>

                  <div className="flex-1 flex justify-between items-center">
                    <span className="text-base font-bold">DigiClub </span>

                    <span className="text-sm font-bold">
                      0<small className="text-neutral-400 mr-1">Score</small>
                    </span>
                  </div>
                </Link>
              </li>

              {menuItems.map((item) => {
                const IconComponent = item.icon;

                return (
                  <li key={item.id} className="px-4 hover:bg-neutral-50">
                    <Link
                      href={item.href}
                      className="
                        flex
                        items-center
                        text-neutral-700
                        w-full
                        py-3
                        border-b
                        border-neutral-200
                      "
                    >
                      <div className="w-8 pl-2 flex justify-center">
                        <IconComponent className="w-5 h-5 text-neutral-600" />
                      </div>

                      <div className="flex-1 text-base font-bold">
                        {item.label}
                      </div>
                    </Link>
                  </li>
                );
              })}

              <li className="px-4 hover:bg-neutral-50 text-red-500">
                <div className="flex items-center w-full py-3">
                  <div className="w-8 pl-2">
                    <IoLogOutOutline className="w-5 h-5" />
                  </div>

                  <div className="flex-1 font-bold">Log out</div>
                </div>
              </li>
            </ul>
          </div>
        )}
      </div>
    );
  }
  return (
    <>
      <button
        type="button"
        onClick={() => {
          setStep(1);
          setIsOpen(true);
        }}
        className="
          group
          flex
          shrink-0
          items-center
          justify-center
          gap-1.5

          w-[92px]
          sm:w-[105px]
          md:w-[120px]

          h-[38px]
          sm:h-[40px]

          px-2
          sm:px-3

          border
          border-neutral-200

          bg-white
          hover:bg-neutral-900

          text-neutral-700
          hover:text-white

          rounded-lg
          sm:rounded-xl

          text-xs
          sm:text-sm
          font-medium

          shadow-sm
          hover:shadow-md

          transition-all
          duration-300

          cursor-pointer
        "
      >
        <TbLogin
          className="
            w-4
            h-4
            sm:w-5
            sm:h-5
            shrink-0
            text-neutral-500
            group-hover:text-white
            transition-colors
          "
        />

        <span className="whitespace-nowrap">Sign In</span>
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
