import React from "react";
import { IoChevronBack } from "react-icons/io5";
import { RiErrorWarningFill } from "react-icons/ri";

function ProfileAllert() {
  return (
    <div
      className="
        w-full
        min-w-0
        rounded-xl
        border
        border-neutral-200
        bg-white
        px-3
        py-3

        sm:px-4
        sm:py-3

        md:px-5

        lg:rounded-lg
      "
    >
      <div
        className="
          flex
          w-full
          flex-col
          gap-3

          sm:flex-row
          sm:items-center
          sm:justify-between
          sm:gap-4
        "
      >
        {/* Alert message */}
        <div
          className="
            flex
            min-w-0
            flex-1
            items-start
            gap-2
          "
        >
          <RiErrorWarningFill
            className="
              mt-0.5
              h-[18px]
              w-[18px]
              shrink-0
              text-yellow-600

              sm:mt-0
            "
          />

          <span
            className="
              min-w-0
              text-xs
              font-medium
              leading-5
              text-yellow-600

              sm:text-[13px]
              sm:leading-5

              md:text-sm
            "
          >
            Verify your identity to increase your account security and
            access the "Buy Now, Pay Later" feature.
          </span>
        </div>

        {/* Verify identity button */}
        <button
          type="button"
          className="
            inline-flex
            w-fit
            shrink-0
            items-center
            gap-0.5
            self-end
            whitespace-nowrap
            text-xs
            font-semibold
            text-blue-600
            transition-colors
            hover:text-blue-700

            focus:outline-none
            focus:ring-2
            focus:ring-blue-200
            focus:ring-offset-2

            sm:self-auto
            sm:text-sm
          "
        >
          <span>Verify Identity</span>

          <IoChevronBack className="h-4 w-4" />
        </button>
      </div>
    </div>
  );
}

export default ProfileAllert;