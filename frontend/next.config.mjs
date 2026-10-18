/** @type {import('next').NextConfig} */

const apiBase = process.env.NEXT_PUBLIC_BASE_URL || "http://localhost:8000";

// Allow images served from the configured backend host (local or Railway domain).
let backendHostname = null;
try {
  backendHostname = new URL(apiBase).hostname;
} catch {
  backendHostname = null;
}

const remotePatterns = [
  {
    protocol: "https",
    hostname: "laravel-q4runs.chbkn.dev",
    pathname: "/storage/**",
  },
  {
    protocol: "http",
    hostname: "localhost",
    port: "8000",
    pathname: "/storage/**",
  },
];

if (backendHostname && !remotePatterns.some((p) => p.hostname === backendHostname)) {
  for (const protocol of ["http", "https"]) {
    const pattern = { protocol, hostname: backendHostname, pathname: "/storage/**" };
    if (protocol === "http" && backendHostname === "localhost") pattern.port = "8000";
    remotePatterns.push(pattern);
  }
}

const nextConfig = {
  output: "standalone",
  images: {
    remotePatterns,
  },
};

export default nextConfig;