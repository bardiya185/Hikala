"use client";

import React, { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { RiErrorWarningLine } from "react-icons/ri";
import { Loader2 } from "lucide-react";
import toast from "react-hot-toast";

import { useCart, useGetAddresses } from "@/core/services/queries";
import { formatPrice } from "@/core/utils/formatPrice";
import AddressSelector from "../templates/cart/AddressSelector";
import api from "@/core/config/api";

function PlaceAnOrder() {
  const router = useRouter();
  const { data: cartData, refetch: refetchCart } = useCart();
  const { data: addressesData } = useGetAddresses();

  const [isLoading, setIsLoading] = useState(false);
  const [selectedAddressId, setSelectedAddressId] = useState(null);
  const [selectedPaymentMethod, setSelectedPaymentMethod] =
    useState("online");

  const finalPrice = cartData?.data?.summary;
  const items = cartData?.data?.items;
  const addresses = addressesData?.data || [];

  // Set default address
  useEffect(() => {
    if (addresses.length > 0) {
      const defaultAddress = addresses.find(
        (addr) => addr.is_default
      );
      const firstAddress = addresses[0];
      const initialAddress = defaultAddress || firstAddress;

      if (initialAddress) {
        setSelectedAddressId(initialAddress.id);
        localStorage.setItem(
          "selected_address_id",
          String(initialAddress.id)
        );
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
        product_variant_id:
          item.product_variant_id || item.variant?.id,
        quantity: item.quantity,
      }));

      const validItems = orderItems.filter(
        (item) =>
          item.product_variant_id &&
          item.quantity > 0
      );

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

      const response = await api.post(
        "/api/orders/checkout",
        payload
      );

      if (response.data?.success) {
        toast.success("Order created successfully!");
        await refetchCart();

        const orderId =
          response.data.data?.order?.id ||
          response.data.data?.id;

        router.push(`/checkout?order=${orderId}`);
      } else {
        toast.error(
          response.data?.message ||
            "Failed to create order"
        );
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 422) {
          const errors = error.response.data?.errors;

          if (errors) {
            const messages = Object.entries(errors)
              .map(
                ([field, msgs]) =>
                  `${field}: ${msgs.join(", ")}`
              )
              .join("\n");

            toast.error(messages);
          } else {
            toast.error(
              error.response.data?.message ||
                "Validation error"
            );
          }
        } else {
          toast.error(
            error.response.data?.message ||
              "Server error"
          );
        }
      } else if (error.request) {
        toast.error("No response from server");
      } else {
        toast.error(
          error.message ||
            "Something went wrong"
        );
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
          z-[90]
          w-full
          border-t
          border-neutral-200
          bg-white
          px-2
          py-2
          shadow-[0_-4px_15px_rgba(0,0,0,0.08)]
          dark:border-neutral-800
          dark:bg-neutral-950
          dark:shadow-[0_-4px_20px_rgba(0,0,0,0.35)]
          xs:px-3
          xs:py-2.5
          sm:px-5
          sm:py-3
          lg:hidden
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
        <div className="flex w-full items-center justify-between gap-2 xs:gap-3">
          {/* Price */}
          <div className="flex min-w-0 flex-1 flex-col">
            <span
              className="
                text-[9px]
                text-neutral-400
                dark:text-neutral-500
                xs:text-[10px]
                sm:text-[11px]
              "
            >
              Shopping Cart Total
            </span>

            <span
              className="
                whitespace-nowrap
                text-xs
                font-bold
                text-neutral-800
                dark:text-neutral-100
                xs:text-sm
                sm:text-base
              "
            >
              ${formatPrice(finalPrice?.final_total)}
            </span>
          </div>

          {/* Order Button */}
          <button
            type="button"
            onClick={handlePlaceOrder}
            disabled={
              isLoading ||
              !selectedAddressId
            }
            className="
              flex
              min-w-[100px]
              max-w-[200px]
              flex-1
              items-center
              justify-center
              gap-1.5
              rounded-lg
              bg-red-500
              px-2
              text-[11px]
              font-medium
              text-white
              transition-colors
              hover:bg-red-600
              active:bg-red-700
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              disabled:cursor-not-allowed
              disabled:opacity-50
              dark:focus-visible:ring-red-400
              dark:focus-visible:ring-offset-neutral-950
              xs:min-w-[120px]
              xs:px-3
              xs:text-xs
              sm:min-w-[140px]
              sm:px-4
              sm:text-sm
              md:h-12
              xs:h-10
              sm:h-11
              h-9
            "
          >
            {isLoading ? (
              <>
                <Loader2
                  aria-hidden="true"
                  className="
                    h-3.5
                    w-3.5
                    animate-spin
                    xs:h-4
                    xs:w-4
                  "
                />

                <span className="hidden xs:inline">
                  Creating...
                </span>

                <span className="inline xs:hidden">
                  ...
                </span>
              </>
            ) : (
              <>
                <span className="hidden xs:inline">
                  Place an Order
                </span>

                <span className="inline xs:hidden">
                  Order
                </span>
              </>
            )}
          </button>
        </div>
      </div>

      {/* =========================================
          DESKTOP
      ========================================= */}
      <div
        className="
          mx-auto
          hidden
          w-full
          max-w-[400px]
          overflow-hidden
          rounded-xl
          border
          border-neutral-200
          bg-white
          shadow-sm
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/20
          lg:block
        "
      >
        <div className="px-4 pt-4 sm:px-5 sm:pt-5">
          <p
            className="
              mb-2
              text-sm
              font-medium
              text-neutral-700
              dark:text-neutral-200
            "
          >
            Shipping Address
          </p>

          <AddressSelector
            onSelect={setSelectedAddressId}
            selectedId={selectedAddressId}
          />
        </div>

        <div
          className="
            mx-4
            mt-4
            border-t
            border-neutral-200
            dark:border-neutral-800
            sm:mx-5
          "
        />

        <div className="px-4 pt-4 sm:px-5 sm:pt-5">
          <p
            className="
              text-base
              font-medium
              text-neutral-800
              dark:text-neutral-100
              sm:text-lg
            "
          >
            Payment details
          </p>
        </div>

        <div
          className="
            mt-6
            flex
            items-center
            justify-between
            gap-4
            px-4
            text-sm
            text-neutral-500
            dark:text-neutral-400
            sm:mt-7
            sm:px-5
          "
        >
          <p>Total price of goods</p>

          <span
            className="
              whitespace-nowrap
              font-medium
              text-neutral-700
              dark:text-neutral-200
            "
          >
            ${formatPrice(finalPrice?.final_total)}
          </span>
        </div>

        <div
          className="
            mt-4
            flex
            items-center
            justify-between
            gap-4
            px-4
            text-sm
            sm:mt-5
            sm:px-5
          "
        >
          <p className="text-neutral-600 dark:text-neutral-300">
            Shopping Cart Total
          </p>

          <span
            className="
              whitespace-nowrap
              font-semibold
              text-neutral-800
              dark:text-neutral-100
            "
          >
            ${formatPrice(finalPrice?.final_total)}
          </span>
        </div>

        <div className="mt-5 px-4 sm:px-5">
          <button
            type="button"
            onClick={handlePlaceOrder}
            disabled={
              isLoading ||
              !selectedAddressId
            }
            className="
              flex
              h-11
              w-full
              items-center
              justify-center
              gap-2
              rounded-lg
              bg-red-500
              text-sm
              font-medium
              text-white
              transition-colors
              hover:bg-red-600
              active:bg-red-700
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              disabled:cursor-not-allowed
              disabled:opacity-50
              dark:focus-visible:ring-red-400
              dark:focus-visible:ring-offset-neutral-900
              sm:h-[43px]
              sm:text-base
            "
          >
            {isLoading ? (
              <>
                <Loader2
                  aria-hidden="true"
                  className="h-4 w-4 animate-spin"
                />
                Creating Order...
              </>
            ) : (
              "Place an Order"
            )}
          </button>
        </div>

        <div
          className="
            mb-4
            mt-4
            flex
            items-start
            gap-2
            px-4
            text-[11px]
            leading-5
            text-neutral-400
            dark:text-neutral-500
            sm:mb-5
            sm:mt-5
            sm:px-5
            sm:text-xs
          "
        >
          <RiErrorWarningLine
            aria-hidden="true"
            className="mt-0.5 shrink-0"
            size={16}
          />

          <p>
            The order has not yet been paid for,
            and if items go out of stock, they
            will be removed from the cart.
          </p>
        </div>
      </div>
    </>
  );
}

export default PlaceAnOrder;