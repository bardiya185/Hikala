"use client";
import { useCheckOtp } from "@/core/services/mutations";
import Image from "next/image";
import { useRouter } from "next/navigation";
import React, { useState } from "react";
import toast from "react-hot-toast";
import OtpInput from "react18-input-otp";

function CheckOtpForm({ mobile, setStep }) {
  const [code, setCode] = useState("");
  const router = useRouter()

  const { isPending, mutate } = useCheckOtp()

  const handleChange = (otp) => setCode(otp);
  const submitHandler = (event) => {
    event.preventDefault();
    if (code.length !== 6) {
      toast.error("لطفاً کد ۶ رقمی را کامل وارد کنید");
      return;
    }
    mutate({ mobile, code }, {
      onSuccess: (data) => {
        setStep(0)
        router.push("/")
        toast.success(data?.data?.message)
        console.log(data)
      }
    })
  };

  return (

    <form onSubmit={submitHandler} className="w-full flex items-center justify-center min-h-[450px]">


      <div className="w-full lg:max-w-[360px] p-6 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-neutral-100 flex flex-col transition-all duration-300">


        <div className="flex justify-center mb-6 mt-2">
          <Image
            src="/icons/en-logo.svg"
            width={150}
            height={24}
            alt="logo"
            className="object-contain"
          />
        </div>


        <div className="flex flex-col text-left">
          <h6 className="text-lg font-bold text-neutral-800 tracking-tight">
            Enter verification code
          </h6>
          <p className="mt-2 text[11px] sm:text-[12px] md:text-sm text-neutral-400 font-medium leading-relaxed">
            An account with <span className="text-neutral-700 font-semibold">{mobile}</span> does not exist.
            A 6-digit verification code has been sent to create a new account.
          </p>
        </div>


        <div className="flex justify-center mt-6" dir="ltr">
          <OtpInput
            value={code}
            onChange={handleChange}
            numInputs={6}
            className="w-[44px] h-[48px] mx-1 text-center text-lg font-semibold border border-neutral-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/10 rounded-xl outline-none bg-neutral-50/50 focus:bg-white transition-all duration-200"
          />
        </div>


        <button isPending={isPending}
          type="submit"
          className="w-full h-[50px] bg-red-600 hover:bg-red-700 active:scale-[0.99] text-white rounded-xl mt-6 font-semibold text-sm shadow-md shadow-red-600/10 hover:shadow-lg hover:shadow-red-600/20 transition-all duration-200 cursor-pointer flex items-center justify-center"
        >
          {isPending ? "Under review..." : "Verify & Proceed"}

        </button>


        <div className="text-center mt-5">
          <span className="text-[12px] text-neutral-400">
            Didn&apos;t receive the code?
          </span>
          <button type="button" className="text-[12px] text-red-600 font-semibold hover:underline bg-transparent border-none cursor-pointer">
            Resend
          </button>
        </div>

      </div>
    </form>
  );
}

export default CheckOtpForm;