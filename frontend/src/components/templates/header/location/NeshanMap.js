
"use client";

import { useCallback, useEffect, useRef } from "react";
import maplibregl from "@neshan-maps-platform/maplibre-sdk";
import "@neshan-maps-platform/maplibre-sdk/style.css";

function NeshanMap({
  latitude,
  longitude,
  onLocationSelect,
}) {
  const containerRef = useRef(null);
  const mapRef = useRef(null);
  const markerRef = useRef(null);
  const onLocationSelectRef = useRef(onLocationSelect);

  const apiKey = process.env.NEXT_PUBLIC_NESHAN_API_KEY;

  // Always keep the latest callback
  useEffect(() => {
    onLocationSelectRef.current = onLocationSelect;
  }, [onLocationSelect]);

  /*
   * Build the map
   */
  useEffect(() => {
    if (!containerRef.current) {
      return;
    }

    if (mapRef.current) {
      return;
    }

    if (!apiKey) {
      console.error(
        "Neshan Map: NEXT_PUBLIC_NESHAN_API_KEY is not configured."
      );
      return;
    }

    const initialLongitude =
      longitude !== null && longitude !== undefined
        ? Number(longitude)
        : 51.389;

    const initialLatitude =
      latitude !== null && latitude !== undefined
        ? Number(latitude)
        : 35.6892;

    const map = new maplibregl.Map({
      container: containerRef.current,

      style:
        "https://static.neshan.org/sdk/maplibre/styles/light.json",

      center: [
        initialLongitude,
        initialLatitude,
      ],

      zoom:
        latitude !== null &&
        latitude !== undefined &&
        longitude !== null &&
        longitude !== undefined
          ? 14
          : 5,

      minZoom: 2,
      maxZoom: 21,

      apiKey: apiKey,
    });

    mapRef.current = map;

    /*
     * Map controls
     */
    map.addControl(
      new maplibregl.NavigationControl(),
      "top-right"
    );

    /*
     * Once the map is ready
     */
    map.on("load", () => {
      // The map lives inside a modal, so
      // it has to be resized after the modal opens.
      setTimeout(() => {
        map.resize();
      }, 100);
    });

    /*
     * Click on the map
     */
    map.on("click", (event) => {
      const { lng, lat } = event.lngLat;

      const selectedLatitude = Number(lat);
      const selectedLongitude = Number(lng);

      // Send the coordinates to the parent
      if (onLocationSelectRef.current) {
        onLocationSelectRef.current({
          latitude: selectedLatitude,
          longitude: selectedLongitude,
        });
      }

      // Remove the previous marker
      if (markerRef.current) {
        markerRef.current.remove();
        markerRef.current = null;
      }

      // Add a new marker
      markerRef.current = new maplibregl.Marker({
        color: "#ef4444",
      })
        .setLngLat([
          selectedLongitude,
          selectedLatitude,
        ])
        .addTo(map);
    });

    /*
     * Cleanup
     */
    return () => {
      if (markerRef.current) {
        markerRef.current.remove();
        markerRef.current = null;
      }

      if (mapRef.current) {
        mapRef.current.remove();
        mapRef.current = null;
      }
    };
  }, [apiKey]);

  /*
   * When latitude / longitude changes
   */
  useEffect(() => {
    const map = mapRef.current;

    if (!map) {
      return;
    }

    if (
      latitude === null ||
      latitude === undefined ||
      longitude === null ||
      longitude === undefined
    ) {
      return;
    }

    const lat = Number(latitude);
    const lng = Number(longitude);

    if (Number.isNaN(lat) || Number.isNaN(lng)) {
      return;
    }

    /*
     * Move the map to the new coordinates
     */
    map.flyTo({
      center: [lng, lat],
      zoom: 14,
      essential: true,
    });

    /*
     * Remove the previous marker
     */
    if (markerRef.current) {
      markerRef.current.remove();
      markerRef.current = null;
    }

    /*
     * Add a new marker
     */
    markerRef.current = new maplibregl.Marker({
      color: "#ef4444",
    })
      .setLngLat([lng, lat])
      .addTo(map);
  }, [latitude, longitude]);

  /*
   * Since the map lives inside a modal,
   * it is resized whenever the viewport changes.
   */
  useEffect(() => {
    const handleResize = () => {
      if (mapRef.current) {
        mapRef.current.resize();
      }
    };

    window.addEventListener("resize", handleResize);

    return () => {
      window.removeEventListener("resize", handleResize);
    };
  }, []);

  /*
   * If the API key is missing
   */
  if (!apiKey) {
    return (
      <div className="flex h-full w-full items-center justify-center bg-neutral-100 px-6 text-center text-sm text-red-500">
        Neshan map API key is not configured.
      </div>
    );
  }

  /*
   * Map container
   */
  return (
    <div
      ref={containerRef}
      className="h-full w-full"
      style={{
        minHeight: "260px",
      }}
    />
  );
}

export default NeshanMap;

