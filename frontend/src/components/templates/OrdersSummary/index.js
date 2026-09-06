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
  Loader2 
} from "lucide-react";

import { useGetOrders } from "@/core/services/queries";

// ============================================================
// STATUS CONFIG
// ============================================================

const STATUS_CONFIG = [
  {
    id: "pending",
    label: "Pending Payment",
    href: "/profile/orders?status=pending",
    icon: Clock,
    iconColor: "text-yellow-500",
    bgColor: "bg-yellow-50",
  },
  {
    id: "processing",
    label: "Processing",
    href: "/profile/orders?status=processing",
    icon: Package,
    iconColor: "text-purple-500",
    bgColor: "bg-purple-50",
  },
  {
    id: "shipped",
    label: "Shipped",
    href: "/profile/orders?status=shipped",
    icon: Truck,
    iconColor: "text-indigo-500",
    bgColor: "bg-indigo-50",
  },
  {
    id: "delivered",
    label: "Delivered",
    href: "/profile/orders?status=delivered",
    icon: CheckCircle,
    iconColor: "text-green-500",
    bgColor: "bg-green-50",
  },
  {
    id: "canceled",
    label: "Canceled",
    href: "/profile/orders?status=canceled",
    icon: XCircle,
    iconColor: "text-red-500",
    bgColor: "bg-red-50",
  },
];

// ============================================================
// MAIN COMPONENT
// ============================================================

function OrderSummary() {
  const router = useRouter();

  // Get orders from API
  const {
    data: ordersData,
    isLoading,
    isError,
    error,
  } = useGetOrders();

  // Calculate order counts by status from the orders data
  const statusCounts = useMemo(() => {
    const counts = {
      pending: 0,
      processing: 0,
      shipped: 0,
      delivered: 0,
      canceled: 0,
    };

    if (ordersData?.data && Array.isArray(ordersData.data)) {
      ordersData.data.forEach((order) => {
        const status = order.status?.value || order.status;
        if (counts.hasOwnProperty(status)) {
          counts[status] = (counts[status] || 0) + 1;
        }
      });
    }

    return counts;
  }, [ordersData]);

  // Calculate total orders
  const totalOrders = useMemo(() => {
    return Object.values(statusCounts).reduce((sum, count) => sum + count, 0);
  }, [statusCounts]);

  const handleViewAll = () => {
    router.push("/profile/orders");
  };

  // Loading state
  if (isLoading) {
    return (
      <div className="w-full max-w-[852px] border border-solid border-neutral-200 rounded-lg bg-white p-8">
        <div className="flex items-center justify-center">
          <Loader2 className="h-8 w-8 animate-spin text-red-500" />
        </div>
      </div>
    );
  }

  // Error state
  if (isError) {
    return (
      <div className="w-full max-w-[852px] border border-solid border-neutral-200 rounded-lg bg-white p-6 text-center">
        <p className="text-sm text-red-500">
          {error?.response?.data?.message || "Failed to load orders"}
        </p>
        <button
          onClick={() => window.location.reload()}
          className="mt-2 text-sm text-red-600 hover:underline"
        >
          Try Again
        </button>
      </div>
    );
  }

  // Empty state
  if (totalOrders === 0) {
    return (
      <div className="w-full max-w-[852px] border border-solid border-neutral-200 rounded-lg bg-white p-6 text-center">
        <div className="flex flex-col items-center gap-3">
          <div className="text-5xl">🛒</div>
          <h3 className="text-base font-bold text-neutral-800">
            No orders yet
          </h3>
          <p className="text-sm text-neutral-500">
            Start shopping and place your first order
          </p>
          <Link
            href="/"
            className="mt-2 rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700"
          >
            Start Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="w-full max-w-[852px] border border-solid border-neutral-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
      {/* Header */}
      <div className="flex justify-between items-center px-4 sm:px-6 pt-4 sm:pt-6">
        <div className="relative py-1">
          <h2 className="text-base sm:text-lg font-bold text-neutral-800">
            My Orders
          </h2>
          <div className="absolute -bottom-[10px] right-0 left-0 h-[2px] bg-red-500 rounded-t-sm" />
        </div>

        <button
          onClick={handleViewAll}
          className="flex items-center gap-0.5 text-sm font-medium text-neutral-600 hover:text-red-600 transition-colors group"
        >
          <span>View All</span>
          <IoChevronBack className="text-xs transition-transform group-hover:-translate-x-0.5" />
        </button>
      </div>

      {/* Order Status Cards */}
      <div className="flex flex-wrap items-center justify-evenly gap-2 py-4 sm:py-6 w-full px-2 sm:px-4">
        {STATUS_CONFIG.map((status) => {
          const count = statusCounts[status.id] || 0;

          return (
            <Link
              key={status.id}
              href={status.href}
              className={`
                flex flex-col sm:flex-row items-center gap-1 sm:gap-3 
                p-2 sm:p-3 rounded-xl transition-all flex-1 
                min-w-[60px] sm:min-w-[80px] justify-center group 
                border-2 border-transparent hover:border-neutral-200
                ${count > 0 ? "hover:bg-neutral-50" : "opacity-50 hover:opacity-70"}
              `}
            >
              {/* Icon */}
              <div className={`
                relative w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 
                flex items-center justify-center rounded-full
                ${status.bgColor} transition-transform duration-300 group-hover:scale-105
              `}>
                <status.icon className={`w-6 h-6 sm:w-7 sm:h-7 ${status.iconColor}`} />
              </div>

              {/* Label & Count */}
              <div className="flex flex-col items-center sm:items-start justify-center text-center sm:text-left">
                <span className="text-sm md:text-base font-bold text-neutral-800">
                  {count.toLocaleString()} {count === 1 ? "Order" : "Orders"}
                </span>
                <span className="text-[10px] sm:text-xs text-neutral-400 font-light mt-0.5">
                  {status.label}
                </span>
              </div>
            </Link>
          );
        })}
      </div>
    </div>
  );
}

export default OrderSummary;