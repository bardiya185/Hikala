"use client";

import { useEffect, useMemo, useState } from "react";

import {
  CiSearch,
  CiLocationOn,
} from "react-icons/ci";

import { IoClose } from "react-icons/io5";

import {
  useGetCities,
  useGetProvinces,
} from "@/core/services/queries";

import { useCreateAddress } from "@/core/services/mutations";

import NeshanMap from "./NeshanMap";

function LocationModal({ isOpen, onClose, onSuccess }) {
  const [provinceSearch, setProvinceSearch] = useState("");
  const [citySearch, setCitySearch] = useState("");

  const [selectedProvince, setSelectedProvince] = useState(null);
  const [selectedCity, setSelectedCity] = useState(null);

  const [location, setLocation] = useState({
    latitude: null,
    longitude: null,
  });

  const [formData, setFormData] = useState({
    title: "Home",
    receiver_name: "",
    receiver_mobile: "",
    address: "",
    postal_code: "",
    unit: "",
    plaque: "",
    description: "",
    is_default: true,
  });

  const {
    data: provinces = [],
    isLoading: isProvincesLoading,
  } = useGetProvinces();

  const {
    data: cities = [],
    isLoading: isCitiesLoading,
  } = useGetCities(selectedProvince?.id);

  const createAddressMutation = useCreateAddress();


  

console.log(
  "NESHAN KEY:",
  process.env.NEXT_PUBLIC_NESHAN_API_KEY
);
  useEffect(() => {
    if (!isOpen) {
      setProvinceSearch("");
      setCitySearch("");
      setSelectedProvince(null);
      setSelectedCity(null);

      setLocation({
        latitude: null,
        longitude: null,
      });
    }
  }, [isOpen]);

  useEffect(() => {
    if (!isOpen) {
      return;
    }

    const handleEscape = (event) => {
      if (event.key === "Escape") {
        onClose();
      }
    };

    document.addEventListener("keydown", handleEscape);
    document.body.style.overflow = "hidden";

    return () => {
      document.removeEventListener("keydown", handleEscape);
      document.body.style.overflow = "";
    };
  }, [isOpen, onClose]);

  const filteredProvinces = useMemo(() => {
    const search = provinceSearch.trim().toLowerCase();

    if (!search) {
      return provinces;
    }

    return provinces.filter((province) =>
      province.name?.toLowerCase().includes(search)
    );
  }, [provinces, provinceSearch]);

  const filteredCities = useMemo(() => {
    const search = citySearch.trim().toLowerCase();

    if (!search) {
      return cities;
    }

    return cities.filter((city) =>
      city.name?.toLowerCase().includes(search)
    );
  }, [cities, citySearch]);

  const handleProvinceSelect = (province) => {
    setSelectedProvince(province);
    setSelectedCity(null);
    setCitySearch("");
  };

  const handleCitySelect = (city) => {
    setSelectedCity(city);
  };

  const handleLocationSelect = ({ latitude, longitude }) => {
    setLocation({
      latitude,
      longitude,
    });
  };

  const handleInputChange = (event) => {
    const { name, value } = event.target;

    setFormData((previous) => ({
      ...previous,
      [name]: value,
    }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (!selectedProvince) {
      return;
    }

    if (!selectedCity) {
      return;
    }

    if (!location.latitude || !location.longitude) {
      return;
    }

    if (!formData.receiver_name.trim()) {
      return;
    }

    if (!formData.receiver_mobile.trim()) {
      return;
    }

    if (!formData.address.trim()) {
      return;
    }

    if (!formData.postal_code.trim()) {
      return;
    }

    const payload = {
      title: formData.title.trim() || "Home",

      receiver_name: formData.receiver_name.trim(),

      receiver_mobile: formData.receiver_mobile.trim(),

      province_id: selectedProvince.id,

      city_id: selectedCity.id,

      address: formData.address.trim(),

      postal_code: formData.postal_code.trim(),

      latitude: String(location.latitude),

      longitude: String(location.longitude),

      unit: formData.unit.trim(),

      plaque: formData.plaque.trim(),

      description: formData.description.trim(),

      is_default: formData.is_default,
    };

    try {
      await createAddressMutation.mutateAsync(payload);

      onSuccess?.({
        province: selectedProvince,
        city: selectedCity,
        latitude: location.latitude,
        longitude: location.longitude,
      });

      onClose();
    } catch (error) {
      console.error(
        "Create address failed:",
        error?.response?.data || error
      );
    }
  };

  if (!isOpen) {
    return null;
  }

  return (
    <div
      className="
        fixed
        inset-0
        z-[200]
        flex
        items-center
        justify-center
        bg-black/50
        p-3
        sm:p-5
      "
      onMouseDown={(event) => {
        if (event.target === event.currentTarget) {
          onClose();
        }
      }}
    >
      <div
        role="dialog"
        aria-modal="true"
        aria-labelledby="location-modal-title"
        className="
          relative
          flex
          max-h-[94vh]
          w-full
          max-w-[850px]
          flex-col
          overflow-hidden
          rounded-2xl
          bg-white
          shadow-2xl
        "
      >
        {/* HEADER */}

        <div className="shrink-0 px-5 pb-4 pt-5 sm:px-7 sm:pt-6">
          <div className="flex items-start justify-between gap-4">
            <div>
              <h2
                id="location-modal-title"
                className="
                  text-left
                  text-lg
                  font-bold
                  text-neutral-900
                  sm:text-xl
                "
              >
                Select Location
              </h2>

              <p
                className="
                  mt-1.5
                  max-w-[650px]
                  text-left
                  text-xs
                  leading-5
                  text-neutral-500
                  sm:text-sm
                "
              >
                To ensure your order is delivered on time,
                please select your location accurately.
              </p>
            </div>

            <button
              type="button"
              onClick={onClose}
              aria-label="Close location modal"
              className="
                flex
                h-9
                w-9
                shrink-0
                items-center
                justify-center
                rounded-full
                text-neutral-500
                transition
                hover:bg-neutral-100
                hover:text-neutral-900
              "
            >
              <IoClose className="h-5 w-5" />
            </button>
          </div>

          <div className="mt-4 h-px bg-neutral-200" />
        </div>

        {/* CONTENT */}

        <div className="flex-1 overflow-y-auto px-5 pb-5 sm:px-7">
          {/* SEARCH */}

          <div className="relative z-20">
            <div
              className="
                flex
                h-11
                items-center
                rounded-lg
                border
                border-neutral-200
                bg-white
                px-3
                shadow-sm
                focus-within:border-orange-400
                focus-within:ring-2
                focus-within:ring-orange-100
              "
            >
              <CiSearch
                className="
                  h-5
                  w-5
                  shrink-0
                  text-neutral-400
                "
              />

              <input
                value={provinceSearch}
                onChange={(event) => {
                  setProvinceSearch(event.target.value);
                  setSelectedProvince(null);
                  setSelectedCity(null);
                }}
                placeholder="Select province or city"
                className="
                  h-full
                  w-full
                  bg-transparent
                  px-2.5
                  text-sm
                  text-neutral-800
                  outline-none
                  placeholder:text-neutral-400
                "
              />
            </div>

            {/* PROVINCES */}

            {provinceSearch.trim() &&
              !selectedProvince &&
              filteredProvinces.length > 0 && (
                <div
                  className="
                    absolute
                    left-0
                    right-0
                    top-[calc(100%+6px)]
                    max-h-52
                    overflow-y-auto
                    rounded-xl
                    border
                    border-neutral-100
                    bg-white
                    p-1
                    shadow-xl
                  "
                >
                  {filteredProvinces.map((province) => (
                    <button
                      key={province.id}
                      type="button"
                      onClick={() =>
                        handleProvinceSelect(province)
                      }
                      className="
                        flex
                        w-full
                        items-center
                        gap-2.5
                        rounded-lg
                        px-3
                        py-2.5
                        text-left
                        text-sm
                        text-neutral-700
                        transition
                        hover:bg-orange-50
                        hover:text-orange-600
                      "
                    >
                      <CiLocationOn className="h-5 w-5 shrink-0 text-orange-500" />

                      <span className="truncate">
                        {province.name}
                      </span>
                    </button>
                  ))}
                </div>
              )}
          </div>

          {/* SELECTED PROVINCE */}

          {selectedProvince && (
            <div className="mt-3">
              <div className="mb-2 flex items-center justify-between">
                <span className="text-xs font-medium text-neutral-500">
                  Province
                </span>

                <button
                  type="button"
                  onClick={() => {
                    setSelectedProvince(null);
                    setSelectedCity(null);
                    setProvinceSearch("");
                    setCitySearch("");
                  }}
                  className="text-xs font-medium text-orange-600 hover:underline"
                >
                  Change
                </button>
              </div>

              <div
                className="
                  flex
                  items-center
                  gap-2
                  rounded-lg
                  border
                  border-orange-100
                  bg-orange-50
                  px-3
                  py-2.5
                "
              >
                <CiLocationOn className="h-5 w-5 text-orange-500" />

                <span className="text-sm font-semibold text-neutral-800">
                  {selectedProvince.name}
                </span>
              </div>
            </div>
          )}

          {/* CITY SEARCH */}

          {selectedProvince && (
            <div className="relative z-10 mt-3">
              <div
                className="
                  flex
                  h-11
                  items-center
                  rounded-lg
                  border
                  border-neutral-200
                  bg-white
                  px-3
                  focus-within:border-orange-400
                  focus-within:ring-2
                  focus-within:ring-orange-100
                "
              >
                <CiSearch className="h-5 w-5 text-neutral-400" />

                <input
                  value={citySearch}
                  onChange={(event) =>
                    setCitySearch(event.target.value)
                  }
                  placeholder="Select city"
                  className="
                    h-full
                    w-full
                    bg-transparent
                    px-2.5
                    text-sm
                    outline-none
                    placeholder:text-neutral-400
                  "
                />
              </div>

              {(citySearch.trim() || cities.length <= 8) &&
                filteredCities.length > 0 && (
                  <div
                    className="
                      mt-1.5
                      max-h-44
                      overflow-y-auto
                      rounded-xl
                      border
                      border-neutral-100
                      bg-white
                      p-1
                      shadow-sm
                    "
                  >
                    {filteredCities.map((city) => (
                      <button
                        key={city.id}
                        type="button"
                        onClick={() => handleCitySelect(city)}
                        className={`
                          flex
                          w-full
                          items-center
                          gap-2.5
                          rounded-lg
                          px-3
                          py-2.5
                          text-left
                          text-sm
                          transition
                          ${
                            selectedCity?.id === city.id
                              ? "bg-orange-50 text-orange-600"
                              : "text-neutral-700 hover:bg-neutral-50"
                          }
                        `}
                      >
                        <CiLocationOn
                          className={`
                            h-5
                            w-5
                            shrink-0
                            ${
                              selectedCity?.id === city.id
                                ? "text-orange-500"
                                : "text-neutral-400"
                            }
                          `}
                        />

                        <span className="truncate">
                          {city.name}
                        </span>
                      </button>
                    ))}
                  </div>
                )}
            </div>
          )}

          {/* MAP */}

          <div
            className="
              mt-4
              h-[260px]
              overflow-hidden
              rounded-xl
              border
              border-neutral-200
              bg-neutral-100
              sm:h-[300px]
            "
          >
            <NeshanMap
              latitude={location.latitude}
              longitude={location.longitude}
              onLocationSelect={handleLocationSelect}
            />
          </div>

          <p className="mt-2 text-xs text-neutral-400">
            Click on the map to select your exact location.
          </p>

          {/* ADDRESS FORM */}

          {selectedCity && (
            <form
              onSubmit={handleSubmit}
              className="mt-5 space-y-4"
            >
              <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                  <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                    Address title
                  </label>

                  <input
                    name="title"
                    value={formData.title}
                    onChange={handleInputChange}
                    placeholder="Home"
                    className="
                      h-10
                      w-full
                      rounded-lg
                      border
                      border-neutral-200
                      px-3
                      text-sm
                      outline-none
                      focus:border-orange-400
                      focus:ring-2
                      focus:ring-orange-100
                    "
                  />
                </div>

                <div>
                  <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                    Receiver name *
                  </label>

                  <input
                    name="receiver_name"
                    value={formData.receiver_name}
                    onChange={handleInputChange}
                    placeholder="John Doe"
                    required
                    className="
                      h-10
                      w-full
                      rounded-lg
                      border
                      border-neutral-200
                      px-3
                      text-sm
                      outline-none
                      focus:border-orange-400
                      focus:ring-2
                      focus:ring-orange-100
                    "
                  />
                </div>

                <div>
                  <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                    Mobile number *
                  </label>

                  <input
                    name="receiver_mobile"
                    value={formData.receiver_mobile}
                    onChange={handleInputChange}
                    placeholder="09123456789"
                    type="tel"
                    required
                    className="
                      h-10
                      w-full
                      rounded-lg
                      border
                      border-neutral-200
                      px-3
                      text-sm
                      outline-none
                      focus:border-orange-400
                      focus:ring-2
                      focus:ring-orange-100
                    "
                  />
                </div>

                <div>
                  <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                    Postal code *
                  </label>

                  <input
                    name="postal_code"
                    value={formData.postal_code}
                    onChange={handleInputChange}
                    placeholder="1234567890"
                    required
                    className="
                      h-10
                      w-full
                      rounded-lg
                      border
                      border-neutral-200
                      px-3
                      text-sm
                      outline-none
                      focus:border-orange-400
                      focus:ring-2
                      focus:ring-orange-100
                    "
                  />
                </div>

                <div>
                  <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                    Plaque
                  </label>

                  <input
                    name="plaque"
                    value={formData.plaque}
                    onChange={handleInputChange}
                    placeholder="12"
                    className="
                      h-10
                      w-full
                      rounded-lg
                      border
                      border-neutral-200
                      px-3
                      text-sm
                      outline-none
                      focus:border-orange-400
                      focus:ring-2
                      focus:ring-orange-100
                    "
                  />
                </div>

                <div>
                  <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                    Unit
                  </label>

                  <input
                    name="unit"
                    value={formData.unit}
                    onChange={handleInputChange}
                    placeholder="3"
                    className="
                      h-10
                      w-full
                      rounded-lg
                      border
                      border-neutral-200
                      px-3
                      text-sm
                      outline-none
                      focus:border-orange-400
                      focus:ring-2
                      focus:ring-orange-100
                    "
                  />
                </div>
              </div>

              <div>
                <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                  Full address *
                </label>

                <textarea
                  name="address"
                  value={formData.address}
                  onChange={handleInputChange}
                  placeholder="Street name, building number..."
                  required
                  rows={3}
                  className="
                    w-full
                    resize-none
                    rounded-lg
                    border
                    border-neutral-200
                    px-3
                    py-2.5
                    text-sm
                    outline-none
                    focus:border-orange-400
                    focus:ring-2
                    focus:ring-orange-100
                  "
                />
              </div>

              <div>
                <label className="mb-1.5 block text-xs font-medium text-neutral-700">
                  Description
                </label>

                <textarea
                  name="description"
                  value={formData.description}
                  onChange={handleInputChange}
                  placeholder="Additional details..."
                  rows={2}
                  className="
                    w-full
                    resize-none
                    rounded-lg
                    border
                    border-neutral-200
                    px-3
                    py-2.5
                    text-sm
                    outline-none
                    focus:border-orange-400
                    focus:ring-2
                    focus:ring-orange-100
                  "
                />
              </div>

              <label className="flex cursor-pointer items-center gap-2">
                <input
                  type="checkbox"
                  checked={formData.is_default}
                  onChange={(event) =>
                    setFormData((previous) => ({
                      ...previous,
                      is_default: event.target.checked,
                    }))
                  }
                  className="h-4 w-4 accent-red-500"
                />

                <span className="text-sm text-neutral-600">
                  Set as default address
                </span>
              </label>

              {/* CONFIRM */}

              <button
                type="submit"
                disabled={
                  createAddressMutation.isPending ||
                  !selectedProvince ||
                  !selectedCity ||
                  !location.latitude ||
                  !location.longitude
                }
                className="
                  flex
                  h-11
                  w-full
                  items-center
                  justify-center
                  rounded-lg
                  bg-red-500
                  px-4
                  text-sm
                  font-semibold
                  text-white
                  transition
                  hover:bg-red-600
                  disabled:cursor-not-allowed
                  disabled:bg-neutral-300
                "
              >
                {createAddressMutation.isPending
                  ? "Saving..."
                  : "Confirm Location"}
              </button>

              {createAddressMutation.isError && (
                <p className="text-center text-xs text-red-500">
                  Unable to save your address. Please try again.
                </p>
              )}
            </form>
          )}
        </div>
      </div>
    </div>
  );
}

export default LocationModal;