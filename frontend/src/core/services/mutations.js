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
      throw new Error(
        res?.data?.message || "کد وارد شده صحیح نیست"
      );
    }

    return res;
  };

  const onSuccess = async (data) => {
    setCookie("access_token", data?.data?.access_token, 30);
    setCookie("refresh_token", data?.data?.refresh_token, 365);

    queryClient.invalidateQueries({
      queryKey: ["user-data"],
    });

    const sessionId = getGuestSessionId();

    if (sessionId) {
      try {
        await api.post("/api/cart/merge", {
          session_id: sessionId,
        });

        clearGuestSessionId();

        queryClient.invalidateQueries({
          queryKey: ["cart"],
        });
      } catch (error) {
        console.error("Cart merge failed:", error);
      }
    }
  };

  return useMutation({
    mutationFn,
    onSuccess,
  });
};

export const useAddProductsBasket = () => {
  const queryClient = useQueryClient();

  const mutationFn = (data) => {
    const sessionId = getGuestSessionId();
    console.log("sseeion id",sessionId)

    return api.post(
      "/api/cart/items",
      data,
      {
        headers: {
          "X-Session-Id": sessionId
        },
      }
    );
  };

  const onSuccess = () => {
    queryClient.invalidateQueries({
      queryKey: ["cart"],
    });
  };

  return useMutation({
    mutationFn,
    onSuccess,
  });
};

export const useUpdateCartItem = () => {
  const queryClient = useQueryClient();

  const mutationFn = ({ itemId, quantity }) => {
    const sessionId = getGuestSessionId();

    return api.put(
      `/api/cart/items/${itemId}`,
      {
        quantity,
      },
      {
        headers: {
          "X-Session-Id": sessionId,
        },
      }
    );
  };

  const onSuccess = () => {
    queryClient.invalidateQueries({
      queryKey: ["cart"],
      refetchType: "active",
    });
  };

  return useMutation({
    mutationFn,
    onSuccess,
  });
};

export const useRemoveCartItem = () => {
  const queryClient = useQueryClient();
  const sessionId = getGuestSessionId()

  const mutationFn = (cartItemId) =>
    api.delete(`/api/cart/items/${cartItemId},`,{
      headers:{
        "X-Session-Id": sessionId,
      }
     
    });

  const onSuccess = () => {
    queryClient.invalidateQueries({ queryKey: ["cart"] });
  };

  return useMutation({ mutationFn, onSuccess });
};


export const useCreateProductReview = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (reviewData) =>
      api.post("/api/reviews", reviewData),

    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({
        queryKey: ["comments", variables.product_id],
      });
    },
  });
};






export const useToggleWishlist = () => {
  const queryClient = useQueryClient();

  const mutationFn = (productId) => {
    if (!productId) {
      throw new Error("Product ID is required");
    }

    console.log(
      "Toggle wishlist product:",
      productId,
    );

    return api.post(
      `/api/wishlist/${productId}/toggle`,
    );
  };

  const onSuccess = (response) => {
    console.log(
      "Wishlist toggle success:",
      response?.data,
    );

    /*
     * اگر query مربوط به wishlist داری،
     * بعد از toggle دوباره اطلاعات را می‌گیریم.
     */

    queryClient.invalidateQueries({
      queryKey: ["wishlist"],
    });
  };

  const onError = (error) => {
    console.error(
      "Wishlist toggle failed:",
      error?.response?.data || error,
    );
  };

  return useMutation({
    mutationFn,
    onSuccess,
    onError,
  });
};

export const useCreateAddress = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (addressData) => {
      const response = await api.post("/api/addresses", addressData);

      return response?.data;
    },

    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ["addresses"],
      });
    },
  });
};