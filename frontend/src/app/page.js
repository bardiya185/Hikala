import Image from "next/image";
import styles from "./page.module.css";
import Stories from "@/components/templates/digikalstories";
import TopBanner from "@/components/banner/Banner";

export default function Home() {
  return (
   <div>
    <Stories/>
    <TopBanner/>
    
   </div>
  );
}
