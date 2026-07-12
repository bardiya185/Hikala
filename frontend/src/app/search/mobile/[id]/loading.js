// inside your folder -> loading.js
import React from "react";

function MobileSkeleton() {
  return (
    <div className="animate-pulse">
      <div className="w-[300px] h-full border border-solid border-neutral-200 rounded-md p-4">
        <div className="w-[200px] h-[200px] bg-neutral-300 rounded-md mx-auto mt-10"></div>
        <div className="mt-9 space-y-3">
          <div className="h-4 bg-neutral-300 rounded w-3/4"></div>
          <div className="h-3 bg-neutral-200 rounded w-full"></div>
        </div>
        <div className="flex justify-between mt-6">
          <div className="h-4 bg-neutral-200 rounded w-1/3"></div>
          <div className="h-4 bg-neutral-200 rounded w-1/4"></div>
        </div>
        <div className="h-5 bg-neutral-300 rounded w-1/4 mt-4"></div>
      </div>
    </div>
  );
}

export default function Loading() {
  return (
    <div className="max-w-[1270px] grid grid-cols-4 gap-2 gap-y-3 p-4">
      {Array.from({ length: 8 }).map((_, index) => (
        <MobileSkeleton key={index} />
      ))}
    </div>
  );
}
