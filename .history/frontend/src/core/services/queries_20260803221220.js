import { useQuery } from "@tanstack/react-query";

import api from "../config/api";

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
  const res = await api.get("/api/cart/items");
  return res.data;
};

export function useCart() {
  return useQuery({
    queryKey: ["cart"],
    queryFn: fetchCart,
    staleTime: 1000 * 30,
  });
}