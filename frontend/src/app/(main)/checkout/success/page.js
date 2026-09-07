// src/app/checkout/success/page.js

"use client";

import React, { useState, useEffect, useRef } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import {
    CheckCircle,
    Home,
    Printer,
    Copy,
    Eye,
    Download,
    RefreshCw,
} from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import toast from "react-hot-toast";
import jsPDF from "jspdf";
import html2canvas from "html2canvas";
import QRCode from "qrcode";

import { useGetOrder } from "@/core/services/queries";
import { formatPrice } from "@/core/utils/formatPrice";
import { formatDate } from "@/core/utils/formatDate";

// ============================================================
// BARCODE GENERATOR
// ============================================================
function generateBarcode(text) {

    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    canvas.width = 300;
    canvas.height = 60;


    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    const chars = text.split("");
    let x = 10;
    const barWidth = 2;
    const height = 45;

    chars.forEach((char) => {
        const code = char.charCodeAt(0);
        const pattern = (code % 8) + 1;
        for (let i = 0; i < pattern; i++) {
            ctx.fillStyle = i % 2 === 0 ? "#000000" : "#ffffff";
            ctx.fillRect(x, 10, barWidth, height);
            x += barWidth;
        }
        x += barWidth * 2;
    });

    ctx.fillStyle = "#000000";
    ctx.font = "12px monospace";
    ctx.textAlign = "center";
    ctx.fillText(text, canvas.width / 2, height + 25);

    return canvas.toDataURL("image/png");
}

