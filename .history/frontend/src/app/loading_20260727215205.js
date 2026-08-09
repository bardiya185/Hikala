
import Spinner from "@/components/atom/Spinner";


export default function Loading() {
  return (
    <div className="w-full min-h-[60vh] flex items-center justify-center">
      <Spinner size={48} />
    </div>
  );
}