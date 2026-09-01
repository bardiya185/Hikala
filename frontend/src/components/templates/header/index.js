"use client";

import { useRef, useState, useCallback, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";

import { CiMobile1, CiSearch, CiLocationOn } from "react-icons/ci";
import {
  TbDeviceLaptop,
  TbFridge,
  TbShirt,
  TbChevronRight,
  TbMapPin,
} from "react-icons/tb";
import { GiGoldBar, GiCarKey } from "react-icons/gi";
import { MdShoppingCartCheckout } from "react-icons/md";
import { RxHamburgerMenu } from "react-icons/rx";
import { IoClose } from "react-icons/io5";

import AuthForm from "../AuthForm";

import {
  useCart,
  useGetMainCategories,
  useGetSubCategory,
  useGetUserData,
  useGetProvinces,
  useGetCities,
} from "@/core/services/queries";

import { useCreateAddress } from "@/core/services/mutations";

import SearchBar from "@/components/atom/SearchBar";
import MiniCart from "../miniCart";
import MobileBottomNav from "./MobileBottomNav";

import maplibregl from "@neshan-maps-platform/maplibre-sdk";
import "@neshan-maps-platform/maplibre-sdk/style.css";

/* =========================================================
   CATEGORY ICONS
========================================================= */

const iconMap = {
  mobile: CiMobile1,
  laptops: TbDeviceLaptop,
  digital: TbDeviceLaptop,
  "home-kitchen": TbFridge,
  fashion: TbShirt,
  "gold-jewelry": GiGoldBar,
  vehicles: GiCarKey,
};

function CategoryIcon({ iconKey, className }) {
  const IconComponent = iconMap[iconKey];

  if (!IconComponent) {
    return null;
  }

  return <IconComponent className={className} />;
}

/* =========================================================
   LOCATION MODAL
========================================================= */

function LocationModal({ onClose, user, onLocationSaved }) {
  const mapContainerRef = useRef(null);
  const mapRef = useRef(null);
  const markerRef = useRef(null);

  const [search, setSearch] = useState("");
  const [selectedProvince, setSelectedProvince] = useState(null);
  const [selectedCity, setSelectedCity] = useState(null);

  const [latitude, setLatitude] = useState(null);
  const [longitude, setLongitude] = useState(null);

  const [isMapReady, setIsMapReady] = useState(false);
  const [isSaving, setIsSaving] = useState(false);

  const {
    data: provincesResponse,
    isLoading: provincesLoading,
  } = useGetProvinces();

  const {
    data: citiesResponse,
    isLoading: citiesLoading,
  } = useGetCities(selectedProvince?.id);

  const createAddressMutation = useCreateAddress();

  /* =========================================================
     DATA
  ========================================================= */

  const provinces =
    provincesResponse?.data?.data ||
    provincesResponse?.data ||
    [];

  const cities =
    citiesResponse?.data?.data ||
    citiesResponse?.data ||
    [];

  /* =========================================================
     FILTER PROVINCES
  ========================================================= */

  const filteredProvinces = Array.isArray(provinces)
    ? provinces.filter((province) =>
        province?.name
          ?.toLowerCase()
          .includes(search.toLowerCase())
      )
    : [];

  /* =========================================================
     FILTER CITIES
  ========================================================= */

  const filteredCities = Array.isArray(cities)
    ? cities.filter((city) =>
        city?.name
          ?.toLowerCase()
          .includes(search.toLowerCase())
      )
    : [];

  /* =========================================================
     ADD / MOVE MARKER
  ========================================================= */

  const setMapMarker = useCallback((lng, lat) => {
    const map = mapRef.current;

    if (!map) {
      return;
    }

    setLongitude(lng);
    setLatitude(lat);

    if (markerRef.current) {
      markerRef.current.remove();
    }

    markerRef.current = new maplibregl.Marker({
      color: "#ef4444",
    })
      .setLngLat([lng, lat])
      .addTo(map);

    map.flyTo({
      center: [lng, lat],
      zoom: Math.max(map.getZoom(), 13),
      essential: true,
    });
  }, []);

  /* =========================================================
     MAP INITIALIZATION
  ========================================================= */

  useEffect(() => {
    if (!mapContainerRef.current || mapRef.current) {
      return;
    }

    const apiKey = process.env.NEXT_PUBLIC_NESHAN_API_KEY;

    if (!apiKey) {
      console.error(
        "Neshan API key is not defined. Please set NEXT_PUBLIC_NESHAN_API_KEY."
      );
      return;
    }

    const map = new maplibregl.Map({
      container: mapContainerRef.current,
      style:
        "https://static.neshan.org/sdk/maplibre/styles/light.json",
      center: [51.389, 35.6892],
      zoom: 11,
      minZoom: 3,
      maxZoom: 21,
      trackResize: true,
      apiKey,
    });

    mapRef.current = map;

    map.addControl(
      new maplibregl.NavigationControl(),
      "top-right"
    );

    map.on("error", (event) => {
      console.error("Neshan map error:", event);
    });

    map.on("load", () => {
      console.log("Neshan map loaded successfully");
      setIsMapReady(true);
    });

    map.on("click", (event) => {
      const { lng, lat } = event.lngLat;

      setMapMarker(lng, lat);
    });

    return () => {
      if (markerRef.current) {
        markerRef.current.remove();
        markerRef.current = null;
      }

      if (mapRef.current) {
        mapRef.current.remove();
        mapRef.current = null;
      }

      setIsMapReady(false);
    };
  }, [setMapMarker]);

  /* =========================================================
     SELECT PROVINCE
  ========================================================= */

  const handleProvinceSelect = (province) => {
    setSelectedProvince(province);
    setSelectedCity(null);
    setSearch("");

    /*
      مختصات قبلی پاک می‌شود تا کاربر بعد از
      انتخاب استان، نقطه دقیق جدید را انتخاب کند.
    */
    setLatitude(null);
    setLongitude(null);

    if (markerRef.current) {
      markerRef.current.remove();
      markerRef.current = null;
    }
  };

  /* =========================================================
     SELECT CITY
  ========================================================= */

  const handleCitySelect = (city) => {
    setSelectedCity(city);
    setSearch(city?.name || "");

    /*
      اگر API شهر مختصات داشته باشد، از آن استفاده می‌کنیم.
      در غیر این صورت کاربر روی نقشه انتخاب می‌کند.
    */

    const cityLat =
      city?.latitude ??
      city?.lat ??
      city?.location?.latitude;

    const cityLng =
      city?.longitude ??
      city?.lng ??
      city?.location?.longitude;

    if (
      cityLat !== undefined &&
      cityLat !== null &&
      cityLng !== undefined &&
      cityLng !== null
    ) {
      setMapMarker(
        Number(cityLng),
        Number(cityLat)
      );
    }
  };

  /* =========================================================
     CONFIRM LOCATION
  ========================================================= */

  const handleConfirmLocation = async () => {
    if (!selectedProvince) {
      alert("Please select a province.");
      return;
    }

    if (!selectedCity) {
      alert("Please select a city.");
      return;
    }

    if (latitude === null || longitude === null) {
      alert("Please select your exact location on the map.");
      return;
    }

    const userData =
      user?.data?.data ||
      user?.data ||
      user ||
      {};

    const receiverName =
      userData?.name ||
      userData?.full_name ||
      "";

    const receiverMobile =
      userData?.mobile ||
      userData?.phone ||
      userData?.phone_number ||
      "";

    const addressData = {
      title: "Default Address",

      receiver_name: receiverName,

      receiver_mobile: receiverMobile,

      province_id: selectedProvince.id,

      city_id: selectedCity.id,

      address: `${selectedCity.name}, ${selectedProvince.name}`,

      postal_code: "",

      latitude: String(latitude),

      longitude: String(longitude),

      unit: "",

      plaque: "",

      description: "",

      is_default: true,
    };

    try {
      setIsSaving(true);

      await createAddressMutation.mutateAsync(
        addressData
      );

      onLocationSaved?.({
        province: selectedProvince,
        city: selectedCity,
        latitude,
        longitude,
      });

      onClose();
    } catch (error) {
      console.error(
        "Create address failed:",
        error?.response?.data || error
      );

      alert(
        error?.response?.data?.message ||
          "Failed to save your address."
      );
    } finally {
      setIsSaving(false);
    }
  };

  /* =========================================================
     SEARCH RESULTS
  ========================================================= */

  const showProvinceResults =
    !selectedProvince &&
    search.trim().length > 0;

  const showCityResults =
    selectedProvince &&
    search.trim().length > 0;

  /* =========================================================
     RENDER
  ========================================================= */

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
        p-4
      "
      onMouseDown={(event) => {
        if (event.target === event.currentTarget) {
          onClose();
        }
      }}
    >
      <div
        className="
          relative
          flex
          w-full
          max-w-[850px]
          max-h-[90vh]
          flex-col
          overflow-hidden
          rounded-2xl
          bg-white
          shadow-2xl
        "
      >
        {/* =================================================
            HEADER
        ================================================= */}

        <div className="px-5 pt-5 sm:px-6 sm:pt-6">
          <div
            className="
              flex
              items-start
              justify-between
              gap-4
            "
          >
            <div className="min-w-0">
              <h2
                className="
                  text-left
                  text-lg
                  font-bold
                  text-black
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
                  leading-6
                  text-gray-500
                  sm:text-sm
                "
              >
                For timely delivery of your order,
                please select your exact location.
              </p>
            </div>

            <button
              type="button"
              onClick={onClose}
              className="
                flex
                h-9
                w-9
                shrink-0
                items-center
                justify-center
                rounded-full
                text-gray-500
                transition
                hover:bg-gray-100
                hover:text-black
              "
              aria-label="Close"
            >
              <IoClose className="h-5 w-5" />
            </button>
          </div>

          <div
            className="
              mt-5
              h-px
              w-full
              bg-gray-200
            "
          />
        </div>

        {/* =================================================
            BODY
        ================================================= */}

        <div
          className="
            flex
            min-h-0
            flex-1
            flex-col
            px-5
            pb-5
            sm:px-6
            sm:pb-6
          "
        >
          {/* =================================================
              SEARCH
          ================================================= */}

          <div className="relative mt-5">
            <CiSearch
              className="
                absolute
                left-3
                top-1/2
                z-10
                h-5
                w-5
                -translate-y-1/2
                text-gray-400
              "
            />

            <input
              type="text"
              value={search}
              onChange={(event) => {
                setSearch(event.target.value);
              }}
              placeholder="Select a province or city"
              className="
                h-11
                w-full
                rounded-lg
                border
                border-gray-200
                bg-white
                pl-10
                pr-4
                text-sm
                text-gray-800
                outline-none
                transition
                placeholder:text-gray-400
                focus:border-orange-400
                focus:ring-2
                focus:ring-orange-100
              "
            />

            {/* =================================================
                PROVINCE RESULTS
            ================================================= */}

            {showProvinceResults && (
              <div
                className="
                  absolute
                  left-0
                  right-0
                  top-[calc(100%+6px)]
                  z-[20]
                  max-h-[220px]
                  overflow-y-auto
                  rounded-lg
                  border
                  border-gray-200
                  bg-white
                  shadow-lg
                "
              >
                {provincesLoading ? (
                  <div
                    className="
                      px-4
                      py-4
                      text-sm
                      text-gray-500
                    "
                  >
                    Loading provinces...
                  </div>
                ) : filteredProvinces.length > 0 ? (
                  filteredProvinces.map((province) => (
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
                        gap-3
                        px-4
                        py-3
                        text-left
                        text-sm
                        text-gray-700
                        transition
                        hover:bg-gray-50
                      "
                    >
                      <TbMapPin
                        className="
                          h-5
                          w-5
                          shrink-0
                          text-orange-500
                        "
                      />

                      <span>{province.name}</span>
                    </button>
                  ))
                ) : (
                  <div
                    className="
                      px-4
                      py-4
                      text-sm
                      text-gray-500
                    "
                  >
                    No province found.
                  </div>
                )}
              </div>
            )}

            {/* =================================================
                CITY RESULTS
            ================================================= */}

            {showCityResults && (
              <div
                className="
                  absolute
                  left-0
                  right-0
                  top-[calc(100%+6px)]
                  z-[20]
                  max-h-[220px]
                  overflow-y-auto
                  rounded-lg
                  border
                  border-gray-200
                  bg-white
                  shadow-lg
                "
              >
                {citiesLoading ? (
                  <div
                    className="
                      px-4
                      py-4
                      text-sm
                      text-gray-500
                    "
                  >
                    Loading cities...
                  </div>
                ) : filteredCities.length > 0 ? (
                  filteredCities.map((city) => (
                    <button
                      key={city.id}
                      type="button"
                      onClick={() =>
                        handleCitySelect(city)
                      }
                      className="
                        flex
                        w-full
                        items-center
                        gap-3
                        px-4
                        py-3
                        text-left
                        text-sm
                        text-gray-700
                        transition
                        hover:bg-gray-50
                      "
                    >
                      <TbMapPin
                        className="
                          h-5
                          w-5
                          shrink-0
                          text-orange-500
                        "
                      />

                      <span>{city.name}</span>
                    </button>
                  ))
                ) : (
                  <div
                    className="
                      px-4
                      py-4
                      text-sm
                      text-gray-500
                    "
                  >
                    No city found.
                  </div>
                )}
              </div>
            )}
          </div>

          {/* =================================================
              SELECTED LOCATION
          ================================================= */}

          {selectedProvince && (
            <div
              className="
                mt-3
                flex
                flex-wrap
                items-center
                gap-2
                text-xs
              "
            >
              <span
                className="
                  rounded-md
                  bg-orange-50
                  px-2.5
                  py-1
                  text-orange-600
                "
              >
                {selectedProvince.name}
              </span>

              {selectedCity && (
                <>
                  <TbChevronRight
                    className="
                      h-3.5
                      w-3.5
                      text-gray-400
                    "
                  />

                  <span
                    className="
                      rounded-md
                      bg-gray-100
                      px-2.5
                      py-1
                      text-gray-700
                    "
                  >
                    {selectedCity.name}
                  </span>
                </>
              )}
            </div>
          )}

          {/* =================================================
              MAP
          ================================================= */}

          <div
            className="
              relative
              mt-4
              h-[300px]
              w-full
              overflow-hidden
              rounded-xl
              border
              border-gray-200
              bg-gray-100
              sm:h-[360px]
            "
          >
            <div
              ref={mapContainerRef}
              className="
                absolute
                inset-0
              "
            />

            {!isMapReady && (
              <div
                className="
                  absolute
                  inset-0
                  flex
                  items-center
                  justify-center
                  bg-gray-100/80
                "
              >
                <div
                  className="
                    flex
                    flex-col
                    items-center
                    gap-2
                    text-sm
                    text-gray-500
                  "
                >
                  <div
                    className="
                      h-6
                      w-6
                      animate-spin
                      rounded-full
                      border-2
                      border-orange-500
                      border-t-transparent
                    "
                  />

                  <span>Loading map...</span>
                </div>
              </div>
            )}

            <div
              className="
                absolute
                left-1/2
                top-3
                z-10
                -translate-x-1/2
                rounded-lg
                bg-white/95
                px-3
                py-2
                text-center
                text-[11px]
                text-gray-600
                shadow-md
                backdrop-blur
              "
            >
              Click on the map to select your exact location
            </div>
          </div>

          {/* =================================================
              COORDINATES
          ================================================= */}

          {latitude !== null && longitude !== null && (
            <div
              className="
                mt-3
                flex
                items-center
                justify-center
                gap-3
                text-[11px]
                text-gray-500
              "
            >
              <span>
                Lat: {Number(latitude).toFixed(6)}
              </span>

              <span>
                Lng: {Number(longitude).toFixed(6)}
              </span>
            </div>
          )}

          {/* =================================================
              CONFIRM BUTTON
          ================================================= */}

          <button
            type="button"
            onClick={handleConfirmLocation}
            disabled={
              isSaving ||
              !selectedProvince ||
              !selectedCity ||
              latitude === null ||
              longitude === null
            }
            className="
              mt-4
              h-11
              w-full
              rounded-lg
              bg-red-500
              px-4
              text-sm
              font-semibold
              text-white
              transition
              hover:bg-red-600
              disabled:cursor-not-allowed
              disabled:bg-gray-300
            "
          >
            {isSaving ? "Saving..." : "Confirm Location"}
          </button>
        </div>
      </div>
    </div>
  );
}

