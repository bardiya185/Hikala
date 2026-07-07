import React from "react";
import { FaPlus } from "react-icons/fa";
import { AiFillEdit } from "react-icons/ai";

function PersoanlInformation() {
  return (
    <div className="w-[852px] mt-4 h-[404px] border border-solid border-neutral-400 rounded-lg relative">
      <div className="flex  items-center mx-6 pb-5 w-auto  border-b border-neutral-400">
        <div className="w-1/2 pl-4">
          <div className="flex justify-between items-center pt-5">
            <span className="pr-4 text-neutral-500 font-iranyekan font-semibold">نام و نام خانوادگی</span>
            <button>
              <FaPlus />
            </button>
          </div>
        </div>

        <div className="w-1/2 px-4 ">
          <div className="flex justify-between items-center pt-5 ">
            <span className="text-neutral-500">کد ملی / گذرنامه / گواهی اقامت</span>
            <button>
              <FaPlus />
            </button>
          </div>
        </div>

        <div className="absolute top-0 bottom-0 right-1/2 w-[1px] bg-neutral-400 -translate-x-1/2"></div>
      </div>
      <div className="flex items-center">
        <div className="w-1/2 pl-4">
          <div className="flex gap-2  items-center pt-5">
            <p className="pr-9 text-neutral-500">شماره موبایل</p>
            <span className="bg-green-500 rounded-md text-white">
              تایید شده
            </span>
          </div>
        </div>
      </div>
      <div className="flex items-center border-b border-neutral-400 w-auto pb-7">
        <div className="w-1/2 pl-4">
          <div className="flex justify-between ">
            <span className="mt-[15px] pr-9">09123456789</span>
            <button>
              <AiFillEdit className="w-[25px] h-[25px] text-neutral-500" />
            </button>
          </div>
        </div>
        <div className="w-1/2 pl-4">
          <div className="flex justify-between items-center px-6">
            <p className="text-neutral-500">ایمیل</p>
            <button>
              <FaPlus />
            </button>
          </div>
        </div>
      </div>

      <div className="flex items-center border-b border-neutral-400 w-auto pb-5 mx-1">
        <div className="w-1/2 pl-4">
          <div className="flex justify-between pr-9 pt-2 items-center ">
            <span className="text-neutral-500">رمز عبور</span>
            <button>
              <FaPlus className="" />
            </button>
          </div>
        </div>

        <div className="w-1/2 pl-4">
          <div className="flex justify-between items-center px-6 pt-1 pl-5">
            <p className="text-neutral-500">روش بازگرداندن پول من</p>
            <button>
              <FaPlus />
            </button>
          </div>
        </div>
      </div>
      <div className="flex items-center border-b border-neutral-400 w-auto pb-5 mx-6">
        <div className="w-1/2 ">
          <div className="flex justify-between pr-5 pl-3 ">
            <p className="text-neutral-500">تاریخ تولد</p>
            <button>
              <AiFillEdit className="w-[25px] h-[25px] text-neutral-500 mt-5" />
            </button>
          </div>
          <span className="pr-5">1405/04/13</span>
        </div>
        <div className="w-1/2">
          <div className="flex justify-between items-center pr-6 pl-4">
            <p className="text-neutral-500">شغل</p>
            <button>
              <FaPlus />
            </button>
          </div>
        </div>
      </div>
      <div className="flex items-center   mx-6">
        <div className="w-1/2">
        <div className="flex justify-between pr-5 pl-3 pt-5 items-center">
          <p className="text-neutral-500">کد اقتصادی</p>
          <button>
            <FaPlus className=""/>
          </button>

        </div>

        </div>
        <div className="w-1/2">
        <div className="flex items-center justify-between pr-5 pt-3 pl-4 ">
          <span>نوع معلولیت</span>
          <button>
            <FaPlus/>
          </button>

        </div>
        <span className="pr-6 text-neutral-500">تعریف نشده</span>

        </div>
      </div>
    </div>
  );
}

export default PersoanlInformation;
