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
    <form onSubmit={submitHandler}>

    <div className='w-[360px] h-[360px] border border-solid border-gray-400 rounded-xl'>
        <div >
          <Image src="/icons/logo.svg" width={162} height={25} alt='logo' className='pt-[35px] mr-[69px]' />
        </div>
        <div className='px-[20px]'>
          <h6 className='mt-[48px]'>ورود یا ثبت نام در دیجی کالا</h6>
          <p className='mt-[20px] text-[10px] text-gray-500'>لطفا شماره موبایل خود را وارد کنید</p>
          <input type='text' value={mobile} onChange={(e)=>setMobile(e.target.value)} className= ' mt-[20px] w-[320px] h-[48px] border border-solid border-gray-300 rounded-md ' placeholder='شماره موبایل...' />
          <button type='submit ' className='w-[320px] h-[48px] bg-red-600 rounded-lg mt-[15px] text-white' >
            ورود به دیجی کالا
          </button>
        </div>
    </div>
    </form>
  )
}

export default SendOtpForm