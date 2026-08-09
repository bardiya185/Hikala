"use client";
import { useState } from 'react';

function MapMatchingComponent() {
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // تابع اصلی برای ارسال درخواست
  const performMapMatching = async () => {
    setLoading(true);
    setError(null);

    // 🔑 کلید API خود را در اینجا قرار دهید (بهتر است از متغیر محیطی استفاده کنید)
    const API_KEY = process.env.NEXT_PUBLIC_NESHAN_API_KEY || 'YOUR_API_KEY';

    // مختصات نمونه (حداقل ۲ نقطه، با | جدا شده‌اند)
    const rawPath = "35.703983747058494,51.3213872909546|35.70363307719029,51.32144361734391";

    try {
      const response = await fetch('https://api.neshan.org/v3/map-matching', {
        method: 'POST',
        headers: {
          'Api-Key': API_KEY,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          path: rawPath, // یا از یک متغیر state استفاده کنید
        }),
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`خطا در ارتباط با سرور: ${response.status} - ${errorText}`);
      }

      const data = await response.json();
      console.log('نتیجه Map Matching:', data);
      setResult(data);
    } catch (err) {
      console.error('مشکل در دریافت داده:', err);
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ padding: '20px', direction: 'rtl' }}>
      <h2 className="text-xl font-bold">آزمایش سرویس Map Matching نشان</h2>
      
      <button
        onClick={performMapMatching}
        disabled={loading}
        className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50"
      >
        {loading ? 'در حال ارسال...' : 'ارسال درخواست'}
      </button>

      {error && (
        <div className="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
          ❌ خطا: {error}
        </div>
      )}

      {result && (
        <div className="mt-4 p-4 bg-gray-100 rounded-lg">
          <h3 className="font-semibold">نتیجه:</h3>
          
          <div className="mt-2">
            <p className="text-sm text-gray-600">
              <span className="font-medium">تعداد نقاط نگاشت شده:</span>{' '}
              {result.snappedPoints?.length || 0}
            </p>
            
            <details className="mt-2">
              <summary className="cursor-pointer text-blue-600">
                مشاهده جزئیات نقاط
              </summary>
              <pre className="mt-2 p-2 bg-white rounded text-xs overflow-x-auto">
                {JSON.stringify(result, null, 2)}
              </pre>
            </details>

            {result.geometry && (
              <div className="mt-3">
                <p className="text-sm text-gray-600">
                  <span className="font-medium">Polyline Encoded:</span>
                </p>
                <p className="mt-1 p-2 bg-white rounded text-xs font-mono break-all">
                  {result.geometry}
                </p>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
}

export default MapMatchingComponent;