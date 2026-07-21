// CountdownTimer.jsx
import { useState, useEffect } from "react";

export default function CountdownTimer({ targetDate }) {
  const [timeLeft, setTimeLeft] = useState(calculateTimeLeft());

  function calculateTimeLeft() {
    const difference = +new Date(targetDate) - +new Date();
    let timeLeft = {};

    if (difference > 0) {
      timeLeft = {
        hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
        minutes: Math.floor((difference / 1000 / 60) % 60),
        seconds: Math.floor((difference / 1000) % 60),
      };
    } else {
      timeLeft = { hours: 0, minutes: 0, seconds: 0 };
    }
    return timeLeft;
  }

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft(calculateTimeLeft());
    }, 1000);

    return () => clearInterval(timer);
  }, [targetDate]);

  const formatNumber = (num) => String(num || 0).padStart(2, "0");

  return (
    <div className="flex items-center gap-1 text-white font-bold dir-ltr">
      <span className="bg-white/20 px-2 py-1 rounded text-sm">{formatNumber(timeLeft.hours)}</span>
      <span>:</span>
      <span className="bg-white/20 px-2 py-1 rounded text-sm">{formatNumber(timeLeft.minutes)}</span>
      <span>:</span>
      <span className="bg-white/20 px-2 py-1 rounded text-sm">{formatNumber(timeLeft.seconds)}</span>
    </div>
  );
}