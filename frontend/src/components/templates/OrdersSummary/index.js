"use client";

import React, { useMemo } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import { IoChevronBack } from "react-icons/io5";
import {
  Clock,
  Package,
  Truck,
  CheckCircle,
  XCircle,
  Loader2,
} from "lucide-react";

import { useGetOrders } from "@/core/services/queries";

const STATUS_CONFIG = [
  {
    id: "pending",
    label: "Pending Payment",
    href: "/profile/orders?status=pending",
    icon: Clock,
    iconColor: "text-yellow-500 dark:text-yellow-400",
    bgColor: "bg-yellow-50 dark:bg-yellow-950/30",
  },
  {
    id: "processing",
    label: "Processing",
    href: "/profile/orders?status=processing",
    icon: Package,
    iconColor: "text-purple-500 dark:text-purple-400",
    bgColor: "bg-purple-50 dark:bg-purple-950/30",
  },
  {
    id: "shipped",
    label: "Shipped",
    href: "/profile/orders?status=shipped",
    icon: Truck,
    iconColor: "text-indigo-500 dark:text-indigo-400",
    bgColor: "bg-indigo-50 dark:bg-indigo-950/30",
  },
  {
    id: "delivered",
    label: "Delivered",
    href: "/profile/orders?status=delivered",
    icon: CheckCircle,
    iconColor: "text-green-500 dark:text-green-400",
    bgColor: "bg-green-50 dark:bg-green-950/30",
  },
  {
    id: "canceled",
    label: "Canceled",
    href: "/profile/orders?status=canceled",
    icon: XCircle,
    iconColor: "text-red-500 dark:text-red-400",
    bgColor: "bg-red-50 dark:bg-red-950/30",
  },
];

