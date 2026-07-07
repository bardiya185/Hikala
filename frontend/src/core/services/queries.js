import { useQuery } from "@tanstack/react-query";

import api from "../config/api";

export const useGetUserData = ()=>{
    const queryFn =()=> api.get("/api/who-am-i")
    const queryKey = ['user-data']
    return useQuery({queryKey,queryFn})
}