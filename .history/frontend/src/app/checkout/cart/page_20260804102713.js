import axios from "axios";
 
export default async function GetCart(sessionId, TOKEN) {

    try{

        const response = await fetch(process.env.NEXT_PUBLIC_BASE_URL + "/api/cart", {
            method: 'GET',
            'X-Session-Id': sessionId || '',
            'X-CSRF-TOKEN': TOKEN || ''
        });
        
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
        
    }catch(error){
        console.error('Error fetching cart:', error);
        throw error;
    }
}


GetCart(localStorage.getItem())