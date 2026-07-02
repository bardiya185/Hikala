import axios from "axios";
import { getCookie, setCookie } from "../utils/cookie";

const baseURL = process.env.NEXT_PUBLIC_BASE_URL;

const api = axios.create({
  baseURL,
  headers: {
    "Content-type": "application/json",
  },
});

api.interceptors.request.use(
  (request) => {
    const accessToken = getCookie("access_Token");
    if (accessToken) {
      request.headers["Authorization"] = `Bearer ${accessToken}`;
    }
    return request;
  },
  (error) => {
    return Promise.reject(error);
  },
);

api.interceptors.response.use(
  (response) => {
    return response;
  },
  async (error) => {
    const originalRequest = error.config;
    if (
      (error.response?.data?.status === "401")|| error.response.status === 403 &&
      !originalRequest._retry
    ) {
      originalRequest._retry = true;

      const res = await getNewTokens();
      console.log(res);
      if (res?.status === 200) {
        setCookie("access_Token", res?.data?.accessToken, 30);
        return api(originalRequest);
      }
    } 

    async (error) => Promise.reject(error?.response?.data);
  },
);

export default api;

const getNewTokens = async () => {
  const refreshToken = getCookie("refresh_Token");
  if (!refreshToken) return;
  try {
    const response = axios.post(`${baseURL}/auth/refresh-token`, {
      refreshToken,
    });
    return response;
  } catch (error) {
    return error;
  }
};