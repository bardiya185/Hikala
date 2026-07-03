import React from "react";
import { FaPlus } from "react-icons/fa";
import { AiFillEdit } from "react-icons/ai";

function PersoanlInformation() {
  return (
    <div className="w-[852px] h-[404px] border border-solid border-neutral-400 rounded-lg relative">
      <div className="flex  items-center mx-6 pb-5 w-auto  border-b border-neutral-400">
        <div className="w-1/2 pl-4">
          <div className="flex justify-between items-center pt-5">
            <span className="pr-4">نام و نام خانوادگی</span>
            <button>
              <FaPlus />
            </button>
          </div>
        </div>

        <div className="w-1/2 px-4 ">
          <div className="flex justify-between items-center pt-5">
            <span>کد ملی / گذرنامه / گواهی اقامت</span>
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
            <p className="pr-9">شماره موبایل</p>
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
                <p>ایمیل</p>
                <button>
                    <FaPlus/>
                </button>
            </div>

            </div>

        </div>
      
    </div>
  );
}

export default PersoanlInformation;
