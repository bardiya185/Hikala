"use client";
import { useCart } from "@/core/services/queries";
import { Trash2, Minus, Plus } from "lucide-react";
import { RotatingLines } from "react-loader-spinner";

export default function CartItem({isPending, item, onIncrease, onDecrease, onRemove }) {
  const{data:ll} = useCart()
  console.log(ll?.data)
  console.log(item)

  const product = ll?.data?.items[0]?.variant?.product   || []
  console.log(product)
  const items = ll?.data?.items[0] ||[]

  
  

  
  return (
    <div className=" mx-auto w-[850px] flex flex-col md:flex-row items-start justify-between gap-6 p-5 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">

      <div className="w-48 relative flex-shrink-0  h-32 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center self-center md:self-start">
        <img
          src="/icons/ip17.jpg"
          // alt={item?.product?.title || "product image"}
          className="w-full h-full object-contain p-2"
        />
      </div>

      <div className="flex-1 flex flex-col justify-between gap-4 w-full">

        <div>
          <h3 className="text-base font-bold text-gray-900 leading-snug line-clamp-2">
            {product?.title}
          </h3>
          {/* <p className="text-xs text-gray-400 mt-1">SKU: {variant?.sku}</p> */}
        </div>

        <div className="flex flex-col gap-2 text-xs text-gray-600">
          {/* {colorAttr && ( */}
            <div className="flex items-center gap-2">
              <span
                className="w-3.5 h-3.5 rounded-full border border-gray-300 inline-block"
                // style={{ backgroundColor: colorAttr?.color_code }}
              ></span>
              <span>
                {/* Color: <strong className="text-gray-800">{colorAttr?.value}</strong> */}
              </span>
            </div>
          {/* )} */}

          <div className="flex items-center gap-2">
            <svg className="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <span>18-Month Sadrtel Official Warranty</span>
          </div>
        </div>

        <div className="flex items-center justify-between md:justify-start gap-4 mt-2">
          <div className="flex items-center border border-gray-300 rounded-lg px-2 py-1 gap-3 bg-gray-50/50">
            <button
              onClick={onDecrease}
              className="w-6 h-6 flex items-center justify-center text-gray-600 hover:text-red-500 transition-colors"
            >
              {items?.quantity === 1 ? (
                <Trash2 size={16} className="text-red-500" />
              ) : (
                <Minus size={16} />
              )}
            </button>
              {!isPending ?(<span className="text-sm font-bold text-gray-900 w-5 text-center">
              {items?.quantity}
            </span>):(<RotatingLines
                      visible={true}
                      height="30"
                      width="30"
                      color="red"
                      strokeWidth="5"
                      animationDuration="0.75"
                      ariaLabel="rotating-lines-loading"
                      wrapperStyle={{}}
                      wrapperClass=""
                    />)}
            

            <button
              onClick={onIncrease}
              // disabled={items?.quantity >= 1}
              className="w-6 h-6 flex items-center justify-center text-gray-600 hover:text-black transition-colors disabled:opacity-30"
            >
              <Plus size={16} />
            </button>
          </div>

          <button
            onClick={onRemove}
            className="text-xs text-gray-400 hover:text-red-500 transition-colors"
          >
            Remove item
          </button>
        </div>

      </div>

      <div className="flex flex-row md:flex-col items-end justify-between w-full md:w-auto h-full self-stretch border-t md:border-t-0 pt-4 md:pt-0 border-gray-100">
        <div className="text-right w-full md:w-auto flex flex-row md:flex-col justify-between items-center md:items-end">
          <div>
            <span className="text-2xl font-black text-green-500">
               ${items?.base_price}
            </span>
          </div>
        </div>

        {/* {variant?.stock <= 3 && ( */}
          <div className="hidden md:flex items-center gap-1.5 text-xs text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200/60 mt-auto">
            <svg className="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
              <path fillRule="evenodd" clipRule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path>
            </svg>
            {/* <span>Only {variant.stock} left in stock</span> */}
          </div>
        {/* )} */}
      </div>

    </div>
  );
}
