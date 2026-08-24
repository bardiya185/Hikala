"use client";

import Image from "next/image";
import Link from "next/link";
import {
  FaArrowUp,
  FaInstagram,
  FaLinkedinIn,
  FaTwitter,
  FaHeart,
} from "react-icons/fa";

const featureItems = [
  {
    id: 1,
    image: "/images/footer/feature-1.webp",
    title: "Fast Delivery",
  },
  {
    id: 2,
    image: "/images/footer/feature-2.webp",
    title: "Secure Payment",
  },
  {
    id: 3,
    image: "/images/footer/feature-3.webp",
    title: "Quality Products",
  },
  {
    id: 4,
    image: "/images/footer/feature-4.webp",
    title: "Customer Support",
  },
];

const footerColumns = [
  {
    id: 1,
    title: "Quick Links",
    links: [
      "About Us",
      "Contact Us",
      "Products",
      "Categories",
      "Privacy Policy",
      "Terms & Conditions",
    ],
  },
  {
    id: 2,
    title: "Customer Service",
    links: ["Help Center", "Shipping", "Returns", "Order Tracking", "FAQ"],
  },
  {
    id: 3,
    title: "Information",
    links: ["About Store", "Our Services", "Support"],
  },
];

const socialLinks = [
  {
    id: 1,
    name: "Instagram",
    href: "#",
    icon: FaInstagram,
  },
  {
    id: 2,
    name: "Twitter",
    href: "#",
    icon: FaTwitter,
  },
  {
    id: 3,
    name: "LinkedIn",
    href: "#",
    icon: FaLinkedinIn,
  },
  {
    id: 4,
    name: "Aparat",
    href: "#",
    icon: FaInstagram,
  },
];

