"use client";

import React, { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import {
  MapPin,
  Plus,
  X,
  Loader2,
  Tag,
  Phone,
  User,
  LogIn,
} from "lucide-react";
import toast from "react-hot-toast";

import {
  useGetAddresses,
  useGetProvinces,
  useGetCities,
} from "@/core/services/queries";
import { useCreateAddress } from "@/core/services/mutations";
import { getCookie } from "@/core/utils/cookie";

// ============================================================
// HELPERS
// ============================================================

const CARD_CLASS =
  "rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-black/20";

const INPUT_CLASS =
  "h-11 w-full rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-800 outline-none transition-colors placeholder:text-neutral-400 focus:border-red-400 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-0 dark:border-neutral-700 dark:bg-neutral-950/40 dark:text-neutral-100 dark:placeholder:text-neutral-500";

const LABEL_CLASS =
  "mb-1.5 block text-xs font-semibold text-neutral-600 dark:text-neutral-300";

const ADDRESS_TYPES = ["Home", "Office", "Other"];

/**
 * The addresses endpoint may return either `{ data: [...] }`,
 * a paginated `{ data: { data: [...] } }` or a raw array.
 */
function extractAddresses(payload) {
  if (!payload) return [];
  if (Array.isArray(payload)) return payload;
  if (Array.isArray(payload.data)) return payload.data;
  if (Array.isArray(payload?.data?.data)) return payload.data.data;
  if (Array.isArray(payload.results)) return payload.results;
  if (Array.isArray(payload?.data?.results)) return payload.data.results;
  return [];
}

const getText = (value) =>
  typeof value === "string" && value.trim() ? value.trim() : "";

const getReceiverName = (address) =>
  getText(address?.receiver_name) ||
  getText(address?.full_name) ||
  getText(address?.name) ||
  "-";

const getReceiverMobile = (address) =>
  getText(address?.receiver_mobile) ||
  getText(address?.phone) ||
  getText(address?.mobile);

const getProvinceName = (address) =>
  getText(address?.province?.name) ||
  getText(address?.province_name) ||
  getText(address?.province);

const getCityName = (address) =>
  getText(address?.city?.name) ||
  getText(address?.city_name) ||
  getText(address?.city);

const getPostalCode = (address) =>
  getText(address?.postal_code) || getText(address?.zipcode);

const getAddressText = (address) =>
  getText(address?.address) || getText(address?.full_address);

// ============================================================
// SKELETON
// ============================================================

function AddressesSkeleton() {
  return (
    <div className="w-full">
      <div className={`${CARD_CLASS} p-4 sm:p-6`}>
        <div className="flex items-center justify-between gap-3">
          <div className="h-7 w-40 animate-pulse rounded bg-neutral-100 dark:bg-neutral-800" />
          <div className="h-9 w-36 animate-pulse rounded-lg bg-neutral-100" />
        </div>

        <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

        <div className="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
          {[1, 2, 3, 4].map((item) => (
            <div
              key={item}
              className="h-[150px] animate-pulse rounded-xl bg-neutral-100 dark:bg-neutral-800"
            />
          ))}
        </div>
      </div>
    </div>
  );
}

// ============================================================
// ADDRESS CARD
// ============================================================

function AddressCard({ address, index }) {
  const province = getProvinceName(address);
  const city = getCityName(address);
  const mobile = getReceiverMobile(address);
  const postalCode = getPostalCode(address);

  const unit = getText(address?.unit);
  const plaque = getText(address?.plaque);

  return (
    <div
      className={`${CARD_CLASS} group relative flex min-w-0 flex-col p-4 transition-shadow hover:shadow-md sm:p-5`}
    >
      <div className="flex items-start justify-between gap-3">
        <div className="flex min-w-0 items-center gap-2">
          <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-950/30 dark:text-red-400">
            <MapPin aria-hidden="true" className="h-5 w-5" />
          </span>

          <h3 className="truncate text-sm font-bold text-neutral-800 dark:text-neutral-100">
            {getText(address?.title) || `Address ${index + 1}`}
          </h3>
        </div>

        {address?.is_default && (
          <span className="shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-600 dark:bg-blue-950/40 dark:text-blue-300">
            Default
          </span>
        )}
      </div>

      <p className="mt-3 text-sm leading-6 text-neutral-700 dark:text-neutral-200">
        {[province, city].filter(Boolean).join(", ")}
        {plaque ? `, No. ${plaque}` : ""}
        {unit ? `, Unit ${unit}` : ""}
      </p>

      <p className="mt-1.5 text-xs leading-5 text-neutral-500 dark:text-neutral-400">
        {getAddressText(address) || "No address recorded."}
      </p>

      <div className="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-neutral-100 pt-3 text-xs text-neutral-500 dark:border-neutral-800 dark:text-neutral-400">
        <span className="inline-flex items-center gap-1.5">
          <User aria-hidden="true" className="h-3.5 w-3.5" />
          {getReceiverName(address)}
        </span>

        {mobile && (
          <span dir="ltr" className="inline-flex items-center gap-1.5">
            <Phone aria-hidden="true" className="h-3.5 w-3.5" />
            {mobile}
          </span>
        )}

        {postalCode && (
          <span dir="ltr" className="inline-flex items-center gap-1.5">
            <Tag aria-hidden="true" className="h-3.5 w-3.5" />
            {postalCode}
          </span>
        )}
      </div>
    </div>
  );
}

// ============================================================
// ADDRESS FORM
// ============================================================

function AddressForm({ onCancel }) {
  const [values, setValues] = useState({
    title: "Home",
    receiver_name: "",
    receiver_mobile: "",
    postal_code: "",
    address: "",
    unit: "",
    plaque: "",
    description: "",
    is_default: true,
  });

  const [provinceId, setProvinceId] = useState("");
  const [cityId, setCityId] = useState("");
  const [errorText, setErrorText] = useState("");

  const {
    data: provinces = [],
    isLoading: isProvincesLoading,
    isError: isProvincesError,
  } = useGetProvinces();

  const {
    data: cities = [],
    isLoading: isCitiesLoading,
    isError: isCitiesError,
  } = useGetCities(provinceId);

  const { mutate: createAddress, isPending: isSaving } = useCreateAddress();

  const provinceList = Array.isArray(provinces) ? provinces : [];
  const cityList = Array.isArray(cities) ? cities : [];

  const handleChange = (event) => {
    const { name, value, type, checked } = event.target;

    setValues((previous) => ({
      ...previous,
      [name]: type === "checkbox" ? checked : value,
    }));

    if (errorText) setErrorText("");
  };

  const handleSubmit = (event) => {
    event.preventDefault();

    const requiredText = (value) => String(value ?? "").trim();

    if (!requiredText(values.receiver_name)) {
      setErrorText("Enter the recipient's name.");
      return;
    }

    if (!/^09\d{9}$/.test(requiredText(values.receiver_mobile))) {
      setErrorText("The recipient's mobile number must be 11 digits and start with 09.");
      return;
    }

    if (!provinceId) {
      setErrorText("Select a province.");
      return;
    }

    if (!cityId) {
      setErrorText("Select a city.");
      return;
    }

    if (!requiredText(values.postal_code)) {
      setErrorText("Enter your postal code.");
      return;
    }

    if (!requiredText(values.address)) {
      setErrorText("Enter the full address.");
      return;
    }

    const payload = {
      title: requiredText(values.title) || "Home",
      receiver_name: requiredText(values.receiver_name),
      receiver_mobile: requiredText(values.receiver_mobile),
      province_id: Number(provinceId),
      city_id: Number(cityId),
      address: requiredText(values.address),
      postal_code: requiredText(values.postal_code),
      unit: requiredText(values.unit),
      plaque: requiredText(values.plaque),
      description: requiredText(values.description),
      is_default: Boolean(values.is_default),
    };

    createAddress(payload, {
      onSuccess: () => {
        toast.success("Your address was saved successfully");
        onCancel();
      },
      onError: (error) => {
        const message =
          error?.response?.data?.message ||
          "Saving the address failed. Please try again.";

        setErrorText(message);
        toast.error(message);
      },
    });
  };

  return (
    <form
      onSubmit={handleSubmit}
      dir="ltr"
      className={`${CARD_CLASS} mt-4 p-4 sm:p-5`}
    >
      <div className="flex items-start justify-between gap-3">
        <div>
          <h2 className="text-sm font-bold text-neutral-800 dark:text-neutral-100">
            New address
          </h2>
          <p className="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
            Enter the recipient&apos;s details accurately so the parcel is delivered
            to the right address.
          </p>
        </div>

        <button
          type="button"
          onClick={onCancel}
          aria-label="Close address form"
          className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-800 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:hover:bg-neutral-800 dark:hover:text-neutral-100"
        >
          <X aria-hidden="true" className="h-5 w-5" />
        </button>
      </div>

      <div className="mt-4 flex flex-wrap gap-2">
        {ADDRESS_TYPES.map((type) => {
          const isActive = values.title === type;

          return (
            <button
              key={type}
              type="button"
              onClick={() =>
                setValues((previous) => ({ ...previous, title: type }))
              }
              className={`rounded-lg border px-3 py-1.5 text-xs font-bold transition-all active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 ${
                isActive
                  ? "border-red-500 bg-red-50 text-red-500 dark:bg-red-950/30 dark:text-red-400"
                  : "border-neutral-200 bg-white text-neutral-600 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
              }`}
            >
              {type}
            </button>
          );
        })}
      </div>

      <div className="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div>
          <label htmlFor="receiver_name" className={LABEL_CLASS}>
            Recipient name
          </label>
          <input
            id="receiver_name"
            name="receiver_name"
            value={values.receiver_name}
            onChange={handleChange}
            placeholder="Full name"
            className={INPUT_CLASS}
          />
        </div>

        <div>
          <label htmlFor="receiver_mobile" className={LABEL_CLASS}>
            Mobile number
          </label>
          <input
            id="receiver_mobile"
            name="receiver_mobile"
            value={values.receiver_mobile}
            onChange={handleChange}
            dir="ltr"
            type="tel"
            placeholder="09123456789"
            className={`${INPUT_CLASS} text-right`}
          />
        </div>

        <div>
          <label htmlFor="province" className={LABEL_CLASS}>
            Province
          </label>
          <select
            id="province"
            value={provinceId}
            disabled={isProvincesLoading || isProvincesError}
            onChange={(event) => {
              setProvinceId(event.target.value);
              setCityId("");
            }}
            className={`${INPUT_CLASS} disabled:cursor-not-allowed disabled:opacity-60`}
          >
            <option value="">
              {isProvincesLoading
                ? "Loading..."
                : isProvincesError
                  ? "No provinces available"
                  : "Select province"}
            </option>
            {provinceList.map((province) => (
              <option key={province?.id} value={province?.id}>
                {province?.name}
              </option>
            ))}
          </select>
        </div>

        <div>
          <label htmlFor="city" className={LABEL_CLASS}>
            City
          </label>
          <select
            id="city"
            value={cityId}
            disabled={!provinceId || isCitiesLoading || isCitiesError}
            onChange={(event) => setCityId(event.target.value)}
            className={`${INPUT_CLASS} disabled:cursor-not-allowed disabled:opacity-60`}
          >
            <option value="">
              {!provinceId
                ? "Select a province first"
                : isCitiesLoading
                  ? "Loading..."
                  : "Select city"}
            </option>
            {cityList.map((city) => (
              <option key={city?.id} value={city?.id}>
                {city?.name}
              </option>
            ))}
          </select>
        </div>

        <div>
          <label htmlFor="postal_code" className={LABEL_CLASS}>
            Postal code
          </label>
          <input
            id="postal_code"
            name="postal_code"
            value={values.postal_code}
            onChange={handleChange}
            dir="ltr"
            inputMode="numeric"
            placeholder="1234567890"
            className={`${INPUT_CLASS} text-right`}
          />
        </div>

        <div className="flex gap-3">
          <div className="flex-1">
            <label htmlFor="plaque" className={LABEL_CLASS}>
              Plate no.
            </label>
            <input
              id="plaque"
              name="plaque"
              value={values.plaque}
              onChange={handleChange}
              placeholder="12"
              className={INPUT_CLASS}
            />
          </div>

          <div className="flex-1">
            <label htmlFor="unit" className={LABEL_CLASS}>
              Unit
            </label>
            <input
              id="unit"
              name="unit"
              value={values.unit}
              onChange={handleChange}
              placeholder="3"
              className={INPUT_CLASS}
            />
          </div>
        </div>
      </div>

      <div className="mt-3">
        <label htmlFor="address" className={LABEL_CLASS}>
          Full address
        </label>
        <textarea
          id="address"
          name="address"
          value={values.address}
          onChange={handleChange}
          rows={3}
          placeholder="Street, lane, building..."
          className={`${INPUT_CLASS} h-auto resize-none py-2.5 leading-6`}
        />
      </div>

      <div className="mt-3">
        <label htmlFor="description" className={LABEL_CLASS}>
          Notes (optional)
        </label>
        <input
          id="description"
          name="description"
          value={values.description}
          onChange={handleChange}
          placeholder="e.g. ring the bell, third floor"
          className={INPUT_CLASS}
        />
      </div>

      <label className="mt-4 flex w-fit cursor-pointer items-center gap-2">
        <input
          type="checkbox"
          name="is_default"
          checked={values.is_default}
          onChange={handleChange}
          className="h-4 w-4 accent-red-500"
        />
        <span className="text-xs font-medium text-neutral-600 dark:text-neutral-300">
          Save this as my default address
        </span>
      </label>

      {errorText && (
        <p className="mt-3 rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-600 dark:bg-red-950/30 dark:text-red-400">
          {errorText}
        </p>
      )}

      <div className="mt-5 flex flex-col gap-2 sm:flex-row">
        <button
          type="submit"
          disabled={isSaving}
          className="flex h-11 items-center justify-center gap-2 rounded-lg bg-red-500 px-5 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus-visible:ring-offset-neutral-900"
        >
          {isSaving ? (
            <>
              <Loader2 aria-hidden="true" className="h-4 w-4 animate-spin" />
              <span>Saving...</span>
            </>
          ) : (
            <span>Save address</span>
          )}
        </button>

        <button
          type="button"
          onClick={onCancel}
          disabled={isSaving}
          className="flex h-11 items-center justify-center rounded-lg border border-neutral-200 bg-white px-5 text-sm font-bold text-neutral-600 transition-colors hover:bg-neutral-50 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus-visible:ring-offset-neutral-900"
        >
          Cancel
        </button>
      </div>
    </form>
  );
}

// ============================================================
// MAIN PAGE
// ============================================================

export default function AddressesPage() {
  const [authState, setAuthState] = useState(null);
  const [isFormOpen, setIsFormOpen] = useState(false);

  const {
    data: addressesData,
    isLoading,
    isError,
    error,
    refetch,
  } = useGetAddresses();

  useEffect(() => {
    setAuthState(Boolean(getCookie("access_token")));
  }, []);

  const addresses = useMemo(
    () => extractAddresses(addressesData),
    [addressesData]
  );

  const isUnauthorized =
    authState === false || error?.response?.status === 401;

  // ---------- Auth gate ----------
  if (isUnauthorized) {
    return (
      <div dir="ltr" className="w-full">
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <h1 className="text-xl font-bold text-neutral-800 dark:text-neutral-100 sm:text-2xl">
            Addresses
          </h1>

          <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

          <div className="flex min-h-[280px] flex-col items-center justify-center px-2 text-center">
            <span className="flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100 text-neutral-400 dark:bg-neutral-800 dark:text-neutral-500">
              <MapPin aria-hidden="true" className="h-8 w-8" />
            </span>

            <h2 className="mt-4 text-base font-bold text-neutral-800 dark:text-neutral-100">
              Sign in to manage addresses
            </h2>

            <p className="mt-2 max-w-md text-sm leading-6 text-neutral-500 dark:text-neutral-400">
              Saved addresses are kept only in your account. Once you sign in you
              can add new addresses or edit existing ones.
            </p>

            <Link
              href="/auth/login/sendOtp"
              className="mt-5 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <LogIn aria-hidden="true" className="h-4 w-4" />
              <span>Sign in</span>
            </Link>
          </div>
        </div>
      </div>
    );
  }

  // ---------- Loading ----------
  if (isLoading || authState === null) {
    return (
      <div dir="ltr" className="w-full">
        <AddressesSkeleton />
      </div>
    );
  }

  // ---------- Error ----------
  if (isError) {
    return (
      <div dir="ltr" className="w-full">
        <div className={`${CARD_CLASS} p-4 sm:p-6`}>
          <h1 className="text-xl font-bold text-neutral-800 dark:text-neutral-100 sm:text-2xl">
            Addresses
          </h1>

          <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

          <div className="flex min-h-[240px] flex-col items-center justify-center text-center">
            <h2 className="text-base font-bold text-neutral-800 dark:text-neutral-100">
              Addresses could not be loaded
            </h2>
            <p className="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
              Please check your internet connection and try again.
            </p>
            <button
              type="button"
              onClick={() => refetch()}
              className="mt-4 h-11 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500"
            >
              Try again
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div dir="ltr" className="w-full">
      <div className={`${CARD_CLASS} p-4 sm:p-6`}>
        {/* Header */}
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="min-w-0">
            <h1 className="text-xl font-bold text-neutral-800 dark:text-neutral-100 sm:text-2xl">
              Addresses
            </h1>
            <p className="mt-1 text-xs text-neutral-500 dark:text-neutral-400 sm:text-sm">
              {addresses.length > 0
                ? `${addresses.length} saved addresses`
                : "No saved addresses yet"}
            </p>
          </div>

          <button
            type="button"
            dir="ltr"
            onClick={() => setIsFormOpen((previous) => !previous)}
            aria-expanded={isFormOpen}
            className="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-red-500 px-4 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
          >
            {isFormOpen ? (
              <>
                <X aria-hidden="true" className="h-4 w-4" />
                <span>Close form</span>
              </>
            ) : (
              <>
                <Plus aria-hidden="true" className="h-4 w-4" />
                <span>Add new address</span>
              </>
            )}
          </button>
        </div>

        <div className="mt-5 h-px w-full bg-neutral-200 dark:bg-neutral-800" />

        {/* Inline form */}
        {isFormOpen && <AddressForm onCancel={() => setIsFormOpen(false)} />}

        {/* Empty state */}
        {addresses.length === 0 && !isFormOpen && (
          <div className="flex min-h-[260px] flex-col items-center justify-center px-2 text-center">
            <span className="flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100 text-neutral-400 dark:bg-neutral-800 dark:text-neutral-500">
              <MapPin aria-hidden="true" className="h-8 w-8" />
            </span>

            <h2 className="mt-4 text-base font-bold text-neutral-800 dark:text-neutral-100">
              You have not saved an address yet
            </h2>

            <p className="mt-2 max-w-md text-sm leading-6 text-neutral-500 dark:text-neutral-400">
              Saving an address makes payment and checkout faster for your next
              purchases.
            </p>

            <button
              type="button"
              dir="ltr"
              onClick={() => setIsFormOpen(true)}
              className="mt-5 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
              <Plus aria-hidden="true" className="h-4 w-4" />
              <span>Add new address</span>
            </button>
          </div>
        )}

        {/* Address grid */}
        {addresses.length > 0 && (
          <div className="mt-5 grid grid-cols-1 gap-3 sm:gap-4 lg:grid-cols-2">
            {addresses.map((address, index) => (
              <AddressCard
                key={address?.id ?? index}
                address={address}
                index={index}
              />
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
