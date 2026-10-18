"use client";

export default function GlobalError({ error, reset }) {
  return (
    <html lang="fa" dir="rtl">
      <body
        style={{
          margin: 0,
          minHeight: "100vh",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          background: "#fafafa",
          fontFamily:
            "system-ui, -apple-system, 'Segoe UI', Tahoma, sans-serif",
          color: "#262626",
        }}
      >
        <div style={{ textAlign: "center", padding: 24, maxWidth: 480 }}>
          <h1 style={{ fontSize: 24, fontWeight: 800, margin: "0 0 8px" }}>
            خطایی رخ داد
          </h1>
          <p style={{ fontSize: 14, lineHeight: 1.9, color: "#737373", margin: "0 0 20px" }}>
            مشکلی در بارگذاری برنامه پیش آمد. لطفاً صفحه را دوباره بارگذاری
            کنید.
          </p>
          <button
            type="button"
            onClick={() => reset()}
            style={{
              background: "#ef4444",
              color: "#fff",
              border: "none",
              borderRadius: 12,
              padding: "12px 22px",
              fontSize: 14,
              fontWeight: 700,
              cursor: "pointer",
            }}
          >
            تلاش مجدد
          </button>
        </div>
      </body>
    </html>
  );
}
