// src/app/profile/orders/[id]/page.js

"use client";

import React from "react";
import { useParams, useRouter } from "next/navigation";
import Link from "next/link";
import Image from "next/image";
import { 
  ArrowLeft, 
  Clock, 
  Package, 
  Truck, 
  CheckCircle, 
  XCircle,
  CreditCard,
  MapPin,
  Loader2,
  Trash2,
  ShoppingBag,
  Copy
} from "lucide-react";
import toast from "react-hot-toast";

import { useGetOrder } from "@/core/services/queries";
import { useCancelOrder, usePayOrder } from "@/core/services/mutations";
import { formatPrice } from "@/core/utils/formatPrice";
import { formatDate } from "@/core/utils/formatDate";

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

// ============================================================
// ORDER TIMELINE
// ============================================================

function OrderTimeline({ history }) {
  if (!history || history.length === 0) return null;

  return (
    <div className="relative">
      {history.map((item, index) => {
        const statusConfig = STATUS_CONFIG[item.to_status?.value] || STATUS_CONFIG.pending;
        const StatusIcon = statusConfig.icon;

        return (
          <div key={item.id} className="flex gap-4 pb-6 last:pb-0">
            {/* Line */}
            {index < history.length - 1 && (
              <div className="absolute left-5 top-8 h-[calc(100%-32px)] w-0.5 bg-neutral-200" />
            )}

            {/* Dot */}
            <div className={`relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full ${statusConfig.bg} ${statusConfig.color}`}>
              <StatusIcon className="h-5 w-5" />
            </div>

            {/* Content */}
            <div className="min-w-0 flex-1">
              <div className="flex flex-wrap items-start justify-between gap-2">
                <div>
                  <p className="text-sm font-semibold text-neutral-900">
                    {item.to_status?.label || statusConfig.label}
                  </p>
                  {item.note && (
                    <p className="mt-0.5 text-xs text-neutral-500">{item.note}</p>
                  )}
                </div>
                <time className="shrink-0 text-xs text-neutral-400">
                  {formatDate(item.created_at)}
                </time>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}

// ============================================================
// MAIN COMPONENT
// ============================================================

function OrderDetailPage() {
  const params = useParams();
  const router = useRouter();
  const orderId = params?.id;

  const { data: orderData, isLoading, isError, error } = useGetOrder(orderId);
  const { mutate: cancelOrder, isPending: isCancelling } = useCancelOrder();
  const { mutate: payOrder, isPending: isPaying } = usePayOrder();

  const order = orderData?.data;

  const handleCancelOrder = () => {
    if (!confirm("Are you sure you want to cancel this order?")) return;
    cancelOrder({ orderId });
  };

  const handlePayOrder = () => {
    payOrder(orderId);
  };

  const handleCopyOrderNumber = () => {
    navigator.clipboard.writeText(order?.order_number || "");
    toast.success("Order number copied!");
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
  if (isError || !order) {
    return (
      <div className="container mx-auto max-w-6xl px-4 py-8">
        <div className="rounded-2xl border border-neutral-200 bg-white p-6 text-center">
          <p className="text-sm text-red-500">
            {error?.response?.data?.message || "Order not found"}
          </p>
          <Link
            href="/profile/orders"
            className="mt-4 inline-block rounded-lg border border-neutral-300 px-6 py-2 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-50"
          >
            Back to Orders
          </Link>
        </div>
      </div>
    );
  }

  const orderStatus = order.status?.value || order.status;
  const statusConfig = STATUS_CONFIG[orderStatus] || STATUS_CONFIG.pending;
  const StatusIcon = statusConfig.icon;

  return (
    <div className="container mx-auto max-w-6xl px-4 py-8">
      {/* Back Button */}
      <Link
        href="/profile/orders"
        className="mb-6 inline-flex items-center gap-2 text-sm font-medium text-neutral-600 transition-colors hover:text-neutral-900"
      >
        <ArrowLeft className="h-4 w-4" />
        Back to Orders
      </Link>

      {/* Order Header */}
      <div className="rounded-2xl border border-neutral-200 bg-white p-6">
        <div className="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div className="flex items-center gap-2">
              <h1 className="text-2xl font-bold text-neutral-800">
                Order #{order.order_number}
              </h1>
              <button
                onClick={handleCopyOrderNumber}
                className="rounded-lg p-1.5 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-600"
              >
                <Copy className="h-4 w-4" />
              </button>
            </div>
            <p className="text-sm text-neutral-500 mt-1">
              Placed on {formatDate(order.created_at)}
            </p>
          </div>

          <div className="flex items-center gap-3">
            <span className={`inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-medium ${statusConfig.bg} ${statusConfig.color}`}>
              <StatusIcon className="h-4 w-4" />
              {statusConfig.label}
            </span>
          </div>
        </div>

        {/* Quick Stats */}
        <div className="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
          <div className="rounded-lg bg-neutral-50 p-3">
            <p className="text-xs text-neutral-500">Total</p>
            <p className="text-lg font-bold text-neutral-800">
              ${formatPrice(order.total_amount)}
            </p>
          </div>
          <div className="rounded-lg bg-neutral-50 p-3">
            <p className="text-xs text-neutral-500">Items</p>
            <p className="text-lg font-bold text-neutral-800">
              {order.items_count || order.items?.length || 0}
            </p>
          </div>
          <div className="rounded-lg bg-neutral-50 p-3">
            <p className="text-xs text-neutral-500">Payment</p>
            <p className="text-sm font-medium text-neutral-800">
              {order.payment_method?.label || "-"}
            </p>
          </div>
          <div className="rounded-lg bg-neutral-50 p-3">
            <p className="text-xs text-neutral-500">Payment Status</p>
            <span className={`inline-block text-sm font-medium ${
              order.payment_status?.value === "paid" 
                ? "text-green-600" 
                : "text-yellow-600"
            }`}>
              {order.payment_status?.label || "-"}
            </span>
          </div>
        </div>
      </div>

      {/* Order Content */}
      <div className="mt-6 grid gap-6 lg:grid-cols-3">
        {/* Left Column - Order Items */}
        <div className="lg:col-span-2 space-y-6">
          {/* Order Items */}
          <div className="rounded-2xl border border-neutral-200 bg-white p-6">
            <h2 className="mb-4 text-base font-semibold text-neutral-800">
              Order Items ({order.items?.length || 0})
            </h2>

            <div className="space-y-4">
              {order.items?.map((item) => (
                <div key={item.id} className="flex gap-4 border-b border-neutral-100 pb-4 last:border-0 last:pb-0">
                  {/* Image */}
                  <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-neutral-100">
                    {item.product_image ? (
                      <Image
                        src={item.product_image}
                        alt={item.product_title}
                        fill
                        className="object-cover"
                      />
                    ) : (
                      <div className="flex h-full items-center justify-center text-2xl text-neutral-300">
                        📱
                      </div>
                    )}
                  </div>

                  {/* Info */}
                  <div className="min-w-0 flex-1">
                    <Link
                      href={`/product/${item.product_variant_id}`}
                      className="text-sm font-semibold text-neutral-800 hover:text-red-600 transition-colors line-clamp-2"
                    >
                      {item.product_title}
                    </Link>
                    <p className="mt-0.5 text-xs text-neutral-500">
                      SKU: {item.product_sku} • Qty: {item.quantity}
                    </p>
                    <div className="mt-1 flex flex-wrap gap-1">
                      {item.variant_attributes?.slice(0, 3).map((attr, idx) => (
                        <span
                          key={idx}
                          className="rounded-full bg-neutral-100 px-2 py-0.5 text-[10px] text-neutral-600"
                        >
                          {attr.attribute}: {attr.value}
                        </span>
                      ))}
                      {item.variant_attributes?.length > 3 && (
                        <span className="text-[10px] text-neutral-400">
                          +{item.variant_attributes.length - 3} more
                        </span>
                      )}
                    </div>
                  </div>

                  {/* Price */}
                  <div className="shrink-0 text-right">
                    <p className="text-sm font-bold text-neutral-800">
                      ${formatPrice(item.final_price)}
                    </p>
                    {item.discount_percent > 0 && (
                      <>
                        <del className="text-xs text-neutral-400">
                          ${formatPrice(item.base_price)}
                        </del>
                        <span className="ml-1 text-xs font-medium text-red-500">
                          -{item.discount_percent}%
                        </span>
                      </>
                    )}
                    <p className="mt-0.5 text-xs text-neutral-500">
                      Total: ${formatPrice(item.total)}
                    </p>
                  </div>
                </div>
              ))}
            </div>

            {/* Order Summary */}
            <div className="mt-4 border-t border-neutral-200 pt-4">
              <div className="space-y-1 text-sm">
                <div className="flex justify-between">
                  <span className="text-neutral-500">Subtotal</span>
                  <span className="text-neutral-700">
                    ${formatPrice(order.subtotal)}
                  </span>
                </div>
                {order.discount_amount > 0 && (
                  <div className="flex justify-between text-green-600">
                    <span>Discount</span>
                    <span>-${formatPrice(order.discount_amount)}</span>
                  </div>
                )}
                {order.coupon_amount > 0 && (
                  <div className="flex justify-between text-green-600">
                    <span>Coupon</span>
                    <span>-${formatPrice(order.coupon_amount)}</span>
                  </div>
                )}
                {order.shipping_cost > 0 && (
                  <div className="flex justify-between">
                    <span className="text-neutral-500">Shipping</span>
                    <span className="text-neutral-700">
                      ${formatPrice(order.shipping_cost)}
                    </span>
                  </div>
                )}
                <div className="flex justify-between border-t border-neutral-200 pt-2 text-base font-bold">
                  <span className="text-neutral-800">Total</span>
                  <span className="text-red-600">
                    ${formatPrice(order.total_amount)}
                  </span>
                </div>
              </div>
            </div>
          </div>

          {/* Address */}
          {order.address && (
            <div className="rounded-2xl border border-neutral-200 bg-white p-6">
              <h2 className="mb-4 text-base font-semibold text-neutral-800">
                Shipping Address
              </h2>
              <div className="flex items-start gap-3">
                <MapPin className="mt-0.5 h-5 w-5 shrink-0 text-neutral-400" />
                <div>
                  <div className="flex items-center gap-2">
                    <h4 className="font-semibold text-neutral-800">
                      {order.address.title}
                    </h4>
                    {order.address.is_default && (
                      <span className="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">
                        Default
                      </span>
                    )}
                  </div>
                  <p className="mt-1 text-sm text-neutral-600">
                    {order.address.receiver_name} • {order.address.receiver_mobile}
                  </p>
                  <p className="mt-0.5 text-sm text-neutral-500">
                    {order.address.address}
                    {order.address.unit && `, Unit ${order.address.unit}`}
                  </p>
                  <p className="text-sm text-neutral-500">
                    {order.address.city?.name}, {order.address.province?.name}
                  </p>
                  <p className="mt-0.5 text-xs text-neutral-400">
                    Postal Code: {order.address.postal_code}
                  </p>
                </div>
              </div>
            </div>
          )}
        </div>

        {/* Right Column */}
        <div className="space-y-6">
          {/* Actions */}
          <div className="rounded-2xl border border-neutral-200 bg-white p-6">
            <h2 className="mb-4 text-base font-semibold text-neutral-800">
              Actions
            </h2>
            <div className="space-y-2">
              {orderStatus === "pending" && (
                <button
                  onClick={handlePayOrder}
                  disabled={isPaying}
                  className="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-red-700 disabled:opacity-50"
                >
                  {isPaying ? "Processing..." : "Pay Now"}
                </button>
              )}

              {order.can_be_canceled && (
                <button
                  onClick={handleCancelOrder}
                  disabled={isCancelling}
                  className="w-full rounded-lg border border-red-200 px-4 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 disabled:opacity-50"
                >
                  {isCancelling ? "Cancelling..." : "Cancel Order"}
                </button>
              )}

              <Link
                href={`/checkout?reorder=${order.id}`}
                className="block w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-center text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-50"
              >
                <ShoppingBag className="inline h-4 w-4 mr-2" />
                Reorder
              </Link>
            </div>
          </div>

          {/* Status Timeline */}
          <div className="rounded-2xl border border-neutral-200 bg-white p-6">
            <h2 className="mb-4 text-base font-semibold text-neutral-800">
              Order Status
            </h2>
            <OrderTimeline history={order.status_history} />
          </div>

          {/* Customer Note */}
          {order.customer_note && (
            <div className="rounded-2xl border border-neutral-200 bg-white p-6">
              <h2 className="mb-3 text-base font-semibold text-neutral-800">
                Customer Note
              </h2>
              <p className="rounded-lg bg-neutral-50 p-3 text-sm text-neutral-600">
                {order.customer_note}
              </p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

export default OrderDetailPage;