import {
  QueryClient,
  useMutation,
  useQueryClient,
} from "@tanstack/react-query";
import { setCookie } from "../utils/cookie";
import api from "../config/api";
import { data } from "autoprefixer";

import { getGuestSessionId, clearGuestSessionId } from "../utils/gustSession";

export const useSendOtp = () => {
  const mutationFn = (data) => api.post("/api/send-otp", data);
  return useMutation({ mutationFn });
};

export const useCheckOtp = () => {
  const queryClient = useQueryClient();

  const mutationFn = async (data) => {
    const res = await api.post("/api/check-otp", data);

    if (!res?.data?.access_token) {
      throw new Error(res?.data?.message || "کد وارد شده صحیح نیست");
    }

    return res;
  };

  const onSuccess = (data) => {
    setCookie("access_token", data?.data?.access_token, 30);
    setCookie("refresh_token", data?.data?.refresh_token, 365);
    queryClient.invalidateQueries({ queryKey: ["user-data"] });

    const sessionId = getGuestSessionId();
    if (sessionId) {
      try {
        api.post("/api/cart/merge", { session_id: sessionId });
        clearGuestSessionId();
        queryClient.invalidateQueries({ queryKey: ["cart"] });
      } catch (error) {
        console.error("Cart merge failed:", error);
      }
    }
  };

  return useMutation({ mutationFn, onSuccess });
};

export const useAddProductsBasket = () => {
  const queryClient = useQueryClient();
  const mutationFn = (data) =>
    api.post(`/api/cart/items`, { ...data, session_id: getGuestSessionId() });
  const onSuccess = () => {
    queryClient.invalidateQueries({ queryKey: ["cart"] });
  };
  return useMutation({ mutationFn, onSuccess });
};

export const useUpdateCartItem = () => {
  const queryClient = useQueryClient();

  const mutationFn = ({ cartItemId, quantity }) =>
    api.put(`/api/cart/items/${cartItemId}`, { quantity });

  const onSuccess = () => {
    queryClient.invalidateQueries({ queryKey: ["cart"],refetchType:"active" });
  };

  return useMutation({ mutationFn, onSuccess });
};

export const useRemoveCartItem = () => {
  const queryClient = useQueryClient();

  const mutationFn = (cartItemId) =>
    api.delete(`/api/cart/items/${cartItemId}`);

  const onSuccess = () => {
    queryClient.invalidateQueries({ queryKey: ["cart"] });
  };

  return useMutation({ mutationFn, onSuccess });
};
