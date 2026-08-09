
import Spinner from "@/components/atom/Spinner";

export default function Loading() {
  return (
    <div className=" flex items-center justify-center">
      <Spinner size={48} />
    </div>
  );
}