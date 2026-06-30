"use client"
import ModalContainer from "@/components/partials/container/ModalContainer";
import React, { useState } from "react";
import { TbLogin } from "react-icons/tb";
import SendOtpForm from "./SendOtpForm";
import CheckOtpForm from "./CheckOtpForm";

function AuthForm() {
  const [step, setStep] = useState(0);
  const [isOpen, setIsOpen] = useState(false);
  const [mobile, setMobile] = useState("");

  return (
    <>
   
      <button onClick={()=>setStep(1)} className=" lg:w-[134px] lg:h-[40px] gap-2 pr-[8px]  flex items-center  border border-solid border-neutral rounded-md w-fit h-[30px]">
        <TbLogin className="w-[24px] h-[24px]" />
        <span>ورود | ثبت نام</span>
      </button>
      {step === 1 &&(
        <ModalContainer isOpen={isOpen} setIsOpen={setIsOpen}  >
            <SendOtpForm setStep={setStep} mobile={mobile} setMobile={setMobile}  />
        </ModalContainer>
      )}

      {step === 2 &&(
        <ModalContainer isOpen={isOpen} setIsOpen={setIsOpen} >
          <CheckOtpForm mobile={mobile} setStep={setStep} setIsOpen={setIsOpen} />
          </ModalContainer>)
  }
</> 
)}



export default AuthForm
