"use client";

import Image from "next/image";
import Link from "next/link";
import { ArrowLeft, Bell } from "lucide-react";

export default function Notifications() {
  return (
    <section className="w-full px-4 py-8 sm:px-6 lg:px-8">
      <div className="mx-auto w-full max-w-5xl">
        {/* Main Card */}
        <div className="rounded-2xl border border-gray-300 bg-white p-5 shadow-sm sm:p-7 lg:p-8">
          {/* Header */}
          <div className="border-b border-gray-200 pb-5">
            <div className="flex items-center gap-3">
              {/* Back Button */}
              <Link
                href="/"
                aria-label="Go back to home"
                className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition-colors hover:bg-gray-100 hover:text-black focus:outline-none focus:ring-2 focus:ring-gray-400"
              >
                <ArrowLeft size={20} strokeWidth={2} />
              </Link>

              {/* Title */}
              <div>
                <h1 className="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                  Messages
                </h1>

                <div className="mt-2 h-1 w-10 rounded-full bg-red-500" />
              </div>
            </div>
          </div>

          {/* All Messages Button */}
          <div className="mt-6">
            <button
              type="button"
              className="flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-gray-800 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 sm:w-auto sm:min-w-[180px]"
            >
              <Bell size={18} strokeWidth={2} />
              <span>All Messages</span>
            </button>
          </div>

          {/* Empty State */}
          <div className="flex flex-col items-center justify-center py-16 text-center sm:py-20">
            {/* Placeholder Image */}
            <div className="relative mb-7 h-48 w-48 overflow-hidden rounded-2xl sm:h-56 sm:w-56">
              <Image
                src="/icons/empty-notification.svg"
                alt="Messages"
                fill
                className="object-cover"
                sizes="(max-width: 640px) 192px, 224px"
              />
            </div>

            {/* Main Message */}
            <h2 className="text-xl font-bold text-purple-600 sm:text-2xl">
              No news yet...
            </h2>

            {/* Description */}
            <p className="mt-3 max-w-md text-sm leading-7 text-gray-500 sm:text-base">
              Messages and news will be displayed here.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}