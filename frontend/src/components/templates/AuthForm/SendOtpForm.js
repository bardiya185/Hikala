"use client"
import { useSendOtp } from '@/core/services/mutations'
import { data } from 'autoprefixer'
import Image from 'next/image'
import React, { useState } from 'react'
import toast from 'react-hot-toast'

function SendOtpForm({setStep,mobile,setMobile}) {
  const [error,setError] = useState("")
  const {isPending,mutate} = useSendOtp()


  const submitHandler = async(event)=>{
    event.preventDefault()
    if(isPending) return
    mutate({mobile},{
      onSuccess:(data)=>{
        console.log(data)
        toast.success(data?.data?.message)
        
        setStep(2)
      }
    })

  }

  return (
    <form onSubmit={submitHandler} className="flex items-center justify-center min-h-[450px]">
  <div className="w-[380px] p-6 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-neutral-100 flex flex-col justify-between transition-all duration-300">
    
    {/* بخش لوگو - تراز دقیق در وسط */}
    <div className="flex justify-center mb-6 mt-2">
      <Image 
        src="/icons/en-logo.svg" 
        width={150} 
        height={24} 
        alt="logo" 
        className="object-contain"
      />
    </div>

    
    <div className="flex flex-col flex-1">
      <h6 className="text-lg font-bold text-neutral-800 tracking-tight text-left">
        Log in or sign up
      </h6>
      
      <p className="mt-1.5 text-[12px] text-neutral-400 text-left font-medium">
        Please enter your mobile number to proceed.
      </p>

      
      <div className="relative mt-6">
        <input 
          type="tel" 
          value={mobile} 
          onChange={(e) => setMobile(e.target.value)} 
          className="w-full h-[50px] px-4 border border-neutral-200 rounded-xl outline-none text-sm text-neutral-800 bg-neutral-50/50 focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-500/10 transition-all duration-200 placeholder:text-neutral-400" 
          placeholder="e.g. 09123456789" 
        />
      </div>

      <button 
        type="submit" 
        className="w-full h-[50px] bg-red-600 hover:bg-red-700 active:scale-[0.99] text-white rounded-xl mt-5 font-semibold text-sm shadow-md shadow-red-600/10 hover:shadow-lg hover:shadow-red-600/20 transition-all duration-200 cursor-pointer flex items-center justify-center"
      >
        Continue
      </button>

      <p className="text-[11px] text-neutral-400 text-center mt-6 leading-relaxed">
        By continuing, you agree to Digikala's <span className="text-neutral-700 underline cursor-pointer">Terms of Service</span>.
      </p>
    </div>
  </div>
</form>
  )
}

export default SendOtpForm