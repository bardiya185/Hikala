export default function Spinner({ size = 48 }) {
  return (
    <div
      className="rounded-full border-[3px] bg-white w-full h-full border-red-100 border-t-red-100 animate-spin"
      style={{ width: size, height: size }}
    />
  );
}