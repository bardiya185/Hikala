"use client";
import React, { useState, useRef, useEffect } from 'react';
import NeshanMap from '@neshan-maps-platform/react-openlayers';
import { fromLonLat, toLonLat } from 'ol/proj';

function MapPicker({ onLocationSelect, initialCenter = [35.699756, 51.338076] }) {
  const [selectedPoint, setSelectedPoint] = useState(null);
  const mapRef = useRef(null);
  const markerRef = useRef(null);

  // تابعی برای اضافه کردن مارکر به نقشه
  const addMarker = (coords) => {
    const map = mapRef.current;
    if (!map) return;

    // حذف مارکر قبلی اگر وجود دارد
    if (markerRef.current) {
      map.removeLayer(markerRef.current);
      markerRef.current = null;
    }

    // ایجاد یک المان HTML برای مارکر
    const markerElement = document.createElement('div');
    markerElement.className = 'custom-marker';
    markerElement.innerHTML = `
      <div style="
        width: 30px;
        height: 30px;
        background: #ef4444;
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        font-weight: bold;
      ">📍</div>
    `;

    // ایجاد لایه مارکر
    const markerLayer = new map.olLayer.Vector({
      source: new map.olSource.Vector({
        features: [
          new map.olFeature({
            geometry: new map.olGeom.Point(fromLonLat(coords)),
          }),
        ],
      }),
      style: new map.olStyle.Style({
        image: new map.olStyle.Icon({
          element: markerElement,
          anchor: [0.5, 0.5],
        }),
      }),
    });

    map.addLayer(markerLayer);
    markerRef.current = markerLayer;

    // ذخیره نقطه انتخاب شده
    setSelectedPoint(coords);
  };

  // تابع برای گرفتن مختصات با کلیک روی نقشه
  const handleMapClick = (event) => {
    const map = mapRef.current;
    if (!map) return;

    // دریافت مختصات کلیک در سیستم تصویر نقشه
    const coordinate = event.coordinate;
    // تبدیل به طول و عرض جغرافیایی
    const lonLat = toLonLat(coordinate);
    const [longitude, latitude] = lonLat;

    // اضافه کردن مارکر
    addMarker([latitude, longitude]);

    // ارسال مختصات به والد
    if (onLocationSelect) {
      onLocationSelect({
        latitude: latitude,
        longitude: longitude,
        display: `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`
      });
    }
  };

  return (
    <div className="w-full h-96 rounded-xl overflow-hidden border border-gray-200 relative">
      <NeshanMap
        ref={mapRef}
        mapKey={process.env.NEXT_PUBLIC_NESHAN_API_KEY || 'YOUR_API_KEY'}
        defaultType="neshan"
        center={{ 
          latitude: initialCenter[0], 
          longitude: initialCenter[1] 
        }}
        zoom={14}
        style={{ height: '100%', width: '100%' }}
        traffic={false}
        poi={true}
        onInit={(map) => {
          // دسترسی به آبجکت نقشه برای کارهای بیشتر
          console.log('نقشه بارگذاری شد!');
        }}
        onClick={handleMapClick}
      />
      
      {/* راهنمای کاربر */}
      <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg shadow-lg text-sm text-gray-700">
        💡 روی نقشه کلیک کنید تا مختصات نقطه را انتخاب کنید
      </div>

      {/* نمایش مختصات انتخاب شده */}
      {selectedPoint && (
        <div className="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg shadow-lg text-sm">
          <span className="font-medium">📍 مختصات:</span>
          <span className="mr-2 font-mono">
            {selectedPoint[0].toFixed(6)}, {selectedPoint[1].toFixed(6)}
          </span>
        </div>
      )}
    </div>
  );
}

export default MapPicker;