"use client";

import React, { useEffect, useState } from "react";
import CheckOtpForm from "./CheckOtpForm";

export default function CheckOtpPage() {
  const [mobile, setMobile] = useState(null);

  useEffect(() => {
    const storedMobile = sessionStorage.getItem("login_mobile");

    if (storedMobile) {
      setMobile(storedMobile);
    }
  }, []);

  if (mobile === null) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-neutral-400">
          Loading...
        </p>
      </div>
    );
  }

  return (
    <CheckOtpForm
      mobile={mobile}
    />
  );
}