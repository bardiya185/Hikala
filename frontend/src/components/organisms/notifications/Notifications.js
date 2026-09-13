"use client";

import Image from "next/image";
import Link from "next/link";
import { ArrowLeft, Bell } from "lucide-react";

export default function Notifications() {
  return (
    <section className="w-full px-4 py-8 sm:px-6 lg:px-8 dark:bg-neutral-950">
      <div className="mx-auto w-full max-w-5xl">
        {/* Main Card */}
        <div
          className="
            rounded-2xl border border-neutral-200
            bg-white p-5 shadow-sm
            sm:p-7 lg:p-8
            dark:border-neutral-800
            dark:bg-neutral-900
            dark:shadow-black/20
          "
        >
          {/* Header */}
          <div className="border-b border-neutral-200 pb-5 dark:border-neutral-800">
            <div className="flex items-center gap-3">
              {/* Back Button */}
              <Link
                href="/"
                aria-label="Go back to home"
                className="
                  flex h-10 w-10 shrink-0 items-center justify-center
                  rounded-full border border-neutral-200
                  text-neutral-700
                  transition-all duration-200
                  hover:bg-neutral-100 hover:text-neutral-950
                  active:scale-95
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-red-500
                  focus-visible:ring-offset-2
                  dark:border-neutral-700
                  dark:text-neutral-300
                  dark:hover:bg-neutral-800
                  dark:hover:text-white
                  dark:focus-visible:ring-offset-neutral-900
                "
              >
                <ArrowLeft size={20} strokeWidth={2} />
              </Link>

              {/* Title */}
              <div>
                <h1
                  className="
                    text-xl font-bold tracking-tight
                    text-neutral-900
                    sm:text-2xl
                    dark:text-neutral-100
                  "
                >
                  Messages
                </h1>

                <div className="mt-2 h-1 w-10 rounded-full bg-red-500 dark:bg-red-400" />
              </div>
            </div>
          </div>

          {/* All Messages Button */}
          <div className="mt-6">
            <button
              type="button"
              className="
                flex min-h-12 w-full items-center justify-center gap-2
                rounded-xl
                bg-neutral-950 px-5 py-3
                text-sm font-semibold text-white
                shadow-sm
                transition-all duration-200
                hover:bg-neutral-800
                active:scale-[0.99]
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                focus-visible:ring-offset-2
                sm:w-auto sm:min-w-[180px]
                dark:bg-white
                dark:text-neutral-950
                dark:hover:bg-neutral-200
                dark:focus-visible:ring-offset-neutral-900
              "
            >
              <Bell size={18} strokeWidth={2} />
              <span>All Messages</span>
            </button>
          </div>

          {/* Empty State */}
          <div className="flex flex-col items-center justify-center py-16 text-center sm:py-20">
            {/* Placeholder Image */}
            <div
              className="
                relative mb-7 h-48 w-48 overflow-hidden
                rounded-2xl
                bg-neutral-50
                sm:h-56 sm:w-56
                dark:bg-neutral-950
              "
            >
              <Image
                src="/icons/empty-notification.svg"
                alt="No messages"
                fill
                className="object-contain p-3"
                sizes="(max-width: 640px) 192px, 224px"
              />
            </div>

            {/* Main Message */}
            <h2
              className="
                text-xl font-bold
                text-purple-600
                sm:text-2xl
                dark:text-purple-400
              "
            >
              No news yet...
            </h2>

            {/* Description */}
            <p
              className="
                mt-3 max-w-md
                text-sm leading-7
                text-neutral-500
                sm:text-base
                dark:text-neutral-400
              "
            >
              Messages and news will be displayed here.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}