import Stories from "@/components/templates/digikalstories";
import TopBanner from "@/components/banner/Banner";
import AmazingSliders from "@/components/organisms/AmazingSliders";
import CardShop from "@/components/templates/cardStore";
import Categories from "@/components/home/Categories";
import SpecialOffers from "@/components/organisms/SpecialOffers";

async function getFlashSaleCampaign() {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_BASE_URL}/api/campaigns/flash-sale/products`,
      {
        next: { revalidate: 60 },
      },
    );

    if (!res.ok) return null;

    return res.json();
  } catch (error) {
    console.error("Error fetching flash sale:", error);
    return null;
  }
}



async function getSpecialOffer(){
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_BASE_URL}/api/campaigns/special/products`, {
      next: { revalidate: 60 },
    })
    if(!res.ok) return null
    return res.json()
  }catch(error){
    console.log(error)
    return null
  }
}


async function getBanners() {
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_BASE_URL}/api/banners`, {
      next: { revalidate: 300 },
    });

    if (!res.ok) return null;
    

    return res.json();
  } catch (error) {
    console.error("Error fetching banners:", error);
    return null;
  }
}
export default async function Home() {
  const [flashSaleData, bannerData,specialOff] = await Promise.all([
    getFlashSaleCampaign(),
    getBanners(),
    getSpecialOffer()
  ]);

  const middleSection = bannerData?.data?.find(
    (item) => item.key === "home_middle_4",
  );

  return (
    <main className="w-full overflow-x-hidden">
 
      <section className="w-full">
        <TopBanner data={bannerData} />
      </section>

    
      <div
        className="
          container
          mx-auto
          w-full
          px-4
          sm:px-5
          md:px-6
          lg:px-8
          xl:px-10
          2xl:px-12
        "
      >
        {}
        {flashSaleData && (
          <section className="w-full">
            <AmazingSliders
              campaign={flashSaleData.campaign}
              products={flashSaleData.data}
            />
          </section>
        )}

        {}
        <section className="w-full">
          <CardShop data={middleSection} />
        </section>

        <section className="w-full">
          <Categories />
        </section>
        {specialOff && (
        <section  className="w-full">
          <SpecialOffers campaign={specialOff.campaign}  
           products={specialOff?.data} />
        </section>

        )}
        
      </div>
    </main>
  );
}