function OrderSummary() {
  const router = useRouter();

  const {
    data: ordersData,
    isLoading,
    isError,
    error,
  } = useGetOrders();

  const statusCounts = useMemo(() => {
    const counts = {
      pending: 0,
      processing: 0,
      shipped: 0,
      delivered: 0,
      canceled: 0,
    };

    const orders = Array.isArray(ordersData?.data)
      ? ordersData.data
      : Array.isArray(ordersData?.data?.data)
        ? ordersData.data.data
        : [];

    orders.forEach((order) => {
      const status = order?.status?.value || order?.status;

      if (Object.prototype.hasOwnProperty.call(counts, status)) {
        counts[status] += 1;
      }
    });

    return counts;
  }, [ordersData]);

  const totalOrders = useMemo(
    () => Object.values(statusCounts).reduce((sum, count) => sum + count, 0),
    [statusCounts]
  );

  const handleViewAll = () => {
    router.push("/profile/orders");
  };

  if (isLoading) {
    return (
      <div
        className="
          w-full max-w-[852px]
          overflow-hidden rounded-2xl
          border border-neutral-200
          bg-white p-8
          shadow-sm
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/20
        "
      >
        <div className="flex flex-col items-center justify-center gap-3">
          <Loader2 className="h-8 w-8 animate-spin text-red-500 dark:text-red-400" />
          <span className="text-sm text-neutral-500 dark:text-neutral-400">
            Loading orders...
          </span>
        </div>
      </div>
    );
  }

  if (isError) {
    return (
      <div
        className="
          w-full max-w-[852px]
          overflow-hidden rounded-2xl
          border border-neutral-200
          bg-white p-6
          text-center
          shadow-sm
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/20
        "
      >
        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 dark:bg-red-950/30">
          <XCircle className="h-6 w-6 text-red-500 dark:text-red-400" />
        </div>

        <p className="mt-3 text-sm font-medium text-red-500 dark:text-red-400">
          {error?.response?.data?.message || "Failed to load orders"}
        </p>

        <button
          type="button"
          onClick={() => window.location.reload()}
          className="
            mt-3 rounded-lg px-4 py-2
            text-sm font-semibold
            text-red-600
            transition-colors
            hover:bg-red-50
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500
            dark:text-red-400
            dark:hover:bg-red-950/30
          "
        >
          Try Again
        </button>
      </div>
    );
  }

  if (totalOrders === 0) {
    return (
      <div
        className="
          w-full max-w-[852px]
          overflow-hidden rounded-2xl
          border border-neutral-200
          bg-white p-6
          text-center
          shadow-sm
          dark:border-neutral-800
          dark:bg-neutral-900
          dark:shadow-black/20
        "
      >
        <div className="flex flex-col items-center gap-3">
          <div
            className="
              flex h-14 w-14 items-center justify-center
              rounded-full
              bg-neutral-100
              text-3xl
              dark:bg-neutral-800
            "
          >
            🛒
          </div>

          <h3 className="text-base font-bold text-neutral-900 dark:text-neutral-100">
            No orders yet
          </h3>

          <p className="text-sm text-neutral-500 dark:text-neutral-400">
            Start shopping and place your first order
          </p>

          <Link
            href="/"
            className="
              mt-2 inline-flex min-h-10
              items-center justify-center
              rounded-xl
              bg-red-600 px-6 py-2
              text-sm font-semibold text-white
              transition-all duration-200
              hover:bg-red-700
              active:scale-[0.98]
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500
              focus-visible:ring-offset-2
              dark:bg-red-500
              dark:hover:bg-red-400
              dark:focus-visible:ring-offset-neutral-900
            "
          >
            Start Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <section
      className="
        w-full max-w-[852px]
        overflow-hidden rounded-2xl
        border border-neutral-200
        bg-white
        shadow-sm
        transition-shadow duration-200
        hover:shadow-md
        dark:border-neutral-800
        dark:bg-neutral-900
        dark:shadow-black/20
        dark:hover:shadow-black/30
      "
      aria-label="My Orders"
    >
      {/* Header */}
      <div
        className="
          flex items-center justify-between
          px-4 pt-4
          sm:px-6 sm:pt-6
        "
      >
        <div className="relative py-1">
          <h2
            className="
              text-base font-bold
              text-neutral-900
              sm:text-lg
              dark:text-neutral-100
            "
          >
            My Orders
          </h2>

          <div className="absolute -bottom-[10px] left-0 right-0 h-[2px] rounded-t-sm bg-red-500 dark:bg-red-400" />
        </div>

        <button
          type="button"
          onClick={handleViewAll}
          className="
            group flex items-center gap-1
            rounded-lg px-2 py-1
            text-sm font-medium
            text-neutral-600
            transition-colors
            hover:text-red-600
            focus:outline-none
            focus-visible:ring-2
            focus-visible:ring-red-500
            dark:text-neutral-400
            dark:hover:text-red-400
          "
        >
          <span>View All</span>

          <IoChevronBack
            className="
              text-xs
              transition-transform duration-200
              group-hover:-translate-x-0.5
            "
          />
        </button>
      </div>

      {/* Order Status Cards */}
      <div
        className="
          grid grid-cols-2
          gap-2
          px-3 py-5
          sm:grid-cols-3
          sm:gap-3 sm:px-4 sm:py-6
          lg:grid-cols-5
        "
      >
        {STATUS_CONFIG.map((status) => {
          const count = statusCounts[status.id] || 0;
          const StatusIcon = status.icon;

          return (
            <Link
              key={status.id}
              href={status.href}
              aria-label={`${status.label}: ${count} ${
                count === 1 ? "order" : "orders"
              }`}
              className={`
                group flex min-w-0 flex-col
                items-center justify-center
                gap-2 rounded-xl
                border border-transparent
                p-3
                transition-all duration-200
                focus:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500
                ${
                  count > 0
                    ? "hover:border-neutral-200 hover:bg-neutral-50 dark:hover:border-neutral-700 dark:hover:bg-neutral-800"
                    : "opacity-60 hover:opacity-80"
                }
                dark:border-transparent
              `}
            >
              {/* Icon */}
              <div
                className={`
                  relative flex
                  h-12 w-12
                  items-center justify-center
                  rounded-full
                  transition-transform duration-300
                  group-hover:scale-105
                  sm:h-14 sm:w-14
                  md:h-16 md:w-16
                  ${status.bgColor}
                `}
              >
                <StatusIcon
                  className={`
                    h-6 w-6
                    sm:h-7 sm:w-7
                    ${status.iconColor}
                  `}
                  strokeWidth={2}
                />

                {count > 0 && (
                  <span
                    className="
                      absolute -right-1 -top-1
                      flex h-5 min-w-5
                      items-center justify-center
                      rounded-full
                      border-2 border-white
                      bg-red-500 px-1
                      text-[9px] font-bold text-white
                      dark:border-neutral-900
                    "
                  >
                    {count > 99 ? "99+" : count}
                  </span>
                )}
              </div>

              {/* Label & Count */}
              <div className="flex min-w-0 flex-col items-center justify-center text-center">
                <span
                  className="
                    text-xs font-bold
                    text-neutral-800
                    sm:text-sm
                    md:text-base
                    dark:text-neutral-100
                  "
                >
                  {count.toLocaleString()}{" "}
                  {count === 1 ? "Order" : "Orders"}
                </span>

                <span
                  className="
                    mt-0.5 line-clamp-2
                    text-[10px] font-normal
                    leading-4
                    text-neutral-400
                    sm:text-xs
                    dark:text-neutral-500
                  "
                >
                  {status.label}
                </span>
              </div>
            </Link>
          );
        })}
      </div>
    </section>
  );
}

export default OrderSummary;