import axios from "axios";


 async function GetCart(sessionId , TOKEN) {

    const response = await fetch(NEXT_PUBLIC_BASE_URL + "/api/cart", {
        method: 'GET',
        'X-Session-Id': sessionId || '',
        'X-CSRF-TOKEN': TOKEN || ''
    })

}

if(!response.o)