// src/app/(main)/order-tracking/page.js

import React from "react";
import TrackOrderClient from "./TrackOrderClient";

export const metadata = {
  title: "Track Order | Hikala",
  description:
    "Track your Hikala order: enter your order ID to see its live status from payment to delivery, and review the items in the parcel.",
};

export default function OrderTrackingPage() {
  return <TrackOrderClient />;
}
