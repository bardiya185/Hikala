import Image from "next/image";
import styles from "./page.module.css";
import Stories from "@/components/templates/digikalstories";
import TopBanner from "@/components/banner/Banner";
import AmazingProducts from "@/components/templates/amazingProducts";
import AmazingSliders from "@/components/organisms/AmazingSliders";
import CardShop from "@/components/templates/cardStore";


async function getAmazingProducts(){
  const res = await fetch(process.env.NEXT_PUBLIC_BASE_URL + "/api/products?")

  return res.json()


}

async function getIamgeBanner(){
const res = await fetch(process.env.NEXT_PUBLIC_BASE_URL+ "/api/banners")

return res.json()
}

export default async function Home() {
  const productDiscounts = await getAmazingProducts()
  const banner = await getIamgeBanner()
  return (
   <div>
    <Stories/>
    <TopBanner data={banner} />
    {/* <AmazingProducts/> */}
    <div className=" container  mx-auto px-28">
    <AmazingSliders data={productDiscounts?.data}  />
    <CardShop/>

    </div>
    
   </div>
  );
}
