"use client";

import React, { useState } from "react";
import SendOtpForm from "./SendOtpForm";

export default function SendOtpPage() {
  const [mobile, setMobile] = useState("");

  return (
    <SendOtpForm
      mobile={mobile}
      setMobile={setMobile}
    />
  );
}