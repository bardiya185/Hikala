import axios from "axios";


export default async function Cart(sessionId , TOKEN) {

    const response = await fetch(NEXT_PUBLIC_BASE_URL + "/api/cart", {
        method: 'GET',
        'X-Session-Id': sessionId,
        'X-CSRF-TOKEN': ?TOKEN

    });




    return (
        <>
            <h1 className=" text-center">Cact</h1>

        </>
    )

}