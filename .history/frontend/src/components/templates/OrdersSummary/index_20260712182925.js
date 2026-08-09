"use client"
import React from "react";
import { IoChevronBack } from "react-icons/io5";
import Image from "next/image";
import { usePathname } from "next/navigation";
import Link from "next/link";


const ORDERS_STATUS_DATA = [
  {id:"in_progress",label:"جاری", href:"/profile/orders/?activeTab=in_progress",icon:"/icons/status-processing.svg"},
  {id:"sent",label:"تحویل شده", href:"/profile/orders/?activeTab=sent",icon:"/icons/status-delivered.svg"},
  {id:"returned",label:"مرجوع شده", href:"/profile/orders/?activeTab=returned",icon:"/icons/status-processing.svg"},
]


function OrderSummary() {
  
  return (
    <div className="w-[852px] h-[170px] border border-solid border-neutral-400 rounded-lg ">
      <div className="flex justify-between items-center px-6 pt-6">
        <div className="relative py-1">

        <h1>سفارش های من</h1>
        <div className="absolute -bottom-[10px] right-0 left-0 h-[2px] bg-red-500 rounded-t-sm" />
        </div>
        
        <button className="flex items-center ">
          مشاهده همه
          <IoChevronBack />
        </button>
      </div>

   

    <div className="flex flex-row justify-evenly items-center py-6 w-full gap-2 md:gap-6">
        {ORDERS_STATUS_DATA.map((status) => (
          <Link
            key={status.id}
            href={status.href}
            className="flex flex-col lg:flex-row items-center gap-2 lg:gap-4 p-2 rounded-xl hover:bg-neutral-50 transition-colors flex-1 justify-center group"
          >
           
            <div className="relative w-14 h-14 md:w-16 md:h-16 transition-transform duration-300 group-hover:scale-105">
              <img
                className="w-full h-full object-contain"
                src={status.icon}
                alt={status.label}
                loading="lazy"
              />
            </div>

           
            <div className="flex flex-col items-center lg:items-start justify-center">
              <span className="text-xs md:text-sm font-bold text-neutral-800">
                0 سفارش
              </span>
              <span className="text-[11px] text-neutral-400 font-light mt-0.5">
                {status.label}
              </span>
            </div>
          </Link>
        ))}
      </div>
    


    </div>
  );
}

export default OrderSummary;
