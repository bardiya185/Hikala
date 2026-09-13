
"use client";

import React, {
  useState,
  useMemo,
  useCallback,
} from "react";

import Link from "next/link";
import Image from "next/image";

import {
  TbLogin,
  TbLogout,
} from "react-icons/tb";
import {
  HiUser,
} from "react-icons/hi2";
import {
  AiOutlineCaretDown,
} from "react-icons/ai";

import ModalContainer from "@/components/partials/container/ModalContainer";

import SendOtpForm from "@/app/auth/login/sendOtp/SendOtpForm";
import CheckOtpForm from "@/app/auth/login/checkOtp/CheckOtpForm";
import LogoutButton from "@/components/LogoutButton/logout_button";

import { menuItems } from "@/core/config/menu";
import { useGetUserData } from "@/core/services/queries";

// ============================================================
// SKELETON COMPONENT
// ============================================================

const AuthSkeleton = () => {
  return (
    <div
      className="
        h-10
        w-[92px]

        animate-pulse

        rounded-xl

        bg-neutral-100

        dark:bg-neutral-900

        sm:w-[105px]
        md:w-[120px]
      "
      aria-hidden="true"
    />
  );
};

// ============================================================
// MAIN COMPONENT
// ============================================================

function AuthForm() {
  const [step, setStep] = useState(0);
  const [isOpen, setIsOpen] = useState(false);
  const [mobile, setMobile] = useState("");

  const {
    data: userData,
    isLoading,
  } = useGetUserData();

  // ============================================================
  // DROPDOWN HANDLERS
  // ============================================================

  const handleToggleDropdown = useCallback(() => {
    setIsOpen((prev) => !prev);
  }, []);

  const handleCloseDropdown = useCallback(() => {
    setIsOpen(false);
  }, []);

  // ============================================================
  // MENU ITEMS
  // ============================================================

  const renderedMenuItems = useMemo(() => {
    return menuItems.map((item) => {
      const IconComponent = item.icon;

      return (
        <li key={item.id}>
          <Link
            href={item.href}
            onClick={handleCloseDropdown}
            className="
              group

              flex
              w-full
              items-center
              gap-1

              border-b
              border-neutral-100

              px-4
              py-3.5

              text-neutral-700

              transition-colors
              duration-150

              hover:bg-neutral-50
              hover:text-neutral-950

              focus-visible:outline-none
              focus-visible:ring-2
              focus-visible:ring-inset
              focus-visible:ring-red-500/30

              dark:border-neutral-800
              dark:text-neutral-300

              dark:hover:bg-neutral-900
              dark:hover:text-white
            "
          >
            <div
              className="
                flex
                w-8
                shrink-0
                items-center
                justify-center
              "
            >
              <IconComponent
                className="
                  h-5
                  w-5

                  text-neutral-500

                  transition-colors

                  group-hover:text-red-500

                  dark:text-neutral-500
                  dark:group-hover:text-red-400
                "
              />
            </div>

            <span
              className="
                flex-1

                text-sm
                font-semibold
              "
            >
              {item.label}
            </span>
          </Link>
        </li>
      );
    });
  }, [handleCloseDropdown]);

  // ============================================================
  // LOADING STATE
  // ============================================================

  if (isLoading) {
    return <AuthSkeleton />;
  }

  // ============================================================
  // AUTHENTICATED USER
  // ============================================================

  if (userData) {
    const userMobile =
      userData?.data?.data?.mobile || "My account";

    return (
      <div className="relative shrink-0">
        {/* ======================================================
            USER BUTTON
        ======================================================= */}
        <button
          type="button"
          onClick={handleToggleDropdown}
          aria-label={
            isOpen
              ? "Close account menu"
              : "Open account menu"
          }
          aria-expanded={isOpen}
          className="
            group

            flex
            h-10
            min-w-[44px]
            cursor-pointer
            items-center
            justify-center
            gap-1.5

            rounded-xl

            border
            border-neutral-200

            bg-white

            px-2.5

            text-neutral-700

            shadow-sm

            transition-all
            duration-200

            hover:border-neutral-300
            hover:bg-neutral-50
            hover:text-neutral-950

            focus-visible:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500/30

            dark:border-neutral-800
            dark:bg-neutral-900
            dark:text-neutral-300

            dark:hover:border-neutral-700
            dark:hover:bg-neutral-800
            dark:hover:text-white
          "
        >
          <HiUser
            className="
              h-5
              w-5

              transition-transform
              duration-200

              group-hover:scale-105
            "
          />

          <AiOutlineCaretDown
            className={`
              h-3.5
              w-3.5

              text-neutral-400

              transition-transform
              duration-200

              dark:text-neutral-500

              ${isOpen ? "rotate-180" : ""}
            `}
          />
        </button>

        {/* ======================================================
            USER DROPDOWN
        ======================================================= */}
        {isOpen && (
          <div
            className="
              absolute
              right-0
              top-full
              z-[100]

              mt-2

              w-[280px]
              max-w-[calc(100vw-24px)]

              overflow-hidden

              rounded-2xl

              border
              border-neutral-200

              bg-white

              shadow-[0_16px_50px_rgba(0,0,0,0.12)]

              dark:border-neutral-800
              dark:bg-neutral-950
              dark:shadow-[0_20px_60px_rgba(0,0,0,0.4)]
            "
          >
            {/* ==================================================
                USER HEADER
            =================================================== */}
            <Link
              href="/profile"
              onClick={handleCloseDropdown}
              className="
                block

                border-b
                border-neutral-200

                bg-neutral-50

                px-4
                py-4

                transition-colors

                hover:bg-neutral-100

                focus-visible:outline-none
                focus-visible:ring-2
                focus-visible:ring-inset
                focus-visible:ring-red-500/30

                dark:border-neutral-800
                dark:bg-neutral-900
                dark:hover:bg-neutral-800
              "
            >
              <div className="flex items-center gap-3">
                {/* Avatar */}
                <div
                  className="
                    flex
                    h-10
                    w-10
                    shrink-0
                    items-center
                    justify-center

                    rounded-full

                    bg-white

                    text-neutral-600

                    shadow-sm

                    dark:bg-neutral-800
                    dark:text-neutral-300
                  "
                >
                  <HiUser className="h-5 w-5" />
                </div>

                {/* User Info */}
                <div className="min-w-0 flex-1">
                  <p
                    className="
                      text-[11px]
                      font-medium
                      uppercase
                      tracking-wide

                      text-neutral-400

                      dark:text-neutral-500
                    "
                  >
                    My account
                  </p>

                  <p
                    className="
                      mt-0.5
                      truncate

                      text-sm
                      font-semibold

                      text-neutral-800

                      dark:text-neutral-100
                    "
                  >
                    {userMobile}
                  </p>
                </div>
              </div>
            </Link>

            {/* ==================================================
                MENU
            =================================================== */}
            <ul className="flex flex-col">
              {/* DigiClub */}
              <li>
                <Link
                  href="/digiclub/"
                  onClick={handleCloseDropdown}
                  className="
                    group

                    flex
                    w-full
                    items-center
                    gap-1

                    border-b
                    border-neutral-100

                    px-4
                    py-3.5

                    text-neutral-700

                    transition-colors
                    duration-150

                    hover:bg-neutral-50
                    hover:text-neutral-950

                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-inset
                    focus-visible:ring-red-500/30

                    dark:border-neutral-800
                    dark:text-neutral-300

                    dark:hover:bg-neutral-900
                    dark:hover:text-white
                  "
                >
                  <div
                    className="
                      flex
                      w-8
                      shrink-0
                      items-center
                      justify-center
                    "
                  >
                    <Image
                      src="/icons/club.svg"
                      alt="DigiClub"
                      width={22}
                      height={22}
                      className="
                        transition-transform
                        duration-200

                        group-hover:scale-105
                      "
                    />
                  </div>

                  <div
                    className="
                      flex
                      min-w-0
                      flex-1
                      items-center
                      justify-between
                      gap-3
                    "
                  >
                    <span
                      className="
                        text-sm
                        font-semibold
                      "
                    >
                      DigiClub
                    </span>

                    <span
                      className="
                        shrink-0

                        text-xs
                        font-semibold

                        text-neutral-500

                        dark:text-neutral-400
                      "
                    >
                      0

                      <span
                        className="
                          ml-1
                          font-normal

                          text-neutral-400

                          dark:text-neutral-600
                        "
                      >
                        Score
                      </span>
                    </span>
                  </div>
                </Link>
              </li>

              {/* Menu Items */}
              {renderedMenuItems}

              {/* ==================================================
                  LOGOUT
              =================================================== */}
              <li>
                <div
                  className="
                    border-t
                    border-neutral-100

                    dark:border-neutral-800
                  "
                >
                  <div
                    className="
                      flex
                      w-full
                      items-center
                      gap-1

                      px-4
                      py-3.5

                      text-red-500

                      transition-colors

                      hover:bg-red-50

                      dark:text-red-400
                      dark:hover:bg-red-950/30
                    "
                  >
                    <div className="flex-1">
                      <LogoutButton />
                    </div>
                  </div>
                </div>
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

          setIsOpen(true);
        }}
        className="
          group

          flex
          h-[38px]
          w-[92px]
          shrink-0
          cursor-pointer
          items-center
          justify-center
          gap-1.5

          rounded-lg

          border
          border-neutral-200

          bg-white

          px-2

          text-xs
          font-semibold

          text-neutral-700

          shadow-sm

          transition-all
          duration-200

          hover:border-neutral-900
          hover:bg-neutral-900
          hover:text-white
          hover:shadow-md

          focus-visible:outline-none
          focus-visible:ring-2
          focus-visible:ring-red-500/30

          active:scale-[0.98]

          dark:border-neutral-800
          dark:bg-neutral-900
          dark:text-neutral-200

          dark:hover:border-white
          dark:hover:bg-white
          dark:hover:text-neutral-950

          sm:h-10
          sm:w-[105px]
          sm:rounded-xl
          sm:px-3
          sm:text-sm

          md:w-[120px]
        "
      >
        <TbLogin
          className="
            h-4
            w-4
            shrink-0

            text-neutral-500

            transition-colors
            duration-200

            group-hover:text-white

            dark:text-neutral-400
            dark:group-hover:text-neutral-950

            sm:h-5
            sm:w-5
          "
        />

        <span className="whitespace-nowrap">
          Sign In
        </span>
      </button>

      {/* ======================================================
          SEND OTP MODAL
      ======================================================= */}
      {step === 1 && (
        <ModalContainer
          isOpen={isOpen}
          setIsOpen={setIsOpen}
        >
          <SendOtpForm
            setStep={setStep}
            mobile={mobile}
            setMobile={setMobile}
          />
        </ModalContainer>
      )}

      {/* ======================================================
          CHECK OTP MODAL
      ======================================================= */}
      {step === 2 && (
        <ModalContainer
          isOpen={isOpen}
          setIsOpen={setIsOpen}
        >
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

