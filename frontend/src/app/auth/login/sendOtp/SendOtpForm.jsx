"use client";

import { useSendOtp } from "@/core/services/mutations";
import Image from "next/image";
import { useRouter } from "next/navigation";
import React, { useState } from "react";
import toast from "react-hot-toast";

function SendOtpForm({
  setStep,
  mobile,
  setMobile
}) {
  const router = useRouter();

 
  const [error, setError] = useState("");

  const { isPending, mutate } = useSendOtp();

  // اگر از AuthForm مقدار آمده باشد، از آن استفاده می‌کنیم
 

  const submitHandler = (event) => {
    event.preventDefault();

    if (isPending) return;

    if (!mobile) {
      
      return;
    }

    setError("");

    mutate(
      { mobile },
      {
       onSuccess: (data) => {
  console.log(data);

  toast.success(data?.data?.code);

  if (setStep) {
    // داخل AuthForm / Modal
    setStep(2);
     window.history.replaceState({}, "", "/");
  
  } else {
    // فقط وقتی فرم در صفحه مستقل استفاده شده
    router.push("/auth/login/checkOtp");
  }
},

        onError: (error) => {
          console.error(error);

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
      className="flex min-h-[450px] w-full items-center justify-center"
    >
      <div className="flex w-full flex-col rounded-2xl border border-neutral-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] lg:max-w-[360px]">

        {/* Logo */}
        <div className="mb-6 mt-2 flex justify-center">
          <Image
            src="/icons/en-logo.svg"
            width={150}
            height={24}
            alt="logo"
            className="object-contain"
          />
        </div>

        {/* Title */}
        <h6 className="text-left text-lg font-bold tracking-tight text-neutral-800">
          Log in or sign up
        </h6>

        <p className="mt-1.5 text-left text-[12px] font-medium text-neutral-400">
          Please enter your mobile number to proceed.
        </p>

        {/* Mobile Input */}
        <div className="relative mt-6">
          <input
            type="tel"
            value={mobile}
            onChange={(e) => setMobile(e.target.value)}
            className="h-[50px] w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 text-sm text-neutral-800 outline-none transition-all duration-200 placeholder:text-neutral-400 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/10"
            placeholder="e.g. 09123456789"
          />

          {error && (
            <p className="mt-2 text-xs text-red-500">
              {error}
            </p>
          )}
        </div>

        {/* Submit */}
        <button
          type="submit"
          disabled={isPending}
          className="mt-5 flex h-[50px] w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 text-sm font-semibold text-white shadow-md shadow-red-600/10 transition-all duration-200 hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
        >
          {isPending ? "Sending..." : "Continue"}
        </button>

        {/* Terms */}
        <p className="mt-6 text-center text-[11px] leading-relaxed text-neutral-400">
          By continuing, you agree to Digikala's{" "}
          <span className="cursor-pointer text-neutral-700 underline">
            Terms of Service
          </span>
          .
        </p>
      </div>
    </form>
  );
}

export default SendOtpForm;