export default function Footer() {
  const handleBackToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  };

  return (
    <footer className="w-full bg-white text-neutral-500">
      {/* Top Border */}
      <div className="w-full border-t border-neutral-300" />

      <div className="mx-auto w-full max-w-[1470px] px-4 sm:px-6 lg:px-8">
        {/* =========================================
            TOP SECTION
        ========================================== */}

        <div className="flex items-center justify-between py-6 sm:py-8">
          {/* Support Icon */}
          <div className="flex items-center">
            <div className="flex  items-center justify-center rounded-xl  text-neutral-500 ">
              <img
                src="/icons/en-logo.svg"
                alt="logo"
                className="w-[200px] h-auto"
              />
            </div>
          </div>

          {/* Back To Top */}
          <button
            type="button"
            onClick={handleBackToTop}
            className="
              flex
              items-center
              gap-2
              rounded-lg
              border
              border-neutral-300
              px-4
              py-2
              text-xs
              text-neutral-500
              transition-colors
              hover:border-neutral-400
              hover:bg-neutral-50
              hover:text-neutral-700
            "
          >
            <span>Back to top</span>

            <FaArrowUp className="h-3.5 w-3.5" />
          </button>
        </div>

        {/* =========================================
            SUPPORT INFORMATION
        ========================================== */}

        <div
          className="
            flex
            flex-col
            gap-4
            border-b
            border-neutral-200
            pb-7
            sm:flex-row
            sm:items-center
            sm:justify-start
            sm:gap-5
          "
        >
          <div className="flex flex-col">
            <span className="text-xs text-neutral-400">Phone Support</span>

            <span className="mt-1 text-sm text-neutral-600">
              +1 800 123 456
            </span>
          </div>

          <div className="hidden h-8 w-px bg-neutral-300 sm:block" />

          <span className="text-xs text-neutral-500">Available every day</span>

          <div className="hidden h-8 w-px bg-neutral-300 sm:block" />

          <span className="text-xs text-neutral-500">
            Fast customer service
          </span>
        </div>

        {/* =========================================
            FEATURE ITEMS
        ========================================== */}

        <div
          className="
            grid
            grid-cols-2
            gap-6
            py-8
            sm:grid-cols-4
            sm:gap-8
            lg:py-10
          "
        >
          {featureItems.map((item) => (
            <div
              key={item.id}
              className="flex flex-col items-center text-center"
            >
              <div
                className="
                  relative
                  h-20
                  w-20
                  overflow-hidden
                  rounded-xl
                  bg-neutral-100
                  sm:h-24
                  sm:w-24
                "
              >
                <Image
                  src={item.image}
                  alt={item.title}
                  fill
                  sizes="96px"
                  className="object-cover"
                />
              </div>

              <p className="mt-3 text-xs text-neutral-500 sm:text-sm">
                {item.title}
              </p>
            </div>
          ))}
        </div>

        {/* =========================================
            FOOTER LINKS + NEWSLETTER
        ========================================== */}

        <div
          className="
            grid
            grid-cols-1
            gap-10
            border-t
            border-neutral-200
            py-9
            sm:grid-cols-2
            lg:grid-cols-4
            lg:gap-12
          "
        >
          {/* Column 1 */}
          <div>
            <h3 className="text-sm font-semibold text-neutral-700">
              {footerColumns[0].title}
            </h3>

            <div className="mt-4 flex flex-col gap-3">
              {footerColumns[0].links.map((link) => (
                <Link
                  key={link}
                  href="#"
                  className="w-fit text-xs text-neutral-500 transition-colors hover:text-neutral-800"
                >
                  {link}
                </Link>
              ))}
            </div>
          </div>

          {/* Column 2 */}
          <div>
            <h3 className="text-sm font-semibold text-neutral-700">
              {footerColumns[1].title}
            </h3>

            <div className="mt-4 flex flex-col gap-3">
              {footerColumns[1].links.map((link) => (
                <Link
                  key={link}
                  href="#"
                  className="w-fit text-xs text-neutral-500 transition-colors hover:text-neutral-800"
                >
                  {link}
                </Link>
              ))}
            </div>
          </div>

          {/* Column 3 */}
          <div>
            <h3 className="text-sm font-semibold text-neutral-700">
              {footerColumns[2].title}
            </h3>

            <div className="mt-4 flex flex-col gap-3">
              {footerColumns[2].links.map((link) => (
                <Link
                  key={link}
                  href="#"
                  className="w-fit text-xs text-neutral-500 transition-colors hover:text-neutral-800"
                >
                  {link}
                </Link>
              ))}
            </div>
          </div>

          {/* Social + Newsletter */}
          <div>
            <h3 className="text-sm font-semibold text-neutral-700">
              Stay Connected
            </h3>

            {/* Social Icons */}
            <div className="mt-4 flex items-center gap-3">
              {socialLinks.map((social) => {
                const Icon = social.icon;

                return (
                  <Link
                    key={social.id}
                    href={social.href}
                    aria-label={social.name}
                    className="
                      flex
                      h-8
                      w-8
                      items-center
                      justify-center
                      rounded-md
                      bg-neutral-100
                      text-neutral-500
                      transition-colors
                      hover:bg-neutral-200
                      hover:text-neutral-700
                    "
                  >
                    <Icon className="h-3.5 w-3.5" />
                  </Link>
                );
              })}
            </div>

            <p className="mt-4 text-xs leading-6 text-neutral-500">
              Subscribe to receive our latest updates.
            </p>

            {/* Newsletter */}
            <form className="mt-4 flex w-full max-w-[360px]">
              <input
                type="email"
                placeholder="Enter your email"
                className="
                  min-w-0
                  flex-1
                  rounded-l-lg
                  border
                  border-neutral-300
                  bg-neutral-100
                  px-3
                  py-2.5
                  text-xs
                  text-neutral-700
                  outline-none
                  transition-all
                  placeholder:text-neutral-400
                  focus:border-neutral-400
                  focus:bg-white
                "
              />

              <button
                type="submit"
                className="
                  shrink-0
                  rounded-r-lg
                  bg-neutral-500
                  px-4
                  py-2.5
                  text-xs
                  font-medium
                  text-white
                  transition-colors
                  hover:bg-neutral-600
                "
              >
                Subscribe
              </button>
            </form>
          </div>
        </div>

        {/* =========================================
            BOTTOM SECTION
        ========================================== */}

        <div className="border-t border-neutral-300 py-5">
          <div
            className="
              flex
              flex-col
              items-center
              justify-center
              gap-2
              text-center
              text-xs
              text-neutral-400
              sm:flex-row
              sm:gap-3
            "
          >
            <span>© 2026 All rights reserved.</span>

            <span className="hidden sm:inline">|</span>

            <span className="flex items-center gap-1">
              Developed by Sobhan
              <FaHeart className="h-3 w-3 text-red-500" />
            </span>
          </div>
        </div>
      </div>
    </footer>
  );
}
