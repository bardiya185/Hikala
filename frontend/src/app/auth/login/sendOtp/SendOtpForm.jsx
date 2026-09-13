"use client";

import { useSendOtp } from "@/core/services/mutations";
import Image from "next/image";
import { useRouter } from "next/navigation";
import React, { useState } from "react";
import toast from "react-hot-toast";

function SendOtpForm({ setStep, mobile, setMobile }) {
  const router = useRouter();

  const [error, setError] = useState("");

  const { isPending, mutate } = useSendOtp();

  const submitHandler = (event) => {
    event.preventDefault();

    if (isPending) return;

    const normalizedMobile = mobile?.trim();

    if (!normalizedMobile) {
      setError("Please enter your mobile number");
      return;
    }

    setError("");

    mutate(
      {
        mobile: normalizedMobile,
      },
      {
        onSuccess: (data) => {
          toast.success(data?.data?.code);

          if (setStep) {
            // Modal mode
            setStep(2);
          } else {
            // Standalone page mode
            sessionStorage.setItem(
              "login_mobile",
              normalizedMobile
            );

            const redirect = new URLSearchParams(
              window.location.search
            ).get("redirect");

            const query = redirect
              ? `?redirect=${encodeURIComponent(redirect)}`
              : "";

            router.push(
              `/auth/login/checkOtp${query}`
            );
          }
        },

        onError: (error) => {
          setError(
            error?.response?.data?.message ||
              "Something went wrong. Please try again."
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
            Log in or sign up
          </h6>

          <p
            className="
              mt-1.5 text-[12px]
              font-medium
              text-neutral-400
              dark:text-neutral-400
            "
          >
            Please enter your mobile number to proceed.
          </p>
        </div>

        {/* Mobile Input */}
        <div className="relative mt-6">
          <label
            htmlFor="mobile"
            className="
              mb-2 block text-xs font-medium
              text-neutral-600
              dark:text-neutral-300
            "
          >
            Mobile number
          </label>

          <input
            id="mobile"
            type="tel"
            inputMode="numeric"
            autoComplete="tel"
            value={mobile}
            onChange={(event) => {
              setMobile(event.target.value);
              if (error) setError("");
            }}
            aria-invalid={Boolean(error)}
            aria-describedby={error ? "mobile-error" : undefined}
            className="
              h-[50px] w-full
              rounded-xl
              border
              border-neutral-200
              bg-neutral-50
              px-4
              text-sm
              text-neutral-800
              outline-none
              transition-all duration-200
              placeholder:text-neutral-400

              hover:border-neutral-300
              focus:border-red-500
              focus:bg-white
              focus:ring-4
              focus:ring-red-500/10

              dark:border-neutral-700
              dark:bg-neutral-950
              dark:text-neutral-100
              dark:placeholder:text-neutral-600
              dark:hover:border-neutral-600
              dark:focus:border-red-500
              dark:focus:bg-neutral-900
              dark:focus:ring-red-500/10
            "
            placeholder="e.g. 09123456789"
          />

          {error && (
            <p
              id="mobile-error"
              role="alert"
              className="
                mt-2 text-xs
                font-medium text-red-500
                dark:text-red-400
              "
            >
              {error}
            </p>
          )}
        </div>

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
          {isPending ? "Sending..." : "Continue"}
        </button>

        {/* Terms */}
        <p
          className="
            mt-6 text-center
            text-[11px]
            leading-relaxed
            text-neutral-400
            dark:text-neutral-500
          "
        >
          By continuing, you agree to Digikala&apos;s{" "}
          <span
            className="
              cursor-pointer
              font-medium
              text-neutral-700
              underline
              underline-offset-2

              dark:text-neutral-300
            "
          >
            Terms of Service
          </span>
          .
        </p>
      </div>
    </form>
  );
}

export default SendOtpForm;