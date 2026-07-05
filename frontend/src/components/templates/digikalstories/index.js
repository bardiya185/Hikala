"use client";
import React, { useState } from 'react';

import StoryModal from './StoryModal'; 

const STORIES_DATA = [
  { id: 1, title: "پاوربانک انکر مدل A1...", img: "https://dkstatics-public.digikala.com/digikala-content-x-post-media/303037ddac33965839e218f528e65b2137d1e603_1771917471.jpg?x-oss-process=image/resize,m_fill,h_115,w_115" },
  { id: 2, title: "تکنولوژی، با این سشو...", img: "https://dkstatics-public.digikala.com/digikala-content-x-post-media/a56a8a3c0aad383ee1bbc4d46daec66d951ca685_1777498909.jpg?x-oss-process=image/resize,m_fill,h_115,w_115" },
  { id: 3, title: "سشوار نئوکلاسیک از ه...", img: "https://dkstatics-public.digikala.com/digikala-content-x-post-media/f701b205db394d095193abefc3f0fec9350ddc83_1777499636.jpg?x-oss-process=image/resize,m_fill,h_115,w_115" },
  { id: 4, title: "به این ۱۰ تا کالا چه نمره ای میدی", img: "https://dkstatics-public.digikala.com/digikala-content-x-post-media/207a310ada09acb730ce18936808ea7aa83fc7dd_1760349231.jpg?x-oss-process=image/resize,m_fill,h_115,w_115" },
  { id: 5, title: "کولر نسل جدید ایرومک...", img: "https://dkstatics-public.digikala.com/digikala-content-x-post-media/f5e34c84e337433d3bfb49935b21bfc1b0d34014_1777483786.jpg?x-oss-process=image/resize,m_fill,h_115,w_115" }
];

function Stories() {
  
  const [selectedStory, setSelectedStory] = useState(null);

  return (
    <div className="w-full max-w-[1200px] mx-auto py-4 px-5 select-none">
      <div className="flex items-center gap-6 overflow-x-auto pb-2 scrollbar-none justify-start">
        {STORIES_DATA.map((item) => (
          <div 
            key={item.id} 
          
            onClick={() => setSelectedStory(item)} 
            className="w-[84px] shrink-0 cursor-pointer flex flex-col items-center"
          >
            
            <div className="rounded-full aspect-square w-full p-[2.5px] bg-gradient-to-tr from-amber-500 via-red-500 to-purple-600 flex items-center justify-center transition-transform duration-300 hover:scale-105">
              <div className="rounded-full overflow-hidden w-full aspect-square relative flex items-center justify-center">
                <picture>
                  <source type="image/webp" srcSet={`${item.img}/format,webp`} />
                  <img 
                    className="w-full h-full object-cover rounded-full" 
                    src={item.img} 
                    alt={item.title} 
                    loading="lazy"
                  />
                </picture>
              </div>
            </div>

            <p className="mt-2 text-[11px] font-light text-center text-neutral-800 font-vazir line-clamp-1 w-full">
              {item.title}
            </p>

          </div>
        ))}
      </div>

      
      <StoryModal 
        isOpen={selectedStory !== null} 
        onClose={() => setSelectedStory(null)} 
        storyData={selectedStory}
      />
    </div>
  );
}

export default Stories;