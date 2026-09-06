// src/components/templates/cart/AddressSelector.jsx

"use client";

import React, { useState, useEffect } from "react";
import { MapPin, CheckCircle, ChevronDown, ChevronUp, Plus } from "lucide-react";
import { useGetAddresses } from "@/core/services/queries";
import { useCreateAddress } from "@/core/services/mutations";
import toast from "react-hot-toast";
import { motion, AnimatePresence } from "framer-motion";
import { X } from "lucide-react";

function AddressSelector({ onSelect, selectedId }) {
  const [isOpen, setIsOpen] = useState(false);
  const [isMobileModalOpen, setIsMobileModalOpen] = useState(false);
  const [selectedAddress, setSelectedAddress] = useState(null);
  const [isCreating, setIsCreating] = useState(false);
  
  const { data: addressesData, isLoading, refetch } = useGetAddresses();
  const { mutate: createAddress } = useCreateAddress();
  
  const addresses = addressesData?.data || [];

  // Check if mobile
  const isMobile = typeof window !== 'undefined' && window.innerWidth < 768;

  // Create default address if no addresses exist
  useEffect(() => {
    if (!isLoading && addresses.length === 0 && !isCreating) {
      createDefaultAddress();
    }
  }, [addresses, isLoading]);

  // Set default address on load
  useEffect(() => {
    if (addresses.length > 0) {
      const defaultAddress = addresses.find((addr) => addr.is_default);
      const firstAddress = addresses[0];
      
      const initialAddress = defaultAddress || firstAddress;
      
      setSelectedAddress(initialAddress);
      onSelect(initialAddress.id);
      
      localStorage.setItem("selected_address_id", String(initialAddress.id));
    }
  }, [addresses]);

  const createDefaultAddress = () => {
    setIsCreating(true);
    
    const defaultAddressData = {
      title: "Home",
      receiver_name: "John Doe",
      receiver_mobile: "09123456789",
      province_id: 1,
      city_id: 1,
      address: "123 Main Street, Downtown",
      building_number: "123",
      unit: "Apt 4B",
      postal_code: "1234567890",
      latitude: 35.6892,
      longitude: 51.3890,
      is_default: true,
    };

    createAddress(defaultAddressData, {
      onSuccess: () => {
        toast.success("Default address created!");
        refetch();
        setIsCreating(false);
      },
      onError: (error) => {
        console.error("Error creating default address:", error);
        toast.error("Could not create default address");
        setIsCreating(false);
      },
    });
  };

  const handleSelect = (address) => {
    setSelectedAddress(address);
    setIsOpen(false);
    setIsMobileModalOpen(false);
    onSelect(address.id);
    
    localStorage.setItem("selected_address_id", String(address.id));
    
    toast.success(`Address: ${address.title} selected`);
  };

  const openSelector = () => {
    if (isMobile) {
      setIsMobileModalOpen(true);
    } else {
      setIsOpen(!isOpen);
    }
  };

  const closeModal = () => {
    setIsMobileModalOpen(false);
  };

  if (isLoading || isCreating) {
    return (
      <div className="flex items-center gap-2 text-sm text-neutral-500">
        <div className="h-4 w-4 animate-spin rounded-full border-2 border-red-500 border-t-transparent" />
        {isCreating ? "Creating default address..." : "Loading addresses..."}
      </div>
    );
  }

  return (
    <>
      {/* ============================================================
          DESKTOP DROPDOWN
      ============================================================ */}
      <div className="relative hidden md:block">
        {/* Selected Address Display */}
        <button
          type="button"
          onClick={openSelector}
          className="flex w-full items-center justify-between rounded-lg border border-neutral-200 bg-white p-3 text-left transition-all hover:border-neutral-300"
        >
          <div className="flex items-start gap-3">
            <MapPin className="mt-0.5 h-5 w-5 shrink-0 text-neutral-400" />
            <div className="min-w-0 flex-1">
              {selectedAddress ? (
                <>
                  <p className="font-semibold text-neutral-800">
                    {selectedAddress.title}
                    {selectedAddress.is_default && (
                      <span className="ml-2 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">
                        Default
                      </span>
                    )}
                  </p>
                  <p className="text-sm text-neutral-500">
                    {selectedAddress.address}
                    {selectedAddress.unit && `, Unit ${selectedAddress.unit}`}
                  </p>
                  <p className="text-xs text-neutral-400">
                    {selectedAddress.receiver_name} • {selectedAddress.receiver_mobile}
                  </p>
                </>
              ) : (
                <p className="text-sm text-neutral-500">Select an address</p>
              )}
            </div>
          </div>
          {isOpen ? (
            <ChevronUp className="h-5 w-5 shrink-0 text-neutral-400" />
          ) : (
            <ChevronDown className="h-5 w-5 shrink-0 text-neutral-400" />
          )}
        </button>

        {/* Desktop Dropdown */}
        {isOpen && (
          <div className="absolute left-0 right-0 top-full z-50 mt-1 max-h-60 overflow-auto rounded-lg border border-neutral-200 bg-white shadow-lg">
            {addresses.map((address) => (
              <button
                key={address.id}
                type="button"
                onClick={() => handleSelect(address)}
                className={`flex w-full items-start gap-4 border-b border-neutral-100 px-4 py-3 text-left transition-colors last:border-0 hover:bg-neutral-50 ${
                  selectedAddress?.id === address.id ? "bg-red-50" : ""
                }`}
              >
                <MapPin className="mt-0.5 h-5 w-5 shrink-0 text-neutral-400" />
                <div className="min-w-0 flex-1">
                  <div className="flex items-center gap-2">
                    <p className="font-semibold text-neutral-800">
                      {address.title}
                    </p>
                    {address.is_default && (
                      <span className="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">
                        Default
                      </span>
                    )}
                    {selectedAddress?.id === address.id && (
                      <CheckCircle className="h-4 w-4 text-red-500" />
                    )}
                  </div>
                  <p className="text-sm text-neutral-500">
                    {address.address}
                    {address.unit && `, Unit ${address.unit}`}
                  </p>
                  <p className="text-xs text-neutral-400">
                    {address.receiver_name} • {address.receiver_mobile}
                  </p>
                </div>
              </button>
            ))}
            
            <button
              type="button"
              onClick={() => window.location.href = "/profile/addresses/new"}
              className="flex w-full items-center gap-2 border-t border-neutral-200 px-4 py-3 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50"
            >
              <Plus className="h-4 w-4" />
              Add New Address
            </button>
          </div>
        )}
      </div>

      {/* ============================================================
          MOBILE MODAL (Full Screen Bottom Sheet)
      ============================================================ */}
      <div className="block md:hidden">
        {/* Selected Address Display - Mobile */}
        <button
          type="button"
          onClick={openSelector}
          className="flex w-full items-center justify-between rounded-lg border border-neutral-200 bg-white p-3 text-left transition-all hover:border-neutral-300"
        >
          <div className="flex items-start gap-3">
            <MapPin className="mt-0.5 h-5 w-5 shrink-0 text-neutral-400" />
            <div className="min-w-0 flex-1">
              {selectedAddress ? (
                <>
                  <p className="font-semibold text-neutral-800">
                    {selectedAddress.title}
                    {selectedAddress.is_default && (
                      <span className="ml-2 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">
                        Default
                      </span>
                    )}
                  </p>
                  <p className="text-sm text-neutral-500">
                    {selectedAddress.address}
                    {selectedAddress.unit && `, Unit ${selectedAddress.unit}`}
                  </p>
                  <p className="text-xs text-neutral-400">
                    {selectedAddress.receiver_name} • {selectedAddress.receiver_mobile}
                  </p>
                </>
              ) : (
                <p className="text-sm text-neutral-500">Select an address</p>
              )}
            </div>
          </div>
          <ChevronDown className="h-5 w-5 shrink-0 text-neutral-400" />
        </button>

        {/* Mobile Modal - Bottom Sheet */}
        <AnimatePresence>
          {isMobileModalOpen && (
            <>
              {/* Backdrop */}
              <motion.div
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                exit={{ opacity: 0 }}
                onClick={closeModal}
                className="fixed inset-0 z-[100] bg-black/50"
              />

              {/* Modal Content */}
              <motion.div
                initial={{ y: "100%" }}
                animate={{ y: 0 }}
                exit={{ y: "100%" }}
                transition={{ type: "spring", damping: 25, stiffness: 300 }}
                className="fixed bottom-0 left-0 right-0 z-[101] max-h-[80vh] rounded-t-2xl bg-white shadow-2xl"
              >
                {/* Header */}
                <div className="flex items-center justify-between border-b border-neutral-200 px-4 py-3">
                  <h3 className="text-base font-semibold text-neutral-800">
                    Select Address
                  </h3>
                  <button
                    onClick={closeModal}
                    className="rounded-full p-1.5 hover:bg-neutral-100 transition-colors"
                  >
                    <X className="h-5 w-5 text-neutral-500" />
                  </button>
                </div>

                {/* Address List */}
                <div className="max-h-[60vh] overflow-y-auto p-2">
                  {addresses.map((address) => (
                    <button
                      key={address.id}
                      type="button"
                      onClick={() => handleSelect(address)}
                      className={`flex w-full items-start gap-4 rounded-lg border-b border-neutral-100 px-3 py-3 text-left transition-colors last:border-0 hover:bg-neutral-50 ${
                        selectedAddress?.id === address.id ? "bg-red-50" : ""
                      }`}
                    >
                      <MapPin className="mt-0.5 h-5 w-5 shrink-0 text-neutral-400" />
                      <div className="min-w-0 flex-1">
                        <div className="flex items-center gap-2">
                          <p className="font-semibold text-neutral-800">
                            {address.title}
                          </p>
                          {address.is_default && (
                            <span className="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">
                              Default
                            </span>
                          )}
                          {selectedAddress?.id === address.id && (
                            <CheckCircle className="h-4 w-4 text-red-500" />
                          )}
                        </div>
                        <p className="text-sm text-neutral-500">
                          {address.address}
                          {address.unit && `, Unit ${address.unit}`}
                        </p>
                        <p className="text-xs text-neutral-400">
                          {address.receiver_name} • {address.receiver_mobile}
                        </p>
                      </div>
                    </button>
                  ))}

                  {/* Add Address Button */}
                  <button
                    type="button"
                    onClick={() => {
                      closeModal();
                      window.location.href = "/profile/addresses/new";
                    }}
                    className="flex w-full items-center justify-center gap-2 rounded-lg border-2 border-dashed border-red-200 py-3 text-sm font-medium text-red-600 transition-colors hover:bg-red-50"
                  >
                    <Plus className="h-4 w-4" />
                    Add New Address
                  </button>
                </div>
              </motion.div>
            </>
          )}
        </AnimatePresence>
      </div>
    </>
  );
}

export default AddressSelector;