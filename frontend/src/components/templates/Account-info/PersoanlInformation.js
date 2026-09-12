"use client";

import React from "react";
import { FaPlus } from "react-icons/fa";
import { AiFillEdit } from "react-icons/ai";

function PersoanlInformation() {
  return (
    <section className="w-full min-w-0">
      <div
        className="
          w-full
          overflow-hidden
          rounded-xl
          border
          border-neutral-200
          bg-white
          shadow-sm
        "
      >
        {/* =====================================================
            HEADER
        ====================================================== */}
        <div
          className="
            flex
            items-center
            justify-between
            border-b
            border-neutral-200
            px-4
            py-4

            sm:px-5
            sm:py-5

            md:px-6
          "
        >
          <div>
            <h2
              className="
                text-base
                font-bold
                text-neutral-900

                sm:text-lg
              "
            >
              Personal Information
            </h2>

            <p
              className="
                mt-1
                text-xs
                text-neutral-500

                sm:text-sm
              "
            >
              Manage your personal account information
            </p>
          </div>
        </div>

        {/* =====================================================
            INFORMATION GRID
        ====================================================== */}
        <div
          className="
            grid
            grid-cols-1
            divide-y
            divide-neutral-200

            md:grid-cols-2
            md:divide-x
            md:divide-y-0
          "
        >
          {/* ===================================================
              FULL NAME
          ==================================================== */}
          <InformationItem
            label="Full Name"
            action="add"
          />

          {/* ===================================================
              NATIONAL ID / PASSPORT
          ==================================================== */}
          <InformationItem
            label="National ID / Passport / Residence Certificate"
            action="add"
          />

          {/* ===================================================
              MOBILE NUMBER
          ==================================================== */}
          <InformationItem
            label="Mobile Number"
            value="09123456789"
            verified
            action="edit"
          />

          {/* ===================================================
              EMAIL
          ==================================================== */}
          <InformationItem
            label="Email"
            action="add"
          />

          {/* ===================================================
              PASSWORD
          ==================================================== */}
          <InformationItem
            label="Password"
            action="add"
          />

          {/* ===================================================
              REFUND METHOD
          ==================================================== */}
          <InformationItem
            label="Refund Method"
            action="add"
          />

          {/* ===================================================
              DATE OF BIRTH
          ==================================================== */}
          <InformationItem
            label="Date of Birth"
            value="1405/04/13"
            action="edit"
          />

          {/* ===================================================
              OCCUPATION
          ==================================================== */}
          <InformationItem
            label="Occupation"
            action="add"
          />

          {/* ===================================================
              ECONOMIC CODE
          ==================================================== */}
          <InformationItem
            label="Economic Code"
            action="add"
          />

          {/* ===================================================
              DISABILITY TYPE
          ==================================================== */}
          <InformationItem
            label="Disability Type"
            value="Not specified"
            action="add"
          />
        </div>
      </div>
    </section>
  );
}

/* =============================================================
   INFORMATION ITEM
============================================================= */

function InformationItem({
  label,
  value,
  action = "add",
  verified = false,
}) {
  const isEdit = action === "edit";

  return (
    <div
      className="
        flex
        min-w-0
        items-start
        justify-between
        gap-4
        px-4
        py-4

        sm:px-5
        sm:py-5

        md:min-h-[108px]
        md:px-6
        md:py-5

        lg:px-7
      "
    >
      {/* =====================================================
          CONTENT
      ====================================================== */}
      <div className="min-w-0 flex-1">
        <div
          className="
            flex
            min-w-0
            flex-wrap
            items-center
            gap-2
          "
        >
          <p
            className="
              min-w-0
              text-sm
              font-medium
              leading-6
              text-neutral-500

              sm:text-[15px]
            "
          >
            {label}
          </p>

          {verified && (
            <span
              className="
                inline-flex
                shrink-0
                items-center
                rounded-full
                bg-green-50
                px-2
                py-1
                text-[10px]
                font-semibold
                text-green-600
                ring-1
                ring-inset
                ring-green-200

                sm:px-2.5
                sm:text-xs
              "
            >
              Verified
            </span>
          )}
        </div>

        {/* VALUE */}
        {value && (
          <p
            className="
              mt-2
              break-words
              text-sm
              font-medium
              text-neutral-800

              sm:text-base
            "
          >
            {value}
          </p>
        )}
      </div>

      {/* =====================================================
          ACTION
      ====================================================== */}
      <button
        type="button"
        aria-label={`${isEdit ? "Edit" : "Add"} ${label}`}
        className="
          group
          flex
          h-9
          w-9
          shrink-0
          items-center
          justify-center
          rounded-full
          text-neutral-500
          transition-all
          duration-200

          hover:bg-neutral-100
          hover:text-neutral-800

          active:scale-95

          focus:outline-none
          focus:ring-2
          focus:ring-neutral-300
          focus:ring-offset-2

          sm:h-10
          sm:w-10
        "
      >
        {isEdit ? (
          <AiFillEdit
            className="
              h-5
              w-5
              transition-transform
              duration-200
              group-hover:scale-110

              sm:h-[22px]
              sm:w-[22px]
            "
          />
        ) : (
          <FaPlus
            className="
              h-3.5
              w-3.5
              transition-transform
              duration-200
              group-hover:scale-110

              sm:h-4
              sm:w-4
            "
          />
        )}
      </button>
    </div>
  );
}

export default PersoanlInformation;
