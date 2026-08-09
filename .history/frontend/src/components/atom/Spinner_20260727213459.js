export default function Spinner({ size = 48 }) {
  return (
    <div className=" w-full h- ">
    <div
      className="rounded-full border-[3px] border-red-100 border-t-red-600 animate-spin"
      style={{ width: size, height: size }}
    />
    </div>
  );
}