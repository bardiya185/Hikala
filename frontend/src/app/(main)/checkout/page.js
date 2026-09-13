"use client";

import React, { useState, useEffect, useMemo } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import Image from "next/image";
import {
  ArrowLeft,
  CreditCard,
  Wallet,
  Banknote,
  Loader2,
  CheckCircle,
  MapPin,
  Truck,
  Clock,
  ChevronDown,
  ChevronUp,
} from "lucide-react";
import toast from "react-hot-toast";

import { useGetOrder } from "@/core/services/queries";
import { usePayOrder } from "@/core/services/mutations";
import { useGetDeliveryOptions } from "@/core/services/queries";
import { formatPrice } from "@/core/utils/formatPrice";
import { formatDate } from "@/core/utils/formatDate";

// ============================================================
// PAYMENT METHODS
// ============================================================

const PAYMENT_METHODS = [
  {
    value: "online",
    label: "Online Payment",
    icon: CreditCard,
    description:
      "Pay with credit card, debit card, or digital wallet",
    color: "text-blue-600 dark:text-blue-400",
    bg: "bg-blue-50 dark:bg-blue-950/30",
    border: "border-blue-200 dark:border-blue-800",
  },
  {
    value: "cash_on_delivery",
    label: "Cash on Delivery",
    icon: Banknote,
    description: "Pay when you receive your order",
    color: "text-green-600 dark:text-green-400",
    bg: "bg-green-50 dark:bg-green-950/30",
    border: "border-green-200 dark:border-green-800",
  },
  {
    value: "wallet",
    label: "Wallet Balance",
    icon: Wallet,
    description: "Pay using your wallet balance",
    color: "text-purple-600 dark:text-purple-400",
    bg: "bg-purple-50 dark:bg-purple-950/30",
    border: "border-purple-200 dark:border-purple-800",
  },
];

// ============================================================
// MAIN COMPONENT
// ============================================================

function CheckoutPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const orderId = searchParams.get("order");

  const [selectedPayment, setSelectedPayment] =
    useState("online");
  const [selectedDate, setSelectedDate] = useState(null);
  const [selectedTime, setSelectedTime] = useState(null);
  const [showDeliveryOptions, setShowDeliveryOptions] =
    useState(false);
  const [termsAccepted, setTermsAccepted] =
    useState(false);

  // Get order details
  const {
    data: orderData,
    isLoading: orderLoading,
    isError: orderError,
  } = useGetOrder(orderId);

  // Get delivery options
  const {
    data: deliveryData,
    isLoading: deliveryLoading,
  } = useGetDeliveryOptions();

  // Pay order mutation
  const {
    mutate: payOrder,
    isPending: isPaying,
  } = usePayOrder();

  const order = orderData?.data;

  // Set default delivery date
  useEffect(() => {
    if (
      deliveryData?.data?.dates?.length > 0 &&
      !selectedDate
    ) {
      const earliest =
        deliveryData.data.dates.find(
          (d) => d.is_earliest
        );

      setSelectedDate(
        earliest?.value ||
          deliveryData.data.dates[0]?.value
      );
    }

    if (
      deliveryData?.data?.time_slots?.length > 0 &&
      !selectedTime
    ) {
      setSelectedTime(
        deliveryData.data.time_slots[0]?.value
      );
    }
  }, [
    deliveryData,
    selectedDate,
    selectedTime,
  ]);

  // Redirect if no order
  useEffect(() => {
    if (!orderId) {
      router.push("/cart");
      toast.error("No order found");
    }
  }, [orderId, router]);

  const handlePayment = () => {
    if (!termsAccepted) {
      toast.error(
        "Please accept the terms and conditions"
      );
      return;
    }

    if (!selectedPayment) {
      toast.error(
        "Please select a payment method"
      );
      return;
    }

    payOrder(orderId, {
      onSuccess: () => {
        toast.success("Payment successful!");
        router.push(
          `/checkout/success?order=${orderId}`
        );
      },
      onError: (error) => {
        toast.error(
          error?.response?.data?.message ||
            "Payment failed"
        );
      },
    });
  };

  // Loading state
  if (orderLoading) {
    return (
      <div
        className="
          container mx-auto max-w-4xl
          px-4 py-8
          text-neutral-900
          dark:text-neutral-100
        "
      >
        <div className="flex items-center justify-center py-12">
          <Loader2
            aria-label="Loading"
            className="h-8 w-8 animate-spin text-red-500"
          />
        </div>
      </div>
    );
  }

  // Error state
  if (orderError || !order) {
    return (
      <div
        className="
          container mx-auto max-w-4xl
          px-4 py-8
          text-neutral-900
          dark:text-neutral-100
        "
      >
        <div
          className="
            rounded-2xl
            border border-neutral-200
            bg-white
            p-6
            text-center
            shadow-sm
            dark:border-neutral-800
            dark:bg-neutral-900
            dark:shadow-black/20
          "
        >
          <p className="text-sm text-red-500 dark:text-red-400">
            Order not found
          </p>

          <Link
            href="/cart"
            className="
              mt-4 inline-block
              rounded-lg
              bg-red-600
              px-6 py-2
              text-sm font-medium
              text-white
              transition-colors
              hover:bg-red-700
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              dark:focus-visible:ring-offset-neutral-900
            "
          >
            Back to Cart
          </Link>
        </div>
      </div>
    );
  }

  const orderStatus =
    order.status?.value || order.status;

  // Check if order can be paid
  if (orderStatus !== "pending") {
    return (
      <div
        className="
          container mx-auto max-w-4xl
          px-4 py-8
          text-neutral-900
          dark:text-neutral-100
        "
      >
        <div
          className="
            rounded-2xl
            border border-neutral-200
            bg-white
            p-6
            text-center
            shadow-sm
            dark:border-neutral-800
            dark:bg-neutral-900
            dark:shadow-black/20
          "
        >
          <CheckCircle className="mx-auto h-12 w-12 text-green-500 dark:text-green-400" />

          <h2
            className="
              mt-3
              text-lg font-bold
              text-neutral-800
              dark:text-neutral-100
            "
          >
            Order Already Processed
          </h2>

          <p
            className="
              mt-1
              text-sm
              text-neutral-500
              dark:text-neutral-400
            "
          >
            This order has already been {orderStatus}
          </p>

          <Link
            href={`/profile/orders/${orderId}`}
            className="
              mt-4 inline-block
              rounded-lg
              bg-red-600
              px-6 py-2
              text-sm font-medium
              text-white
              transition-colors
              hover:bg-red-700
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              dark:focus-visible:ring-offset-neutral-900
            "
          >
            View Order
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div
      className="
        container mx-auto max-w-4xl
        px-4 py-8
        text-neutral-900
        dark:text-neutral-100
      "
    >
      {/* Header */}
      <div className="mb-6">
        <Link
          href="/cart"
          className="
            inline-flex
            items-center
            gap-2
            text-sm font-medium
            text-neutral-600
            transition-colors
            hover:text-neutral-900
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500
            focus-visible:ring-offset-2
            dark:text-neutral-400
            dark:hover:text-white
            dark:focus-visible:ring-red-400
            dark:focus-visible:ring-offset-neutral-950
          "
        >
          <ArrowLeft
            aria-hidden="true"
            className="h-4 w-4"
          />
          Back to Cart
        </Link>

        <h1
          className="
            mt-3
            text-2xl font-bold
            text-neutral-800
            dark:text-white
          "
        >
          Checkout
        </h1>

        <p
          className="
            text-sm
            text-neutral-500
            dark:text-neutral-400
          "
        >
          Order #{order.order_number}
        </p>
      </div>

      <div className="grid gap-6 lg:grid-cols-3">
        {/* Left Column - 2/3 */}
        <div className="space-y-6 lg:col-span-2">
          {/* Delivery Address */}
          <div
            className="
              rounded-2xl
              border border-neutral-200
              bg-white
              p-6
              shadow-sm
              dark:border-neutral-800
              dark:bg-neutral-900
              dark:shadow-black/20
            "
          >
            <h2
              className="
                mb-4
                text-base font-semibold
                text-neutral-800
                dark:text-neutral-100
              "
            >
              Delivery Address
            </h2>

            {order.address ? (
              <div className="flex items-start gap-3">
                <MapPin
                  aria-hidden="true"
                  className="
                    mt-0.5
                    h-5 w-5
                    shrink-0
                    text-neutral-400
                    dark:text-neutral-500
                  "
                />

                <div className="min-w-0">
                  <div className="flex flex-wrap items-center gap-2">
                    <h4
                      className="
                        font-semibold
                        text-neutral-800
                        dark:text-neutral-100
                      "
                    >
                      {order.address.title}
                    </h4>

                    {order.address.is_default && (
                      <span
                        className="
                          rounded-full
                          bg-blue-50
                          px-2 py-0.5
                          text-[10px]
                          font-medium
                          text-blue-600
                          dark:bg-blue-950/30
                          dark:text-blue-400
                        "
                      >
                        Default
                      </span>
                    )}
                  </div>

                  <p
                    className="
                      mt-1
                      text-sm
                      text-neutral-600
                      dark:text-neutral-300
                    "
                  >
                    {order.address.receiver_name} •{" "}
                    {order.address.receiver_mobile}
                  </p>

                  <p
                    className="
                      mt-0.5
                      text-sm
                      text-neutral-500
                      dark:text-neutral-400
                    "
                  >
                    {order.address.address}
                    {order.address.unit &&
                      `, Unit ${order.address.unit}`}
                  </p>

                  <p
                    className="
                      text-sm
                      text-neutral-500
                      dark:text-neutral-400
                    "
                  >
                    {order.address.city?.name},{" "}
                    {order.address.province?.name}
                  </p>
                </div>
              </div>
            ) : (
              <p
                className="
                  text-sm
                  text-neutral-500
                  dark:text-neutral-400
                "
              >
                No address provided
              </p>
            )}
          </div>

          {/* Delivery Options */}
          <div
            className="
              rounded-2xl
              border border-neutral-200
              bg-white
              p-6
              shadow-sm
              dark:border-neutral-800
              dark:bg-neutral-900
              dark:shadow-black/20
            "
          >
            <button
              type="button"
              onClick={() =>
                setShowDeliveryOptions(
                  !showDeliveryOptions
                )
              }
              aria-expanded={showDeliveryOptions}
              className="
                flex
                w-full
                items-center
                justify-between
                rounded-lg
                text-left
                outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                focus-visible:ring-offset-2
                dark:focus-visible:ring-offset-neutral-900
              "
            >
              <div className="flex items-center gap-2">
                <Truck
                  aria-hidden="true"
                  className="
                    h-5 w-5
                    text-neutral-600
                    dark:text-neutral-300
                  "
                />

                <h2
                  className="
                    text-base font-semibold
                    text-neutral-800
                    dark:text-neutral-100
                  "
                >
                  Delivery Options
                </h2>
              </div>

              {showDeliveryOptions ? (
                <ChevronUp
                  aria-hidden="true"
                  className="
                    h-5 w-5
                    text-neutral-400
                    dark:text-neutral-500
                  "
                />
              ) : (
                <ChevronDown
                  aria-hidden="true"
                  className="
                    h-5 w-5
                    text-neutral-400
                    dark:text-neutral-500
                  "
                />
              )}
            </button>

            {showDeliveryOptions && (
              <div className="mt-4 space-y-4">
                {deliveryLoading ? (
                  <div className="flex items-center justify-center py-4">
                    <Loader2
                      aria-label="Loading delivery options"
                      className="h-6 w-6 animate-spin text-red-500"
                    />
                  </div>
                ) : deliveryData?.data ? (
                  <>
                    {/* Delivery Info */}
                    <div
                      className="
                        rounded-lg
                        bg-blue-50
                        p-3
                        text-sm
                        text-blue-700
                        dark:bg-blue-950/30
                        dark:text-blue-300
                      "
                    >
                      <div className="flex items-center gap-2">
                        <Clock
                          aria-hidden="true"
                          className="h-4 w-4 shrink-0"
                        />

                        <span>
                          {deliveryData.data
                            .delivery_info
                            ?.is_same_day_available
                            ? "Same day delivery available!"
                            : `Estimated delivery: ${deliveryData.data.delivery_info?.earliest_date_formatted} - ${deliveryData.data.delivery_info?.latest_date_formatted}`}
                        </span>
                      </div>
                    </div>

                    {/* Dates */}
                    <div>
                      <p
                        className="
                          mb-2
                          text-xs font-medium
                          text-neutral-500
                          dark:text-neutral-400
                        "
                      >
                        Select Delivery Date
                      </p>

                      <div className="flex gap-2 overflow-x-auto pb-1">
                        {deliveryData.data.dates?.map(
                          (date) => (
                            <button
                              key={date.value}
                              type="button"
                              onClick={() =>
                                setSelectedDate(
                                  date.value
                                )
                              }
                              className={`
                                shrink-0
                                rounded-lg
                                border
                                px-3 py-2
                                text-center
                                transition-all
                                focus:outline-none
                                focus-visible:ring-2
                                focus-visible:ring-red-500
                                focus-visible:ring-offset-2
                                dark:focus-visible:ring-offset-neutral-900
                                ${
                                  selectedDate ===
                                  date.value
                                    ? "border-red-500 bg-red-50 dark:border-red-500 dark:bg-red-950/30"
                                    : "border-neutral-200 hover:border-neutral-300 dark:border-neutral-700 dark:hover:border-neutral-600"
                                }
                              `}
                            >
                              <p
                                className={`
                                  text-xs font-medium
                                  ${
                                    selectedDate ===
                                    date.value
                                      ? "text-red-600 dark:text-red-400"
                                      : "text-neutral-600 dark:text-neutral-300"
                                  }
                                `}
                              >
                                {date.day_name}
                              </p>

                              <p
                                className={`
                                  text-[10px]
                                  ${
                                    selectedDate ===
                                    date.value
                                      ? "text-red-500 dark:text-red-400"
                                      : "text-neutral-400 dark:text-neutral-500"
                                  }
                                `}
                              >
                                {date.is_today
                                  ? "Today"
                                  : date.is_tomorrow
                                  ? "Tomorrow"
                                  : formatDate(
                                      date.value
                                    )}
                              </p>
                            </button>
                          )
                        )}
                      </div>
                    </div>

                    {/* Time Slots */}
                    <div>
                      <p
                        className="
                          mb-2
                          text-xs font-medium
                          text-neutral-500
                          dark:text-neutral-400
                        "
                      >
                        Select Time Slot
                      </p>

                      <div className="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        {deliveryData.data.time_slots?.map(
                          (slot) => (
                            <button
                              key={slot.value}
                              type="button"
                              onClick={() =>
                                setSelectedTime(
                                  slot.value
                                )
                              }
                              className={`
                                rounded-lg
                                border
                                p-2
                                text-center
                                transition-all
                                focus:outline-none
                                focus-visible:ring-2
                                focus-visible:ring-red-500
                                focus-visible:ring-offset-2
                                dark:focus-visible:ring-offset-neutral-900
                                ${
                                  selectedTime ===
                                  slot.value
                                    ? "border-red-500 bg-red-50 dark:border-red-500 dark:bg-red-950/30"
                                    : "border-neutral-200 hover:border-neutral-300 dark:border-neutral-700 dark:hover:border-neutral-600"
                                }
                              `}
                            >
                              <span
                                aria-hidden="true"
                                className="text-base"
                              >
                                {slot.icon}
                              </span>

                              <p
                                className={`
                                  text-xs font-medium
                                  ${
                                    selectedTime ===
                                    slot.value
                                      ? "text-red-600 dark:text-red-400"
                                      : "text-neutral-600 dark:text-neutral-300"
                                  }
                                `}
                              >
                                {slot.label}
                              </p>

                              <p
                                className="
                                  text-[9px]
                                  text-neutral-400
                                  dark:text-neutral-500
                                "
                              >
                                {slot.time_range}
                              </p>
                            </button>
                          )
                        )}
                      </div>
                    </div>
                  </>
                ) : (
                  <p
                    className="
                      text-sm
                      text-neutral-500
                      dark:text-neutral-400
                    "
                  >
                    No delivery options available
                  </p>
                )}
              </div>
            )}
          </div>

          {/* Payment Method */}
          <div
            className="
              rounded-2xl
              border border-neutral-200
              bg-white
              p-6
              shadow-sm
              dark:border-neutral-800
              dark:bg-neutral-900
              dark:shadow-black/20
            "
          >
            <h2
              className="
                mb-4
                text-base font-semibold
                text-neutral-800
                dark:text-neutral-100
              "
            >
              Payment Method
            </h2>

            <div className="space-y-3">
              {PAYMENT_METHODS.map((method) => {
                const Icon = method.icon;
                const isSelected =
                  selectedPayment === method.value;

                return (
                  <button
                    key={method.value}
                    type="button"
                    onClick={() =>
                      setSelectedPayment(
                        method.value
                      )
                    }
                    aria-pressed={isSelected}
                    className={`
                      flex w-full
                      items-start
                      gap-4
                      rounded-xl
                      border-2
                      p-4
                      text-left
                      transition-all
                      outline-none
                      focus-visible:ring-2
                      focus-visible:ring-red-500
                      focus-visible:ring-offset-2
                      dark:focus-visible:ring-offset-neutral-900
                      ${
                        isSelected
                          ? `${method.border} ${method.bg}`
                          : "border-neutral-200 hover:border-neutral-300 dark:border-neutral-700 dark:hover:border-neutral-600"
                      }
                    `}
                  >
                    <div
                      className={`
                        mt-0.5
                        rounded-full
                        p-2
                        ${
                          isSelected
                            ? method.bg
                            : "bg-neutral-100 dark:bg-neutral-800"
                        }
                      `}
                    >
                      <Icon
                        aria-hidden="true"
                        className={`
                          h-5 w-5
                          ${
                            isSelected
                              ? method.color
                              : "text-neutral-400 dark:text-neutral-500"
                          }
                        `}
                      />
                    </div>

                    <div className="flex-1">
                      <p
                        className={`
                          font-semibold
                          ${
                            isSelected
                              ? method.color
                              : "text-neutral-800 dark:text-neutral-100"
                          }
                        `}
                      >
                        {method.label}
                      </p>

                      <p
                        className="
                          text-sm
                          text-neutral-500
                          dark:text-neutral-400
                        "
                      >
                        {method.description}
                      </p>
                    </div>

                    {isSelected && (
                      <CheckCircle
                        aria-hidden="true"
                        className="
                          h-5 w-5
                          shrink-0
                          text-red-500
                          dark:text-red-400
                        "
                      />
                    )}
                  </button>
                );
              })}
            </div>
          </div>
        </div>

        {/* Right Column - 1/3 */}
        <div className="space-y-6">
          {/* Order Summary */}
          <div
            className="
              rounded-2xl
              border border-neutral-200
              bg-white
              p-6
              shadow-sm
              dark:border-neutral-800
              dark:bg-neutral-900
              dark:shadow-black/20
            "
          >
            <h2
              className="
                mb-4
                text-base font-semibold
                text-neutral-800
                dark:text-neutral-100
              "
            >
              Order Summary
            </h2>

            <div className="space-y-3">
              <div className="flex justify-between text-sm">
                <span
                  className="
                    text-neutral-500
                    dark:text-neutral-400
                  "
                >
                  Subtotal
                </span>

                <span
                  className="
                    text-neutral-700
                    dark:text-neutral-200
                  "
                >
                  ${formatPrice(order.subtotal)}
                </span>
              </div>

              {order.discount_amount > 0 && (
                <div className="flex justify-between text-sm text-green-600 dark:text-green-400">
                  <span>Discount</span>
                  <span>
                    -${formatPrice(
                      order.discount_amount
                    )}
                  </span>
                </div>
              )}

              {order.coupon_amount > 0 && (
                <div className="flex justify-between text-sm text-green-600 dark:text-green-400">
                  <span>Coupon</span>
                  <span>
                    -${formatPrice(
                      order.coupon_amount
                    )}
                  </span>
                </div>
              )}

              <div className="flex justify-between text-sm">
                <span
                  className="
                    text-neutral-500
                    dark:text-neutral-400
                  "
                >
                  Shipping
                </span>

                <span
                  className="
                    text-neutral-700
                    dark:text-neutral-200
                  "
                >
                  {order.shipping_cost > 0
                    ? `$${formatPrice(
                        order.shipping_cost
                      )}`
                    : "Free"}
                </span>
              </div>

              <div
                className="
                  border-t
                  border-neutral-200
                  pt-3
                  dark:border-neutral-800
                "
              >
                <div className="flex justify-between text-base font-bold">
                  <span
                    className="
                      text-neutral-800
                      dark:text-neutral-100
                    "
                  >
                    Total
                  </span>

                  <span
                    className="
                      text-red-600
                      dark:text-red-400
                    "
                  >
                    ${formatPrice(
                      order.total_amount
                    )}
                  </span>
                </div>
              </div>
            </div>
          </div>

          {/* Terms */}
          <div
            className="
              rounded-2xl
              border border-neutral-200
              bg-white
              p-6
              shadow-sm
              dark:border-neutral-800
              dark:bg-neutral-900
              dark:shadow-black/20
            "
          >
            <label className="flex items-start gap-3">
              <input
                type="checkbox"
                checked={termsAccepted}
                onChange={(e) =>
                  setTermsAccepted(
                    e.target.checked
                  )
                }
                className="
                  mt-0.5
                  h-4 w-4
                  rounded
                  border-neutral-300
                  text-red-600
                  accent-red-600
                  focus:ring-red-500
                  dark:border-neutral-600
                  dark:bg-neutral-800
                "
              />

              <span
                className="
                  text-sm
                  text-neutral-600
                  dark:text-neutral-300
                "
              >
                I agree to the{" "}
                <Link
                  href="/terms"
                  className="
                    text-red-600
                    hover:underline
                    dark:text-red-400
                  "
                >
                  Terms and Conditions
                </Link>{" "}
                and{" "}
                <Link
                  href="/privacy"
                  className="
                    text-red-600
                    hover:underline
                    dark:text-red-400
                  "
                >
                  Privacy Policy
                </Link>
              </span>
            </label>
          </div>

          {/* Pay Button */}
          <button
            type="button"
            onClick={handlePayment}
            disabled={
              isPaying ||
              !termsAccepted
            }
            className="
              w-full
              rounded-lg
              bg-red-600
              py-3
              text-sm
              font-bold
              text-white
              transition-all
              hover:bg-red-700
              active:scale-[0.99]
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              disabled:cursor-not-allowed
              disabled:opacity-50
              dark:focus-visible:ring-red-400
              dark:focus-visible:ring-offset-neutral-950
            "
          >
            {isPaying ? (
              <span className="flex items-center justify-center gap-2">
                <Loader2
                  aria-hidden="true"
                  className="h-4 w-4 animate-spin"
                />
                Processing...
              </span>
            ) : (
              `Pay $${formatPrice(
                order.total_amount
              )}`
            )}
          </button>
        </div>
      </div>
    </div>
  );
}

export default CheckoutPage;