import React from 'react'
import { IoChevronBack } from 'react-icons/io5';
import { RiErrorWarningFill } from "react-icons/ri";

function ProfileAllert() {
  return (
    <div className='w-[852px] h-[51px] border border-solid border-neutral-400 rounded-lg'>
        <div className='flex items-center justify-between px-4 pt-3'>
            <div className='flex items-center  gap-2 '>
                <RiErrorWarningFill className='text-yellow-600' width={18} height={18} />
                <span className='text-yellow-600 text-[13px]'>با تایید هویت می‌توانید‌ امنیت حساب کاربری‌تان را افزایش دهید و از امکان «خرید اعتباری» نیز استفاده کنید</span>
            </div>
            <button className='flex items-center text-blue-600'>تایید هویت
                <IoChevronBack className='mt-[5px]' />
            </button>

        </div>
    </div>
  )
}

export default ProfileAllert