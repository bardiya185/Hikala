"use client"
import React, { useState } from 'react'
import AccountInfoTemplates from '@/components/templates/Account-info'

function Profile() {
  const [userAccountInformatations,setUserAccountInformations] = useState(true)
  return (
    <>
    {userAccountInformatations ? <AccountInfoTemplates/>:null}
  </>
  )
}

export default Profile