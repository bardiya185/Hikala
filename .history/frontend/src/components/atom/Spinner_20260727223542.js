'use client';

import { motion, AnimatePresence } from 'framer-motion';
import { useState, useEffect } from 'react';

export default function PageLoader({ children }: { children: React.ReactNode }) {
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    // شبیه‌سازی بارگذاری - در پروژه واقعی این را بردارید
    const timer = setTimeout(() => {
      setIsLoading(false);
    }, 1500); // بعد از ۱.۵ ثانیه محو شود

    return () => clearTimeout(timer);
  }, []);

  return (
    <>
      <AnimatePresence mode="wait">
        {isLoading && (
          <motion.div
            className="fixed inset-0 z-50 flex items-center justify-center bg-white"
            initial={{ opacity: 1 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.8, ease: 'easeInOut' }}
          >
            <div className="text-center">
              {/* اسپینر */}
              <motion.div
                className="w-16 h-16 border-4 border-red-600 border-t-transparent rounded-full mx-auto"
                animate={{ rotate: 360 }}
                transition={{
                  repeat: Infinity,
                  duration: 1,
                  ease: 'linear',
                }}
              />
              <p className="mt-4 text-gray-700 font-medium">در حال بارگذاری...</p>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* محتوای اصلی بعد از لود شدن */}
      {!isLoading && children}
    </>
  );
}