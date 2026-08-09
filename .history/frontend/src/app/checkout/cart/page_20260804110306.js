import { useCart } from "@/core/services/queries";


export default function Cart() {

    const { 
        data, 
        isLoading, 
        isError, 
        error, 
        refetch,
        isFetching 
    } = useCart();





}
