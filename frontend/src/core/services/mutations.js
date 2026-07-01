import { useMutation,useQueryClient } from "@tanstack/react-query";
import { setCookie } from "../utils/cookie";
import api from "../config/api";

export const useSendOtp = ()=>{
    const mutationFn = (data)=>api.post("/api/send-otp",data)
    return useMutation({mutationFn})
}

export const useCheckOtp = ()=>{
    const mutationFn = (mobile,code)=>api.post("/api/check-otp",mobile,code)
    return useMutation({mutationFn})

}