"use client";

import React, { useMemo } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import { 
  Clock, 
  Package, 
  Truck, 
  CheckCircle, 
  XCircle, 
  Loader2,
  Eye,
  Trash2,
  CreditCard,
  ArrowLeft
} from "lucide-react";
import { formatPrice } from "@/core/utils/formatPrice";
import { formatDate } from "@/core/utils/formatDate";
import { useGetOrders } from "@/core/services/queries";
import { useCancelOrder } from "@/core/services/mutations";
import toast from "react-hot-toast";

// ============================================================
// STATUS CONFIG
// ============================================================

const STATUS_CONFIG = {
  pending: { label: "Pending Payment", color: "text-yellow-600", bg: "bg-yellow-50", icon: Clock },
  paid: { label: "Paid", color: "text-blue-600", bg: "bg-blue-50", icon: CreditCard },
  processing: { label: "Processing", color: "text-purple-600", bg: "bg-purple-50", icon: Package },
  shipped: { label: "Shipped", color: "text-indigo-600", bg: "bg-indigo-50", icon: Truck },
  delivered: { label: "Delivered", color: "text-green-600", bg: "bg-green-50", icon: CheckCircle },
  canceled: { label: "Canceled", color: "text-red-600", bg: "bg-red-50", icon: XCircle },
};

const STATUS_OPTIONS = [
  { value: "all", label: "All Orders" },
  { value: "pending", label: "Pending Payment" },
  { value: "processing", label: "Processing" },
  { value: "shipped", label: "Shipped" },
  { value: "delivered", label: "Delivered" },
  { value: "canceled", label: "Canceled" },
];

// ============================================================
// MAIN COMPONENT
// ============================================================

function OrdersPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const statusFilter = searchParams.get("status") || "all";

  const { data: ordersData, isLoading, isError, error } = useGetOrders();
  const { mutate: cancelOrder, isPending: isCancelling } = useCancelOrder();

  // Filter orders by status
  const filteredOrders = useMemo(() => {
    if (!ordersData?.data) return [];
    
    const orders = Array.isArray(ordersData.data) ? ordersData.data : [];
    
    if (statusFilter === "all") return orders;
    
    return orders.filter((order) => {
      const orderStatus = order.status?.value || order.status;
      return orderStatus === statusFilter;
    });
  }, [ordersData, statusFilter]);

  const handleStatusChange = (status) => {
    const params = new URLSearchParams(searchParams);
    if (status === "all") {
      params.delete("status");
    } else {
      params.set("status", status);
    }
    router.push(`/profile/orders?${params.toString()}`);
  };

  const handleCancelOrder = (orderId) => {
    if (!confirm("Are you sure you want to cancel this order?")) return;
    cancelOrder({ orderId });
  };

  const handleViewOrder = (orderId) => {
    router.push(`/profile/orders/${orderId}`);
  };

  // Loading state
  if (isLoading) {
    return (
      <div className="container mx-auto max-w-6xl px-4 py-8">
        <div className="flex items-center justify-center py-12">
          <Loader2 className="h-8 w-8 animate-spin text-red-500" />
        </div>
      </div>
    );
  }

  // Error state
  if (isError) {
    return (
      <div className="container mx-auto max-w-6xl px-4 py-8">
        <div className="rounded-2xl border border-neutral-200 bg-white p-6 text-center">
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
      </div>
    );
  }

  // Empty state
  if (!filteredOrders || filteredOrders.length === 0) {
    return (
      <div className="container mx-auto max-w-6xl px-4 py-8">
        <div className="mb-6">
          <h1 className="text-2xl font-bold text-neutral-800">My Orders</h1>
        </div>

        {/* Status Filter */}
        <div className="mb-6 flex flex-wrap gap-2">
          {STATUS_OPTIONS.map((status) => (
            <button
              key={status.value}
              onClick={() => handleStatusChange(status.value)}
              className={`rounded-full px-4 py-1.5 text-sm font-medium transition-colors ${
                statusFilter === status.value
                  ? "bg-red-600 text-white"
                  : "bg-neutral-100 text-neutral-600 hover:bg-neutral-200"
              }`}
            >
              {status.label}
            </button>
          ))}
        </div>

        <div className="rounded-2xl border border-neutral-200 bg-white p-12 text-center">
          <div className="text-5xl mb-4">📦</div>
          <h3 className="text-lg font-bold text-neutral-800">No orders found</h3>
          <p className="mt-1 text-sm text-neutral-500">
            {statusFilter === "all" 
              ? "You haven't placed any orders yet"
              : `No ${STATUS_CONFIG[statusFilter]?.label || ""} orders found`}
          </p>
          <Link
            href="/"
            className="mt-4 inline-block rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700"
          >
            Start Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="container mx-auto max-w-6xl px-4 py-8">
      {/* Header */}
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-neutral-800">My Orders</h1>
        <p className="text-sm text-neutral-500">
          {filteredOrders.length} {filteredOrders.length === 1 ? "order" : "orders"}
        </p>
      </div>

      {/* Status Filter */}
      <div className="mb-6 flex flex-wrap gap-2">
        {STATUS_OPTIONS.map((status) => (
          <button
            key={status.value}
            onClick={() => handleStatusChange(status.value)}
            className={`rounded-full px-4 py-1.5 text-sm font-medium transition-colors ${
              statusFilter === status.value
                ? "bg-red-600 text-white"
                : "bg-neutral-100 text-neutral-600 hover:bg-neutral-200"
            }`}
          >
            {status.label}
          </button>
        ))}
      </div>

      {/* Orders List */}
      <div className="space-y-4">
        {filteredOrders.map((order) => {
          const orderStatus = order.status?.value || order.status;
          const statusConfig = STATUS_CONFIG[orderStatus] || STATUS_CONFIG.pending;
          const StatusIcon = statusConfig.icon;

          return (
            <div
              key={order.id}
              className="rounded-2xl border border-neutral-200 bg-white p-4 transition-shadow hover:shadow-md sm:p-6"
            >
              {/* Order Header */}
              <div className="flex flex-wrap items-start justify-between gap-3">
                <div>
                  <Link
                    href={`/profile/orders/${order.id}`}
                    className="text-sm font-semibold text-neutral-800 hover:text-red-600 transition-colors"
                  >
                    Order #{order.order_number}
                  </Link>
                  <p className="text-xs text-neutral-400 mt-0.5">
                    {formatDate(order.created_at)}
                  </p>
                </div>

                <div className="flex items-center gap-2">
                  <span className={`inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium ${statusConfig.bg} ${statusConfig.color}`}>
                    <StatusIcon className="h-3.5 w-3.5" />
                    {statusConfig.label}
                  </span>
                </div>
              </div>

              {/* Order Items Preview */}
              <div className="mt-3 flex flex-wrap items-center gap-2">
                {order.items?.slice(0, 3).map((item, index) => (
                  <div key={index} className="flex items-center gap-1 text-sm text-neutral-600">
                    <span>{item.product_title}</span>
                    {index < 2 && <span className="text-neutral-300">•</span>}
                  </div>
                ))}
                {order.items?.length > 3 && (
                  <span className="text-xs text-neutral-400">
                    +{order.items.length - 3} more
                  </span>
                )}
              </div>

              {/* Order Footer */}
              <div className="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-neutral-100 pt-4">
                <div>
                  <span className="text-xs text-neutral-500">Total</span>
                  <p className="text-lg font-bold text-neutral-800">
                    ${formatPrice(order.total_amount)}
                  </p>
                </div>

                <div className="flex flex-wrap gap-2">
                  {orderStatus === "pending" && (
                    <button
                      onClick={() => router.push(`/checkout?order=${order.id}`)}
                      className="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700"
                    >
                      Pay Now
                    </button>
                  )}

                  {order.can_be_canceled && (
                    <button
                      onClick={() => handleCancelOrder(order.id)}
                      disabled={isCancelling}
                      className="flex items-center gap-1.5 rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 disabled:opacity-50"
                    >
                      <Trash2 className="h-4 w-4" />
                      Cancel
                    </button>
                  )}

                  <button
                    onClick={() => handleViewOrder(order.id)}
                    className="flex items-center gap-1.5 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-50"
                  >
                    <Eye className="h-4 w-4" />
                    View Details
                  </button>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

export default OrdersPage;