import { useQuery, useQueryClient } from "@tanstack/react-query";

import api from "../config/api";
import { getGuestSessionId } from "../utils/gustSession";

export const useGetUserData = ()=>{
    const queryFn =()=> api.get("/api/who-am-i")
    const queryKey = ['user-data']
    return useQuery({queryKey,queryFn})
}

export const useGetMainCategories = ()=>{
    const queryFn = ()=>api.get("/api/categories")
    const queryKey = ["categories-data"]
    return useQuery({queryKey,queryFn})
}

export const useGetSubCategory = (activeId) => {
  const queryKey = ["subCategory", activeId];
  
  
  const queryFn = () => api.get(`/api/categories/${activeId}`); 
  
  return useQuery({
    queryKey,
    queryFn,
    enabled: !!activeId, 
  });
};


export const usegetBrandsFilter = () => {
  const queryFn = () => api.get("/api/brands");
  const queryKey = ["brands-filter"];
  
  return useQuery({
    queryFn,
    queryKey,
    
    select: (response) => response?.data?.data || [], 
  });
};

const searchProducts = async (query) => {
  const res = await api.get(`/api/products`, {
    params: { search: query, },
  });
  return res.data;
};

export function useSearchProducts(query) {
  return useQuery({
    queryKey: ["search-products", query],
    queryFn: () => searchProducts(query),
    enabled: query.trim().length >= 2, 
    staleTime: 1000 * 30,
  });
}

const fetchCart = async () => {
  const sessionId = getGuestSessionId();

  const res = await api.get("/api/cart", {
    headers: {
      "X-Session-Id": sessionId,
    },
  });

  return res.data;
};

export function useCart() {
  return useQuery({
    queryKey: ["cart"],
    queryFn: fetchCart,
  });
}

export const useGetCommentProduct = (productId)=>{
  const queryKey = ["comments",productId]
  const queryFn = ()=>api.get(`/api/products/${productId}/reviews`)

  return useQuery({queryKey,queryFn})
}



export const useGetWishlist = (perPage = 10) => {
  const queryKey = ["wishlist", perPage];

  const queryFn = async () => {
    console.log("WISHLIST QUERY STARTED");

    const response = await api.get("/api/wishlist", {
      params: {
        "per-page": perPage,
      },
    });

    console.log("WISHLIST RESPONSE:", response);
    console.log("WISHLIST DATA:", response?.data);

    return response?.data ?? [];
  };

  return useQuery({
    queryKey,
    queryFn,
  });
};

export const useGetCategoriesHomePage = ()=>{
    const queryFn = async ()=>{
     const response = await api.get("/api/categories")
      return response?.data ?? [];
      
    }
    const queryKey = ["categories-data_p"]
    return useQuery({queryKey,queryFn})
}

export const useGetProvinces = () => {
  return useQuery({
    queryKey: ["provinces"],
    queryFn: async () => {
      const response = await api.get("/api/provinces");

      return response?.data?.data || [];
    },
    staleTime: 1000 * 60 * 60,
  });
};

export const useGetCities = (provinceId) => {
  return useQuery({
    queryKey: ["cities", provinceId],
    queryFn: async () => {
      const response = await api.get("/api/cities", {
        params: {
          province_id: provinceId,
        },
      });

      return response?.data?.data || [];
    },
    enabled: Boolean(provinceId),
    staleTime: 1000 * 60 * 60,
  });
};

export const useGetAddresses = () => {
  return useQuery({
    queryKey: ["addresses"],
    queryFn: async () => {
      const response = await api.get("/api/addresses", {
        params: {
          page: 1,
          per_page: 50,
        },
      });

      return response?.data || {};
    },
  });
};


// export const useToggleWishlist  = () => {
//   return useQuery({
//     queryKey: ["wishlist"],
//     queryFn: async () => {
//       const response = await api.get("/api/wishlist", {
//         params: {
//           page: 1,
//           per_page: 50,
//         },
//       });

//       return response?.data;
//     },
//   });
// };



export const useWishlistIds = () => {
  return useQuery({
    queryKey: ["wishlist_ids"],
    queryFn: async () => {

      const response = await api.get("/api/wishlist");

      const items = response?.data?.data ?? response?.data ?? [];

         const ids = Array.isArray(items)
        ? items.map((item) => item?.product_id ?? item?.id ?? item)
        : [];

      return ids.filter(Boolean);
    },
    staleTime: 10 * 60 * 1000,
    gcTime: 30 * 60 * 1000,
    refetchOnMount: false,
    refetchOnWindowFocus: false,
  });
};


