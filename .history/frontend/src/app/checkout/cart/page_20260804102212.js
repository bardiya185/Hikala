import axios from "axios";
 
export default async function GetCart(sessionId, TOKEN) {

    const response = await fetch(process.env.NEXT_PUBLIC_BASE_URL + "/api/cart", {
        method: 'GET',
        'X-Session-Id': sessionId || '',
        'X-CSRF-TOKEN': TOKEN || ''
    })

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    
    console.log(response);
}
