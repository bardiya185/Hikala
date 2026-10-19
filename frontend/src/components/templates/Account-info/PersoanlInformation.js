"use client";

import React, { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { FaPlus } from "react-icons/fa";
import { AiFillEdit } from "react-icons/ai";

import { useUpdateProfile } from "@/core/services/mutations";
import { useGetUserData } from "@/core/services/queries";
import { getCookie } from "@/core/utils/cookie";

/* ============================================================
   HELPERS
============================================================ */

/** Persian / Arabic digits -> Latin digits, spaces and dashes removed. */
const PERSIAN_DIGIT_RANGE = new RegExp(
  `[${String.fromCharCode(0x06f0)}-${String.fromCharCode(0x06f9)}]`,
  "g"
);

const ARABIC_DIGIT_RANGE = new RegExp(
  `[${String.fromCharCode(0x0660)}-${String.fromCharCode(0x0669)}]`,
  "g"
);

const toLatinDigits = (value) => {
  const withLatinNumbers = String(value ?? "")
    .replace(PERSIAN_DIGIT_RANGE, (d) => String(d.charCodeAt(0) - 0x06f0))
    .replace(ARABIC_DIGIT_RANGE, (d) => String(d.charCodeAt(0) - 0x0660));

  return withLatinNumbers.replace(/[^0-9]/g, "");
};

const VALIDATORS = {
  name: (value) => {
    const trimmed = String(value ?? "").trim();

    if (trimmed.length < 2) return "Name must be at least 2 characters";

    if (trimmed.length > 255) return "Name must be 255 characters or fewer";

    return "";
  },
  mobile: (value) => {
    if (value && /^09[0-9]{9}$/.test(toLatinDigits(value))) return "";

    return "Mobile number must be 11 digits and start with 09";
  },
};

/* ============================================================
   MAIN
============================================================ */

function PersoanlInformation() {
  const { data: userData, isLoading } = useGetUserData();

  const updateProfile = useUpdateProfile();

  const user = userData?.data?.data ?? null;

  const isLoggedIn = Boolean(getCookie("access_token"));

  /* Which field is currently in edit mode (only one at a time). */
  const [editingKey, setEditingKey] = useState(null);

  const pendingKey = useMemo(() => {
    if (!updateProfile.isPending) return null;

    return Object.keys(updateProfile.variables ?? {})[0] ?? null;
  }, [updateProfile.isPending, updateProfile.variables]);

  const startEditing = (key) => {
    if (pendingKey) return;

    setEditingKey(key);
  };

  const cancelEditing = () => setEditingKey(null);

  const handleSave = async (key, value) => {
    const payload =
      key === "mobile"
        ? { mobile: toLatinDigits(value) }
        : { [key]: String(value).trim() };

    try {
      await updateProfile.mutateAsync(payload);
      setEditingKey(null);
    } catch {
      /* The mutation already surfaces an error toast. */
    }
  };

  const rows = [
    {
      key: "name",
      label: "Full name",
      value: user?.name ?? "",
      editable: true,
      placeholder: "e.g. Sara Mohammadi",
    },
    {
      key: null,
      label: "National ID / Passport number / Residence permit",
      value: "",
      editable: false,
    },
    {
      key: "mobile",
      label: "Mobile number",
      value: user?.mobile ?? "",
      editable: true,
      verified: true,
      inputMode: "tel",
      placeholder: "09123456789",
      hint: "After you change it, your next sign-in will use this number.",
    },
    {
      key: null,
      label: "Email",
      /* The users table of this project has no email column yet, so the
         value is read-only until the backend supports it. */
      value: user?.email ?? "",
      editable: false,
      emptyLabel: "Not registered on your account",
    },
    {
      key: null,
      label: "Password",
      value: "",
      editable: false,
    },
    {
      key: null,
      label: "Repayment method",
      value: "",
      editable: false,
    },
    {
      key: null,
      label: "Date of birth",
      value: "",
      editable: false,
    },
    {
      key: null,
      label: "Occupation",
      value: "",
      editable: false,
    },
    {
      key: null,
      label: "Economic code",
      value: "",
      editable: false,
    },
    {
      key: null,
      label: "Disability type",
      value: "",
      editable: false,
    },
  ];

  return (
    <section className="w-full min-w-0">
      <div
        className="
          w-full overflow-hidden rounded-xl
          border border-neutral-200
          bg-white shadow-sm
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/20
        "
      >
        {/* HEADER */}
        <div
          className="
            flex items-center justify-between
            border-b border-neutral-200
            px-4 py-4
            sm:px-5 sm:py-5
            md:px-6
            dark:border-neutral-800
          "
        >
          <div>
            <h2
              className="
                text-base font-bold
                text-neutral-900
                sm:text-lg
                dark:text-neutral-100
              "
            >
              Personal information
            </h2>

            <p
              className="
                mt-1 text-xs
                text-neutral-500
                sm:text-sm
                dark:text-neutral-400
              "
            >
              Manage and edit your account details
            </p>
          </div>
        </div>

        {/* NOT LOGGED IN */}
        {isLoggedIn && !isLoading && !user ? (
          <div
            className="
              border-b border-neutral-200
              px-4 py-4
              sm:px-5
              md:px-6
              dark:border-neutral-800
            "
          >
            <p
              className="
                text-xs font-medium
                text-red-500
                sm:text-sm
              "
            >
              Your account details are not available. Please sign in again.
            </p>
          </div>
        ) : null}

        {!isLoggedIn ? (
          <div
            className="
              flex flex-wrap items-center gap-2
              border-b border-neutral-200
              px-4 py-4
              sm:px-5
              md:px-6
              dark:border-neutral-800
            "
          >
            <span
              className="
                text-xs font-medium
                text-neutral-500
                sm:text-sm
                dark:text-neutral-400
              "
            >
              Sign in to view and edit your personal information.
            </span>

            <Link
              href="/auth/login/sendOtp"
              className="
                inline-flex min-h-9 items-center
                rounded-xl
                bg-neutral-950 px-4
                text-xs font-semibold text-white
                transition-all duration-200
                hover:bg-neutral-800
                active:scale-[0.99]
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                focus-visible:ring-offset-2
                dark:bg-white
                dark:text-neutral-950
                dark:hover:bg-neutral-200
                dark:focus-visible:ring-offset-neutral-900
              "
            >
              Sign in or sign up
            </Link>
          </div>
        ) : null}

        {/* INFORMATION GRID */}
        <div
          className="
            grid grid-cols-1
            divide-y divide-neutral-200
            md:grid-cols-2
            md:divide-x md:divide-y-0
            dark:divide-neutral-800
          "
        >
          {rows.map((row) => (
            <InformationItem
              key={row.label}
              row={row}
              loading={isLoggedIn && isLoading}
              isEditing={Boolean(row.editable) && editingKey === row.key}
              saving={pendingKey !== null && pendingKey === row.key}
              blocked={pendingKey !== null && pendingKey !== row.key}
              onStartEdit={() => startEditing(row.key)}
              onCancel={cancelEditing}
              onSave={(value) => handleSave(row.key, value)}
            />
          ))}
        </div>
      </div>
    </section>
  );
}

/* ============================================================
   ITEM
============================================================ */

function InformationItem({
  row,
  loading = false,
  isEditing = false,
  saving = false,
  blocked = false,
  onStartEdit,
  onCancel,
  onSave,
}) {
  const [draft, setDraft] = useState(row.value ?? "");
  const [error, setError] = useState("");

  const canEdit = Boolean(row.editable);
  const hasValue = Boolean(row.value);
  const showEditor = isEditing && canEdit;

  /* Re-seed the input whenever the row enters edit mode or
     the stored value changes from the server. */
  useEffect(() => {
    if (isEditing) {
      setDraft(row.value ?? "");
      setError("");
    }
  }, [isEditing, row.value]);

  const handleSubmit = (event) => {
    event.preventDefault();

    const validator = VALIDATORS[row.key];
    const message = validator ? validator(draft) : "";

    setError(message);

    if (message) return;

    if (String(draft).trim() === String(row.value ?? "").trim()) {
      onCancel();
      return;
    }

    onSave(String(draft).trim());
  };

  return (
    <div
      className="
        flex min-w-0 items-start justify-between gap-4
        px-4 py-4
        sm:px-5 sm:py-5
        md:min-h-[108px] md:px-6 md:py-5
        lg:px-7
      "
    >
      {/* CONTENT */}
      <div className="min-w-0 flex-1">
        <div
          className="
            flex min-w-0 flex-wrap
            items-center gap-2
          "
        >
          <p
            className="
              min-w-0
              text-sm font-medium leading-6
              text-neutral-500
              sm:text-[15px]
              dark:text-neutral-400
            "
          >
            {row.label}
          </p>

          {row.verified && hasValue && (
            <span
              className="
                inline-flex shrink-0 items-center
                rounded-full
                bg-green-50
                px-2 py-1
                text-[10px] font-semibold
                text-green-600
                ring-1 ring-inset ring-green-200
                sm:px-2.5 sm:text-xs
                dark:bg-green-950/40
                dark:text-green-400
                dark:ring-green-900
              "
            >
              Verified
            </span>
          )}
        </div>

        {/* LOADING */}
        {loading ? (
          <div className="mt-3 h-4 w-2/5 animate-pulse rounded bg-neutral-200 dark:bg-neutral-800" />
        ) : null}

        {/* EDITOR */}
        {!loading && showEditor ? (
          <form onSubmit={handleSubmit} className="mt-3 w-full">
            <label className="sr-only" htmlFor={`field-${row.key}`}>
              {row.label}
            </label>

            <input
              id={`field-${row.key}`}
              name={`field-${row.key}`}
              type="text"
              inputMode={row.inputMode ?? "text"}
              value={draft}
              disabled={saving}
              placeholder={row.placeholder}
              onChange={(event) => {
                setDraft(event.target.value);

                if (error) setError("");
              }}
              className="
                w-full rounded-xl
                border border-neutral-200
                bg-white px-3 py-2
                text-sm font-medium
                text-neutral-800
                placeholder:text-neutral-400
                transition-colors duration-200
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                focus-visible:ring-offset-2
                disabled:opacity-60
                sm:text-[15px]
                dark:border-neutral-800
                dark:bg-neutral-950
                dark:text-neutral-100
                dark:placeholder:text-neutral-600
                dark:focus-visible:ring-offset-neutral-900
              "
            />

            {error ? (
              <p className="mt-2 text-xs font-medium text-red-500">{error}</p>
            ) : null}

            {!error && row.hint ? (
              <p
                className="
                  mt-2 text-[11px] leading-5
                  text-neutral-400
                  dark:text-neutral-500
                "
              >
                {row.hint}
              </p>
            ) : null}

            <div className="mt-3 flex flex-wrap items-center gap-2">
              <button
                type="submit"
                disabled={saving}
                className="
                  inline-flex min-h-9 items-center
                  rounded-xl
                  bg-neutral-950 px-4 py-1.5
                  text-xs font-semibold text-white
                  transition-all duration-200
                  hover:bg-neutral-800
                  active:scale-[0.99]
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-red-500
                  focus-visible:ring-offset-2
                  disabled:opacity-60
                  dark:bg-white
                  dark:text-neutral-950
                  dark:hover:bg-neutral-200
                  dark:focus-visible:ring-offset-neutral-900
                "
              >
                {saving ? "Saving…" : "Save"}
              </button>

              <button
                type="button"
                onClick={onCancel}
                disabled={saving}
                className="
                  inline-flex min-h-9 items-center
                  rounded-xl
                  border border-neutral-200
                  bg-white px-4 py-1.5
                  text-xs font-semibold
                  text-neutral-700
                  transition-all duration-200
                  hover:bg-neutral-100
                  active:scale-[0.99]
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-red-500
                  focus-visible:ring-offset-2
                  disabled:opacity-60
                  dark:border-neutral-800
                  dark:bg-neutral-900
                  dark:text-neutral-300
                  dark:hover:bg-neutral-800
                  dark:focus-visible:ring-offset-neutral-900
                "
              >
                Cancel
              </button>
            </div>
          </form>
        ) : null}

        {/* VALUE */}
        {!loading && !showEditor && (hasValue || row.emptyLabel) ? (
          <p
            className="
              mt-2 break-words
              text-sm font-medium
              text-neutral-800
              sm:text-base
              dark:text-neutral-100
            "
          >
            {hasValue
              ? row.key === "mobile"
                ? toLatinDigits(row.value)
                : row.value
              : null}

            {!hasValue && row.emptyLabel ? (
              <span
                className="
                  text-xs font-normal
                  text-neutral-400
                  sm:text-sm
                  dark:text-neutral-500
                "
              >
                {row.emptyLabel}
              </span>
            ) : null}
          </p>
        ) : null}
      </div>

      {/* ACTION */}
      <button
        type="button"
        aria-label={canEdit ? `Edit ${row.label}` : `Add ${row.label}`}
        disabled={!canEdit || blocked || loading || saving}
        onClick={() => {
          if (!canEdit) return;

          if (isEditing) {
            onCancel();
            return;
          }

          onStartEdit();
        }}
        className="
          group flex h-9 w-9 shrink-0
          items-center justify-center
          rounded-full
          text-neutral-500
          transition-all duration-200

          hover:bg-neutral-100
          hover:text-neutral-800
          active:scale-95

          focus:outline-none
          focus-visible:ring-2
          focus-visible:ring-red-500
          focus-visible:ring-offset-2

          disabled:cursor-not-allowed
          disabled:opacity-40
          disabled:hover:bg-transparent

          sm:h-10 sm:w-10

          dark:text-neutral-400
          dark:hover:bg-neutral-800
          dark:hover:text-white
          dark:focus-visible:ring-offset-neutral-900
          dark:disabled:hover:bg-transparent
        "
      >
        {canEdit ? (
          <AiFillEdit
            className="
              h-5 w-5
              transition-transform duration-200
              group-hover:scale-110
              sm:h-[22px] sm:w-[22px]
            "
          />
        ) : (
          <FaPlus
            className="
              h-3.5 w-3.5
              transition-transform duration-200
              group-hover:scale-110
              sm:h-4 sm:w-4
            "
          />
        )}
      </button>
    </div>
  );
}

export default PersoanlInformation;
