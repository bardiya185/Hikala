"use client";

import { Moon, Sun } from "lucide-react";
import { useTheme } from "@/components/partials/provider/ThemeProvider";

export default function ThemeToggle() {
  const { theme, setTheme, mounted } = useTheme();

  if (!mounted) {
    return (
      <button
        type="button"
        className="flex h-10 w-10 items-center justify-center rounded-full border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900"
        aria-label="Toggle theme"
      />
    );
  }

  const isDark = theme === "dark";

  const handleToggle = () => {
    setTheme(isDark ? "light" : "dark");
  };

  return (
    <button
      type="button"
      onClick={handleToggle}
      aria-label={isDark ? "Switch to light mode" : "Switch to dark mode"}
      title={isDark ? "Switch to light mode" : "Switch to dark mode"}
      className="
        flex h-10 w-10 items-center justify-center
        rounded-full
        border border-neutral-200
        bg-white
        text-neutral-700
        transition-all
        hover:bg-neutral-100
        hover:text-neutral-900
        dark:border-neutral-700
        dark:bg-neutral-900
        dark:text-neutral-200
        dark:hover:bg-neutral-800
        dark:hover:text-white
      "
    >
      {isDark ? (
        <Sun className="h-5 w-5" />
      ) : (
        <Moon className="h-5 w-5" />
      )}
    </button>
  );
}