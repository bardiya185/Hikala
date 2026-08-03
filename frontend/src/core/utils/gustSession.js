
export function getGuestSessionId() {
  if (typeof window === "undefined") return null;

  let sessionId = localStorage.getItem("guest_session_id");

  if (!sessionId) {
    sessionId = "guest-" + crypto.randomUUID();
    localStorage.setItem("guest_session_id", sessionId);
  }

  return sessionId;
}


export function clearGuestSessionId() {
  localStorage.removeItem("guest_session_id");
}