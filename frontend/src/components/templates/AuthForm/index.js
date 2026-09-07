"use client";

import React, { useState, useMemo, useCallback } from "react";
import Link from "next/link";
import Image from "next/image";

import { TbLogin } from "react-icons/tb";
import { HiUser } from "react-icons/hi2";
import { AiOutlineCaretDown } from "react-icons/ai";

import ModalContainer from "@/components/partials/container/ModalContainer";

import SendOtpForm from "@/app/auth/login/sendOtp/SendOtpForm";
import CheckOtpForm from "@/app/auth/login/checkOtp/CheckOtpForm";
import LogoutButton from "@/components/LogoutButton/logout_button";
import { menuItems } from "@/core/config/menu";
import { useGetUserData } from "@/core/services/queries";
import { useRouter } from "next/navigation";


// ============================================================
// SKELETON COMPONENT
// ============================================================

const AuthSkeleton = () => (
  <div className="h-10 w-[92px] animate-pulse rounded-xl bg-neutral-100 sm:w-[105px] md:w-[120px]" />
);

// ============================================================
// MAIN COMPONENT
// ============================================================

function AuthForm() {
  const [step, setStep] = useState(0);
  const [isOpen, setIsOpen] = useState(false);
  const [mobile, setMobile] = useState("");

  const { data:userData, isLoading } = useGetUserData();

  const router = useRouter()

  console.log(userData);
  



  const handleToggleDropdown = useCallback(() => {
    setIsOpen((prev) => !prev);
  }, []);

  const handleCloseDropdown = useCallback(() => {
    setIsOpen(false);
  }, []);

  

 


  // ✅ useMemo برای منوی کاربر (برای جلوگیری از رندر مجدد)
  const renderedMenuItems = useMemo(() => {
    return menuItems.map((item) => {
      const IconComponent = item.icon;
      return (
        <li key={item.id} className="px-4 hover:bg-neutral-50">
          <Link
            href={item.href}
            onClick={handleCloseDropdown}
            className="flex w-full items-center border-b border-neutral-200 py-3 text-neutral-700"
          >
            <div className="flex w-8 justify-center pl-2">
              <IconComponent className="h-5 w-5 text-neutral-600" />
            </div>
            <div className="flex-1 text-base font-bold">{item.label}</div>
          </Link>
        </li>
      );
    });
  }, [handleCloseDropdown]);

  // Loading State
  if (isLoading) {
    return <AuthSkeleton />;
  }

  // ============================================================
  // AUTHENTICATED USER
  // ============================================================

  if (userData) {
    return (
      <div className="relative shrink-0">
        {/* User Button */}
        <button
          type="button"
          onClick={handleToggleDropdown}
          className="flex h-10 min-w-[44px] cursor-pointer items-center justify-center gap-1 rounded-xl border border-neutral-200 bg-white px-2 transition-colors hover:bg-neutral-50"
        >
          <HiUser className="h-5 w-5 text-neutral-700" />
          <AiOutlineCaretDown
            className={`h-4 w-4 text-neutral-500 transition-transform ${
              isOpen ? "rotate-180" : ""
            }`}
          />
        </button>

        {/* Dropdown */}
        {isOpen && (
          <div className="absolute right-0 top-full z-[100] mt-2 max-h-[80vh] w-[256px] max-w-[calc(100vw-24px)] overflow-y-auto rounded-xl border border-neutral-100 bg-white shadow-xl">
            {/* User Mobile */}
            <Link
              href="/profile"
              className="block text-neutral-700 hover:bg-neutral-50"
              onClick={handleCloseDropdown}
            >
              <div className="mx-4 border-b border-neutral-200 py-4">
                <span className="text-sm font-bold text-neutral-800">
                  {userData?.data?.data?.mobile}
                </span>
              </div>
            </Link>

            <ul className="flex flex-col">
              {/* DigiClub */}
              <li className="px-4 hover:bg-neutral-50">
                <Link
                  href="/digiclub/"
                  onClick={handleCloseDropdown}
                  className="flex w-full items-center border-b border-neutral-200 py-3 text-neutral-700"
                >
                  <div className="w-8 pl-2">
                    <Image
                      src="/icons/club.svg"
                      alt="DigiClub"
                      width={24}
                      height={24}
                    />
                  </div>
                  <div className="flex flex-1 items-center justify-between">
                    <span className="text-base font-bold">DigiClub</span>
                    <span className="text-sm font-bold">
                      0
                      <small className="ml-1 text-neutral-400">Score</small>
                    </span>
                  </div>
                </Link>
              </li>

              {/* Menu Items */}
              {renderedMenuItems}

              {/* Logout */}
              <li className="px-4 text-red-500 hover:bg-neutral-50">
                <button
                  type="button"
                  className="flex w-full items-center py-3 text-left"
                >
                  <div className="flex-1 font-bold">
                    <LogoutButton />
                  </div>
                </button>
              </li>
            </ul>
          </div>
        )}
      </div>
    );
  }

  // ============================================================
  // GUEST USER
  // ============================================================

  return (
    <>
      <button
        type="button"
        onClick={() => {
          setStep(1);
           window.history.replaceState(
    {},
    "",
    "/auth/login/sendOtp"
  );
          setIsOpen(true)}}
        
        className="group flex h-[38px] w-[92px] shrink-0 cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-2 text-xs font-medium text-neutral-700 shadow-sm transition-all duration-300 hover:bg-neutral-900 hover:text-white hover:shadow-md sm:h-[40px] sm:w-[105px] sm:rounded-xl sm:px-3 sm:text-sm md:w-[120px]"
      >
        <TbLogin className="h-4 w-4 shrink-0 text-neutral-500 transition-colors group-hover:text-white sm:h-5 sm:w-5" />
        <span className="whitespace-nowrap">Sign In</span>
      </button>

      {/* Send OTP Modal */}
      {step === 1 && (
        <ModalContainer isOpen={isOpen} setIsOpen={setIsOpen}>
          <SendOtpForm
            setStep={setStep}
            mobile={mobile}
            setMobile={setMobile}
          />
        </ModalContainer>
      )}

      {/* Check OTP Modal */}
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