// ============================================================
// MAIN COMPONENT
// ============================================================
function SuccessPage() {
    const router = useRouter();
    const searchParams = useSearchParams();
    const orderId = searchParams.get("order");

    const [isPrinting, setIsPrinting] = useState(false);
    const [isCopied, setIsCopied] = useState(false);
    const [printStarted, setPrintStarted] = useState(false);
    const [showStamp, setShowStamp] = useState(false);
    const [barcodeImage, setBarcodeImage] = useState(null);
    const [qrCodeImage, setQrCodeImage] = useState(null);
    const [scroll, setscroll] = useState(0);

    const receiptRef = useRef(null);

    const { data: orderData, isLoading } = useGetOrder(orderId);
    const order = orderData?.data;


    useEffect(() => {

        const int = setInterval(() => {

            if (scroll <= 80) {
                setscroll(prev => prev + 10)
                window.scrollTo(0, scroll);
            }
        }, 100);
        return () => {
            clearInterval(int);
        };
    }, [scroll]);

    useEffect(() => {
        if (order?.order_number) {
            setBarcodeImage(generateBarcode(order.order_number));
            QRCode.toDataURL(
                `${window.location.origin}/profile/orders/${order.id}`,
                { width: 150, margin: 2 },
                (err, url) => !err && setQrCodeImage(url)
            );
        }
    }, [order]);

    useEffect(() => {
        if (!orderId) router.push("/");
    }, [orderId, router]);

    // شروع انیمیشن چاپ بعد از لود شدن
    useEffect(() => {
        if (order && !isLoading) {
            const timer = setTimeout(() => setPrintStarted(true), 500);
            const stampTimer = setTimeout(() => setShowStamp(true), 4000);
            return () => {
                clearTimeout(timer);
                clearTimeout(stampTimer);
            };
        }
    }, [order, isLoading]);

    const handleRePrint = () => {
        setShowStamp(false);
        setPrintStarted(false);
        setTimeout(() => setPrintStarted(true), 2500);
        setTimeout(() => setShowStamp(true), 4000);
    };

    const handleCopy = () => {
        if (order?.order_number) {
            navigator.clipboard.writeText(order.order_number);
            setIsCopied(true);
            toast.success("Order number copied!");
            setTimeout(() => setIsCopied(false), 3000);
        }
    };

    const handleDownloadPDF = async () => {
        if (!receiptRef.current) return;
        toast.loading("Generating PDF...");
        try {
            const canvas = await html2canvas(receiptRef.current, {
                scale: 2,
                backgroundColor: "#ffffff",
                useCORS: true,
                logging: false,
            });
            const imgData = canvas.toDataURL("image/png");
            const pdf = new jsPDF("p", "mm", "a4");
            const imgWidth = 210;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
            pdf.save(`receipt-${order?.order_number}.pdf`);
            toast.dismiss();
            toast.success("PDF downloaded successfully!");
        } catch (error) {
            toast.dismiss();
            toast.error("Failed to generate PDF");
        }
    };



    if (isLoading) {
        return (
            <div className="flex min-h-screen items-center justify-center bg-gradient-to-b from-neutral-50 to-white">
                <div className="h-10 w-10 animate-spin rounded-full border-4 border-red-500 border-t-transparent" />
            </div>
        );
    }

    return (
        <div className="min-h-screen py-3 sm:py-6 overflow-hidden">
            <div className="mx-auto max-w-md px-6">
                {/* Title */}
                <motion.div
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    className="text-center  sm:mb-6"
                >
                    <h1 className="text-2xl sm:text-3xl font-bold text-neutral-800">
                        Payment Successful
                    </h1>
                    <p className="text-sm text-neutral-500 mt-1">
                        You&apos;re all set — now let the receipt roll!
                    </p>
                </motion.div>

                {/* ===== PRINTER MACHINE ===== */}
                <div className="relative w-full mt-5 overflow-hidden flex flex-col items-center">
                    {/* Printer body */}
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 1, ease: "easeOut" }}
                        className="relative z-20 w-[100%] max-w-[460px]"
                    >
                        {/* Printer top */}
                        <div
                            className="relative h-[70px] sm:h-[85px] rounded-t-[40px] rounded-b-lg bg-gradient-to-b from-neutral-800 via-neutral-900 to-black shadow-2xl"
                        >
                            {/* Highlight */}
                            <div className="absolute top-2 left-6 right-6 h-1 rounded-full bg-white/10" />

                            {/* Slot (Paper output) */}
                            <div className="absolute bottom-0 left-1/2 -translate-x-1/2 w-[85%] h-[8px] bg-black rounded-b-lg shadow-inner">
                                <div className="absolute inset-x-2 top-0 h-[2px] bg-neutral-700 rounded-full" />
                            </div>

                            {/* LED */}
                            <motion.div
                                animate={{ opacity: [1, 0.3, 1] }}
                                transition={{ duration: 1.5, repeat: Infinity }}
                                className="absolute top-4 right-6 w-2 h-2 rounded-full bg-green-400 shadow-[0_0_8px_rgba(74,222,128,0.8)]"
                            />
                        </div>
                    </motion.div>

                    {/* ===== RECEIPT PAPER (Comes out of printer) ===== */}
                    <div className="relative w-[92%] max-w-[380px] pb-10 -mt-2 z-10 overflow-visible">
                        <AnimatePresence>
                            {printStarted && (
                                <motion.div
                                    ref={receiptRef}
                                    initial={{ y: "-100%", opacity: 0 }}
                                    animate={{ y: 0, opacity: 1 }}
                                    exit={{ y: "-100%", opacity: 0 }}
                                    transition={{
                                        duration: 3.5,
                                        ease: [0.25, 0.1, 0.25, 1],
                                    }}
                                    className="relative bg-white shadow-xl"

                                >
                                    {/* Top serrated edge */}
                                    <div
                                        className="absolute -top-[10px] left-0 right-0 h-[10px] bg-white"
                                        style={{
                                            clipPath:
                                                "polygon(0 100%, 5% 0, 10% 100%, 15% 0, 20% 100%, 25% 0, 30% 100%, 35% 0, 40% 100%, 45% 0, 50% 100%, 55% 0, 60% 100%, 65% 0, 70% 100%, 75% 0, 80% 100%, 85% 0, 90% 100%, 95% 0, 100% 100%)",
                                        }}
                                    />

                                    {/* Content */}
                                    <div className="p-5 sm:p-6 font-mono">
                                        {/* Header */}
                                        <div className="text-center border-b border-dashed border-neutral-200 pb-4">
                                            <div className="flex items-center justify-center gap-2">
                                                {/* لوگو یا آیکون دیجی‌کالا */}
                                                <svg
                                                    className="h-5 w-5 sm:h-6 sm:w-6 text-red-500"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                >
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                                                </svg>
                                                <h2 className="text-sm sm:text-base font-bold tracking-wider text-neutral-900">
                                                    DIGIKALA
                                                </h2>
                                            </div>
                                            <p className="text-[10px] sm:text-xs text-neutral-500 mt-1 tracking-wide">
                                                DIGITAL SERVICES RECEIPT
                                            </p>
                                            <div className="flex items-center justify-center gap-2 mt-2">
                                                <span className="h-px w-8 bg-red-400" />
                                                <span className="text-[8px] text-red-400 font-medium">✦</span>
                                                <span className="h-px w-8 bg-red-400" />
                                            </div>
                                        </div>

                                        {/* Client Info */}
                                        <div className="space-y-1 text-[11px] sm:text-xs text-neutral-800 mb-3">
                                            <div className="flex justify-between">
                                                <span>CLIENT:</span>
                                                <span className="font-bold uppercase">
                                                    {order?.address?.receiver_name || "GUEST"}
                                                </span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span>ORDER:</span>
                                                <span className="font-bold">
                                                    #{order?.order_number}
                                                </span>
                                            </div>
                                        </div>

                                        {/* Big Amount + Stamp */}
                                        <div className="relative py-3 border-b border-dashed border-neutral-300">
                                            <div className="text-2xl sm:text-3xl font-black text-neutral-900">
                                                ${formatPrice(order?.total_amount)}
                                            </div>
                                            <p className="text-[10px] text-neutral-600 mt-1">
                                                {formatDate(order?.created_at)} | INVOICE PAID
                                            </p>

                                            {/* PAID STAMP */}
                                            <AnimatePresence>
                                                {showStamp && (
                                                    <motion.div
                                                        initial={{ scale: 3, opacity: 0, rotate: 0 }}
                                                        animate={{
                                                            scale: 1,
                                                            opacity: 1,
                                                            rotate: -15,
                                                        }}
                                                        transition={{
                                                            type: "spring",
                                                            stiffness: 200,
                                                            damping: 12,
                                                            duration: 0.5,
                                                        }}
                                                        className="absolute -right-2 top-2 sm:right-0 sm:top-0"
                                                    >
                                                        <div
                                                            className="border-[3px] border-red-500 rounded-md px-3 py-1.5 text-center"
                                                            style={{
                                                                boxShadow:
                                                                    "inset 0 0 2px rgba(239,68,68,0.4)",
                                                            }}
                                                        >
                                                            <div className="text-red-500 font-black text-lg sm:text-xl leading-none tracking-wider">
                                                                PAID
                                                            </div>
                                                            <div className="text-red-500 text-[8px] font-bold mt-0.5">
                                                                {formatDate(order?.paid_at || order?.created_at)}
                                                            </div>
                                                        </div>
                                                    </motion.div>
                                                )}
                                            </AnimatePresence>
                                        </div>

                                        {/* Items */}
                                        <div className="py-3 space-y-1.5 border-b border-dashed border-neutral-300">
                                            {order?.items?.map((item) => (
                                                <div
                                                    key={item.id}
                                                    className="flex justify-between text-[11px] sm:text-xs text-neutral-800"
                                                >
                                                    <span className="pr-2 truncate">
                                                        {item.quantity}X {item.product_title}
                                                    </span>
                                                    <span className="font-semibold whitespace-nowrap">
                                                        ${formatPrice(item.total)}
                                                    </span>
                                                </div>
                                            ))}
                                        </div>

                                        {/* Totals */}
                                        <div className="py-3 space-y-1 text-[11px] sm:text-xs text-neutral-800 border-b border-dashed border-neutral-300">
                                            <div className="flex justify-between">
                                                <span>Subtotal</span>
                                                <span>${formatPrice(order?.subtotal)}</span>
                                            </div>
                                            {order?.discount_amount > 0 && (
                                                <div className="flex justify-between text-red-600">
                                                    <span>Discount (Promo)</span>
                                                    <span>-${formatPrice(order?.discount_amount)}</span>
                                                </div>
                                            )}
                                            {order?.shipping_cost > 0 && (
                                                <div className="flex justify-between">
                                                    <span>Shipping</span>
                                                    <span>${formatPrice(order?.shipping_cost)}</span>
                                                </div>
                                            )}
                                        </div>

                                        {/* Grand Total */}
                                        <div className="py-3 flex justify-between items-center border-b border-dashed border-neutral-300">
                                            <span className="text-sm font-bold text-neutral-900">
                                                GRAND TOTAL
                                            </span>
                                            <span className="text-lg sm:text-xl font-black text-neutral-900">
                                                ${formatPrice(order?.total_amount)}
                                            </span>
                                        </div>

                                        {/* Thank You */}
                                        <div className="text-center py-3">
                                            <p className="text-[11px] sm:text-xs font-bold text-neutral-800 leading-relaxed">
                                                THANK YOU FOR PARTNERING
                                                <br />
                                                WITH BIZY MEDIA!
                                            </p>
                                        </div>

                                        {/* Barcode */}
                                        {barcodeImage && (
                                            <div className="flex flex-col self-center justify-content-center items-center py-2">
                                                <img
                                                    src={barcodeImage}
                                                    alt="Barcode"
                                                    className="w-[80%] m-auto -mr-3 items-center self-center h-auto"
                                                />
                                                <p className="text-[10px] font-mono text-neutral-700 mt-1">
                                                    TXN-
                                                    {order?.order_number?.replace("ORD-", "") ||
                                                        "000000"}
                                                    -BM
                                                </p>
                                            </div>
                                        )}
                                    </div>

                                    {/* Bottom serrated edge */}
                                    <div
                                        className="absolute -bottom-[10px] left-0 right-0 h-[10px] bg-white"
                                        style={{
                                            clipPath:
                                                "polygon(0 0, 5% 100%, 10% 0, 15% 100%, 20% 0, 25% 100%, 30% 0, 35% 100%, 40% 0, 45% 100%, 50% 0, 55% 100%, 60% 0, 65% 100%, 70% 0, 75% 100%, 80% 0, 85% 100%, 90% 0, 95% 100%, 100% 0)",
                                        }}
                                    />
                                </motion.div>
                            )}
                        </AnimatePresence>
                    </div>
                </div>

                {/* ===== ACTIONS ===== */}
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    animate={{ opacity: showStamp ? 1 : 0, y: showStamp ? 0 : 30 }}
                    transition={{ duration: 0.5 }}
                    className="mt-10 sm:mt-14 grid grid-cols-3 gap-2 sm:gap-3"
                >
                    <button
                        type="button"
                        onClick={handleRePrint}
                        className="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 rounded-xl border border-neutral-200 bg-white px-2 py-3 text-[11px] sm:text-xs font-medium text-neutral-700 shadow-sm transition-all hover:bg-neutral-50 hover:shadow-md"
                    >
                        <Printer className="h-4 w-4" />
                        <span>Re-print</span>
                    </button>

                    <button
                        type="button"
                        onClick={handleDownloadPDF}
                        className="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 rounded-xl border border-neutral-200 bg-white px-2 py-3 text-[11px] sm:text-xs font-medium text-neutral-700 shadow-sm transition-all hover:bg-neutral-50 hover:shadow-md"
                    >
                        <Download className="h-4 w-4" />
                        <span>Download</span>
                    </button>

                    <button
                        type="button"
                        onClick={handleCopy}
                        className="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 rounded-xl border border-neutral-200 bg-white px-2 py-3 text-[11px] sm:text-xs font-medium text-neutral-700 shadow-sm transition-all hover:bg-neutral-50 hover:shadow-md"
                    >
                        {isCopied ? (
                            <CheckCircle className="h-4 w-4 text-green-500" />
                        ) : (
                            <Copy className="h-4 w-4" />
                        )}
                        <span>{isCopied ? "Copied" : "Copy"}</span>
                    </button>
                </motion.div>

                {/* Continue Shopping */}
                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: showStamp ? 1 : 0 }}
                    transition={{ delay: 0.3 }}
                    className="mt-3"
                >
                    <Link
                        href="/"
                        className="flex w-full items-center justify-center gap-2 rounded-xl bg-neutral-900 px-4 py-3 text-sm font-semibold text-white transition-all hover:bg-neutral-800 shadow-lg"
                    >
                        <Home className="h-4 w-4" />
                        Continue Shopping
                    </Link>
                </motion.div>
            </div>

            {/* Print styles */}
            <style jsx global>{`
        @media print {
          body * {
            visibility: hidden;
          }
        }
      `}</style>
        </div>
    );
}

export default SuccessPage;