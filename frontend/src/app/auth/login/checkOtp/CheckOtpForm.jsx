"use client";

import { useCheckOtp } from "@/core/services/mutations";
import Image from "next/image";
import { useRouter, useSearchParams } from "next/navigation";
import React, { useRef, useState } from "react";
import toast from "react-hot-toast";

function CheckOtpForm({ mobile, setStep, setIsOpen }) {
  const [code, setCode] = useState("");
  const inputRefs = useRef([]);

  const router = useRouter();
  const searchParams = useSearchParams();

  const { isPending, mutate } = useCheckOtp();

  const handleChange = (index, value) => {
    const digit = value.replace(/\D/g, "").slice(-1);

    const currentCode = code.split("");
    currentCode[index] = digit;

    const newCode = currentCode.join("").slice(0, 6);

    setCode(newCode);

    if (digit && index < 5) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handleKeyDown = (index, event) => {
    if (
      event.key === "Backspace" &&
      !code[index] &&
      index > 0
    ) {
      inputRefs.current[index - 1]?.focus();
    }

    if (event.key === "ArrowLeft" && index > 0) {
      event.preventDefault();
      inputRefs.current[index - 1]?.focus();
    }

    if (event.key === "ArrowRight" && index < 5) {
      event.preventDefault();
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handlePaste = (event) => {
    event.preventDefault();

    const pastedCode = event.clipboardData
      .getData("text")
      .replace(/\D/g, "")
      .slice(0, 6);

    if (!pastedCode) return;

    setCode(pastedCode);

    const nextIndex = Math.min(pastedCode.length, 5);
    inputRefs.current[nextIndex]?.focus();
  };

  const submitHandler = (event) => {
    event.preventDefault();

    if (isPending) return;

    if (!mobile) {
      toast.error("Mobile number is missing");
      return;
    }

    if (code.length !== 6) {
      toast.error("Please enter the complete 6-digit code");
      return;
    }

    mutate(
      {
        mobile,
        code,
      },
      {
        onSuccess: () => {
          toast.success("Login successful");

          const redirect = searchParams.get("redirect");

          sessionStorage.removeItem("login_mobile");

          // Modal mode
          if (setStep && setIsOpen) {
            setStep(0);
            setIsOpen(false);

            if (redirect) {
              router.push(redirect);
            } else {
              router.refresh();
            }

            return;
          }

          // Standalone page mode
          if (redirect) {
            router.push(redirect);
          } else {
            router.push("/");
          }
        },

        onError: (error) => {
          toast.error(
            error?.response?.data?.message ||
              "Invalid verification code. Please try again."
          );
        },
      }
    );
  };

  return (
    <form
      onSubmit={submitHandler}
      className="
        flex min-h-[450px] w-full
        items-center justify-center
        px-2
      "
    >
      <div
        className="
          flex w-full max-w-[380px] flex-col
          rounded-2xl
          border border-neutral-100
          bg-white
          p-5
          shadow-[0_8px_30px_rgb(0,0,0,0.04)]
          transition-all duration-300
          sm:p-6

          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-[0_8px_30px_rgb(0,0,0,0.25)]
        "
      >
        {/* Logo */}
        <div className="mb-6 mt-1 flex justify-center">
          <Image
            src="/icons/en-logo.svg"
            width={150}
            height={24}
            alt="Digikala"
            className="h-auto w-[140px] object-contain sm:w-[150px]"
          />
        </div>

        {/* Title */}
        <div className="text-left">
          <h6
            className="
              text-lg font-bold tracking-tight
              text-neutral-800
              dark:text-neutral-100
            "
          >
            Enter verification code
          </h6>

          <p
            className="
              mt-2 text-[11px] font-medium leading-relaxed
              text-neutral-400
              sm:text-xs
              md:text-sm
              dark:text-neutral-400
            "
          >
            A 6-digit verification code has been sent to{" "}
            <span className="font-semibold text-neutral-700 dark:text-neutral-200">
              {mobile}
            </span>
          </p>
        </div>

        {/* OTP */}
        <div
          className="mt-7 flex justify-center gap-1.5 sm:gap-2"
          dir="ltr"
          role="group"
          aria-label="Verification code"
        >
          {Array.from({ length: 6 }).map((_, index) => (
            <input
              key={index}
              ref={(element) => {
                inputRefs.current[index] = element;
              }}
              type="text"
              inputMode="numeric"
              autoComplete={
                index === 0 ? "one-time-code" : "off"
              }
              maxLength={1}
              value={code[index] || ""}
              autoFocus={index === 0}
              onChange={(event) =>
                handleChange(index, event.target.value)
              }
              onKeyDown={(event) =>
                handleKeyDown(index, event)
              }
              onPaste={handlePaste}
              aria-label={`Verification code digit ${index + 1}`}
              className="
                h-12 w-10
                rounded-xl
                border border-neutral-200
                bg-neutral-50
                text-center
                text-lg font-semibold
                text-neutral-800
                outline-none
                transition-all duration-200

                hover:border-neutral-300
                focus:border-red-500
                focus:bg-white
                focus:ring-4
                focus:ring-red-500/10

                sm:h-13 sm:w-11

                dark:border-neutral-700
                dark:bg-neutral-950
                dark:text-neutral-100
                dark:hover:border-neutral-600
                dark:focus:border-red-500
                dark:focus:bg-neutral-900
                dark:focus:ring-red-500/10
              "
            />
          ))}
        </div>

        {/* Helper */}
        <p
          className="
            mt-3 text-center text-[11px]
            text-neutral-400
            dark:text-neutral-500
          "
        >
          Enter the 6-digit code to continue
        </p>

        {/* Submit */}
        <button
          type="submit"
          disabled={isPending}
          className="
            mt-5 flex h-[50px] w-full
            items-center justify-center
            rounded-xl
            bg-red-600
            text-sm font-semibold text-white
            shadow-md shadow-red-600/10
            transition-all duration-200

            hover:bg-red-700
            active:scale-[0.99]
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500
            focus-visible:ring-offset-2

            disabled:cursor-not-allowed
            disabled:opacity-60

            dark:bg-red-600
            dark:hover:bg-red-500
            dark:focus-visible:ring-offset-neutral-900
          "
        >
          {isPending ? "Verifying..." : "Verify & Proceed"}
        </button>

        {/* Resend */}
        <div className="mt-5 text-center">
          <span
            className="
              text-[12px]
              text-neutral-400
              dark:text-neutral-500
            "
          >
            Didn't receive the code?{" "}
          </span>

          <button
            type="button"
            disabled
            className="
              cursor-not-allowed
              border-none
              bg-transparent
              text-[12px]
              font-semibold
              text-red-600
              opacity-70
              dark:text-red-400
            "
          >
            Resend
          </button>
        </div>
      </div>
    </form>
  );
}

export default CheckOtpForm;