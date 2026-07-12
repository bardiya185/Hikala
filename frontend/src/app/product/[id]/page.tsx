'use client';

import { useState, useEffect } from 'react';
import { useParams } from 'next/navigation';
import Image from 'next/image';

export default function ProductDetail() {
    const { id } = useParams();
    const [product, setProduct] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedVariant, setSelectedVariant] = useState(null);

    // ===== دریافت محصول =====
    useEffect(() => {
        if (id) {
            fetchProduct(id);
        }
    }, [id]);

    const fetchProduct = async (productId) => {
        setLoading(true);
        setError(null);

        try {
            const response = await fetch(
                `http://127.0.0.1:8000/api/products/${productId}`,
                {
                    headers: {
                        'Accept': 'application/json',
                    },
                }
            );

            if (!response.ok) {
                throw new Error('محصولی یافت نشد');
            }

            const data = await response.json();
            setProduct(data.data);
            
            if (data.data.variants?.length > 0) {
                setSelectedVariant(data.data.variants[0]);
            }

        } catch (err) {
            console.error('Error fetching product:', err);
            setError(err.message || 'خطا در دریافت اطلاعات محصول');
        } finally {
            setLoading(false);
        }
    };

    // ===== تغییر تنوع =====
    const handleVariantChange = (variant) => {
        setSelectedVariant(variant);
    };

    // ===== لودینگ =====
    if (loading) {
        return (
            <div className="loading-container">
                <div className="spinner"></div>
                <p>در حال بارگذاری...</p>
            </div>
        );
    }

    // ===== خطا =====
    if (error) {
        return (
            <div className="error-container">
                <p>❌ {error}</p>
                <button onClick={() => fetchProduct(id)}>تلاش مجدد</button>
            </div>
        );
    }

    // ===== محصول وجود نداره =====
    if (!product) {
        return (
            <div className="not-found">
                <p>محصولی یافت نشد</p>
            </div>
        );
    }

    // ===== پیدا کردن عکس اصلی =====
    const mainImage = product.images?.find(img => img.is_main) || product.images?.[0];

    // ===== Placeholder برای عکس =====
    const PLACEHOLDER_IMAGE = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 500 500"%3E%3Crect width="500" height="500" fill="%23f8f9fa"/%3E%3Ctext x="50%25" y="50%25" text-anchor="middle" dy=".3em" font-family="Arial" font-size="24" fill="%23adb5bd"%3E📷 No Image%3C/text%3E%3C/svg%3E';

    return (
        <div className="product-detail">
            <div className="product-container">
                {/* ===== تصویر محصول ===== */}
                <div className="product-image-section">
                    <div className="main-image-wrapper">
                        <img
                            src={mainImage?.image_url || PLACEHOLDER_IMAGE}
                            alt={product.title}
                            className="main-image"
                            onError={(e) => {
                                e.target.src = PLACEHOLDER_IMAGE;
                            }}
                        />
                    </div>

                    {/* ===== تصاویر کوچک ===== */}
                    {product.images?.length > 1 && (
                        <div className="thumbnails">
                            {product.images.map((img) => (
                                <div 
                                    key={img.id} 
                                    className={`thumbnail-wrapper ${img.is_main ? 'active' : ''}`}
                                >
                                    <img
                                        src={img.image_url}
                                        alt={product.title}
                                        className="thumbnail"
                                        onError={(e) => {
                                            e.target.src = PLACEHOLDER_IMAGE;
                                        }}
                                    />
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* ===== اطلاعات محصول ===== */}
                <div className="product-info-section">
                    {/* برند */}
                    {product.brand && (
                        <div className="brand">
                            <span>برند:</span>
                            <strong>{product.brand.name}</strong>
                        </div>
                    )}

                    {/* عنوان */}
                    <h1 className="title">{product.title}</h1>

                    {/* دسته‌بندی‌ها */}
                    {product.categories?.length > 0 && (
                        <div className="categories">
                            <span>دسته‌بندی:</span>
                            {product.categories.map((cat, index) => (
                                <span key={cat.id}>
                                    {cat.name}
                                    {index < product.categories.length - 1 && ' > '}
                                </span>
                            ))}
                        </div>
                    )}

                    {/* توضیحات مختصر */}
                    {product.short_description && (
                        <p className="short-description">{product.short_description}</p>
                    )}

                    <div className="divider"></div>

                    {/* ===== قیمت ===== */}
                    <div className="price-section">
                        {selectedVariant?.sale_price ? (
                            <>
                                <span className="sale-price">
                                    {selectedVariant.sale_price.toLocaleString()} تومان
                                </span>
                                <span className="original-price">
                                    {selectedVariant.price.toLocaleString()} تومان
                                </span>
                                <span className="discount-percent">
                                    {Math.round(((selectedVariant.price - selectedVariant.sale_price) / selectedVariant.price) * 100)}%
                                </span>
                            </>
                        ) : (
                            <span className="price">
                                {selectedVariant?.price?.toLocaleString() || 'نامشخص'} تومان
                            </span>
                        )}
                    </div>

                    {/* ===== موجودی ===== */}
                    <div className="stock">
                        <span>موجودی:</span>
                        <span className={selectedVariant?.stock > 0 ? 'in-stock' : 'out-of-stock'}>
                            {selectedVariant?.stock > 0 ? `${selectedVariant.stock} عدد` : 'ناموجود'}
                        </span>
                    </div>

                    {/* ===== انتخاب تنوع ===== */}
                    {product.variants?.length > 1 && (
                        <div className="variants">
                            <label>انتخاب تنوع:</label>
                            <div className="variant-buttons">
                                {product.variants.map((variant, index) => (
                                    <button
                                        key={variant.id}
                                        className={`variant-btn ${selectedVariant?.id === variant.id ? 'active' : ''}`}
                                        onClick={() => handleVariantChange(variant)}
                                    >
                                        تنوع {index + 1}
                                        {variant.attributes?.length > 0 && (
                                            <span className="variant-attributes">
                                                {variant.attributes.map(attr => attr.value).join(' - ')}
                                            </span>
                                        )}
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ===== ویژگی‌ها ===== */}
                    {selectedVariant?.attributes?.length > 0 && (
                        <div className="attributes">
                            <h3>ویژگی‌ها:</h3>
                            <div className="attributes-grid">
                                {selectedVariant.attributes.map((attr, index) => (
                                    <div key={index} className="attribute-item">
                                        <span className="attr-name">{attr.attribute_name}:</span>
                                        <span className="attr-value">
                                            {attr.color_code && (
                                                <span 
                                                    className="color-dot" 
                                                    style={{ backgroundColor: attr.color_code }}
                                                />
                                            )}
                                            {attr.value}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ===== توضیحات کامل ===== */}
                    {product.description && (
                        <div className="description">
                            <h3>توضیحات کامل:</h3>
                            <div dangerouslySetInnerHTML={{ __html: product.description }} />
                        </div>
                    )}

                    <div className="divider"></div>

                    {/* ===== دکمه‌ها ===== */}
                    <div className="actions">
                        <button className="add-to-cart-btn">
                            🛒 افزودن به سبد خرید
                        </button>
                        <button className="favorite-btn">
                            ♡
                        </button>
                    </div>
                </div>
            </div>

            {/* ===== استایل‌ها ===== */}
            <style jsx>{`
                .product-detail {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                    direction: rtl;
                    font-family: Tahoma, sans-serif;
                }

                .product-container {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 40px;
                    background: #fff;
                    border-radius: 12px;
                    padding: 30px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }

                .product-image-section {
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                }

                .main-image-wrapper {
                    width: 100%;
                    background: #f8f9fa;
                    border-radius: 8px;
                    overflow: hidden;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .main-image {
                    width: 100%;
                    height: auto;
                    max-height: 500px;
                    object-fit: contain;
                    border-radius: 8px;
                }

                .thumbnails {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .thumbnail-wrapper {
                    cursor: pointer;
                    border: 2px solid transparent;
                    border-radius: 8px;
                    overflow: hidden;
                    transition: border-color 0.2s;
                }

                .thumbnail-wrapper:hover {
                    border-color: #3498db;
                }

                .thumbnail-wrapper.active {
                    border-color: #3498db;
                }

                .thumbnail {
                    width: 80px;
                    height: 80px;
                    object-fit: cover;
                    border-radius: 6px;
                }

                .product-info-section {
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                }

                .brand {
                    color: #666;
                    font-size: 14px;
                }

                .brand strong {
                    color: #333;
                }

                .title {
                    font-size: 28px;
                    color: #333;
                    margin: 0;
                }

                .categories {
                    color: #666;
                    font-size: 14px;
                }

                .short-description {
                    color: #555;
                    font-size: 16px;
                    line-height: 1.6;
                }

                .divider {
                    border-top: 1px solid #eee;
                    margin: 5px 0;
                }

                .price-section {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    flex-wrap: wrap;
                }

                .sale-price {
                    font-size: 28px;
                    font-weight: bold;
                    color: #e74c3c;
                }

                .original-price {
                    font-size: 18px;
                    color: #999;
                    text-decoration: line-through;
                }

                .discount-percent {
                    background: #e74c3c;
                    color: #fff;
                    padding: 4px 12px;
                    border-radius: 4px;
                    font-size: 14px;
                    font-weight: bold;
                }

                .price {
                    font-size: 28px;
                    font-weight: bold;
                    color: #333;
                }

                .stock {
                    font-size: 15px;
                }

                .in-stock {
                    color: #27ae60;
                    font-weight: bold;
                }

                .out-of-stock {
                    color: #e74c3c;
                    font-weight: bold;
                }

                .variants {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                }

                .variants label {
                    font-weight: bold;
                    color: #333;
                }

                .variant-buttons {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .variant-btn {
                    padding: 10px 20px;
                    border: 2px solid #ddd;
                    border-radius: 8px;
                    background: #fff;
                    cursor: pointer;
                    transition: all 0.2s;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 4px;
                }

                .variant-btn:hover {
                    border-color: #3498db;
                }

                .variant-btn.active {
                    border-color: #3498db;
                    background: #ebf5fb;
                }

                .variant-attributes {
                    font-size: 12px;
                    color: #666;
                }

                .attributes h3 {
                    margin: 0 0 10px 0;
                    color: #333;
                    font-size: 16px;
                }

                .attributes-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                }

                .attribute-item {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 12px;
                    background: #f8f9fa;
                    border-radius: 4px;
                    font-size: 14px;
                }

                .attr-name {
                    color: #666;
                }

                .attr-value {
                    color: #333;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }

                .color-dot {
                    display: inline-block;
                    width: 20px;
                    height: 20px;
                    border-radius: 50%;
                    border: 1px solid #ddd;
                }

                .description {
                    margin-top: 5px;
                }

                .description h3 {
                    margin: 0 0 10px 0;
                    color: #333;
                    font-size: 16px;
                }

                .description div {
                    color: #555;
                    line-height: 1.8;
                    font-size: 15px;
                }

                .actions {
                    display: flex;
                    gap: 15px;
                    margin-top: 5px;
                }

                .add-to-cart-btn {
                    flex: 1;
                    padding: 14px 30px;
                    background: #e74c3c;
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: background 0.2s;
                }

                .add-to-cart-btn:hover {
                    background: #c0392b;
                }

                .favorite-btn {
                    padding: 14px 20px;
                    background: #fff;
                    color: #e74c3c;
                    border: 2px solid #e74c3c;
                    border-radius: 8px;
                    font-size: 20px;
                    cursor: pointer;
                    transition: all 0.2s;
                }

                .favorite-btn:hover {
                    background: #e74c3c;
                    color: #fff;
                }

                .loading-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    min-height: 400px;
                }

                .spinner {
                    width: 40px;
                    height: 40px;
                    border: 4px solid #f3f3f3;
                    border-top: 4px solid #3498db;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .error-container {
                    text-align: center;
                    padding: 50px;
                }

                .error-container button {
                    margin-top: 15px;
                    padding: 10px 25px;
                    background: #3498db;
                    color: #fff;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                }

                .not-found {
                    text-align: center;
                    padding: 50px;
                    color: #999;
                }

                @media (max-width: 768px) {
                    .product-container {
                        grid-template-columns: 1fr;
                        gap: 20px;
                        padding: 15px;
                    }

                    .title {
                        font-size: 22px;
                    }

                    .sale-price {
                        font-size: 22px;
                    }

                    .attributes-grid {
                        grid-template-columns: 1fr;
                    }

                    .actions {
                        flex-direction: column;
                    }

                    .thumbnail {
                        width: 60px;
                        height: 60px;
                    }
                }
            `}</style>
        </div>
    );
}