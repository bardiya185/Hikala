import Image from "next/image";
import Link from "next/link";

const categories = [
  {
    id: 1,
    title: "Mobiles",
    image: "/icons/ip17.jpg",
    href: "/category/mobiles",
  },
  {
    id: 2,
    title: "Laptops",
    image: "/icons/ip17.jpg",
    href: "/category/laptops",
  },
  {
    id: 3,
    title: "Headphones",
    image: "/icons/ip17.jpg",
    href: "/category/headphones",
  },
  {
    id: 4,
    title: "Smart Watches",
    image: "/icons/ip17.jpg",
    href: "/category/smart-watches",
  },
  {
    id: 5,
    title: "Cameras",
    image: "/icons/ip17.jpg",
    href: "/category/cameras",
  },
  {
    id: 6,
    title: "Tablets",
    image: "/icons/ip17.jpg",
    href: "/category/tablets",
  },
  {
    id: 7,
    title: "Gaming",
    image: "/icons/ip17.jpg",
    href: "/category/gaming",
  },
  {
    id: 8,
    title: "Accessories",
    image: "/icons/ip17.jpg",
    href: "/category/accessories",
  },
  {
    id: 9,
    title: "Monitors",
    image: "/icons/ip17.jpg",
    href: "/category/monitors",
  },
  {
    id: 10,
    title: "Speakers",
    image: "/icons/ip17.jpg",
    href: "/category/speakers",
  },
  {
    id: 11,
    title: "Keyboards",
    image: "/icons/ip17.jpg",
    href: "/category/keyboards",
  },
  {
    id: 12,
    title: "Mice",
    image: "/icons/ip17.jpg",
    href: "/category/mice",
  },
  {
    id: 13,
    title: "Power Banks",
    image: "/icons/ip17.jpg",
    href: "/category/power-banks",
  },
  {
    id: 14,
    title: "Chargers",
    image: "/icons/ip17.jpg",
    href: "/category/chargers",
  },
  {
    id: 15,
    title: "Storage",
    image: "/icons/ip17.jpg",
    href: "/category/storage",
  },
  {
    id: 16,
    title: "Printers",
    image: "/icons/ip17.jpg",
    href: "/category/printers",
  },
  {
    id: 17,
    title: "TVs",
    image: "/icons/ip17.jpg",
    href: "/category/tvs",
  },
  {
    id: 18,
    title: "Smart Home",
    image: "/icons/ip17.jpg",
    href: "/category/smart-home",
  },
];

export default function Categories() {
  return (
    <section className="w-full py-8 sm:py-10 lg:py-12">
      <div className="mx-auto w-full max-w-[1280px] px-4 sm:px-6 lg:px-8">
        {/* Title */}
        <h2 className="text-center text-xl font-semibold text-neutral-900 sm:text-2xl">
          Categories
        </h2>

        {/* Categories */}
        <div className="mt-8 grid grid-cols-3 gap-x-4 gap-y-7 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 md:grid-cols-6 lg:grid-cols-9 lg:gap-x-7 lg:gap-y-9">
          {categories.map((category) => (
            <Link
              key={category.id}
              href={category.href}
              className="
                group
                flex
                flex-col
                items-center
                text-center
                outline-none
              "
            >
              {/* Image */}
              <div
                className="
                  relative
                  h-[72px]
                  w-[72px]
                  overflow-hidden
                  rounded-full
                  bg-neutral-100
                  transition-all
                  duration-300
                  group-hover:scale-105
                  group-hover:shadow-md
                  group-focus-visible:ring-2
                  group-focus-visible:ring-red-500
                  group-focus-visible:ring-offset-2
                  sm:h-[82px]
                  sm:w-[82px]
                  lg:h-[92px]
                  lg:w-[92px]
                "
              >
                <Image
                  src={category.image}
                  alt={category.title}
                  fill
                  sizes="
                    (max-width: 640px) 72px,
                    (max-width: 1024px) 82px,
                    92px
                  "
                  className="object-cover"
                />
              </div>

              {/* Title */}
              <span
                className="
                  mt-3
                  line-clamp-2
                  text-xs
                  font-medium
                  text-neutral-700
                  transition-colors
                  duration-200
                  group-hover:text-red-500
                  sm:text-sm
                "
              >
                {category.title}
              </span>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}