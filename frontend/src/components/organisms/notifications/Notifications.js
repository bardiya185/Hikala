"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import { ArrowLeft, Bell, X } from "lucide-react";

import {
  useDeleteNotification,
  useMarkNotificationRead,
} from "@/core/services/mutations";
import { useGetNotifications } from "@/core/services/queries";
import { formatPersianDate } from "@/core/utils/formatDate";
import { getCookie } from "@/core/utils/cookie";

/* ============================================================
   HELPERS
============================================================ */

const FA_DIGITS = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];

const toFaDigits = (value) =>
  String(value).replace(/[0-9]/g, (digit) => FA_DIGITS[Number(digit)]);

/**
 * Persian relative time ("۵ دقیقه پیش") that falls back to an
 * absolute Jalali-friendly Persian date for older notifications.
 */
function formatNotificationTime(date) {
  if (!date) return "";

  const parsed = new Date(date);

  if (Number.isNaN(parsed.getTime())) return "";

  const diffInSeconds = Math.floor((Date.now() - parsed.getTime()) / 1000);

  if (diffInSeconds < 60) return "همین حالا";

  const minutes = Math.floor(diffInSeconds / 60);
  if (minutes < 60) return `${toFaDigits(minutes)} دقیقه پیش`;

  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${toFaDigits(hours)} ساعت پیش`;

  const days = Math.floor(hours / 24);
  if (days < 7) return `${toFaDigits(days)} روز پیش`;

  return formatPersianDate(parsed);
}

/* ============================================================
   SMALL PIECES
============================================================ */

function NotificationSkeleton() {
  return (
    <div
      className="
        animate-pulse rounded-xl border border-neutral-200
        bg-white p-4
        dark:border-neutral-800
        dark:bg-neutral-900
      "
    >
      <div className="flex items-start gap-3">
        <div className="h-2.5 w-2.5 shrink-0 rounded-full bg-neutral-200 dark:bg-neutral-800" />

        <div className="min-w-0 flex-1">
          <div className="h-4 w-2/5 rounded bg-neutral-200 dark:bg-neutral-800" />
          <div className="mt-3 h-3 w-full rounded bg-neutral-200 dark:bg-neutral-800" />
          <div className="mt-2 h-3 w-3/5 rounded bg-neutral-200 dark:bg-neutral-800" />
          <div className="mt-4 h-3 w-24 rounded bg-neutral-200 dark:bg-neutral-800" />
        </div>
      </div>
    </div>
  );
}

function EmptyState({ title, description, action }) {
  return (
    <div className="flex flex-col items-center justify-center py-16 text-center sm:py-20">
      {/* Placeholder Image */}
      <div
        className="
          relative mb-7 h-48 w-48 overflow-hidden
          rounded-2xl
          bg-neutral-50
          sm:h-56 sm:w-56
          dark:bg-neutral-950
        "
      >
        <Image
          src="/icons/empty-notification.svg"
          alt="بدون اعلان"
          fill
          className="object-contain p-3"
          sizes="(max-width: 640px) 192px, 224px"
        />
      </div>

      <h2
        className="
          text-xl font-bold
          text-purple-600
          sm:text-2xl
          dark:text-purple-400
        "
      >
        {title}
      </h2>

      <p
        className="
          mt-3 max-w-md
          text-sm leading-7
          text-neutral-500
          sm:text-base
          dark:text-neutral-400
        "
      >
        {description}
      </p>

      {action ? <div className="mt-6">{action}</div> : null}
    </div>
  );
}

function FilterButton({ active, children, onClick, count }) {
  return (
    <button
      type="button"
      onClick={onClick}
      aria-pressed={active}
      className={`
        flex min-h-12 items-center justify-center gap-2
        rounded-xl px-5 py-3
        text-sm font-semibold
        shadow-sm
        transition-all duration-200
        active:scale-[0.99]
        focus:outline-none
        focus-visible:ring-2
        focus-visible:ring-red-500
        focus-visible:ring-offset-2
        dark:focus-visible:ring-offset-neutral-900
        ${
          active
            ? `
              bg-neutral-950 text-white
              hover:bg-neutral-800
              dark:bg-white
              dark:text-neutral-950
              dark:hover:bg-neutral-200
            `
            : `
              border border-neutral-200
              bg-white text-neutral-700
              hover:bg-neutral-100 hover:text-neutral-950
              dark:border-neutral-800
              dark:bg-neutral-900
              dark:text-neutral-300
              dark:hover:bg-neutral-800
              dark:hover:text-white
            `
        }
      `}
    >
      <Bell size={18} strokeWidth={2} />
      <span>{children}</span>
      {count > 0 && (
        <span
          className={`
            inline-flex min-w-6 items-center justify-center
            rounded-full px-1.5 py-0.5
            text-[11px] font-bold
            ${
              active
                ? "bg-white/20 text-white dark:bg-neutral-950/10 dark:text-neutral-950"
                : "bg-red-50 text-red-500 dark:bg-red-950/40 dark:text-red-400"
            }
          `}
        >
          {toFaDigits(count)}
        </span>
      )}
    </button>
  );
}

/* ============================================================
   ITEM
============================================================ */

function NotificationItem({ item, isBusy, actions }) {
  const title = (item?.title || "").trim();
  const message = (item?.message || "").trim();
  const time = formatNotificationTime(item?.created_at);

  const content = (
    <div className="min-w-0 flex-1 text-start">
      <div className="flex min-w-0 items-start gap-3">
        {/* Unread dot */}
        <span
          aria-hidden="true"
          className={`
            mt-2 h-2.5 w-2.5 shrink-0 rounded-full
            ${item?.is_read ? "bg-neutral-300 dark:bg-neutral-700" : "bg-red-500"}
          `}
        />

        <div className="min-w-0 flex-1">
          {title ? (
            <h3
              className={`
                truncate text-sm font-bold
                sm:text-[15px]
                ${
                  item?.is_read
                    ? "text-neutral-600 dark:text-neutral-400"
                    : "text-neutral-900 dark:text-neutral-100"
                }
              `}
            >
              {title}
            </h3>
          ) : null}

          {message ? (
            <p
              className={`
                mt-1.5 line-clamp-3 text-sm leading-7
                ${
                  item?.is_read
                    ? "text-neutral-500 dark:text-neutral-400"
                    : "text-neutral-700 dark:text-neutral-300"
                }
              `}
            >
              {message}
            </p>
          ) : null}

          <div className="mt-3 flex items-center gap-2">
            <span
              className="
                text-xs font-medium
                text-neutral-400
                dark:text-neutral-500
              "
            >
              {time || "-"}
            </span>

            {!item?.is_read && (
              <span
                className="
                  inline-flex items-center rounded-full
                  bg-red-50 px-2 py-0.5
                  text-[10px] font-semibold
                  text-red-500
                  ring-1 ring-inset ring-red-200
                  dark:bg-red-950/40
                  dark:text-red-400
                  dark:ring-red-900
                "
              >
                خوانده‌نشده
              </span>
            )}
          </div>
        </div>
      </div>
    </div>
  );

  const shellClassName = `
    group flex min-w-0 flex-1 items-start rounded-xl
    border border-neutral-200
    bg-white p-4
    text-start
    transition-all duration-200
    hover:bg-neutral-50
    focus:outline-none
    focus-visible:ring-2
    focus-visible:ring-red-500
    focus-visible:ring-offset-2
    active:scale-[0.995]
    disabled:cursor-default
    sm:p-5
    dark:border-neutral-800
    dark:bg-neutral-900
    dark:hover:bg-neutral-800/60
    dark:focus-visible:ring-offset-neutral-900
    ${item?.is_read ? "" : "border-r-4 border-r-red-500"}
    ${isBusy ? "opacity-60" : ""}
  `;

  return (
    <li className="flex min-w-0 items-start gap-2">
      {item?.url ? (
        <Link
          href={item.url}
          onClick={() => !item.is_read && !isBusy && actions.onRead?.()}
          className={shellClassName}
        >
          {content}
        </Link>
      ) : (
        <button
          type="button"
          disabled={isBusy}
          onClick={() => !item.is_read && actions.onRead?.()}
          className={shellClassName}
        >
          {content}
        </button>
      )}

      {/* Delete */}
      <button
        type="button"
        aria-label={`حذف اعلان ${title || "بدون عنوان"}`}
        onClick={(event) => actions.onRemove(event)}
        disabled={actions.removing}
        className="
          flex h-9 w-9 shrink-0 items-center justify-center
          rounded-full
          text-neutral-400
          transition-all duration-200
          hover:bg-red-50 hover:text-red-500
          active:scale-95
          focus:outline-none
          focus-visible:ring-2
          focus-visible:ring-red-500
          focus-visible:ring-offset-2
          disabled:opacity-50
          sm:h-10 sm:w-10
          dark:text-neutral-500
          dark:hover:bg-red-950/40
          dark:hover:text-red-400
          dark:focus-visible:ring-offset-neutral-900
        "
      >
        <X size={18} strokeWidth={2} />
      </button>
    </li>
  );
}

/* ============================================================
   MAIN
============================================================ */

export default function Notifications() {
  const [onlyUnread, setOnlyUnread] = useState(false);

  const { data, isLoading, isPending, isError, isFetching, refetch } =
    useGetNotifications();

  const markRead = useMarkNotificationRead();
  const removeNotification = useDeleteNotification();

  /*
   * The query is disabled while there is no access token, and a disabled
   * TanStack query never reports isLoading, so the pending state is
   * resolved manually to avoid flashing the empty state.
   */
  const hasToken = Boolean(getCookie("access_token"));
  const showLoading = isLoading || (isPending && hasToken);

  const items = Array.isArray(data?.data) ? data.data : [];

  const unreadCount =
    typeof data?.unread_count === "number"
      ? data.unread_count
      : items.filter((item) => !item?.is_read).length;

  const visibleItems = onlyUnread
    ? items.filter((item) => !item?.is_read)
    : items;

  const emptyCopy = !hasToken
    ? {
        title: "برای دیدن اعلان‌ها وارد شوید",
        description:
          "پس از ورود، اعلان سفارش‌ها، تخفیف‌ها و پیام‌های هیکالا اینجا نمایش داده می‌شود.",
      }
    : onlyUnread && items.length > 0
      ? {
          title: "همه اعلان‌ها خوانده شده‌اند",
          description: "پیامی در این فیلتر باقی نمانده است.",
        }
      : {
          title: "هنوز اعلانی ندارید",
          description:
            "هر زمان سفارش، تخفیف یا پیام جدیدی برای شما ارسال شود، اینجا نمایش داده می‌شود.",
        };

  const handleRemove = (event, item) => {
    event.preventDefault();
    event.stopPropagation();

    if (removeNotification.isPending) return;

    removeNotification.mutate(item.id);
  };

  return (
    <section className="w-full px-4 py-8 sm:px-6 lg:px-8 dark:bg-neutral-950">
      <div className="mx-auto w-full max-w-5xl">
        {/* Main Card */}
        <div
          className="
            rounded-2xl border border-neutral-200
            bg-white p-5 shadow-sm
            sm:p-7 lg:p-8
            dark:border-neutral-800
            dark:bg-neutral-900
            dark:shadow-black/20
          "
        >
          {/* Header */}
          <div className="border-b border-neutral-200 pb-5 dark:border-neutral-800">
            <div className="flex items-center gap-3">
              {/* Back Button */}
              <Link
                href="/profile"
                aria-label="بازگشت به حساب کاربری"
                className="
                  flex h-10 w-10 shrink-0 items-center justify-center
                  rounded-full border border-neutral-200
                  text-neutral-700
                  transition-all duration-200
                  hover:bg-neutral-100 hover:text-neutral-950
                  active:scale-95
                  focus:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-red-500
                  focus-visible:ring-offset-2
                  dark:border-neutral-700
                  dark:text-neutral-300
                  dark:hover:bg-neutral-800
                  dark:hover:text-white
                  dark:focus-visible:ring-offset-neutral-900
                "
              >
                <ArrowLeft size={20} strokeWidth={2} />
              </Link>

              {/* Title */}
              <div>
                <div className="flex flex-wrap items-center gap-2">
                  <h1
                    className="
                      text-xl font-bold tracking-tight
                      text-neutral-900
                      sm:text-2xl
                      dark:text-neutral-100
                    "
                  >
                    اعلان‌ها
                  </h1>

                  {!showLoading && !isError && unreadCount > 0 && (
                    <span
                      className="
                        inline-flex min-w-6 items-center justify-center
                        rounded-full bg-red-50 px-2 py-0.5
                        text-xs font-bold text-red-500
                        ring-1 ring-inset ring-red-200
                        dark:bg-red-950/40
                        dark:text-red-400
                        dark:ring-red-900
                      "
                    >
                      {toFaDigits(unreadCount)} خوانده‌نشده
                    </span>
                  )}
                </div>

                <div className="mt-2 h-1 w-10 rounded-full bg-red-500 dark:bg-red-400" />
              </div>
            </div>
          </div>

          {/* Filters */}
          {hasToken && (
            <div className="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
              <FilterButton
                active={!onlyUnread}
                onClick={() => setOnlyUnread(false)}
              >
                همه اعلان‌ها
              </FilterButton>

              <FilterButton
                active={onlyUnread}
                count={unreadCount}
                onClick={() => setOnlyUnread(true)}
              >
                فقط خوانده‌نشده‌ها
              </FilterButton>

              {isFetching && !showLoading && (
                <span
                  className="
                    text-xs font-medium
                    text-neutral-400
                    dark:text-neutral-500
                  "
                >
                  در حال به‌روزرسانی…
                </span>
              )}
            </div>
          )}

          {/* Body */}
          <div className="mt-6">
            {/* Loading */}
            {showLoading && (
              <div className="flex flex-col gap-3">
                <NotificationSkeleton />
                <NotificationSkeleton />
                <NotificationSkeleton />
              </div>
            )}

            {/* Error */}
            {!showLoading && isError && (
              <div
                className="
                  rounded-xl border border-neutral-200
                  bg-white p-5
                  text-center
                  dark:border-neutral-800
                  dark:bg-neutral-900
                "
              >
                <p
                  className="
                    text-sm font-medium
                    text-neutral-600
                    dark:text-neutral-300
                  "
                >
                  دریافت اعلان‌ها انجام نشد.
                </p>

                <button
                  type="button"
                  onClick={() => refetch()}
                  className="
                    mt-4 inline-flex min-h-10 items-center justify-center
                    rounded-xl
                    bg-neutral-950 px-5 py-2
                    text-sm font-semibold text-white
                    transition-all duration-200
                    hover:bg-neutral-800
                    active:scale-[0.99]
                    focus:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-red-500
                    focus-visible:ring-offset-2
                    dark:bg-white
                    dark:text-neutral-950
                    dark:hover:bg-neutral-200
                    dark:focus-visible:ring-offset-neutral-900
                  "
                >
                  تلاش دوباره
                </button>
              </div>
            )}

            {/* Empty */}
            {!showLoading && !isError && visibleItems.length === 0 && (
              <EmptyState
                title={emptyCopy.title}
                description={emptyCopy.description}
                action={
                  !hasToken ? (
                    <Link
                      href="/auth/login/sendOtp"
                      className="
                        inline-flex min-h-11 items-center justify-center
                        rounded-xl
                        bg-neutral-950 px-5 py-2
                        text-sm font-semibold text-white
                        transition-all duration-200
                        hover:bg-neutral-800
                        active:scale-[0.99]
                        focus:outline-none
                        focus-visible:ring-2
                        focus-visible:ring-red-500
                        focus-visible:ring-offset-2
                        dark:bg-white
                        dark:text-neutral-950
                        dark:hover:bg-neutral-200
                        dark:focus-visible:ring-offset-neutral-900
                      "
                    >
                      ورود یا ثبت‌نام
                    </Link>
                  ) : null
                }
              />
            )}

            {/* List */}
            {!showLoading && !isError && visibleItems.length > 0 && (
              <ul className="flex flex-col gap-3">
                {visibleItems.map((item) => {
                  const isBusy =
                    (markRead.isPending && markRead.variables === item.id) ||
                    (removeNotification.isPending &&
                      removeNotification.variables === item.id);

                  return (
                    <NotificationItem
                      key={item.id}
                      item={item}
                      isBusy={isBusy}
                      actions={{
                        onRead: () => markRead.mutate(item.id),
                        onRemove: (event) => handleRemove(event, item),
                        removing:
                          removeNotification.isPending &&
                          removeNotification.variables === item.id,
                      }}
                    />
                  );
                })}
              </ul>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}
