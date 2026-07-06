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
    const accessToken = getCookie("access_token");
    if (accessToken) {
      request.headers["Authorization"] = `Bearer ${accessToken}`;
    }
    return request;
  },
  (error) => Promise.reject(error)
);

api.interceptors.response.use(
  (response) => {
    return response;
  },
  async (error) => {
    const originalRequest = error.config;
    
    const status = error.response ? error.response.status : null;

    if (status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;

      try {
        const res = await getNewTokens();
        
        if (res?.status === 200) {
          const newAccessToken = res?.data?.access_token;
          
          setCookie("access_token", newAccessToken, 30);
          
          originalRequest.headers["Authorization"] = `Bearer ${newAccessToken}`;
          return api(originalRequest);
        }
      } catch (refreshError) {
      
        console.error("Refresh token failed:", refreshError);
        return Promise.reject(refreshError);
      }
    } 

    
  }
);

export default api;

const getNewTokens = async () => {
  const refreshToken = getCookie("refresh_token");
  if (!refreshToken) return null;
  
  try {
    
    const response = await axios.post(`${baseURL}/api/refresh-token`, {
      refresh_token: refreshToken
    });
    return response;
  } catch (error) {
    console.error("Error fetching new tokens:", error);
    throw error;
  }
};