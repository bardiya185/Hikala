"use client";

import React from "react";
import { useLogOut } from "@/core/services/mutations";
import { Loader2, LogOut } from "lucide-react";

function LogoutButton() {
  const { mutate: logout, isPending } = useLogOut();

  const handleLogout = () => {
    logout();
  };

  return (
    <button
      onClick={handleLogout}
      disabled={isPending}
      className="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 disabled:opacity-50"
    >
      {isPending ? (
        <>
          <Loader2 className="h-4 w-4 animate-spin" />
          Logging out...
        </>
      ) : (
        <>
          <LogOut className="h-4 w-4" />
          Logout
        </>
      )}
    </button>
  );
}

export default LogoutButton;
