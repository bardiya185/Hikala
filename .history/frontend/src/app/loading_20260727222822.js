'use client';

import { motion, AnimatePresence } from 'framer-motion';
import { useState, useEffect } from 'react';

export default function Loading() {
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    // شبیه‌سازی بارگذاری (در پروژه واقعی، این تایمر را بردارید)
    const timer = setTimeout(() => {
      setIsLoading(false);
    }, 200); // بعد از ۲ ثانیه محو شود

    return () => clearTimeout(timer);
  }, []);

  return (
    <AnimatePresence>
      {isLoading && (
        <motion.div
          className="loading-overlay"
          initial={{ opacity: 1 }}
          animate={{ opacity: 0 }}
          transition={{ duration: 5, ease: 'easeInOut' }}
          style={{
            position: 'fixed',
            top: 0,
            left: 0,
            width: '100vw',
            height: '100vh',
            backgroundColor: 'white',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            zIndex: 9999,
            flexDirection: 'column',
            gap: '20px',
            transition:'1s '
          }}
        >
          {/* اسپینر یا هر محتوای دلخواه */}
          <motion.div
            animate={{ rotate: 360 }}
            transition={{ repeat: Infinity, duration: 5, ease: 'linear' }}
            style={{
              width: 50,
              height: 50,
              border: '4px solid #e0e0e0',
              borderTop: '4px solid #3498db',
              borderRadius: '50%',
            }}
          />
          <p style={{ color: '#333', fontSize: '16px' }}>در حال بارگذاری...</p>
        </motion.div>
      )}
    </AnimatePresence>
  );
}