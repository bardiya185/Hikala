"use client";

import Link from "next/link";
import { ChevronRight } from "lucide-react";

export default function Breadcrumb({ items = [] }) {
  return (
    <nav
      aria-label="Breadcrumb"
      dir="ltr"
      className="w-full py-3 "
    >
      <ol className="flex items-center gap-2 text-sm pl-7">
        {items.map((item, index) => {
          const isLast = index === items.length - 1;

          return (
            <li
              key={`${item.title}-${index}`}
              className="flex items-center gap-2 min-w-0"
            >
              {item.href ? (
                <Link
                  href={item.href}
                  className={`
                    truncate
                    transition-colors
                    ${
                      isLast
                        ? "font-medium text-neutral-800"
                        : "text-neutral-500 hover:text-red-600"
                    }
                  `}
                >
                  {item.title}
                </Link>
              ) : (
                <span className="truncate font-medium text-neutral-800">
                  {item.title}
                </span>
              )}

              {!isLast && (
                <ChevronRight
                  size={15}
                  className="shrink-0 text-neutral-400"
                />
              )}
            </li>
          );
        })}
      </ol>
    </nav>
  );
}