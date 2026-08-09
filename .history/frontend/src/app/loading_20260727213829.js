
import Spinner from "@/components/atom/Spinner";

export default function Loading() {
  return (
    <div className="w-full h-full bg-white z-20 flex items-center justify-center">
      <Spinner size={48} />
    </div>
  );
}