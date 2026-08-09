import { useCart } from "@/core/services/queries";


export default function Cart() {

    const { 
        data, 
        isLoading, 
        isError, 
        error, 
        refetch,
        isFetching 
    } = useCart();

   // حالت‌های مختلف
   if (isLoading) {
    return <div>در حال بارگذاری سبد خرید...</div>;
}

if (isError) {
    return (
        <div>
            <p>خطا در دریافت سبد خرید: {error.message}</p>
            <button onClick={() => refetch()}>تلاش مجدد</button>
        </div>
    );
}

// اگر سبد خرید خالی باشه
if (!data || data.items?.length === 0) {
    return <div>سبد خرید خالی است</div>;
}



}
