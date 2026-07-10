'use client';

import { useState, useEffect, useCallback, useRef } from 'react';
import axios from 'axios';

export default function InfiniteScrollTest() {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(false);
    const [hasMore, setHasMore] = useState(true);
    const [nextCursor, setNextCursor] = useState(null);
    const [error, setError] = useState(null);

    const loadProducts = useCallback(async (cursor = null) => {
        if (loading) return;
        if (!hasMore) return;

        setLoading(true);
        setError(null);

        try {
            const response = await axios.get('http://127.0.0.1:8000/api/products/infinite', {
                params: {
                    limit: 10,
                    cursor: cursor,
                },
                headers: {
                    'Accept': 'application/json',
                },
                timeout: 10000, // 10 ثانیه تایم‌اوت
            });

            console.log('Response:', response.data); // 👈 لاگ کن

            const { data, meta } = response.data;

            if (cursor) {
                setProducts(prev => [...prev, ...data]);
            } else {
                setProducts(data);
            }

            setHasMore(meta.has_more);
            setNextCursor(meta.next_cursor);

        } catch (error) {
            console.error('Error loading products:', error);
            setError(error.message);
        } finally {
            setLoading(false);
        }
    }, [loading, hasMore]);

    useEffect(() => {
        loadProducts(null);
    }, []);

    const observerRef = useRef();
    const lastProductRef = useRef();

    useEffect(() => {
        if (loading) return;
        if (!hasMore) return;

        if (observerRef.current) {
            observerRef.current.disconnect();
        }

        observerRef.current = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && hasMore && !loading) {
                loadProducts(nextCursor);
            }
        }, {
            rootMargin: '200px',
        });

        if (lastProductRef.current) {
            observerRef.current.observe(lastProductRef.current);
        }

        return () => {
            if (observerRef.current) {
                observerRef.current.disconnect();
            }
        };
    }, [loading, hasMore, nextCursor, loadProducts, products]);

    return (
        <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '20px' }}>
            <h1>🔄 Infinite Scroll Test</h1>
            <p>Total: {products.length} products loaded</p>
            
            {error && (
                <div style={{ background: '#ff6b6b', color: 'white', padding: '15px', borderRadius: '8px', marginBottom: '20px' }}>
                    ❌ Error: {error}
                </div>
            )}

            <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
                {products.map((product, index) => {
                    const isLast = index === products.length - 1;
                    const variant = product.variants?.[0];
                    
                    return (
                        <div 
                            key={product.id} 
                            ref={isLast ? lastProductRef : null}
                            style={{
                                border: '1px solid #ddd',
                                borderRadius: '8px',
                                padding: '15px',
                                background: '#fff',
                            }}
                        >
                            <h3>{product.title}</h3>
                            <p style={{ color: '#666' }}>{product.short_description}</p>
                            {variant && (
                                <p style={{ color: '#e74c3c', fontWeight: 'bold' }}>
                                    {variant.sale_price?.toLocaleString() || variant.price?.toLocaleString()} تومان
                                </p>
                            )}
                            <p style={{ fontSize: '12px', color: '#999' }}>
                                Brand: {product.brand?.name}
                            </p>
                        </div>
                    );
                })}
            </div>

            {loading && (
                <div style={{ textAlign: 'center', padding: '20px' }}>
                    <div style={{ 
                        display: 'inline-block',
                        width: '40px',
                        height: '40px',
                        border: '4px solid #f3f3f3',
                        borderTop: '4px solid #3498db',
                        borderRadius: '50%',
                        animation: 'spin 1s linear infinite'
                    }} />
                    <p>Loading...</p>
                </div>
            )}

            {!hasMore && products.length > 0 && (
                <div style={{ textAlign: 'center', padding: '20px', color: '#999' }}>
                    ✨ همه محصولات نمایش داده شدند ({products.length} محصول)
                </div>
            )}

            {!loading && products.length === 0 && !error && (
                <div style={{ textAlign: 'center', padding: '40px', color: '#999' }}>
                    📭 محصولی یافت نشد
                </div>
            )}

            <style jsx>{`
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `}</style>
        </div>
    );
}