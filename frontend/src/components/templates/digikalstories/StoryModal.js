"use client";
import React from 'react'


import { Dialog, DialogPanel } from "@headlessui/react";

export default function StoryModal({ isOpen, onClose, storyData }) {
  const mockDetails = {
    username: "Anker_iran",
    profileImg: "https://dkstatics-public.digikala.com/digikala-content-x-profile/68ee255cda58f15f60cd7068279cb5e3efff683a_1739952327.jpg?x-oss-process=image/resize,m_lfit,h_150,w_150/quality,q_80/format,webp",
    videoUrl: "https://www.w3schools.com/html/mov_bbb.mp4", // ویدیو تستی کوتاه
    likes: "۱.۳ هزار",
    comments: "۲۹",
    product: {
      title: "پاوربانک 100 وات انکر مدل A1336 ظرفیت 20000 میلی آمپر ساعت",
      img: "https://dkstatics-public.digikala.com/digikala-products/257cf635f0e598c97e96a8f0736bf8160a451111_1762159610.jpg?x-oss-process=image/resize,m_lfit,h_115,w_115/quality,q_60/format,webp"
    }
  };

  return (
    <Dialog open={isOpen} onClose={onClose} className="relative z-50">
      
      <div className="fixed inset-0 bg-black/90" aria-hidden="true" />

     
      <div className="fixed inset-0 flex items-center justify-center p-0 lg:p-4">
        <DialogPanel className="relative w-full max-w-[420px] h-full lg:h-[85vh] bg-neutral-950 rounded-none lg:rounded-2xl overflow-hidden flex flex-col justify-between shadow-2xl">
          
          
          <div className="absolute top-0 inset-x-0 z-20 p-4 bg-gradient-to-b from-black/70 to-transparent flex items-center justify-between">
            <div className="flex items-center gap-3">
              
              <button onClick={onClose} className="text-white hover:opacity-80 transition-opacity">
                <svg className="w-6 h-6 fill-current rotate-180" viewBox="0 0 24 24">
                  <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                </svg>
              </button>
            
              <img src={mockDetails.profileImg} alt={mockDetails.username} className="w-10 h-10 rounded-full border border-white/20 object-cover" />
              <span className="text-sm font-bold text-white font-vazir">{mockDetails.username}</span>
            </div>
          
            <button className="bg-white text-black px-4 py-1.5 rounded-lg text-xs font-medium font-vazir hover:bg-neutral-200 transition-colors">
              دنبال کن
            </button>
          </div>

          
          <div className="relative flex-1 w-full h-full bg-black flex items-center justify-center">
            
            <video
              key={storyData?.id}
              src={mockDetails.videoUrl}
              autoPlay
              loop
              muted
              playsInline
              className="w-full h-full object-cover"
            />

           
            <div className="absolute bottom-28 left-4 z-20 flex flex-col gap-5 text-white">
              <div className="flex flex-col items-center cursor-pointer group">
                <div className="p-2 bg-black/40 rounded-full group-hover:bg-black/60 transition-colors">
                  <svg className="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                </div>
                <span className="text-xs mt-1 font-vazir font-light">{mockDetails.likes}</span>
              </div>
              <div className="flex flex-col items-center cursor-pointer group">
                <div className="p-2 bg-black/40 rounded-full group-hover:bg-black/60 transition-colors">
                  <svg className="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
                  </svg>
                </div>
                <span className="text-xs mt-1 font-vazir font-light">{mockDetails.comments}</span>
              </div>
            </div>

            <div className="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10 text-white flex flex-col gap-2">
              
              <h3 className="text-sm font-bold font-vazir">
                {storyData?.title}
              </h3>
              
              
              <div className="w-full flex items-center gap-2 mt-1">
                <div className="flex-1 h-1 bg-white/30 rounded-full overflow-hidden">
                  <div className="h-full bg-white rounded-full w-1/3 animate-pulse" /> 
                </div>
                <span className="text-[10px] text-neutral-300 font-vazir">۰۰:۳۱</span>
              </div>
            </div>
          </div>

       
          <div className="w-full bg-neutral-900 p-3 border-t border-neutral-800 z-20">
            <div className="bg-neutral-950 p-2 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-neutral-800/50 transition-colors">
              <img src={mockDetails.product.img} alt={mockDetails.product.title} className="w-14 h-14 rounded-lg bg-white object-contain p-1 shrink-0" />
              <p className="text-xs text-neutral-200 font-vazir font-light line-clamp-2 leading-5">
                {mockDetails.product.title}
              </p>
            </div>
          </div>

        </DialogPanel>
      </div>
    </Dialog>
  );
}