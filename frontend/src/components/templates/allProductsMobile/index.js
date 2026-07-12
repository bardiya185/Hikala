import Image from "next/image";
import React from "react";
import { SiLocalsend } from "react-icons/si";
import { MdStarRate } from "react-icons/md";

function AllMobile({ data }) {
  console.log(data);
  return (
    <div className="max-w-[1270px] grid grid-cols-4  gap-2 gap-y-3  ">
      {data?.data?.map((ddd) => (
        <div key={ddd.id} className=" ">
          <div className="w-[300px] h-full border border-solid border-neutral-400 rounded-md ">
            <Image
              src="/icons/82101-samsung-galaxy-a57-6.jpg"
              width={380}
              height={200}
              alt="p"
              className="w-[200px] h-auto mx-auto mt-10"
            />
            <div className="pl-3 mt-9">
              <p>{ddd?.title}</p>
              <p className="mt-3 truncate">{ddd?.short_description}</p>
            </div>
            <div className="flex justify-between">
              <div className="flex gap-2 items-center px-3">
                <SiLocalsend className="w-[22px] h-[22px] text-blue-600 " />
                <p>ارسال سریع دیجی کالا</p>
              </div>
              <div className="flex items-center gap-2 pr-3">
                <MdStarRate className="w-[22px] h-[22px] text-yellow-400" />
                <p>3.5</p>
              </div>
            </div>
            <span className="mt-4 inline-block pl-3 ">1500 $</span>
          </div>
        </div>
      ))}
    </div>
  );
}

export default AllMobile;
