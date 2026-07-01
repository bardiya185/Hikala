"use client"
import React from 'react'

function ModalContainer({children}) {
  return (
    <div className='fixed top-0 right-0 left-0 w-full h-full bg-white z-10'>
        <div className='w-full h-full flex items-center justify-center '>
            <div>
              {children}
            </div>

        </div>
    </div>
  )
}

export default ModalContainer