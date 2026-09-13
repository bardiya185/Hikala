
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
    links: [
      "Help Center",
      "Shipping",
      "Returns",
      "Order Tracking",
      "FAQ",
    ],
  },
  {
    id: 3,
    title: "Information",
    links: [
      "About Store",
      "Our Services",
      "Support",
    ],
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

  const handleNewsletterSubmit = (event) => {
    event.preventDefault();
  };

  return (
    <footer
      className="
        w-full

        bg-white
        text-neutral-500

        transition-colors
        duration-200

        dark:bg-neutral-950
        dark:text-neutral-400
      "
    >
      {/* =====================================================
          TOP BORDER
      ====================================================== */}
      <div
        className="
          w-full
          border-t
          border-neutral-200

          dark:border-neutral-800
        "
      />

      <div
        className="
          mx-auto
          w-full
          max-w-[1470px]

          px-4
          sm:px-6
          lg:px-8
        "
      >
        {/* =====================================================
            TOP SECTION
        ====================================================== */}
        <div
          className="
            flex
            items-center
            justify-between

            py-6
            sm:py-8
          "
        >
          {/* Logo */}
          <Link
            href="/"
            aria-label="Go to homepage"
            className="
              inline-flex
              items-center

              rounded-lg

              transition-opacity
              duration-200

              hover:opacity-80

              focus-visible:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500/30
            "
          >
            <Image
              src="/icons/en-logo.svg"
              width={200}
              height={60}
              alt="Digikala"
              className="
                h-auto
                w-[150px]

                sm:w-[180px]
                lg:w-[200px]
              "
            />
          </Link>

          {/* Back To Top */}
          <button
            type="button"
            onClick={handleBackToTop}
            className="
              group

              flex
              items-center
              gap-2

              rounded-xl

              border
              border-neutral-200

              bg-white

              px-3.5
              py-2.5

              text-xs
              font-medium

              text-neutral-500

              transition-all
              duration-200

              hover:border-neutral-300
              hover:bg-neutral-50
              hover:text-neutral-800

              focus-visible:outline-none
              focus-visible:ring-2
              focus-visible:ring-red-500/30

              dark:border-neutral-800
              dark:bg-neutral-900
              dark:text-neutral-400

              dark:hover:border-neutral-700
              dark:hover:bg-neutral-800
              dark:hover:text-neutral-100
            "
          >
            <span>Back to top</span>

            <FaArrowUp
              className="
                h-3.5
                w-3.5

                transition-transform
                duration-200

                group-hover:-translate-y-0.5
              "
            />
          </button>
        </div>

        {/* =====================================================
            SUPPORT INFORMATION
        ====================================================== */}
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
            sm:gap-5

            dark:border-neutral-800
          "
        >
          <div className="flex flex-col">
            <span
              className="
                text-[11px]
                font-medium
                uppercase
                tracking-wide

                text-neutral-400

                dark:text-neutral-500
              "
            >
              Phone Support
            </span>

            <a
              href="tel:+1800123456"
              className="
                mt-1
                w-fit

                text-sm
                font-medium

                text-neutral-700

                transition-colors

                hover:text-red-500

                focus-visible:outline-none
                focus-visible:ring-2
                focus-visible:ring-red-500/30

                dark:text-neutral-200
                dark:hover:text-red-400
              "
            >
              +1 800 123 456
            </a>
          </div>

          <div
            className="
              hidden
              h-8
              w-px

              bg-neutral-200

              sm:block

              dark:bg-neutral-800
            "
          />

          <span
            className="
              text-xs
              text-neutral-500

              dark:text-neutral-400
            "
          >
            Available every day
          </span>

          <div
            className="
              hidden
              h-8
              w-px

              bg-neutral-200

              sm:block

              dark:bg-neutral-800
            "
          />

          <span
            className="
              text-xs
              text-neutral-500

              dark:text-neutral-400
            "
          >
            Fast customer service
          </span>
        </div>

        {/* =====================================================
            FEATURE ITEMS
        ====================================================== */}
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
              className="
                group

                flex
                flex-col
                items-center

                text-center
              "
            >
              <div
                className="
                  relative

                  h-20
                  w-20

                  overflow-hidden

                  rounded-2xl

                  border
                  border-neutral-100

                  bg-neutral-50

                  transition-all
                  duration-300

                  group-hover:-translate-y-1
                  group-hover:border-neutral-200
                  group-hover:shadow-md

                  sm:h-24
                  sm:w-24

                  dark:border-neutral-800
                  dark:bg-neutral-900

                  dark:group-hover:border-neutral-700
                  dark:group-hover:shadow-[0_8px_30px_rgba(0,0,0,0.25)]
                "
              >
                <Image
                  src={item.image}
                  alt={item.title}
                  fill
                  sizes="96px"
                  className="
                    object-cover

                    transition-transform
                    duration-300

                    group-hover:scale-105
                  "
                />
              </div>

              <p
                className="
                  mt-3

                  text-xs
                  font-medium

                  text-neutral-500

                  sm:text-sm

                  dark:text-neutral-400
                "
              >
                {item.title}
              </p>
            </div>
          ))}
        </div>

        {/* =====================================================
            FOOTER LINKS + NEWSLETTER
        ====================================================== */}
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

            dark:border-neutral-800
          "
        >
          {/* ===================================================
              COLUMN 1
          ==================================================== */}
          <div>
            <h3
              className="
                text-sm
                font-semibold

                text-neutral-800

                dark:text-neutral-100
              "
            >
              {footerColumns[0].title}
            </h3>

            <div className="mt-4 flex flex-col gap-3">
              {footerColumns[0].links.map((link) => (
                <Link
                  key={link}
                  href="#"
                  className="
                    w-fit

                    text-xs
                    text-neutral-500

                    transition-colors

                    hover:text-red-500

                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-red-500/20

                    dark:text-neutral-400
                    dark:hover:text-red-400
                  "
                >
                  {link}
                </Link>
              ))}
            </div>
          </div>

          {/* ===================================================
              COLUMN 2
          ==================================================== */}
          <div>
            <h3
              className="
                text-sm
                font-semibold

                text-neutral-800

                dark:text-neutral-100
              "
            >
              {footerColumns[1].title}
            </h3>

            <div className="mt-4 flex flex-col gap-3">
              {footerColumns[1].links.map((link) => (
                <Link
                  key={link}
                  href="#"
                  className="
                    w-fit

                    text-xs
                    text-neutral-500

                    transition-colors

                    hover:text-red-500

                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-red-500/20

                    dark:text-neutral-400
                    dark:hover:text-red-400
                  "
                >
                  {link}
                </Link>
              ))}
            </div>
          </div>

          {/* ===================================================
              COLUMN 3
          ==================================================== */}
          <div>
            <h3
              className="
                text-sm
                font-semibold

                text-neutral-800

                dark:text-neutral-100
              "
            >
              {footerColumns[2].title}
            </h3>

            <div className="mt-4 flex flex-col gap-3">
              {footerColumns[2].links.map((link) => (
                <Link
                  key={link}
                  href="#"
                  className="
                    w-fit

                    text-xs
                    text-neutral-500

                    transition-colors

                    hover:text-red-500

                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-red-500/20

                    dark:text-neutral-400
                    dark:hover:text-red-400
                  "
                >
                  {link}
                </Link>
              ))}
            </div>
          </div>

          {/* ===================================================
              SOCIAL + NEWSLETTER
          ==================================================== */}
          <div>
            <h3
              className="
                text-sm
                font-semibold

                text-neutral-800

                dark:text-neutral-100
              "
            >
              Stay Connected
            </h3>

            {/* Social Icons */}
            <div className="mt-4 flex items-center gap-2.5">
              {socialLinks.map((social) => {
                const Icon = social.icon;

                return (
                  <Link
                    key={social.id}
                    href={social.href}
                    aria-label={social.name}
                    className="
                      flex
                      h-9
                      w-9
                      items-center
                      justify-center

                      rounded-xl

                      border
                      border-neutral-200

                      bg-neutral-50

                      text-neutral-500

                      transition-all
                      duration-200

                      hover:-translate-y-0.5
                      hover:border-neutral-300
                      hover:bg-neutral-100
                      hover:text-neutral-800

                      focus-visible:outline-none
                      focus-visible:ring-2
                      focus-visible:ring-red-500/30

                      dark:border-neutral-800
                      dark:bg-neutral-900
                      dark:text-neutral-400

                      dark:hover:border-neutral-700
                      dark:hover:bg-neutral-800
                      dark:hover:text-white
                    "
                  >
                    <Icon className="h-3.5 w-3.5" />
                  </Link>
                );
              })}
            </div>

            <p
              className="
                mt-4

                text-xs
                leading-6

                text-neutral-500

                dark:text-neutral-400
              "
            >
              Subscribe to receive our latest updates.
            </p>

            {/* Newsletter */}
            <form
              onSubmit={handleNewsletterSubmit}
              className="
                mt-4
                flex
                w-full
                max-w-[360px]
              "
            >
              <label
                htmlFor="footer-email"
                className="sr-only"
              >
                Email address
              </label>

              <input
                id="footer-email"
                type="email"
                placeholder="Enter your email"
                autoComplete="email"
                className="
                  min-w-0
                  flex-1

                  rounded-l-xl

                  border
                  border-r-0
                  border-neutral-200

                  bg-neutral-50

                  px-3
                  py-2.5

                  text-xs
                  text-neutral-800

                  outline-none

                  transition-all

                  placeholder:text-neutral-400

                  focus:border-red-500
                  focus:bg-white
                  focus:ring-2
                  focus:ring-red-500/10

                  dark:border-neutral-800
                  dark:bg-neutral-900
                  dark:text-neutral-100

                  dark:placeholder:text-neutral-500

                  dark:focus:border-red-500
                  dark:focus:bg-neutral-900
                "
              />

              <button
                type="submit"
                className="
                  shrink-0

                  rounded-r-xl

                  bg-neutral-900

                  px-4
                  py-2.5

                  text-xs
                  font-semibold

                  text-white

                  transition-all
                  duration-200

                  hover:bg-neutral-800

                  focus-visible:outline-none
                  focus-visible:ring-2
                  focus-visible:ring-red-500/30

                  active:scale-[0.98]

                  dark:bg-white
                  dark:text-neutral-950

                  dark:hover:bg-neutral-200
                "
              >
                Subscribe
              </button>
            </form>
          </div>
        </div>

        {/* =====================================================
            BOTTOM SECTION
        ====================================================== */}
        <div
          className="
            border-t
            border-neutral-200

            py-5

            dark:border-neutral-800
          "
        >
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
            <span>
              © 2026 All rights reserved.
            </span>

            <span className="hidden sm:inline">
              |
            </span>

            <span className="flex items-center gap-1">
              Developed by Sobhan

              <FaHeart
                className="
                  h-3
                  w-3

                  text-red-500

                  transition-transform
                  duration-200

                  hover:scale-110
                "
              />
            </span>
          </div>
        </div>
      </div>
    </footer>
  );
}