/* =========================================================
   HEADER
========================================================= */

function Header() {
  const [isOpen, setIsOpen] = useState(false);
  const [activeId, setActiveId] = useState(null);

  const [isOpenMiniCart, setIsOpenMiniCart] =
    useState(false);

  const [isLocationModalOpen, setIsLocationModalOpen] =
    useState(false);

  const [selectedLocation, setSelectedLocation] =
    useState(null);

  const closeTimer = useRef(null);

  const { data: cart } = useCart();
  const { data: user } = useGetUserData();

  const totalCount = cart?.data?.items_count || 0;

  const { data: categoriess } =
    useGetMainCategories();

  const mainDataArray =
    categoriess?.data?.data || [];

  const {
    data: categoryMenu,
    isLoading: isSubLoading,
  } = useGetSubCategory(activeId);

  const subDataArray =
    categoryMenu?.data?.data || [];

  /* =========================================================
     DEFAULT CATEGORY
  ========================================================= */

  useEffect(() => {
    if (
      mainDataArray.length > 0 &&
      !activeId
    ) {
      const targetCategory =
        mainDataArray.find(
          (category) =>
            category.slug === "mobile"
        );

      setActiveId(
        targetCategory?.id ||
          mainDataArray[0]?.id
      );
    }
  }, [mainDataArray, activeId]);

  /* =========================================================
     MENU HELPERS
  ========================================================= */

  const clearCloseTimer = useCallback(() => {
    if (closeTimer.current) {
      clearTimeout(closeTimer.current);
      closeTimer.current = null;
    }
  }, []);

  const scheduleClose = useCallback(() => {
    clearCloseTimer();

    closeTimer.current = setTimeout(() => {
      setIsOpen(false);
    }, 150);
  }, [clearCloseTimer]);

  const openMenu = useCallback(() => {
    clearCloseTimer();
    setIsOpen(true);
  }, [clearCloseTimer]);

  /* =========================================================
     CLEANUP MENU TIMER
  ========================================================= */

  useEffect(() => {
    return () => {
      if (closeTimer.current) {
        clearTimeout(closeTimer.current);
      }
    };
  }, []);

  /* =========================================================
     ACTIVE CATEGORY
  ========================================================= */

  const activeCategory =
    mainDataArray?.find(
      (category) => category.id === activeId
    ) ||
    mainDataArray[0];

  /* =========================================================
     TARGET CATEGORY
  ========================================================= */

  const getTargetCategory = (category) => {
    if (!category) {
      return null;
    }

    if (
      category.children &&
      category.children.length > 0
    ) {
      return category.children[0];
    }

    return category;
  };

  const targetCategory =
    getTargetCategory(activeCategory);

  /* =========================================================
     SUB CATEGORY LIST
  ========================================================= */

  const finalSubList = Array.isArray(subDataArray)
    ? subDataArray
    : subDataArray?.children ||
      subDataArray?.subs ||
      subDataArray?.subcategories ||
      [];

  /* =========================================================
     LOCATION SAVED
  ========================================================= */

  const handleLocationSaved = (location) => {
    setSelectedLocation(location);
  };

  /* =========================================================
     RENDER
  ========================================================= */

  return (
    <>
      <header
        dir="ltr"
        className="
          w-full
          bg-white
          font-sans
          select-none
        "
      >
        {/* ==================================================
            TOP BANNER
        ================================================== */}

        <div className="w-full overflow-hidden">
          <Image
            src="/icons/1.png"
            width={1270}
            height={60}
            alt="banner"
            priority
            className="
              block
              h-[45px]
              w-full
              object-cover
              sm:h-[50px]
              md:h-[60px]
            "
          />
        </div>

        {/* ==================================================
            MAIN HEADER
        ================================================== */}

        <div
          className="
            mt-3
            w-full
            px-3
            sm:mt-4
            sm:px-4
            lg:px-6
          "
        >
          <div
            className="
              flex
              w-full
              items-center
              justify-between
              gap-2
              sm:gap-3
              lg:gap-6
            "
          >
            {/* ==================================================
                LOGO + SEARCH
            ================================================== */}

            <div
              className="
                flex
                min-w-0
                flex-1
                items-center
                gap-2
                sm:gap-4
                lg:gap-6
              "
            >
              <Link
                href="/"
                className="
                  hidden
                  shrink-0
                  items-center
                  lg:flex
                "
              >
                <Image
                  src="/icons/en-logo.svg"
                  width={195}
                  height={30}
                  alt="logo"
                  priority
                  className="
                    h-auto
                    w-[195px]
                  "
                />
              </Link>

              <div className="min-w-0 flex-1">
                <SearchBar />
              </div>
            </div>

            {/* ==================================================
                LOGIN + CART
            ================================================== */}

            <div
              className="
                hidden
                shrink-0
                items-center
                gap-1
                sm:gap-2
                lg:flex
              "
            >
              <AuthForm />

              <div
                onMouseEnter={() =>
                  setIsOpenMiniCart(true)
                }
                onMouseLeave={() =>
                  setIsOpenMiniCart(false)
                }
                className="
                  relative
                  hidden
                  shrink-0
                  items-center
                  lg:flex
                "
              >
                <Link
                  href="/checkout/cart"
                  className="relative block"
                >
                  <div
                    className="
                      rounded-full
                      p-1.5
                      transition-colors
                      hover:bg-neutral-100
                      sm:p-2
                    "
                  >
                    <MdShoppingCartCheckout
                      className="
                        h-[21px]
                        w-[21px]
                        text-neutral-700
                        sm:h-[24px]
                        sm:w-[24px]
                      "
                    />
                  </div>

                  <span
                    className="
                      absolute
                      -right-1
                      -top-1
                      flex
                      h-4
                      w-4
                      items-center
                      justify-center
                      rounded-full
                      bg-red-500
                      text-[9px]
                      font-bold
                      text-white
                      shadow-sm
                      sm:h-5
                      sm:w-5
                      sm:text-[11px]
                    "
                  >
                    {totalCount > 99
                      ? "+99"
                      : totalCount}
                  </span>
                </Link>

                {isOpenMiniCart && (
                  <div className="hidden sm:block">
                    <MiniCart />
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* ==================================================
            CATEGORY + LOCATION
        ================================================== */}

        <div
          className="
            relative
            mt-3
            px-3
            sm:mt-4
            sm:px-4
            lg:px-6
          "
          onMouseLeave={scheduleClose}
        >
          <div className="flex items-center gap-5">
            {/* ==================================================
                CATEGORIES BUTTON
            ================================================== */}

            <button
              type="button"
              onMouseEnter={openMenu}
              onClick={() =>
                setIsOpen((prev) => !prev)
              }
              className="
                hidden
                cursor-pointer
                items-center
                gap-2
                py-2
                text-neutral-800
                transition-colors
                hover:text-red-600
                lg:flex
              "
            >
              <RxHamburgerMenu
                className="h-5 w-5"
              />

              <span
                className="
                  text-xs
                  font-bold
                  sm:text-sm
                "
              >
                Categories
              </span>
            </button>

            {/* ==================================================
                SELECT LOCATION
            ================================================== */}

            <button
              type="button"
              onClick={() =>
                setIsLocationModalOpen(true)
              }
              className="
                hidden
                cursor-pointer
                items-center
                gap-1.5
                py-2
                text-sm
                font-medium
                text-orange-500
                transition-colors
                hover:text-orange-600
                lg:flex
              "
            >
              <CiLocationOn
                className="
                  h-[20px]
                  w-[20px]
                  text-orange-400
                "
              />

              <span>
                {selectedLocation?.city?.name ||
                  "Select Location"}
              </span>
            </button>
          </div>

          {/* ==================================================
              DESKTOP MEGA MENU
          ================================================== */}

          {isOpen && (
            <div
              onMouseEnter={clearCloseTimer}
              className="
                absolute
                left-3
                top-[calc(100%+4px)]
                z-[80]
                hidden
                h-[350px]
                w-[750px]
                overflow-hidden
                rounded-xl
                border
                border-neutral-100
                bg-white
                shadow-2xl
                lg:flex
                sm:left-4
              "
            >
              {/* ==================================================
                  MAIN CATEGORIES
              ================================================== */}

              <nav
                className="
                  flex
                  w-[220px]
                  shrink-0
                  flex-col
                  overflow-y-auto
                  border-r
                  border-neutral-100
                  bg-neutral-50
                  py-2
                "
              >
                {mainDataArray?.map((category) => {
                  const isActive =
                    activeId === category.id;

                  return (
                    <div
                      key={category.id}
                      onMouseEnter={() =>
                        setActiveId(category.id)
                      }
                      className={`
                        flex
                        cursor-pointer
                        items-center
                        justify-between
                        px-4
                        py-2.5
                        text-[13px]
                        font-semibold
                        transition-colors
                        ${
                          isActive
                            ? "border-l-4 border-l-red-500 bg-white text-red-600"
                            : "text-neutral-900 hover:bg-neutral-100"
                        }
                      `}
                    >
                      <div
                        className="
                          flex
                          min-w-0
                          items-center
                          gap-2
                        "
                      >
                        <CategoryIcon
                          iconKey={
                            category.icon_key
                          }
                          className={`
                            h-[18px]
                            w-[18px]
                            shrink-0
                            ${
                              isActive
                                ? "text-red-600"
                                : "text-neutral-500"
                            }
                          `}
                        />

                        <span className="truncate">
                          {category.name}
                        </span>
                      </div>

                      <TbChevronRight
                        className={`
                          h-3.5
                          w-3.5
                          shrink-0
                          ${
                            isActive
                              ? "text-red-500"
                              : "text-neutral-300"
                          }
                        `}
                      />
                    </div>
                  );
                })}
              </nav>

              {/* ==================================================
                  SUB CATEGORIES
              ================================================== */}

              <div
                className="
                  relative
                  flex-1
                  overflow-y-auto
                  bg-white
                  px-5
                  py-5
                  lg:px-6
                "
              >
                {isSubLoading ? (
                  <div
                    className="
                      absolute
                      inset-0
                      flex
                      items-center
                      justify-center
                      bg-white/60
                    "
                  >
                    <div
                      className="
                        h-6
                        w-6
                        animate-spin
                        rounded-full
                        border-2
                        border-red-500
                        border-t-transparent
                      "
                    />
                  </div>
                ) : (
                  <>
                    <Link
                      href={`/search/${
                        targetCategory?.slug ||
                        "all"
                      }?category_id=${
                        targetCategory?.id || ""
                      }`}
                      className="
                        mb-4
                        flex
                        items-center
                        gap-1
                        whitespace-nowrap
                        text-[13px]
                        font-bold
                        text-red-600
                        hover:underline
                      "
                    >
                      All{" "}
                      {activeCategory?.name}{" "}
                      Products

                      <TbChevronRight
                        className="h-3.5 w-3.5"
                      />
                    </Link>

                    <div
                      className="
                        grid
                        grid-cols-2
                        gap-5
                        lg:grid-cols-3
                        lg:gap-6
                      "
                    >
                      {finalSubList?.map(
                        (col, idx) => (
                          <div
                            key={col.id || idx}
                            className="
                              flex
                              min-w-0
                              flex-col
                            "
                          >
                            <Link
                              href={`/search/${
                                col.slug ||
                                "category"
                              }?category_id=${
                                col.id
                              }`}
                              className="
                                group
                                mb-2
                                flex
                                items-center
                                justify-between
                                border-b
                                border-neutral-100
                                py-1
                                text-sm
                                font-bold
                                text-neutral-900
                              "
                            >
                              <span
                                className="
                                  truncate
                                  transition-colors
                                  group-hover:text-red-600
                                "
                              >
                                {col.name}
                              </span>

                              <TbChevronRight
                                className="
                                  h-3.5
                                  w-3.5
                                  shrink-0
                                  text-neutral-400
                                  transition-colors
                                  group-hover:text-red-600
                                "
                              />
                            </Link>

                            {(
                              col.children ||
                              col.subs ||
                              col.leaves ||
                              []
                            ).map(
                              (
                                leaf,
                                leafIndex
                              ) => (
                                <Link
                                  key={
                                    leaf.id ||
                                    leafIndex
                                  }
                                  href={`/search/${
                                    leaf.slug ||
                                    "child"
                                  }?category_id=${
                                    leaf.id
                                  }`}
                                  className="
                                    truncate
                                    py-1
                                    text-[13px]
                                    text-neutral-500
                                    transition-colors
                                    hover:text-red-600
                                  "
                                >
                                  {leaf.name}
                                </Link>
                              )
                            )}
                          </div>
                        )
                      )}
                    </div>
                  </>
                )}
              </div>
            </div>
          )}

          {/* ==================================================
              MOBILE CATEGORY MENU
          ================================================== */}

          {isOpen && (
            <div
              className="
                absolute
                left-3
                right-3
                top-[calc(100%+4px)]
                z-[80]
                overflow-hidden
                rounded-xl
                border
                border-neutral-100
                bg-white
                shadow-2xl
                lg:hidden
              "
            >
              <div
                className="
                  max-h-[70vh]
                  overflow-y-auto
                "
              >
                {mainDataArray?.map((category) => (
                  <Link
                    key={category.id}
                    href={`/search/${category.slug}?category_id=${category.id}`}
                    onClick={() =>
                      setIsOpen(false)
                    }
                    className="
                      flex
                      items-center
                      justify-between
                      border-b
                      border-neutral-100
                      px-4
                      py-3
                      text-sm
                      font-medium
                      text-neutral-800
                      hover:bg-neutral-50
                      active:bg-neutral-100
                    "
                  >
                    <div
                      className="
                        flex
                        items-center
                        gap-3
                      "
                    >
                      <CategoryIcon
                        iconKey={
                          category.icon_key
                        }
                        className="
                          h-5
                          w-5
                          text-neutral-500
                        "
                      />

                      <span>
                        {category.name}
                      </span>
                    </div>

                    <TbChevronRight
                      className="
                        h-4
                        w-4
                        text-neutral-400
                      "
                    />
                  </Link>
                ))}
              </div>
            </div>
          )}
        </div>
      </header>

      {/* ==================================================
          LOCATION MODAL
      ================================================== */}

      {isLocationModalOpen && (
        <LocationModal
          user={user}
          onClose={() =>
            setIsLocationModalOpen(false)
          }
          onLocationSaved={
            handleLocationSaved
          }
        />
      )}

      {/* ==================================================
          MOBILE BOTTOM NAV
      ================================================== */}

      <MobileBottomNav
        totalCount={totalCount}
        openMenu={() => setIsOpen(true)}
      />
    </>
  );
}

export default Header;