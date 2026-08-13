"use client";

import { useState, useEffect, useRef } from "react";

// ================================================================
// 🎴 Flip Unit Component
// ================================================================
function FlipUnit({ value, label }) {
  const [displayValue, setDisplayValue] = useState(value);
  const [flipping, setFlipping] = useState(false);
  const prevValue = useRef(value);

  useEffect(() => {
    if (value === prevValue.current) return;
    prevValue.current = value;
    setFlipping(true);
    const t1 = setTimeout(() => setDisplayValue(value), 300);
    const t2 = setTimeout(() => setFlipping(false), 600);
    return () => {
      clearTimeout(t1);
      clearTimeout(t2);
    };
  }, [value]);

  return (
    <div className="flex flex-col items-center gap-1.5">
      <div style={{ perspective: "200px", width: 44, height: 48 }}>
        <div
          className={`w-full h-full bg-white rounded-lg flex items-center justify-center ${
            flipping ? "animate-flip3d" : ""
          }`}
        >
          <span className="text-lg font-bold text-red-900 tabular-nums">
            {displayValue}
          </span>
        </div>
      </div>
      <span className="text-[10px] text-red-200 tracking-wide font-medium">
        {label}
      </span>
    </div>
  );
}

// ================================================================
// ⏰ Countdown Timer
// ================================================================
export default function CountdownTimer({ targetDate }) {
  const [timeLeft, setTimeLeft] = useState(() => calculateTimeLeft(targetDate));

  // 🔥 محاسبه با روز
  function calculateTimeLeft(target) {
    const difference = +new Date(target) - +new Date();
    let timeLeft = {};

    if (difference > 0) {
      timeLeft = {
        days: Math.floor(difference / (1000 * 60 * 60 * 24)),      // 🔥 روز اضافه شد
        hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
        minutes: Math.floor((difference / 1000 / 60) % 60),
        seconds: Math.floor((difference / 1000) % 60),
      };
    } else {
      timeLeft = { days: 0, hours: 0, minutes: 0, seconds: 0 };
    }
    return timeLeft;
  }

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft(calculateTimeLeft(targetDate));
    }, 1000);

    return () => clearInterval(timer);
  }, [targetDate]);

  const formatNumber = (num) => String(num || 0).padStart(2, "0");

  return (
    <div className="flex items-center gap-2" dir="ltr">
      {/* 🔥 روز فقط وقتی نشون داده میشه که > 0 باشه */}
      {timeLeft.days > 0 && (
        <>
          <FlipUnit value={formatNumber(timeLeft.days)} label="days" />
          <span className="text-red-200 text-lg font-bold -mt-4">:</span>
        </>
      )}
      <FlipUnit value={formatNumber(timeLeft.hours)} label="hours" />
      <span className="text-red-200 text-lg font-bold -mt-4">:</span>
      <FlipUnit value={formatNumber(timeLeft.minutes)} label="minutes" />
      <span className="text-red-200 text-lg font-bold -mt-4">:</span>
      <FlipUnit value={formatNumber(timeLeft.seconds)} label="seconds" />
    </div>
  );
}