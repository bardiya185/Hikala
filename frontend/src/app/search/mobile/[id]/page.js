import AllMobile from "@/components/templates/allProductsMobile";
import React from "react";

const fetchDataOnServer = async (id) => {
  const res = await fetch(
    process.env.NEXT_PUBLIC_BASE_URL + `/api/products/infinite`,
  );
  return res.json();
  return { id };
};

export default async function AllMob({ params }) {
  const { id } = await params;
  const initialData = await fetchDataOnServer(id);
  console.log(initialData);
  return (
    <div>
      <AllMobile data={initialData} />
    </div>
  );
}
