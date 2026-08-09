

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

return (
    <div className="cart-container">
        <h2>سبد خرید</h2>
        
        {/* نمایش تعداد آیتم‌ها */}
        <p>تعداد آیتم‌ها: {data.items?.length || 0}</p>
        
        {/* لیست آیتم‌ها */}
        {data.items?.map((item) => (
            <div key={item.id} className="cart-item">
                <h3>{item.product.title}</h3>
                <p>قیمت: {item.price} تومان</p>
                
                <div className="quantity-control">
                    <button 
                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                        disabled={item.quantity <= 1}
                    >
                        -
                    </button>
                    <span>{item.quantity}</span>
                    <button 
                        onClick={() => updateQuantity(item.id, item.quantity + 1)}
                    >
                        +
                    </button>
                </div>
                
                <button 
                    onClick={() => removeFromCart(item.id)}
                    className="remove-btn"
                >
                    حذف
                </button>
            </div>
        ))}
        
        {/* جمع کل */}
        <div className="cart-summary">
            <p>جمع کل: {data.total} تومان</p>
        </div>
        
        {/* دکمه بروزرسانی دستی */}
        <button 
            onClick={() => refetch()} 
            disabled={isFetching}
        >
            {isFetching ? 'در حال بروزرسانی...' : 'بروزرسانی'}
        </button>
    </div>
);

}
