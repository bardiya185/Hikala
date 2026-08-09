"use client";
import { useState } from 'react';

export default function MapMatching() {
  const [points, setPoints] = useState('');
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // 🔑 کلید API خود را اینجا وارد کنید
  const API_KEY = 'service.2b74aad4c3a74d7a9021e3be5f57eae7'; // ⬅️ این را عوض کنید

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setResult(null);

    try {
      const response = await fetch('https://api.neshan.org/v3/map-matching', {
        method: 'POST',
        headers: {
          'Api-Key': API_KEY,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          path: points,
        }),
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`خطا: ${response.status} - ${errorText}`);
      }

      const data = await response.json();
      setResult(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="p-6 max-w-2xl mx-auto" dir="rtl">
      <h2 className="text-2xl font-bold mb-4">تبدیل مختصات به مسیر (Map Matching)</h2>
      
      {/* فرم ورودی */}
      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block text-sm font-medium mb-1">
            مختصات نقاط (با | جدا کنید):
          </label>
          <textarea
            className="w-full p-2 border border-gray-300 rounded-lg font-mono text-sm"
            rows="3"
            value={points}
            onChange={(e) => setPoints(e.target.value)}
            placeholder="35.703983747058494,51.3213872909546|35.70363307719029,51.32144361734391"
            required
          />
          <p className="text-xs text-gray-500 mt-1">
            حداقل ۲ نقطه و حداکثر ۱۰۰۰ نقطه
          </p>
        </div>

        <button
          type="submit"
          disabled={loading}
          className="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold disabled:opacity-50 transition-colors"
        >
          {loading ? 'در حال پردازش...' : 'ارسال درخواست'}
        </button>
      </form>

      {/* نمایش خطا */}
      {error && (
        <div className="mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg">
          ❌ {error}
        </div>
      )}

      {/* نمایش نتیجه */}
      {result && (
        <div className="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
          <h3 className="font-semibold mb-2">✅ نتیجه:</h3>
          
          <div className="space-y-2 text-sm">
            <p>
              <span className="font-medium">تعداد نقاط نگاشت شده:</span>{' '}
              {result.snappedPoints?.length || 0}
            </p>
            
            {result.snappedPoints && result.snappedPoints.length > 0 && (
              <div>
                <p className="font-medium">نقاط تصحیح شده:</p>
                <ul className="list-disc list-inside bg-white p-2 rounded border mt-1">
                  {result.snappedPoints.slice(0, 5).map((point, idx) => (
                    <li key={idx} className="font-mono text-xs">
                      {point.location[0].toFixed(6)}, {point.location[1].toFixed(6)}
                      {point.originalIndex !== undefined && 
                        ` (نقطه ${point.originalIndex + 1})`
                      }
                    </li>
                  ))}
                  {result.snappedPoints.length > 5 && (
                    <li className="text-gray-500">... و {result.snappedPoints.length - 5} نقطه دیگر</li>
                  )}
                </ul>
              </div>
            )}

            {result.geometry && (
              <div className="mt-2">
                <p className="font-medium">Polyline (مسیر):</p>
                <p className="mt-1 p-2 bg-white rounded border font-mono text-xs break-all">
                  {result.geometry.substring(0, 100)}...
                </p>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
}