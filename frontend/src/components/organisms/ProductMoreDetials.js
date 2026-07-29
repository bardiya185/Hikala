"use client";

import { ChevronDown, X } from "lucide-react";
import { useState } from "react";

const TABS = [
  { id: "specifications", label: "Specifications" },
  { id: "description", label: "Description" },
  { id: "reviews", label: "Reviews" },
];

const INITIAL_SPECS_COUNT = 4;

export default function ProductMoreDetails({ data, selectedVariant }) {
  const [activeTab, setActiveTab] = useState("specifications");
  const [showAllSpecs, setShowAllSpecs] = useState(false);

  const allAttributes = selectedVariant?.attributes || [];
  const visibleAttributes = showAllSpecs
    ? allAttributes
    : allAttributes.slice(0, INITIAL_SPECS_COUNT);
  const hasMoreSpecs = allAttributes.length > INITIAL_SPECS_COUNT;

  return (
    <div>
      <section id="more-details">
        <div className="flex gap-2">
          {TABS.map((tab) => (
            <button
              className={`px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors ${
                activeTab === tab.id
                  ? "border-red-600 "
                  : "border-transparent text-neutral-500 hover:text-neutral-700"
              }`}
              onClick={() => setActiveTab(tab.id)}
              key={tab.id}
            >
              {tab.label}
            </button>
          ))}
        </div>

        <div>
          {activeTab === "specifications" && (
            <>
              <div className="grid grid-cols-1 pl-5 ">
                {visibleAttributes?.map((attr) => (
                  <div
                    className="flex items-center gap-3 py-2 border-b border-neutral-100 text-sm"
                    key={attr.id}
                  >
                    <span className="text-neutral-500">
                      {attr.attribute_name}
                    </span>
                    <span className="text-neutral-800 font-medium">
                      {attr.value}
                    </span>
                  </div>
                ))}
              </div>

              {hasMoreSpecs && (
                <div className="mt-3">
                  {/* دکمه نمایش بیشتر - فقط وقتی بسته‌ست نشون داده می‌شه */}
                  {!showAllSpecs && (
                    <button
                      className="flex items-center gap-2 text-sm text-red-500 hover:text-red-600"
                      onClick={() => setShowAllSpecs(true)}
                    >
                      Show more specifications
                      <ChevronDown size={16} />
                    </button>
                  )}

                  {/* دکمه بستن - فقط وقتی باز شده‌ست نشون داده می‌شه */}
                  {showAllSpecs && (
                    <button
                      className="flex items-center gap-2 text-sm text-neutral-500 hover:text-neutral-700"
                      onClick={() => setShowAllSpecs(false)}
                    >
                      Close
                      <X size={16} />
                    </button>
                  )}
                </div>
              )}
            </>
          )}

          {activeTab === "description" && (
            <div
              className="pl-5 prose prose-sm text-neutral-600"
              dangerouslySetInnerHTML={{ __html: data?.description }}
            />
          )}

          {activeTab === "reviews" && (
            <div className="py-10 text-center text-neutral-400 text-sm">
              No reviews yet.
            </div>
          )}
        </div>
      </section>
    </div>
  );
}
