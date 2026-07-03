 import { BsBag } from "react-icons/bs";
 import { SiGooglestreetview } from "react-icons/si";
 import { GrFavorite } from "react-icons/gr";
 import { FaRegComment } from "react-icons/fa";
 
 
 export const menuItems = [
    {
      id: "orders",
      label: "سفارش ها",
      href: "/profile/orders",
      icon: BsBag,
    },
    {
      id: "address",
      label: "ادرس ها",
      href: "/profile/addresess",
      icon: SiGooglestreetview,
    },
    {
      id: "lists",
      label: "لیست ها",
      href: "/profile/lists/",
      icon: GrFavorite,
    },
    {
      id: "comments",
      label: "دیدگاه و پرسش ها",
      href: "/profile/comments",
      icon: FaRegComment,
    },
  ];