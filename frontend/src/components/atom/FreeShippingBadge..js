import Image from "next/image";

export function FreeShippingBadge() {
  return (
    <div
      className="
        flex
        shrink-0
        items-center
        gap-1.5
        rounded-md
        bg-green-200
        px-2
        py-1.5
        text-[10px]
        font-medium
        text-green-600
        sm:text-xs
      "
      title="Free Shipping"
    >
      <Image
        src="/icons/free_shipping.png"
        alt="Free Shipping"
        width={20}
        height={20}
        className="h-5 w-5 object-contain"
      />

      <span>Free Shipping</span>
    </div>
  );
}