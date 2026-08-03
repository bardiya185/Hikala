"use client";
import Link from "next/link";
import Image from "next/image";
import React from "react";
import { useState } from "react";
import { FcRating } from "react-icons/fc";
import { TbBrandSpeedtest } from "react-icons/tb";
import { RiErrorWarningFill } from "react-icons/ri";
import { IoMdCheckmark, IoMdCheckmarkCircle } from "react-icons/io";
import { FaFire } from "react-icons/fa6";
import { VscCopilotSuccess } from "react-icons/vsc";
import { gsap } from "gsap";

import { SplitText } from "gsap/SplitText";
import { useEffect } from "react";
import ColorSwatchSelector from "@/components/atom/ColorSwatchSelector";
import { IoWarningOutline } from "react-icons/io5";
import { FaRegStar } from "react-icons/fa";
import { BsDot } from "react-icons/bs";
import ViewDetailsButton from "@/components/atom/ViewDetailsButton";
import ProductMoreDetials from "@/components/organisms/ProductMoreDetials";
import {
  useAddProductsBasket,
  useRemoveCartItem,
  useUpdateCartItem,
} from "@/core/services/mutations";
import toast from "react-hot-toast";
import { useCart } from "@/core/services/queries";

gsap.registerPlugin(SplitText);

function ProductsDe({ data }) {
  const [selectedVariant, setSelectedVariant] = useState(
    data?.variants?.[0] || null,
  );

  const [quantity, setQuantity] = useState(1);

  console.log(data);
  const { data: p, isPending, mutate } = useAddProductsBasket();

  const { data: cart } = useCart();
  const { mutate: updateCartItem } = useUpdateCartItem();
  const { mutate: removeCartItem } = useRemoveCartItem();

  const cartItem = cart?.items?.find(
    (item) => item.product_variant_id === selectedVariant?.id,
  );

  const handleIncrease = () => {
    if (quantity >= (selectedVariant?.stock ?? 1)) return;
    updateCartItem({
      cartItemId: cartItem.id,
      quantity: cartItem.quantity + 1,
    });
  };

  const handleDeacrease = () => {
    if (cartItem.quantity === 1) {
      removeCartItem(cartItem.id, {
        onSuccess: () => toast.success("Removed from cart"),
      });
      return;
    }
    updateCartItem({
      cartItemId: cartItem.id,
      quantity: cartItem.quantity - 1,
    });
  };

  useEffect(() => {
    if (selectedVariant && quantity > selectedVariant.stock) {
      setQuantity(selectedVariant.stock > 0 ? selectedVariant.stock : 1);
    }
  }, [selectedVariant]);

  const handleAddToCarts = async () => {
    if (!data) return;
    mutate(
      { product_variant_id: selectedVariant?.id, quantity: quantity },
      {
        onSuccess: (data) => {
          console.log(data);
          toast.success("add succesfully");
        },
      },
    );
  };

  const colors = [
    { id: 1, name: "White", hex: "#ffffff", borderClass: "border-neutral-300" },
    { id: 2, name: "Black", hex: "#000000", borderClass: "border-black" },
    { id: 3, name: "Purple", hex: "#6366f1", borderClass: "border-indigo-500" },
    { id: 4, name: "Blue", hex: "#3b82f6", borderClass: "border-blue-500" },
  ];
  const [selectedColor, setSelectedColor] = useState(colors[2]);

  useEffect(() => {
    const split = new SplitText(".animate-split", {
      type: "words",
      wordsClass: "inline-block overflow-hidden",
    });

    gsap.from(split.words, {
      duration: 0.8,
      y: 20,
      opacity: 0,
      stagger: 0.1,
      ease: "back.out(1.7)",
    });

    return () => {
      split.revert();
    };
  }, []);

  return (
    <>
      <div className="max-w-[1440px] ">
        <div className="flex gap-3">
          <div className="flex flex-col">
            <p className="text-red-600 bg-red-500/30 w-4/4 h-[50px] pt-2">
              فروش ویژه
            </p>
            <Image
              src="/icons/test.webp"
              width={450}
              height={500}
              alt="phone"
              className=" "
            />
          </div>
          <div className="flex flex-col">
            <p>{data?.description}</p>
            <p className="text-neutral-400 mt-3">{data?.description}</p>

            <div className="w-4/4 border border-solid border-neutral-500 mt-3"></div>
            <div className="flex items-center gap-3 mt-4">
              <FcRating className="w-[22px] h-[22px] text-yellow-500" />
              <span>4.3</span>
              <span>31 خریدار</span>
              <Link href="/" className="">
                109 دیدگاه
              </Link>
              <Link href="/">260 پرسش</Link>
            </div>
            <div className="flex flex-col ">
              <div className="flex flex-col">
                <span className="mt-5">Color</span>
                <ColorSwatchSelector
                  variants={data?.variants}
                  selectedVariant={selectedVariant}
                  onSelectVariant={setSelectedVariant}
                />
              </div>

              <div className="flex mt-5 items-center h-[75px] border border-solid borde-neutral-400 mt-8 bg-blue-400/20 gap-2 ">
                <TbBrandSpeedtest className="text-blue-600 w-[25px] h-[25px]  " />
                <p>تحویل امروز با ارسال سریع دیجی‌کالا</p>
              </div>
              <p className="mt-7">ویژگی ها</p>
              <div className="  grid grid-cols-3 gap-y-3 mt-4">
                <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                  <div className="flex flex-col">
                    <p className="text-neutral-500 text-[12px]">
                      Display technology
                    </p>
                    <p className="text-black/80">
                      {" "}
                      {data?.variants[0]?.attributes[5]?.value}
                    </p>
                  </div>
                </div>
                <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                  <div className="flex flex-col">
                    <p className="text-neutral-500 text-[12px]">
                      Operating system version
                    </p>
                    <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                  </div>
                </div>
                <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                  <div className="flex flex-col">
                    <p className="text-neutral-500 text-[12px]">
                      Main camera resolution
                    </p>
                    <p className="text-black/80">
                      {" "}
                      {data?.variants[0]?.attributes[6]?.value}
                    </p>
                  </div>
                </div>
                <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                  <div className="flex flex-col">
                    <p className="text-neutral-500 text-[12px]">Size</p>
                    <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                  </div>
                </div>
                <div className="w-[200px] h-[100px] bg-neutral-300 rounded-lg text-center py-7">
                  <div className="flex flex-col">
                    <p className="text-neutral-500 text-[12px]">
                      Display technology
                    </p>
                    <p className="text-black/80"> dynamic LTPO AMOLED 2</p>
                  </div>
                </div>
              </div>
              <ViewDetailsButton />
              <div className="  flex mt-5">
                <IoWarningOutline className=" text-neutral-400" />

                <p className="max-w-[700px] text-justify text-[14px]  text-neutral-400">
                  The possibility of returning goods in the mobile category with
                  the reason "cancellation of purchase" is only accepted if the
                  product seal has not been opened. All Digikala phones have a
                  registry guarantee. In case of a registry problem, you can
                  return the purchased phone after the 30-day legal deadline.
                </p>
              </div>
              <div className=" mt-8 w-full  h-fit  border border-solid border-neutral-400 rounded-[20px]">
                <div className=" pl-5 flex items-center pt-4 ">
                  <FaRegStar className="text-purple-600 w-[30px] h-[30px] " />
                  <p className="text-[18px] font-bold text-purple-600">
                    Free shipping for Plus members
                  </p>
                </div>
                <div className="pl-5 flex items-center">
                  <BsDot className="w-[25px] h-[25px] text-purple-600" />
                  <p>4 Free digital delivery</p>
                </div>
                <div className="pl-5 flex items-center">
                  <BsDot className="w-[25px] h-[25px] text-purple-600" />
                  <p>2 Supermarket delivery</p>
                </div>
                <div className="pl-5 flex items-center">
                  <BsDot className="w-[25px] h-[25px] text-purple-600" />
                  <p>4 free 45-minute deliveries</p>
                </div>
                <div className="pl-5 flex items-center">
                  <BsDot className="w-[25px] h-[25px] text-purple-600" />
                  <p>Dedicated support</p>
                </div>
                <div className="pl-5 flex items-center">
                  <BsDot className="w-[25px] h-[25px] text-purple-600" />
                  <p>
                    Fast and free delivery of digital goods (Tehran and Karaj
                    only)
                  </p>
                </div>
                <button className="pl-5 text-[14px] text-blue-500">
                  Buy a subscription
                </button>
                <div className="flex justify-end">
                  <Image
                    src="/icons/free-delivery.svg"
                    width={100}
                    height={100}
                    alt="bus"
                  />
                </div>
              </div>
            </div>
          </div>
          <div className="w-[360px] h-[500px] border border-solid border-neutral-500 rounded-lg mt-16">
            <div className="flex justify-between px-5 pt-5">
              <p>Seller</p>
              <span className="text-orange-400 animate-split font-medium">
                3 other sellers
              </span>
            </div>
            <div className="flex items-center gap-2 pl-5 mt-5">
              <Image
                src="/icons/idigi.jfif"
                width={22}
                height={22}
                alt="icon"
                className="rounded-lg"
              />
              <span>Digikala</span>
            </div>
            <div className="flex items-center gap-2 pl-12 mt-3">
              <p className="text-[10px] text-neutral-400">Performance</p>
              <span className="text-[14px] text-green-600">Excellent</span>
            </div>
            <div className=" w-[300] ml-5 border border-solid border-neutral-400"></div>
            <div className="flex items-center pl-5 mt-5 gap-2">
              <span className="w-[30px] bg-red-600 rounded-md text-center text-white ">
                3%
              </span>
              <del className="text-neutral-400">3.500 $</del>
            </div>
            <span className="pl-5 mt-3 inline-block text-[20px]">
              {data?.variants?.base_price} $
            </span>
            <div className="pl-5 mt-3 flex items-center gap-2">
              <FaFire className="w-[22px] h-[22px] text-orange-600 " />
              <span className="text-orange-400 animate-split font-me">
                Only 1 item left in stock.
              </span>
            </div>
            <div className="px-5 mt-4">
              <button
                onClick={handleAddToCarts}
                className="w-[310px] h-[40px]  bg-red-500 rounded-lg px-5 pl-5 text-white"
              >
                Add to Basket
              </button>
              <div className="flex items-center text-neutral-400 mt-4 gap-3">
                <VscCopilotSuccess className="w-[22px] h-[22px] text-neutral-400 gap-2 " />
                <span>Sadrtel 18-month warranty</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className=" w-1/1 mx-10 mt-5 border-t border-solid border-neutral-400">
        <div className="flex justify-between items-center gap-1 text-neutral-400 whitespace-nowrap mt-5 ">
          <Image
            src="/icons/express-delivery.svg"
            width={70}
            height={70}
            alt="+"
          />
          <p>Express delivery possible</p>

          <Image src="/icons/support.svg" width={70} height={70} alt="+" />
          <p>24 hours a day ,7 days a week</p>

          <Image
            src="/icons/cash-on-delivery.svg"
            width={70}
            height={70}
            alt="+"
          />
          <p>Possibility of payment on site</p>
          <Image src="/icons/days-return.svg" width={70} height={70} alt="+" />
          <p>Seven-day return guaratee</p>

          <Image
            src="/icons/original-products.svg"
            width={70}
            height={70}
            alt="+"
          />
          <p>Guarantee of authenticity of the product</p>
        </div>
      </div>
      <div className="border-b border-solid border-neutral-400 w-1/1 mx-10 mt-8"></div>
      <div>
        <ProductMoreDetials data={data} selectedVariant={selectedVariant} />
      </div>
    </>
  );
}

export default ProductsDe;
