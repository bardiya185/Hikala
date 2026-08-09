export default function Spinner({ size = 48 }) {
  return (
    <div
      className="rounded-full border-[3px] bg-white w-full h-f border-red-100 border-t-red-600 animate-spin"
      style={{ width: size, height: size }}
    />
  );
}