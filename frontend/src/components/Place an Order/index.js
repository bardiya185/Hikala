"use client";

import React, { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { RiErrorWarningLine } from "react-icons/ri";
import { Loader2 } from "lucide-react";
import toast from "react-hot-toast";

import { useCart , useGetAddresses } from "@/core/services/queries";
import { formatPrice } from "@/core/utils/formatPrice";
import AddressSelector from "../templates/cart/AddressSelector";
import api from "@/core/config/api";

function PlaceAnOrder() {
  const router = useRouter();
  const { data: cartData, refetch: refetchCart } = useCart();
  const { data: addressesData } = useGetAddresses();
  
  const [isLoading, setIsLoading] = useState(false);
  const [selectedAddressId, setSelectedAddressId] = useState(null);
  const [selectedPaymentMethod, setSelectedPaymentMethod] = useState("online");

  const finalPrice = cartData?.data?.summary;
  const items = cartData?.data?.items;
  const addresses = addressesData?.data || [];

  // Set default address
  useEffect(() => {
    if (addresses.length > 0) {
      const defaultAddress = addresses.find((addr) => addr.is_default);
      const firstAddress = addresses[0];
      const initialAddress = defaultAddress || firstAddress;
      
      if (initialAddress) {
        setSelectedAddressId(initialAddress.id);
        localStorage.setItem("selected_address_id", String(initialAddress.id));
      }
    }
  }, [addresses]);

  if (!items || items.length === 0) {
    return null;
  }

  const handlePlaceOrder = async () => {
    if (isLoading) return;

    if (!selectedAddressId) {
      toast.error("Please select a shipping address");
      return;
    }

    setIsLoading(true);

    try {
      const orderItems = items.map((item) => ({
        product_variant_id: item.product_variant_id || item.variant?.id,
        quantity: item.quantity,
      }));

      const validItems = orderItems.filter(item => item.product_variant_id && item.quantity > 0);
      
      if (validItems.length === 0) {
        toast.error("No valid items in cart");
        return;
      }

      const payload = {
        items: validItems,
        address_id: selectedAddressId,
        payment_method: selectedPaymentMethod,
        shipping_method: "standard",
        customer_note: "",
      };

      console.log("📤 Sending payload:", payload);

      const response = await api.post("/api/orders/checkout", payload);

      console.log("📥 Response:", response.data);

      if (response.data?.success) {
        toast.success("Order created successfully!");
        await refetchCart();
        
        const orderId = response.data.data?.order?.id || response.data.data?.id;
        router.push(`/checkout?order=${orderId}`);
      } else {
        toast.error(response.data?.message || "Failed to create order");
      }

    } catch (error) {
      console.error("❌ Error:", error);
      
      if (error.response) {
        console.error("❌ Status:", error.response.status);
        console.error("❌ Response data:", error.response.data);
        
        if (error.response.status === 422) {
          const errors = error.response.data?.errors;
          if (errors) {
            const messages = Object.entries(errors)
              .map(([field, msgs]) => `${field}: ${msgs.join(", ")}`)
              .join("\n");
            toast.error(messages);
          } else {
            toast.error(error.response.data?.message || "Validation error");
          }
        } else {
          toast.error(error.response.data?.message || "Server error");
        }
      } else if (error.request) {
        toast.error("No response from server");
      } else {
        toast.error(error.message || "Something went wrong");
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <>
      {/* =========================================
          MOBILE / TABLET - RESPONSIVE
      ========================================= */}
      <div
        className="
          fixed
          bottom-16
          left-0
          right-0
          lg:hidden
          z-[90]
          w-full
          bg-white
          border-t
          border-neutral-200
          shadow-[0_-4px_15px_rgba(0,0,0,0.08)]
          px-2
          xs:px-3
          sm:px-5
          py-2
          xs:py-2.5
          sm:py-3
        "
      >
        {/* Address Selector */}
        <div className="mb-2 xs:mb-2.5 sm:mb-3">
          <AddressSelector 
            onSelect={setSelectedAddressId}
            selectedId={selectedAddressId}
          />
        </div>

        {/* Price + Button */}
        <div className="flex items-center justify-between gap-2 xs:gap-3 w-full">
          {/* Price */}
          <div className="flex flex-col min-w-0 flex-1">
            <span className="text-[9px] xs:text-[10px] sm:text-[11px] text-neutral-400">
              Shopping Cart Total
            </span>
            <span className="text-xs xs:text-sm sm:text-base font-bold text-neutral-800 whitespace-nowrap">
              ${formatPrice(finalPrice?.final_total)}
            </span>
          </div>

          {/* Order Button */}
          <button
            type="button"
            onClick={handlePlaceOrder}
            disabled={isLoading || !selectedAddressId}
            className="
              flex-1
              min-w-[100px]
              xs:min-w-[120px]
              sm:min-w-[140px]
              max-w-[200px]
              xs:max-w-[220px]
              sm:max-w-[240px]
              h-9
              xs:h-10
              sm:h-11
              md:h-12
              rounded-lg
              bg-red-500
              hover:bg-red-600
              active:bg-red-700
              text-white
              text-[11px]
              xs:text-xs
              sm:text-sm
              md:text-base
              font-medium
              transition-colors
              disabled:opacity-50
              disabled:cursor-not-allowed
              flex
              items-center
              justify-center
              gap-1.5
              xs:gap-2
              px-2
              xs:px-3
              sm:px-4
            "
          >
            {isLoading ? (
              <>
                <Loader2 className="h-3.5 w-3.5 xs:h-4 xs:w-4 animate-spin" />
                <span className="hidden xs:inline">Creating...</span>
                <span className="inline xs:hidden">...</span>
              </>
            ) : (
              <>
                <span className="hidden xs:inline">Place an Order</span>
                <span className="inline xs:hidden">Order</span>
              </>
            )}
          </button>
        </div>
      </div>

      {/* =========================================
          DESKTOP
      ========================================= */}
      <div className="hidden lg:block w-full max-w-[400px] border border-neutral-200 bg-white rounded-xl mx-auto shadow-sm overflow-hidden">
        <div className="px-4 sm:px-5 pt-4 sm:pt-5">
          <p className="text-sm font-medium text-neutral-700 mb-2">
            Shipping Address
          </p>
          <AddressSelector 
            onSelect={setSelectedAddressId}
            selectedId={selectedAddressId}
          />
        </div>

        <div className="mx-4 sm:mx-5 mt-4 border-t border-neutral-200" />

        <div className="px-4 sm:px-5 pt-4 sm:pt-5">
          <p className="text-base sm:text-lg font-medium text-neutral-800">
            Payment details
          </p>
        </div>

        <div className="flex items-center justify-between gap-4 px-4 sm:px-5 mt-6 sm:mt-7 text-sm text-neutral-500">
          <p>Total price of goods</p>
          <span className="whitespace-nowrap font-medium text-neutral-700">
            ${formatPrice(finalPrice?.final_total)}
          </span>
        </div>

        <div className="flex items-center justify-between gap-4 px-4 sm:px-5 mt-4 sm:mt-5 text-sm">
          <p>Shopping Cart Total</p>
          <span className="whitespace-nowrap font-semibold text-neutral-800">
            ${formatPrice(finalPrice?.final_total)}
          </span>
        </div>

        <div className="px-4 sm:px-5 mt-5">
          <button
            type="button"
            onClick={handlePlaceOrder}
            disabled={isLoading || !selectedAddressId}
            className="w-full h-11 sm:h-[43px] rounded-lg bg-red-500 hover:bg-red-600 active:bg-red-700 text-white text-sm sm:text-base font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            {isLoading ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
                Creating Order...
              </>
            ) : (
              "Place an Order"
            )}
          </button>
        </div>

        <div className="flex items-start gap-2 px-4 sm:px-5 mt-4 sm:mt-5 mb-4 sm:mb-5 text-neutral-400 text-[11px] sm:text-xs leading-5">
          <RiErrorWarningLine className="shrink-0 mt-0.5" size={16} />
          <p>
            The order has not yet been paid for, and if items go out of
            stock, they will be removed from the cart.
          </p>
        </div>
      </div>
    </>
  );
}

export default PlaceAnOrder;