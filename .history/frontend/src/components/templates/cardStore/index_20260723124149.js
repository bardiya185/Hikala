import Image from 'next/image'
import Link from 'next/link'
import React from 'react'

function CardShop() {
  return (
    <div className='grid grid-cols-4 gap-5  '>
        <Link href="/">
            <Image src="/icons/off1.webp" width={200} height={200} className='w-full h-full rounded-lg' alt=''  />        
        </Link>
        <Link href="/">
            <Image src="/icons/off2.webp" width={200} height={200} className='w-full h-full rounded-lg' alt=''  />        
        </Link>
        <Link href="/">
            <Image src="/icons/off3.webp" width={200} height={200} className='w-full h-full rounded-lg' alt=''  />        
        </Link>
        <Link href="/">
            <Image src="/icons/off4.webp" width={200} height={200} className='w-full h-full rounded-lg' alt=''  />        
        </Link>
    </div>
  )
}

export default CardShop