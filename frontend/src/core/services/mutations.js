import {
  QueryClient,
  useMutation,
  useQueryClient,
} from "@tanstack/react-query";
import { setCookie } from "../utils/cookie";
import api from "../config/api";
import { data } from "autoprefixer";

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
  };

  return useMutation({ mutationFn, onSuccess });
};