"use client";
import { useCheckOtp } from "@/core/services/mutations";
import Image from "next/image";
import { useRouter } from "next/navigation";
import React, { useState } from "react";
import toast from "react-hot-toast";
import OtpInput from "react18-input-otp";

function CheckOtpForm({ mobile,setStep }) {
  const [code, setCode] = useState();
  const router = useRouter()

  const {isPending,mutate} = useCheckOtp()

  const handleChange = (otp) => setCode(otp);
  const submitHandler = (event) => {
    event.preventDefault();
    mutate({mobile,code},{
      onSuccess:(data)=>{
        setStep(0)
        router.push("/")
        toast.success(data?.data?.message)
        console.log(data)

      }
    })
    
  };

  return (
    <form onSubmit={submitHandler}>
      <div className="w-[360px] h-[360px] border border-solid border-gray-400 rounded-xl">
        <div>
          <Image
            src="/icons/logo.svg"
            width={162}
            height={25}
            alt="logo"
            className="pt-[35px] mr-[69px]"
          />
        </div>
        <div className="px-[20px]">
          <h6 className="mt-[48px]">کد تایید را وارد کنید</h6>
          <p className="text-gray-500 text-[12px] mt-[20px]">
            حساب کاربری با شماره موبایل {mobile} وجود ندارد. برای ساخت حساب
            جدید، کد تایید برای این شماره ارسال گردید.
          </p>
          <div
            style={{
              direction: "ltr",
              display: "flex",
              justifyContent: "center",
              marginTop: "18px",
            }}
          >
            <div className=" ltr flex justify-cente">
              <OtpInput
                value={code}
                onChange={handleChange}
                numInputs={6}
                className="border border-solid border-silver rounded-[5px] lg:w-[35px] lg:h-[40px] lg:m-[12px] w-[50px] h-[45px] m-[4px] rounded-[6px] justify-center mt-[21px]  "
              />
            </div>
          </div>
            <button type="submit" className="w-[320px] h-[48px] bg-red-600 rounded-lg mt-[15px] text-white">
              تایید
            </button>
        </div>
      </div>
    </form>
  );
}

export default CheckOtpForm;
