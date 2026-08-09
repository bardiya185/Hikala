import Stories from "@/components/templates/digikalstories";
import TopBanner from "@/components/banner/Banner";
import AmazingSliders from "@/components/organisms/AmazingSliders";
import CardShop from "@/components/templates/cardStore";

// ================================================================
// 🎯 Fetch Flash Sale Campaign + Products
// ================================================================
async function getFlashSaleCampaign() {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_BASE_URL}/api/campaigns/clearance/products`,
      { 
        next: { revalidate: 60 } // ⚡ Cache 60 ثانیه (برای Flash Sale مهمه که تازه باشه)
      }
    );

    if (!res.ok) return null;
    return res.json();
  } catch (error) {
    console.error("Error fetching flash sale:", error);
    return null;
  }
}

// ================================================================
// 🖼️ Fetch Banners
// ================================================================
async function getBanners() {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_BASE_URL}/api/banners`,
      { 
        next: { revalidate: 300 } // 🖼️ Cache 5 دقیقه
      }
    );

    if (!res.ok) return null;
    return res.json();
  } catch (error) {
    console.error("Error fetching banners:", error);
    return null;
  }
}

// ================================================================
// 🏠 Home Page
// ================================================================
export default async function Home() {
  // 🚀 موازی fetch میشن (سریع‌تر)
  const [flashSaleData, bannerData] = await Promise.all([
    getFlashSaleCampaign(),
    getBanners(),
  ]);

  // 🔍 پیدا کردن بنر وسط
  const middleSection = bannerData?.data?.find(
    (item) => item.key === "home_middle_4"
  );

  return (
    <div>
      <Stories />
      <TopBanner data={bannerData} />

      <div className="container mx-auto px-28">
        {/* ⚡ Flash Sale Section */}
        {flashSaleData && (
          <AmazingSliders 
            campaign={flashSaleData.campaign}
            products={flashSaleData.data}
          />
        )}

        {/* 🖼️ Middle Banners */}
        <CardShop data={middleSection} />
      </div>
    </div>
  );
}