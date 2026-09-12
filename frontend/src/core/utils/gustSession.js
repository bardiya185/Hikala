export function getGuestSessionId() {
  if (typeof window === "undefined") return null;

  try {
    let sessionId = localStorage.getItem("guest_session_id");

    if (!sessionId) {
      sessionId = `guest-${crypto.randomUUID()}`;
      localStorage.setItem("guest_session_id", sessionId);
    }

    return sessionId;
  } catch (error) {
    console.error("Guest session error:", error);
    return null;
  }
}

export function clearGuestSessionId() {
  if (typeof window === "undefined") return;

  try {
    localStorage.removeItem("guest_session_id");
  } catch (error) {
    console.error("Clear guest session error:", error);
  }
}