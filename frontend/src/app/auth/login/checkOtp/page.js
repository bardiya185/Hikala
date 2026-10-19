"use client";

import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import CheckOtpForm from "./CheckOtpForm";

export default function CheckOtpPage() {
  const [mobile, setMobile] = useState(null);
  const router = useRouter();

  useEffect(() => {
    const storedMobile = sessionStorage.getItem("login_mobile");

    if (storedMobile) {
      setMobile(storedMobile);
    } else {
      // Nothing to verify against — restart from the mobile step
      // instead of hanging on "Loading..." forever.
      router.replace("/auth/login/sendOtp");
    }
  }, [router]);

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