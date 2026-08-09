import axios from "axios";


export default async function Cart(sessionId) {

    const response = await fetch(NEXT_PUBLIC_BASE_URL + "/api/cart", {
        method: 'GET',
        headers:{

        }



    });




    return (
        <>
            <h1 className=" text-center">Cact</h1>

        </>
    )

}