import Image from 'next/image'
import Link from 'next/link'
import React from 'react'

function CardShop({data}) {
    console.log(data)
    const banners = data?.banners || []

  return (
    <div className='grid grid-cols-4 gap-5  '>
        {banners?.map((banner)=>(
        <Link key={banner.id} href={banner?.url}>
            <Image unoptimized src={banner?.image} width={200} height={200} className='w-full h-full rounded-lg' alt=''  />        
        </Link>

        ))}

       

    </div>
  )
}

export default CardShop