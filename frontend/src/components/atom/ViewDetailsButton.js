"use client";
import { ChevronDown } from "lucide-react";

export default function ViewDetailsButton() {
  const handleClick = () => {
    const target = document.getElementById("more-details");
    target?.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  return (
    <button
      onClick={handleClick}
      className="flex items-center gap-1.5 text-sm text-red-600 font-medium hover:underline"
    >
      View more details
      <ChevronDown size={16} />
    </button>
  );
}