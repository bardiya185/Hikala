"use client";

import { useCheckOtp } from "@/core/services/mutations";
import Image from "next/image";
import { useRouter } from "next/navigation";
import React, { useState } from "react";
import toast from "react-hot-toast";
import OtpInput from "react18-input-otp";

function CheckOtpForm({mobile,  setStep, setIsOpen }) {
  const [code, setCode] = useState("");
 
  const router = useRouter();

  const { isPending, mutate } = useCheckOtp();

  const handleChange = (otp) => {
    setCode(otp);
  };

  const submitHandler = (event) => {
    event.preventDefault();

    if (isPending) return;

    if (code.length !== 6) {
      toast.error("Please enter the complete 6-digit code");
      return;
    }

    mutate(
      {
        mobile,code
      },
      {
        onSuccess: (data) => {
          console.log("OTP verification success:", data);

          toast.success("Login successful");

          setStep(0);
          setIsOpen(false);

        window.history.replaceState({}, "", "/");
  router.refresh();
        },

        onError: (error) => {
          console.error("OTP verification error:", error);

          
        },
      }
    );
  };

  return (
    <form
      onSubmit={submitHandler}
      className="flex min-h-[450px] w-full items-center justify-center"
    >
      <div className="flex w-full flex-col rounded-2xl border border-neutral-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 lg:max-w-[360px]">

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
        <div className="flex flex-col text-left">
          <h6 className="text-lg font-bold tracking-tight text-neutral-800">
            Enter verification code
          </h6>

          <p className="mt-2 text-[11px] font-medium leading-relaxed text-neutral-400 sm:text-[12px] md:text-sm">
            A 6-digit verification code has been sent to{" "}
            <span className="font-semibold text-neutral-700">
              {mobile}
            </span>
          </p>
        </div>

        {/* OTP */}
        <div className="mt-6 flex justify-center" dir="ltr">
          <OtpInput
            value={code}
            onChange={handleChange}
            numInputs={6}
            shouldAutoFocus
            inputStyle={{
              width: "44px",
              height: "48px",
              margin: "0 4px",
              textAlign: "center",
              fontSize: "18px",
              fontWeight: "600",
              border: "1px solid #e5e5e5",
              borderRadius: "12px",
              outline: "none",
              background: "#fafafa",
            }}
          />
        </div>

        {/* Submit */}
        <button
          type="submit"
          disabled={isPending}
          className="mt-6 flex h-[50px] w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 text-sm font-semibold text-white shadow-md shadow-red-600/10 transition-all duration-200 hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
        >
          {isPending ? "Verifying..." : "Verify & Proceed"}
        </button>

        {/* Resend */}
        <div className="mt-5 text-center">
          <span className="text-[12px] text-neutral-400">
            Didn't receive the code?{" "}
          </span>

          <button
            type="button"
            className="cursor-pointer border-none bg-transparent text-[12px] font-semibold text-red-600 hover:underline"
          >
            Resend
          </button>
        </div>
      </div>
    </form>
  );
}

export default CheckOtpForm;