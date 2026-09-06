 import { BsBag } from "react-icons/bs";
 import { SiGooglestreetview } from "react-icons/si";
 import { GrFavorite } from "react-icons/gr";
 import { FaRegComment } from "react-icons/fa";
 
 
 export const menuItems = [
    {
      id: "orders",
      label: "orders",
      href: "/profile/orders",
      icon: BsBag,
    },
    {
      id: "address",
      label: "address",
      href: "/profile/addresess",
      icon: SiGooglestreetview,
    },
    {
      id: "lists",
      label: "lists",
      href: "/profile/lists/",
      icon: GrFavorite,
    },
    {
      id: "comments",
      label: "comments",
      href: "/profile/comments",
      icon: FaRegComment,
    },
  ];