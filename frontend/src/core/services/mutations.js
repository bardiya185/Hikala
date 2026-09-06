import {
  QueryClient,
  useMutation,
  useQueryClient,
} from "@tanstack/react-query";
import { removeCookie, setCookie } from "../utils/cookie";
import api from "../config/api";
import { data } from "autoprefixer";
import  toast  from "react-hot-toast";
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
        res?.data?.message
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
export const useLogOut = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      const response = await api.post("/api/logout");
      return response.data;
    },

    onSuccess: () => {

      removeCookie("access_token");
      removeCookie("refresh_token");
      clearGuestSessionId();


      queryClient.clear();

      toast.success("Logged out successfully");

  
      if (typeof window !== "undefined") {
        window.location.href = "/";
      }
    },

    onError: (error) => {
      console.error("Logout error:", error);

   
      removeCookie("access_token");
      removeCookie("refresh_token");
      clearGuestSessionId();

      queryClient.clear();

      toast.error(error?.response?.data?.message || "Logout failed");

      if (typeof window !== "undefined") {
        window.location.href = "/";
      }
    },
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
    api.delete(`/api/cart/items/${cartItemId}`,{
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




export const useAddToWishlist = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (productId) => {
      console.log("Toggling wishlist for product:", productId);
      return api.post(`/api/wishlist/${productId}/toggle`);
    },
    
    onMutate: async (productId) => {
      await queryClient.cancelQueries({ queryKey: ["wishlist_ids"] });
      
      const previousIds = queryClient.getQueryData(["wishlist_ids"]) || [];
      const pIdStr = String(productId);
      const exists = previousIds.some((id) => String(id) === pIdStr);
      
      const nextIds = exists
        ? previousIds.filter((id) => String(id) !== pIdStr)
        : [...previousIds, productId];
      
      queryClient.setQueryData(["wishlist_ids"], nextIds);
      
      return { previousIds };
    },
    
    onError: (_err, _id, context) => {
      if (context?.previousIds) {
        queryClient.setQueryData(["wishlist_ids"], context.previousIds);
      }
    },
    
    onSettled: () => {
      queryClient.invalidateQueries({ queryKey: ["wishlist_ids"] });
    },
  });
};export const useCreateAddress = () => {
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

// ============================================================
// CANCEL ORDER
// ============================================================

export function useCancelOrder() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ orderId, reason }) => {
      const response = await api.post(`/api/orders/${orderId}/cancel`, { reason });
      return response.data;
    },
    onSuccess: (data, variables) => {
      toast.success("Order cancelled successfully");
      queryClient.invalidateQueries({ queryKey: ["orders"] });
      queryClient.invalidateQueries({ queryKey: ["order", variables.orderId] });
    },
    onError: (error) => {
      toast.error(error?.response?.data?.message || "Failed to cancel order");
    },
  });
}

// ============================================================
// REQUEST REFUND
// ============================================================

export function useRequestRefund() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ orderId, reason, description }) => {
      const response = await api.post(`/api/orders/${orderId}/refund`, {
        reason,
        description,
      });
      return response.data;
    },
    onSuccess: (data, variables) => {
      toast.success("Refund request submitted successfully");
      queryClient.invalidateQueries({ queryKey: ["orders"] });
      queryClient.invalidateQueries({ queryKey: ["order", variables.orderId] });
    },
    onError: (error) => {
      toast.error(error?.response?.data?.message || "Failed to submit refund request");
    },
  });
}

// ============================================================
// PAY ORDER (Fake payment)
// ============================================================

export function usePayOrder() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (orderId) => {
      const response = await api.post(`/api/orders/${orderId}/pay`);
      return response.data;
    },
    onSuccess: (data, variables) => {
      toast.success("Payment successful!");
      queryClient.invalidateQueries({ queryKey: ["orders"] });
      queryClient.invalidateQueries({ queryKey: ["order", variables] });
    },
    onError: (error) => {
      toast.error(error?.response?.data?.message || "Payment failed");
    },
  });
}