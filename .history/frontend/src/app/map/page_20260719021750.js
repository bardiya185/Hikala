"use client";
import React, { useState } from 'react';
import MapPicker from '@/components/MapPicker';

function LocationPickerPage() {
  const [selectedLocation, setSelectedLocation] = useState(null);

  // تابعی که بعد از انتخاب مختصات صدا زده می‌شود
  const handleLocationSelect = (location) => {
    console.log('مختصات انتخاب شده:', location);
    setSelectedLocation(location);
    // اینجا می‌توانید مختصات را به state یا API خود ارسال کنید
  };

  return (
    <div className="container mx-auto p-4" dir="rtl">
      <h1 className="text-2xl font-bold mb-4">انتخاب مکان روی نقشه</h1>
      
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* نقشه - ۳/۴ صفحه */}
        <div className="lg:col-span-3">
          <MapPicker 
            onLocationSelect={handleLocationSelect}
            initialCenter={[35.699756, 51.338076]} // مرکز اولیه (تهران)
          />
        </div>
        
        {/* پنل اطلاعات - ۱/۴ صفحه */}
        <div className="lg:col-span-1">
          <div className="bg-white p-4 rounded-xl border border-gray-200 sticky top-4">
            <h3 className="font-semibold mb-3">📍 مکان انتخاب شده</h3>
            
            {selectedLocation ? (
              <div className="space-y-2">
                <div className="p-3 bg-blue-50 rounded-lg">
                  <p className="text-sm text-gray-600">مختصات:</p>
                  <p className="font-mono text-sm font-medium">
                    {selectedLocation.display}
                  </p>
                </div>
                
                <div className="p-3 bg-green-50 rounded-lg">
                  <p className="text-sm text-gray-600">عرض جغرافیایی:</p>
                  <p className="font-mono text-sm font-medium">
                    {selectedLocation.latitude.toFixed(6)}
                  </p>
                </div>
                
                <div className="p-3 bg-purple-50 rounded-lg">
                  <p className="text-sm text-gray-600">طول جغرافیایی:</p>
                  <p className="font-mono text-sm font-medium">
                    {selectedLocation.longitude.toFixed(6)}
                  </p>
                </div>

                {/* دکمه برای استفاده از مختصات */}
                <button 
                  className="w-full mt-2 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                  onClick={() => {
                    // اینجا می‌توانید مختصات را به API Map Matching ارسال کنید
                    console.log('ارسال مختصات به Map Matching:', selectedLocation);
                    alert(`مختصات انتخاب شد: ${selectedLocation.display}`);
                  }}
                >
                  استفاده از این مختصات
                </button>
              </div>
            ) : (
              <p className="text-gray-500 text-sm">
                روی نقشه کلیک کنید تا مختصات نقطه را انتخاب کنید
              </p>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

export default LocationPickerPage;